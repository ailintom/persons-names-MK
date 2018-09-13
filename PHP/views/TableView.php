<?php
/*
 * Description of TableView
 * This class is used to render tables with results
 */

namespace PNM\views;

use \PNM\Request,
    \PNM\Config;

class TableView
{

    protected $data;
    protected $id_field;
    protected $sort_param;
    protected $default_field;
    protected $target_controller;
    protected $hashpos;
    protected $extraHeader;
    protected $leftBorderColIds = [];

    const SORT_HIGHLIGHT = 0;
    const SORT_PARAM = 1;
    const SORT_TITLE = 2;
    const SORT_ICON = 3;

    public function __construct(\PNM\models\ListModel $data, $id_field, $target_controller, $sort_param = 'sort', $hashpos = null)
    {
        $this->data = $data;
        $this->id_field = (array) $id_field;
        $this->target_controller = $target_controller;
        $this->sort_param = $sort_param;
        $this->default_field = $data->defaultsort;
        if (!empty($hashpos)) {
            $this->hashpos = "'$hashpos'||";
        }
    }

    protected function renderSort($field)
    {
//echo ( $this->sort_param . '++' . Request::get($this->sort_param) .';;;'. $field . ' ASC');
        if ((empty(Request::get($this->sort_param)) && $field == $this->default_field) || (Request::get($this->sort_param) == $field . ' ASC')) {
            return [' -highlight', $field . ' DESC', 'descending', Icon::get('arrow-up')];
        } elseif (Request::get($this->sort_param) == $field . ' DESC') {
            return [' -highlight', $field . ' ASC', 'ascending', ' ' . Icon::get('arrow-down')];
        } else {
            return [null, $field . ' ASC', 'ascending', null];
        }
    }

    public function addLeftBorder($colIds)
    {
        $this->leftBorderColIds = (array) $colIds;
    }

    protected function getLeftBorder($id)
    {
        if (in_array($id, $this->leftBorderColIds)) {
            return " -border-left";
        }
    }

    public function addHeader($header)
    {
        $this->extraHeader = $header;
    }

