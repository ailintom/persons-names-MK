<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PNM\views;

/**
 * Description of TextInputView
 *
 * @author Tomich
 */
class TextInputView
{

    protected $name;
    protected $label;
    protected $title;
    protected $list;
    protected $id;
    protected $oldVal;
    protected $placeholder = null;
    protected $class;

    public function __construct($name, $label, $title, $placeholderInput = null, $listInput = null, $srOnly = false, $id = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->title = $title;
        $this->id = $id ?: $name;
        $this->oldVal = View::oldValue($name);
        if (!empty($placeholderInput)) {
            $this->placeholder = ' placeholder="' . $placeholderInput . '"';
        }
        if (!empty($listInput)) {
            $this->list = ' list="' . $listInput . '"';
        }
        if ($srOnly) {
            $this->class = ' class="sr-only"';
        } else {
            $this->class = null;
        }
        //list="object-types"
    }

    public function render()
    {
        return <<<EOF
<label for="$this->id"$this->class>$this->label</label>
<input name="$this->name" id="$this->id"$this->placeholder$this->list type="text" title="$this->title"$this->oldVal>
EOF;
    }
}
