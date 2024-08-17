<?php

/*
 * Description of title_spellings
 * A model for spellings of a particular title   *
 */

namespace PNM\models;

class title_spellings extends ListModel {

    protected $tablename = 'titles_att INNER JOIN (attestations INNER JOIN inscriptions ON attestations.inscriptions_id = inscriptions.inscriptions_id) ON titles_att.attestations_id = attestations.attestations_id';
    public $defaultsort = 'titles_att_id';

    protected function initFieldNames() {
        $this->field_names = new FieldList(['titles_att_id', 'spelling', 'inscriptions.inscriptions_id', 'inscriptions.title'], ['titles_att_id', 'spelling', 'inscriptions_id', 'title']);
    }
}
