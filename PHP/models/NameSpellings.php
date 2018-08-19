<?php

/* Description of NameSpellings
 * A model for spellings of a particular personal name
 *
 */

namespace PNM\models;

class NameSpellings extends ListModel
{

    protected $tablename = 'spellings';
    public $defaultsort = 'count_attestations DESC, spelling_norm ASC';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['spellings_id', 'spelling', 'ranke', 'SELECT Count(DISTINCT attestations_id) FROM spellings_attestations_xref WHERE spellings.spellings_id=spellings_attestations_xref.spellings_id', 'usage_period', 'usage_area', 'usage_period_note', 'usage_area_note'], ['spellings_id', 'spelling', 'ranke', 'count_attestations', 'usage_period', 'usage_area', 'usage_period_note', 'usage_area_note']);
    }

    protected function loadChildren()
    {
        $total = count($this->data);
        for ($i = 0; $i < $total; $i++) {
            $filter = new Filter([new Rule('spellings_id', 'exact', $this->data[$i]['spellings_id'], 'i')]);
            $objAltReadings = new alternative_readings(null, 0, 0, $filter);
            $this->data[$i]['alt_readings'] = $objAltReadings;
            $rulesAtt = [new Rule('spellings_id', 'exact', $this->data[$i]['spellings_id'], 'i')];
            $filterSpellAtt = new Filter($rulesAtt);
            $objSpellAtt = new SpellingAttestations(null, 0, 0, $filterSpellAtt);
            $this->data[$i]['attestations'] = $objSpellAtt;
        }
    }
}
