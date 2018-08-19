<?php

/*
 * Description of place
 * A model for a single place
 */

namespace PNM\models;

class place extends EntryModel
{

    protected $tablename = 'places';
    protected $hasBiblio = false;
    protected $idField = 'places_id';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList([
            'places_id', 'place_name', 'long_place_name', 'relative_location', 'macro_region', 'latitude', 'topbib_id', 'tm_geoid', 'pleiades_id', 'artefacts_url',
            'SELECT COUNT(inscriptions_id) from inscriptions WHERE inscriptions.provenance=places.place_name',
            'SELECT COUNT(inscriptions_id) from inscriptions WHERE inscriptions.installation_place=places.place_name',
            'SELECT COUNT(inscriptions_id) from inscriptions WHERE inscriptions.origin=places.place_name',
            'SELECT COUNT(inscriptions_id) from inscriptions WHERE inscriptions.production_place=places.place_name'
                ], [
            'places_id', 'place_name', 'long_place_name', 'relative_location', 'macro_region', 'latitude', 'topbib_id', 'tm_geoid', 'pleiades_id', 'artefacts_url',
            'count_provenance', 'count_installation_place', 'count_origin', 'count_production_place']);
    }

    protected function parse()
    {
        $this->data['count_total'] = $this->data['count_provenance'] + $this->data['count_installation_place'] + $this->data['count_origin'] + $this->data['count_production_place'];
        //This should be implemented in child classes to parse data after retrieving from the database
        //$this->parseNote(['provenance_note', 'installation_place_note', 'origin_note', 'production_place_note', 'dating_note', 'note']);
    }
}
