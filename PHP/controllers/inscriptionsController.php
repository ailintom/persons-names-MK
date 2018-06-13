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

class inscriptionsController
{

    public function load()
    {
        $rules = [];
        if (!empty(Request::get('collection'))) {
            $collID = Lookup::collectionsGet(Request::get('collection'));
            if (empty($collID)) {
                array_push($rules, new Rule('0', 'exact', 1, 'i'));
            } elseif (!empty(Request::get('title'))) {
                $inv = str_replace("*", "%", Request::get('title'));
                array_push($rules, new RuleExists('inv_nos WHERE inv_nos.inscriptions_id = inscriptions.inscriptions_id '
                        . 'AND inv_nos.collections_id = ? AND inv_nos.inv_no LIKE ? ', [$collID, $inv], 'is'));
            } else {
                array_push($rules, new RuleExists('inv_nos WHERE inv_nos.inscriptions_id = inscriptions.inscriptions_id AND inv_nos.collections_id =?', $collID, 'i'));
            }
        } elseif (!empty(Request::get('title'))) {
            array_push($rules, new Rule('title', 'exactlike', Request::get('title')));
        }
        if (!empty(Request::get('object_subtype'))) {
            array_push($rules, new Rule('object_subtype', 'exact', Request::get('object_subtype')));
        } elseif (!empty(Request::get('object_type'))) {
            array_push($rules, new Rule('object_type', 'exact', Request::get('object_type')));
        }
        if (!empty(Request::get('material'))) {
            array_push($rules, new Rule('material', 'exact', Request::get('material')));
        }
        if (!empty(Request::get('text_content'))) {
            array_push($rules, new Rule('text_content', 'exact', Request::get('text_content')));
        }
        if (!empty(Request::get('script'))) {
            array_push($rules, new Rule('script', 'exact', Request::get('script')));
        }
        if (!empty(Request::get('size'))) {
            if (Request::get('size-option') == 'less') {
                array_push($rules, new Rule('GREATEST(IFNULL(length,0), IFNULL(height,0), IFNULL(width,0), IFNULL(thickness,0))', 'lessorequal', Request::get('size'), 'i'));
                array_push($rules, new Rule('GREATEST(IFNULL(length,0), IFNULL(height,0), IFNULL(width,0), IFNULL(thickness,0))', 'not', 0, 'i'));
            } elseif (Request::get('size-option') == 'greater') {
                array_push($rules, new Rule('GREATEST(IFNULL(length,0), IFNULL(height,0), IFNULL(width,0), IFNULL(thickness,0))', 'moreorequal', Request::get('size'), 'i'));
            }
        }
        if (!empty(Request::get('place'))) {
            switch (Request::get('geo-filter')) {
                case 'origin':
                    array_push($rules, new Rule('origin', 'exact', Request::get('place')));
                    break;
                case 'provenance':
                    array_push($rules, new Rule('provenance', 'exact', Request::get('place')));
                    break;
                case 'installation':
                    array_push($rules, new Rule('installation_place', 'exact', Request::get('place')));
                    break;
                case 'production':
                    array_push($rules, new Rule('production_place', 'exact', Request::get('place')));
                    break;
                case 'all':
                default:
                    array_push($rules, new Rule(['origin', 'provenance', 'installation_place', 'production_place'], 'exact', Request::get('place')));
            }
        }
        if (!empty(Request::get('period'))) {
            $periodEnd = Lookup::dateEnd(Request::get('period'));
            $periodStart = Lookup::dateStart(Request::get('period'));
            if (empty($periodStart) || empty($periodEnd)) {
                array_push($rules, new Rule('0', 'exact', 1, 'i'));
            } else {
                switch (Request::get('chrono-filter')) {
                    case 'during':
                        array_push($rules, new Rule('dating_sort_start', 'lessorequal', $periodEnd, 'i'));
                        array_push($rules, new Rule('dating_sort_end', 'moreorequal', $periodStart, 'i'));
                        break;
                    case 'not-later':
                        array_push($rules, new Rule('dating_sort_start', 'lessorequal', $periodEnd, 'i'));
                        break;
                    case 'not-earlier':
                        array_push($rules, new Rule('dating_sort_end', 'moreorequal', $periodStart, 'i'));
                        break;
                }
            }
        }
        $filter = new Filter($rules);
        $inscriptions = new inscriptions(Request::get('sort'), (Request::get('start') ?: 0), 50, $filter); //$sort = null, $start = 0, $count = 0, Filter $filter = null
        $view = new inscriptionsView();
        $view->echoRender($inscriptions);
    }
}
