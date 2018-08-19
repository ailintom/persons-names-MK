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
            '(SELECT COUNT(inscriptions_id) from inscriptions WHERE inscriptions.provenance=places.place_name OR inscriptions.installation_place=places.place_name
                OR inscriptions.origin=places.place_name OR inscriptions.production_place=places.place_name)'], ['places_id', 'place', 'region', 'latitude',
            'inscriptions_count']);
    }
}
