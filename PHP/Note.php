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

namespace PNM;

Class Note {

    const TITLE_FIELDS = ['thesauri' => 'item_name', 'criteria' => 'title', 'publications' => 'author_year', 'biblio_refs' => 'biblio_refs_id', 'inscriptions' => 'title',
        'find_groups' => 'title', 'workshops' => 'title', 'inscriptions_workshops_xref' => 'inscriptions_workshops_xref_id', 'places' => 'place_name', 'inv_nos' => 'inv_no',
        'collections' => 'title', 'attestations' => 'CONCAT_WS(" ", title_string, personal_name) as title', 'spellings_attestations_xref' => 'spellings_attestations_xref_id', 'persons_attestations_xref' => 'persons_attestations_xref_id',
        'persons' => 'title', 'titles_att' => 'titles_att_id', 'titles' => 'title', 'spellings' => 'spelling', 'alternative_readings' => 'alternative_readings_id',
        'personal_names' => 'personal_name', 'name_types' => 'title', 'names_types_xref' => 'names_types_xref_id', 'bonds' => 'bonds_id', 'persons_bonds' => 'persons_bonds_id'];

    private $NoteText = null;
    public $ParsedNote = null;

    public function __construct($NoteInput = null) {

        if (!empty($NoteInput)) {

            $this->NoteText = $NoteInput;

            $this->ParsedNote = $this->Parse($NoteInput);
        }
    }

    public function Parse($NoteInput) {
        return preg_replace_callback("/@(\d+)[а-яА-Яa-zA-Z-_]*[0-9а-яА-Яa-zA-Z-_]*/", array($this, 'ProcessNoteLink'), $NoteInput);
    }

    private function ProcessNoteLink($matches) {

        return $this->ProcessID($matches[1]);
    }

    private function ProcessID($idInput) {
        $id = new ID(intval($idInput));
        if (!($id->getID())) {
            return $idInput;
        }
        $TableName = $id->getTableName();
        $ViewClass = 'PNM\\' . $TableName . 'MicroView';

        $Title = Lookup::get('SELECT ' . self::TITLE_FIELDS[$TableName] . ' FROM ' . $TableName . ' WHERE ' . $TableName . '_id = ?', $id->getID(), 'i'); //$this->GetUniversalTitle($id);

        $View = New $ViewClass();
        $res = $View->render($Title, $id->getID());
        return $res;
    }

 

}
