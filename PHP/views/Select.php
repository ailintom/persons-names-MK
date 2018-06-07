<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PNM;

/**
 * Description of TextInput
 *
 * @author Tomich
 */
class Select {

    protected $name;
    protected $label;
    protected $title;
    protected $list;
    protected $id;
    protected $oldVal;
    protected $values;
    protected $default;

    public function __construct($name, $label, $title, $values, $default, $startWithEmpty = TRUE, $id = NULL) {
        $this->name = $name;
        $this->label = $label;
        $this->title = $title;
        $this->id = $id ?: $name;
        $this->default = $default;
        $this->values = $values;
        if ($startWithEmpty) {
              array_unshift($this->values, '');
        } 
    }

    public function render() {

        //$this->oldVal = View::oldValueSelect($name);
        return '<label for="' . $this->id . '">' . $this->label . '</label><select name="' . $this->name . '" id="' . $this->id . '">'
                . implode(array_map(array($this, 'renderOption'), $this->values))
                . '</select>';
    }

    protected function renderOption($option) {
        if (isset($this->default)) {
            $isDef = $option == $this->default;
        } else {
            $isDef = False;
        }
        $selected = View::oldValueSelect($this->name, $option, $isDef);
        return '<option value="' . $option . '"' . $selected . '>' . ($option ?: '&nbsp;') . '</option>';
    }

}
