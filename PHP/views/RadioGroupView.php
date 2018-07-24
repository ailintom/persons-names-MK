<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PNM\views;

/**
 * Description of RadioGroupView
 *
 * @author Tomich
 */
class RadioGroupView
{

//put your code here
    protected $name;
    protected $elements;
    protected $default;
    protected $filterName;

    public function __construct($name, array $elements, $default, $filterName)
    {
        $this->name = $name;
        $this->elements = $elements;
        $this->default = $default;
        $this->filterName = $filterName;
    }

    public function render()
    {
        return implode(' / ', array_map(array($this, 'renderSingleRadio'), $this->elements));
    }

    protected function renderSingleRadio($element)
    {
        $checked = View::oldValueRadio($this->name, $element[0], $element[0] == $this->default);
        $title = (empty($element[2]) ? null : ' title="' . $element[2] . '"' );
        return <<<EOT
<input id="$this->name-$element[0]" name="$this->name" value="$element[0]" type="radio" aria-labelledby="$this->filterName-label"$checked>
<label for="$this->name-$element[0]"$title>$element[1]</label>
EOT;
    }
}
