<?php

/*
 * This class renders notes containing links tagged by @123comments where 123 is a unique ID number
 */

namespace PNM;

class Note
{

    const TITLE_FIELDS = ['thesauri' => 'item_name', 'criteria' => 'title', 'objects' => 'title', 'publications' => 'author_year', 'biblio_refs' => 'biblio_refs_id', 'inscriptions' => 'title',
        'find_groups' => 'title', 'workshops' => 'title', 'inscriptions_workshops_xref' => 'inscriptions_workshops_xref_id', 'places' => 'place_name', 'inv_nos' => 'inv_no',
        'collections' => 'title', 'attestations' => 'CONCAT_WS(" ", title_string, personal_name) as title', 'spellings_attestations_xref' => 'spellings_attestations_xref_id', 'persons_attestations_xref' => 'persons_attestations_xref_id',
        'persons' => 'title', 'titles_att' => 'titles_att_id', 'titles' => 'title', 'spellings' => 'spelling', 'alternative_readings' => 'alternative_readings_id',
        'personal_names' => 'personal_name', 'name_types' => 'title', 'names_types_xref' => 'names_types_xref_id', 'bonds' => 'bonds_id', 'persons_bonds' => 'persons_bonds_id'];

    private $NoteText = null;
    public $ParsedNote = null;

    public function __construct($NoteInput = null)
    {
        if (!empty($NoteInput)) {
            $this->NoteText = $NoteInput;
            $this->ParsedNote = $this->parse($NoteInput);
        }
    }

    public function parse($NoteInput)
    {
        return preg_replace_callback("/@(\d+)[а-яА-Яa-zA-Z-_]*[0-9а-яА-Яa-zA-Z-_]*/", array($this, 'processNoteLink'), $NoteInput);
    }

    private function processNoteLink($matches)
    {
        return self::processID($matches[1]);
    }
    /*
     * renders the reference to a single entity referred to by ID
     * can be used outside the Note class
     */

    public static function processID($idInput)
    {
        $id = new ID(intval($idInput));
        if (!($id->getID())) {
            return $idInput;
        }
        $TableName = $id->getTableName();
        $ViewClass = 'PNM\\views\\' . $TableName . 'MicroView';
        $Title = models\Lookup::get('SELECT ' . self::TITLE_FIELDS[$TableName] . ' FROM ' . $TableName . ' WHERE ' . $TableName . '_id = ?', $id->getID(), 'i'); //$this->GetUniversalTitle($id);
        $View = new $ViewClass();
        $res = $View->render($Title, $id->getID());
        return $res;
    }
}
