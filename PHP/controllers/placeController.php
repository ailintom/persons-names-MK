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

namespace PNM\controllers;

class placeController extends EntryController
{

    const NAME = 'place';

    protected function loadChildren()
    {
        $filterFG = new \PNM\models\Filter([new \PNM\models\Rule('site', 'exact', $this->record->get('place_name'), 's')]);
        $objFG = new \PNM\models\find_groups(\PNM\Request::get('find_groups_sort'), 0, 0, $filterFG);
        $totalFG = count($objFG->data);
        $this->record->data['count_find_groups'] = $totalFG;
        $this->record->data['find_groups'] = $objFG;
        $filterWk = new \PNM\models\Filter([new \PNM\models\Rule('production_place', 'exact', $this->record->get('place_name'), 's')]);
        $objWk = new \PNM\models\workshops(\PNM\Request::get('workshops_sort'), 0, 0, $filterWk);
        $totalWk = count($objWk->data);
        $this->record->data['count_workshops'] = $totalWk;
        $this->record->data['workshops'] = $objWk;
    }
}
