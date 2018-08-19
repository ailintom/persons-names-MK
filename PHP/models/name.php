<?php

/*
 * Description of name
 * A model for a personal name
 */

namespace PNM\models;

class name extends EntryModel
{

    protected $tablename = 'personal_names';
    protected $hasBiblio = true;
    protected $idField = 'personal_names_id';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['personal_names_id', 'personal_name', 'gender', 'SELECT Count(DISTINCT attestations_id) FROM spellings JOIN spellings_attestations_xref ON spellings_attestations_xref.spellings_id=spellings.spellings_id WHERE spellings.personal_names_id=personal_names.personal_names_id', 'usage_period', 'usage_area', 'usage_period_note', 'usage_area_note', 'note', 'ranke', '`scheele-schweitzer`', 'tla', 'agea', 'translation_en', 'translation_de'], ['personal_names_id', 'personal_name', 'gender', 'count_attestations', 'usage_period', 'usage_area', 'usage_period_note', 'usage_area_note', 'note', 'ranke', '`scheele-schweitzer`', 'tla', 'agea', 'translation_en', 'translation_de']);
    }

    protected function parse()
    {
        //This should be implemented in child classes to parse data after retrieving from the database
        $this->parseNote(['usage_period_note', 'usage_area_note', 'note']);
    }

    protected function loadChildren()
    {
        $filterTypes = new Filter([new Rule('personal_names_id', 'exact', $this->data['personal_names_id'], 'i')]);
        $this->data['name_types'] = new NameTypes(null, 0, 0, $filterTypes);
        $filterAlt = new Filter([new Rule('alternative_readings.personal_names_id', 'exact', $this->data['personal_names_id'], 'i')]);
        $this->data['alt_readings'] = new NameReadings(null, 0, 0, $filterAlt);
        //
    }
}
