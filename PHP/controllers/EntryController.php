<?php

namespace PNM\controllers;

/**
 * This this is a parent class for controllers displaying a single record, optionally with child records, loaded
 * based on the request by loadChildren.
 */
class EntryController
{

    const NAME = null; // the name of the controller to be defined in child classes

    protected $record;
    /*
     * This function gets the id from the request and passes it to the loadID function
     */

    public function load()
    {
        $this->loadID((int) \PNM\Request::get('id'));
    }
    /*
     * This function loads the record into the EntryModel and sends it to the view
     */

    protected function loadID($id)
    {
        $modelClass = 'PNM\\models\\' . static::NAME;
        $this->record = new $modelClass(); // an instance of the EntryModel class
        $tableName = $this->record->getTable();
        if ((new \PNM\ID((int) $id))->getTableName() != $tableName) {
            $id = new \PNM\ID((int) $id, $tableName);
        } else {
            $id = new \PNM\ID((int) $id);
        }
        $this->record->find($id->getID());
        if ($this->record->noMatch == true) { // the record was not found
            (new NotFoundView())->echoRender();
        } else { // the record was found
            $this->loadChildren();
            $viewClass = 'PNM\\views\\' . static::NAME . 'View';
            (new $viewClass())->echoRender($this->record);
        }
    }
    /*
     * This optional function loads children of the main record from other tables, based on user input in the request
     *
     */

    protected function loadChildren()
    {
        // to be used in child classes
    }
}
