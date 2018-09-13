<?php

/* Description of EntryController
 * This is a parent class for controllers requesting a single record, optionally with child records (loaded
 * based on the request data by loadChildren; if children are loaded without using request data, loadChildren in the respective model can be used).
 */

namespace PNM\controllers;
use \PNM\Request,
    \PNM\models\Rule,
    \PNM\models\RuleExists,
    \PNM\models\Filter,
    \PNM\Config;

class EntryController
{

    const NAME = null; // the name of the controller to be defined in child classes 

    protected $record; // the variable holding the data
    /* load
     * This function gets the id from the request and passes it to the loadID function
     */

    public function load()
    {
        $this->loadID((int) Request::get('id'));
    }
    /* loadID
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
            (new \PNM\views\NotFoundView())->echoRender();
        } else { // the record was found
            $this->loadChildren();
            $viewClass = 'PNM\\views\\' . static::NAME . 'View';
            (new $viewClass())->echoRender($this->record);
        }
    }
    /* loadChildren
     * This optional function loads children of the main record from other tables, based on user input in the request data
     *
     */

    protected function loadChildren()
    {
        // to be used in child classes
    }
}
