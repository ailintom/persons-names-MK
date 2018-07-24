<?php

/*
 * MIT License
 *
 * Copyright (c) 2017 Alexander Ilin-Tomich (unless specified otherwise for individual source files and documents)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace PNM\models;

/**
 * Description of EntryModel
 *
 * @author Tomich
 */
class EntryModel
{

    public $data = null;
    public $subEntries = null;
    public $noMatch = false;
    protected $hasBiblio = false;
    protected $idField = null; // This should be the name of the principal id field in the dataset
    protected $field_names = null;
    protected $tablename = null; //This should be implemented in child classes to set the list of fields retrieved from the database
    protected $bindParam = 'i';

    protected function initFieldNames()
    {
//This should be implemented in child classes to set the list of fields retrieved from the database
    }

    protected function parse()
    {
        //This should be implemented in child classes to parse data after retrieving from the database
    }

    protected function loadChildren()
    {
//This should be implemented in child classes to set the list of fields retrieved from the database
    }

    public function __construct(array $data = null)
    {
        if (!empty($data)) {
            $this->data = $data;
        }
    }

    protected function validate($id_input)
    {
        return intval($id_input);
    }

    public function find($id_input)
    {
        // Check if ID is valid and get table name
        $id = $this->validate($id_input);
        if (empty($id)) {
            return null;
        }
        if (empty($this->tablename)) {
            $IDobj = new \PNM\ID($id);
            $this->tablename = $IDobj->getTableName();
        }
        $this->initFieldNames();
        $db = \PNM\Db::getInstance();
        $SQL = $this->sqlString();
        //  echo ($SQL . ';;;' . $this->bindParam . ';;;' . $id) ;
        try {
            $stmt = $db->prepare($SQL);
            $stmt->bind_param($this->bindParam, $id);
            $stmt->execute();
        } catch (\mysqli_sql_exception $e) {
            \PNM\CriticalError::show($e);
        }
        $result = $stmt->get_result();
        if ($result->num_rows !== 0) {
            $this->data = $result->fetch_assoc();
            if ($this->hasBiblio) {
                $this->setBiblio();
            }
            $this->loadChildren();
            $this->parse();
        } else {
            $this->noMatch = true;
        }
    }

    protected function sqlString()
    {
        return 'SELECT ' . $this->field_names->SQL() . ' FROM ' . $this->tablename . ' WHERE ' . $this->idField . ' = ?;';
    }

    public function get($field)
    {
        if (is_int($field)) {
            $field_name = $this->field_names->getFieldName($field);
        } else {
            $field_name = $field;
        }
        if (empty($this->data) || (empty($this->data[$field_name]))) {
            return null;
        } else {
            return $this->data[$field_name];
        }
    }

    protected function parseNote($fieldName)
    {
        if (is_array($fieldName)) {
            foreach ($fieldName as $name) {
                $this->parseNote($name);
            }
        } elseif (!empty($this->data[$fieldName])) {
            $dnoteobj = new \PNM\Note($this->data[$fieldName]);
            $this->data[$fieldName] = $dnoteobj->ParsedNote;
        }
    }

    protected function setBiblio()
    {
        $filter = new Filter([new Rule('object_id', 'exact', $this->getID(), 'i')]);
        $objbibliography = new ObjectBibliography(null, 0, 0, $filter); //$sort = null, $start = 0, $count = 0, Filter $filter = null
        $res = null;
        $bibView = new \PNM\views\publicationsMicroView();
        foreach ($objbibliography->data as $bib_etry) {
            //    print_r ( $bib_etry);
            $res .= (empty($res) ? null : '; ');
            if (!empty($bib_etry['author_year'])) {
                $res .= $bibView->render($bib_etry['author_year'], $bib_etry['source_id']);
            } elseif (!empty($bib_etry['source_url'])) {
                $res .= "<a href='" . htmlspecialchars($bib_etry['source_url'], ENT_HTML5) . "'>" . htmlspecialchars(($bib_etry['source_title'] ?: $bib_etry['source_url']), ENT_HTML5) . "</a>" . $this->getAccessedOn($bib_etry['accessed_on']);
            } elseif (!empty($bib_etry['source_title'])) {
                $res .= htmlspecialchars($bib_etry['source_title'], ENT_HTML5) . $this->getAccessedOn($bib_etry['accessed_on']);
            }
            if (!empty($bib_etry['pages'])) {
                $res .= ", " . $bib_etry['pages'];
            }
            if (!empty($bib_etry['reference_type'])) {
                $res .= " [" . $bib_etry['reference_type'] . "]";
            }
        }
        $this->data['bibliography'] = $res;
    }

    protected function getAccessedOn($accessedOn)
    {
        if (!empty($accessedOn)) {
            return " (accessed on " . htmlspecialchars($accessedOn, ENT_HTML5) . ")";
        }
    }
    /*
     * gets the id of the record
     */

    public function getID()
    {
        return $this->get($this->idField);
    }
    /*
     * gets the name of the table based on the name of the id field
     */

    public function getTable()
    {
        return substr($this->idField, 0, strlen($this->idField) - 3);
    }
}
