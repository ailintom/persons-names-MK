<?php

/*
 * Description of TextInput
 * This class renders the HTML code for an input control on a search page
 */

namespace PNM\views;

use \PNM\Request;

class TextInput
{

    protected $name;
    protected $label;
    protected $title;
    protected $list;
    protected $listName;
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
            $this->listName = $listInput;
        }
        if ($srOnly) {
            $this->class = ' class="sr-only"';
        } else {
            $this->class = null;
        }

    }

    public function render()
    {

        if (!\PNM\Request::$noDatalist || empty($this->listName)) {
            if (!empty($this->listName)) {
                $dl = new Datalist();
                $list = $dl->get($this->listName);
            } else {
                $list = null;
            }
            return <<<EOF
<label for="$this->id"$this->class>$this->label</label>            
<input name="$this->name" id="$this->id"$this->placeholder$this->list type="text" title="$this->title"$this->oldVal>$list
EOF;
        } else {
            $dl = new Datalist();
            $list = $dl->get($this->listName, Request::get($this->name));

            return <<<EOF
<label for="$this->id"$this->class>$this->label</label>            
<select name="$this->name" id="$this->id"$this->placeholder type="text" title="$this->title">$list
</select>
EOF;
        }
    }
}