    public function renderTable(array $columns, array $column_titles, $pagination = false, $items_name = null, array $extended_column_titles = [])
    {
        $sort_renders = array_map(array($this, 'renderSort'), $columns);
        // print_r($columns);
        // print_r($sort_renders);
        if ($pagination) {
            $hashpos = $this->id_field[0] . "_nav";
            $onclick_nav = ' onclick="window.location.replace(this.href + (' . "'#$hashpos'" . '));return false;"';
            //echo('<p class="pagination" id="' . $hashpos . '">');
            echo('<p id="' . $hashpos . '">Displaying ' . ($items_name ?: Request::get('controller') ) . ' ' . $this->data->start . '&ndash;' . ($this->data->start + $this->data->count - 1) . ' out of ' . $this->data->total_count . '</p>');
            $pag = '<p class="pagination">';
            if ($this->data->start > 1) {
                $pag .= '<a class="pagination_link -first" href="' . Request::makeURL(Request::get('controller'), Request::get('id'), null, null, true, 0) . '"' . $onclick_nav . '>First</a>'
                        . '<a class="pagination_link -previous" href="' . Request::makeURL(Request::get('controller'), Request::get('id'), null, null, true, (($this->data->start - 1 - Config::ROWS_ON_PAGE) >= 0 ? $this->data->start - 1 - Config::ROWS_ON_PAGE : 0))
                        . '"' . $onclick_nav . '>' . Icon::get('chevron-left')
                        . ' Prev ' . ($this->data->start > Config::ROWS_ON_PAGE ? Config::ROWS_ON_PAGE : $this->data->start - 1) . '</a>';
            } else {
                $pag .= '<span class="pagination_link -first -disabled">First</span><span class="pagination_link -previous -disabled">' . Icon::get('chevron-left') .
                        'Prev ' . Config::ROWS_ON_PAGE . '</span>';
            }
            if ($this->data->start + $this->data->count - 1 < $this->data->total_count) {
                $pag .= '<a class="pagination_link -next" href="' . Request::makeURL(Request::get('controller'), Request::get('id'), null, null, true, (($this->data->start + Config::ROWS_ON_PAGE - 1) < $this->data->total_count ? $this->data->start + Config::ROWS_ON_PAGE - 1 : $this->data->total_count - Config::ROWS_ON_PAGE))
                        . '"' . $onclick_nav . '>Next ' . (($this->data->start + (2 * Config::ROWS_ON_PAGE) - 1) < $this->data->total_count ? Config::ROWS_ON_PAGE : $this->data->total_count - $this->data->start - Config::ROWS_ON_PAGE + 1 ) . ' ' . Icon::get('chevron-right') . '</a>'
                        . '<a class="pagination_link -last" href="' . Request::makeURL(Request::get('controller'), Request::get('id'), null, null, true, intdiv($this->data->total_count, Config::ROWS_ON_PAGE) * Config::ROWS_ON_PAGE) . '"' . $onclick_nav . '>Last</a>';
            }
            $pag .= '</p>';
            echo ($pag);
        } else {
            $pag = null;
        }
        ?>
        <div class="table-container">
            <div class="table" role="grid"><?= $this->extraHeader ?>
                <div class="tr" role="row">
                    <?php
                    echo ( "\r");
                    for ($i = 0; $i < count($columns); ++$i) {
                        $url = Request::makeURL(Request::get('controller'), Request::get('id'), $sort_renders[$i][self::SORT_PARAM], $this->sort_param, true, 0);
                        $hashpos = $this->id_field[0] . "_" . $i;
                        echo ('<div class="th' . $this->getLeftBorder($i) . $sort_renders[$i][self::SORT_HIGHLIGHT] . '" role="gridcell">'
                        . '<a href="' . $url . '" title="Sort by ' . lcfirst(empty($extended_column_titles[$i]) ? $column_titles[$i] : $extended_column_titles[$i]) . ', ' . $sort_renders[$i][self::SORT_TITLE] . '" id="' . $hashpos . '"'
                        . ' onclick="window.location.replace(this.href + (' . "'#$hashpos'||" . 'window.location.hash));return false;">'
                        . $column_titles[$i] . $sort_renders[$i][self::SORT_ICON] . '</a></div>' . "\r");
                    }
                    ?>
                </div>
                <?php
                foreach ($this->data->data as $row) {
                    echo ( "\r");
                    if ($this->target_controller == 'auto') {
                        //getDefaultController
                        // print_r (array_filter(array_intersect_key($row, array_flip($this->id_field))));
                        $arr = array_filter(array_intersect_key($row, array_flip($this->id_field)));
                        $idObj = new \PNM\ID(intval(reset($arr)));
                        $controller = $idObj->getDefaultController();
                    } else {
                        $controller = $this->target_controller;
                    }
                    $url = Request::makeURL($controller, array_intersect_key($row, array_flip($this->id_field)));
                    /* <tr onclick="MK.open(event, '<?= $url ?>')" onkeydown="MK.open(event, '<?= $url ?>')" role="link" tabindex="0">
                     */
                    ?>
                    <a class="tr" role="row" href="<?= $url ?>">
                        <?php
                        for ($i = 0; $i < count($columns); ++$i) {
                            if ($columns[$i] == 'gender' || $columns[$i] == 'gender_b') {
                                $cellval = View::renderGender(empty($row[$columns[$i]]) ? null : $row[$columns[$i]]) ?: '&nbsp;';
                            } else {
                                $cellval = !empty($row[$columns[$i]]) && strlen($row[$columns[$i]]) > 0 ? $row[$columns[$i]] : '&nbsp;';
                            }
                            //role="presentation"
                            echo('<div class="td' . $this->getLeftBorder($i) . $sort_renders[$i][self::SORT_HIGHLIGHT] . '" role="gridcell">' . $cellval . '</div>' . "\r" );
                        }
                        echo ( "\r");
                        ?>
                    </a><?php
                }
                ?>
            </div>
        </div>   <?php
        echo ($pag);
    }
}
