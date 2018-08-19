<?php

/*
 * Description of EntryModel
 * This is a general class for models used to load a single record from the database 
 * (including child records from other tables loaded by loadChildren
 * if these are loaded regardless of the user input; otherwise the loadChildren
 * in the respective controller is used)
 */

namespace PNM\models;

class EntryModel
{

    public $data = null;
    public $subEntries = null;
    public $noMatch = false; // true if record is not found
    protected $hasBiblio = false; // true if loaded entity can have associated bibliography
    protected $idField = null; // This should be the name of the principal id field in the dataset
    protected $field_names = null;
    protected $tablename = null; //This should be implemented in child classes to set the list of fields retrieved from the database
    protected $bindParam = 'i'; // 'i' if a numerical ID is used

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

    // validates the provided ID 
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
            if ($this->hasBiblio) { // If the loaded record has associated bibliography, load it 
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
    /*
     * Parses a note field turning all refereces formatted as @123decription where 123 is the unique record id
     * into hyperlinks
     */

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
    /*
     * setBiblio loads the bibliography for an object and saves in the "bibliography" property
     * 
     */

    protected function setBiblio()
    {
        $filter = new Filter([new Rule('object_id', 'exact', $this->getID(), 'i')]);
        $objbibliography = new EntryBibliography(null, 0, 0, $filter);

        $this->data['bibliography'] = $objbibliography;
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
