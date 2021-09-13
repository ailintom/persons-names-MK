<?php

/*
 * Description of publication
 * A model for a single publication, including child records (references)
 */

namespace PNM\models;

class publication extends EntryModel
{

    protected $tablename = 'publications';
    protected $hasBiblio = false;
    protected $idField = 'publications_id';
    public $tables = [['criteria'], ['inscriptions', 'Inscriptions'], ['objects', 'Objects'],['find_groups', 'Find groups'], ['workshops'], ['persons'], ['titles'], ['spellings'], ['personal_names', 'Personal names'], ['name_types', 'Name types']];

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['publications_id', 'author_year', 'html_entry', 'oeb_id']);
    }

    protected function loadChildren()
    {
        $this->data['refs_count'] = 0;
        foreach ($this->tables as $table) {
            $SQL = 'SELECT biblio_refs.biblio_refs_id as biblio_refs_id, biblio_refs.reference_type as reference_type, '
                    . 'biblio_refs.object_id as object_id, biblio_refs.pages as pages, biblio_refs.note as note, '
                    . $table[0] . '.' . \PNM\Note::TITLE_FIELDS[$table[0]] . ' as title FROM biblio_refs INNER JOIN ' . $table[0]
                    . ' ON biblio_refs.object_id = ' . $table[0] . '.' . $table[0] . '_id WHERE biblio_refs.source_id = ? '
                    . 'ORDER BY pages_sort';
            $this->data[$table[0]] = Lookup::getList($SQL, $this->getID(), 'i');
            if (!empty($this->data[$table[0]])) {
                $this->data['refs_count'] += count($this->data[$table[0]]);
            }
        }
    }
}
