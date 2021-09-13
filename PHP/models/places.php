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
            '(SELECT COUNT(DISTINCT (inscriptions.inscriptions_id)) from  (objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id) INNER JOIN inscriptions ON objects_inscriptions_xref.inscriptions_id = inscriptions.inscriptions_id WHERE objects.provenance=places.place_name OR objects.installation_place=places.place_name
                OR inscriptions.origin=places.place_name OR objects.production_place=places.place_name)'], ['places_id', 'place', 'region', 'latitude',
            'inscriptions_count']);
    }
}
