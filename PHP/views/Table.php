<?php
/*
 * MIT License
 * 
 * Copyright (c) 2017 Alexander Ilin-Tomich (unless specified otherwise for individual source files and documents)
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace PNM;

/**
 * Description of Table
 *
 * @author Tomich
 */
class Table {

    //put your code here
    protected $data;
    protected $id_field;
    protected $sort_param;
    protected $default_field;
    protected $target_controller;
    protected $hashpos;
    protected $extraHeader;

    public function __construct(ListModel $data, $id_field, $target_controller, $sort_param = 'sort', $hashpos = NULL) {
        $this->data = $data;
        $this->id_field = $id_field;
        $this->target_controller = $target_controller;
        $this->sort_param = $sort_param;
        $this->default_field = $data->defaultsort;
        if (!empty($hashpos)) {
            $this->hashpos = "'$hashpos'||";
        }
    }

    protected function render_sort($field) {
//echo ( $this->sort_param . '++' . Request::get($this->sort_param) .';;;'. $field . ' ASC');
        if ((empty(Request::get($this->sort_param)) && $field == $this->default_field) || (Request::get($this->sort_param) == $field . ' ASC')) {


            return [' class = "-highlight"', $field . ' DESC', 'descending', icon('arrow-up')];
        } elseif (Request::get($this->sort_param) == $field . ' DESC') {
            return [' class = "-highlight"', $field . ' ASC', 'ascending', ' ' . icon('arrow-down')];
        } else {
            return [NULL, $field . ' ASC', 'ascending', NULL];
        }
    }
    public function addHeader($header){
        $this->extraHeader = $header;
    }

    public function render_table(Array $columns, Array $column_titles, $pagination = FALSE, $items_name = NULL) {

        $sort_renders = array_map(array($this, 'render_sort'), $columns);
        // print_r($columns);
        // print_r($sort_renders);
        if ($pagination) {
            $hashpos = $this->id_field . "_nav";
            $onclick_nav = ' onclick="window.location.replace(this.href + (' . "'#$hashpos'" . '));return false;"';
            //echo('<p class="pagination" id="' . $hashpos . '">');

            echo('<p id="' . $hashpos . '">Displaying ' . ($items_name ?: Request::get('controller') ) . ' ' . $this->data->start . '&ndash;' . ($this->data->start + $this->data->count - 1) . ' out of ' . $this->data->total_count . '</p>');

            $pag = '<p class="pagination">';
            if ($this->data->start > 1) {
                $pag .= '<a class="pagination_link -first" href="' . Request::makeURL(Request::get('controller'), Request::get('id'), NULL, NULL, TRUE, 0) . '"' . $onclick_nav . '>First</a>'
                        . '<a class="pagination_link -previous" href="' . Request::makeURL(Request::get('controller'), Request::get('id'), NULL, NULL, TRUE, (($this->data->start - 1 - 50) >= 0 ? $this->data->start - 1 - 50 : 0))
                        . '"' . $onclick_nav . '>' . icon('chevron-left')
                        . ' Prev ' . ($this->data->start > 50 ? 50 : $this->data->start - 1) . '</a>';
            }
            if ($this->data->start + $this->data->count - 1 < $this->data->total_count) {
                $pag .= '<a class="pagination_link -next" href="' . Request::makeURL(Request::get('controller'), Request::get('id'), NULL, NULL, TRUE, (($this->data->start + 49) < $this->data->total_count ? $this->data->start + 49 : $this->data->total_count - 50))
                        . '"' . $onclick_nav . '>Next ' . (($this->data->start + 99) < $this->data->total_count ? 50 : $this->data->total_count - $this->data->start - 49 ) . ' ' . icon('chevron-right') . '</a>'
                        . '<a class="pagination_link -last" href="' . Request::makeURL(Request::get('controller'), Request::get('id'), NULL, NULL, TRUE, intdiv($this->data->total_count, 50) * 50) . '"' . $onclick_nav . '>Last</a>';
            }
            $pag .= '</p>';
            echo ($pag);
        } else {
            $pag = NULL;
        }
        ?>  
        <div class="table-container">
            <table><?=$this->extraHeader?>
                <tr>
                    <?php
                    echo ( "\r");
                    for ($i = 0; $i < count($columns); ++$i) {
                        $url = Request::makeURL(Request::get('controller'), Request::get('id'), $sort_renders[$i][1], $this->sort_param, TRUE, 0);
                        $hashpos = $this->id_field . "_" . $i;
                        echo ('<th' . $sort_renders[$i][0] . '>'
                        . '<a href="' . $url . '" title="Sort by ' . lcfirst($column_titles[$i]) . ', ' . $sort_renders[$i][2] . '" id="' . $hashpos . '"'
                        . ' onclick="window.location.replace(this.href + (' . "'#$hashpos'||" . 'window.location.hash));return false;">'
                        . $column_titles[$i] . $sort_renders[$i][3] . '</a></th>' . "\r");
                    }
                    ?>
                </tr>
                <?php
                foreach ($this->data->data as $row) {
                    echo ( "\r");
                    if ($this->target_controller == 'auto') {
                        //getDefaultController
                        $idObj = New ID(intval($row[$this->id_field]));
                        $controller = $idObj->getDefaultController();
                    } else {
                        $controller = $this->target_controller;
                    }

                    $url = Request::makeURL($controller, $row[$this->id_field]);
                    /* <tr onclick="MK.open(event, '<?= $url ?>')" onkeydown="MK.open(event, '<?= $url ?>')" role="link" tabindex="0">
                     */
                    ?>
                    <tr onclick="MK.open(event, '<?= $url ?>')" tabindex="0"><?php
                        for ($i = 0; $i < count($columns); ++$i) {
                            if ($columns[$i] == 'gender' || $columns[$i] == 'gender_b') {
                                $cellval = View::renderGender(empty($row[$columns[$i]]) ? NULL : $row[$columns[$i]]) ?: '&nbsp;';
                            } else {
                                $cellval = empty($row[$columns[$i]]) ? '&nbsp;' : $row[$columns[$i]];
                            }
                            //role="presentation"
                            echo('<td' . $sort_renders[$i][0] . '><a href="' . $url . '" style="border-bottom-width:0px;display: block;text-decoration: none;">' . $cellval . '</a></td>' . "\r" );
                        }
                        echo ( "\r");
                        ?>
                    </tr><?php
                }
                ?>
            </table>
        </div>   <?php
        echo ($pag);
    }

}
