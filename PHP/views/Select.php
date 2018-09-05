<?php

/*
 * Description of Select
 * This class renders the HTML code for a select control on a search page
 */

namespace PNM\views;

class Select
{

    protected $name;
    protected $label;
    protected $title;
    protected $list;
    protected $id;
    protected $oldVal;
    protected $values;
    protected $default;

    public function __construct($name, $label, $title, $values, $default, $startWithEmpty = true, $id = null)
    {
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

    public function render()
    {
        //$this->oldVal = View::oldValueSelect($name);
        return '<label for="' . $this->id . '">' . $this->label . '</label><select name="' . $this->name . '" id="' . $this->id . '">'
                . implode(array_map(array($this, 'renderOption'), $this->values))
                . '</select>';
    }

    protected function renderOption($option)
    {
        if (isset($this->default)) {
            $isDef = $option == $this->default;
        } else {
            $isDef = false;
        }
        $selected = View::oldValueSelect($this->name, $option, $isDef);
        return '<option value="' . htmlspecialchars(trim($option), ENT_QUOTES, 'UTF-8') . '"' . $selected . '>' . (htmlspecialchars(trim($option), ENT_QUOTES, 'UTF-8') ?: '&nbsp;') . '</option>';
    }
}
