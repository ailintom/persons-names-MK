<?php

namespace PNM\models;

/*
 * Description of AttestationSpellings
 * A model for spellings associated with a particular attestation of a personal name on an inscribed object
 *
 */

class AttestationSpellings extends ListModel
{

    protected $tablename = '(spellings_attestations_xref INNER JOIN spellings ON spellings_attestations_xref.spellings_id = spellings.spellings_id) INNER JOIN personal_names ON spellings.personal_names_id = personal_names.personal_names_id';
    public $defaultsort = 'spellings_attestations_xref_id';

    //(source_id>0) DESC , source_url, source_title, author_year_sort
    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['spellings.spellings_id', 'spelling', 'personal_names.personal_names_id', 'personal_name', 'classifier', 'epithet_mdc'], ['spellings_id', 'spelling', 'personal_names_id', 'personal_name', 'classifier', 'epithet_mdc']);
    }

    public function getSpellings()
    {
        $spellings = [];
        foreach ($this->data as $spelling) {
            $index = $this->rowInArray($spelling['personal_names_id'], 'personal_names_id', $spellings);
            if (!isset($index)) {
                $index = array_push($spellings, array('personal_names_id' => $spelling['personal_names_id'], 'personal_name' => $spelling['personal_name'], 'spellings' => [])) - 1;
            }
            $filter = new Filter([new Rule('spellings_id', 'exact', $spelling['spellings_id'], 'i')]);
            $objAltReadings = new alternative_readings(null, 0, 0, $filter, null, null, true);
            array_push($spellings[$index]['spellings'], array('spelling' => $spelling['spelling'], 'spellings_id' => $spelling['spellings_id'],'classifier' => $spelling['classifier'],'epithet_mdc' => $spelling['epithet_mdc'], 'alt_readings' => $objAltReadings));
        }
        return $spellings;
    }
}
