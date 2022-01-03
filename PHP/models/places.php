<?php

/*
 * Description of places
 * A model for places
 *
 */

namespace PNM\models;

class places extends ListModel
{

    protected $tablename = 'places';
    public $defaultsort = 'latitude';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['places_id', 'IFNULL(long_place_name,place_name)', 'IFNULL(macro_region, relative_location)', 'latitude',
            'inscriptions_count_temp'], ['places_id', 'place', 'region', 'latitude', 'inscriptions_count']);
    }
}
