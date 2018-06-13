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

$page = Request::get('controller');
?>
<nav class="nav" id="nav">
    <ul class="nav_list">
        <li class="nav_item <?= in_array($page, ['names', 'name']) ? '-active' : '' ?>">
            <a href="<?= Request::makeURL('names') ?>" class="nav_link"><?= Icon::get('name', '') ?> Personal Names</a>
        </li>
        <li class="nav_item <?= in_array($page, ['titles', 'title']) ? '-active' : '' ?>">
            <a href="<?= Request::makeURL('titles') ?>" class="nav_link"><?= Icon::get('title', '') ?> Titles</a>
        </li>
        <li class="nav_item <?= in_array($page, ['people', 'person', 'attestation']) ? '-active' : '' ?>">
            <a href="<?= Request::makeURL('people') ?>" class="nav_link"><?= Icon::get('people', '') ?> People</a>
        </li>
        <li class="nav_item <?= in_array($page, ['inscriptions', 'inscription']) ? '-active' : '' ?>">
            <a href="<?= Request::makeURL('inscriptions') ?>" class="nav_link"><?= Icon::get('object', '') ?> Inscribed Objects</a>
        </li>
        <li class="nav_item <?= in_array($page, ['places', 'place', 'group', 'workshop']) ? '-active' : '' ?>">
            <a href="<?= Request::makeURL('places') ?>" class="nav_link"><?= Icon::get('place', '') ?> Places</a>
        </li>
        <li class="nav_item <?= in_array($page, ['collections', 'collection']) ? '-active' : '' ?>">
            <a href="<?= Request::makeURL('collections') ?>" class="nav_link"><?= Icon::get('collection', '') ?> Collections</a>
        </li>
    </ul>
    <ul class="nav_list -aside">
        <li class="nav_item <?= in_array($page, ['types', 'type']) ? '-active' : '' ?>">
            <a href="<?= Request::makeURL('types') ?>" class="nav_link -no-icon">Name Types</a>
        </li>
        <li class="nav_item <?= in_array($page, ['bibliography', 'publication']) ? '-active' : '' ?>">
            <a href="<?= Request::makeURL('bibliography') ?>" class="nav_link -no-icon">Bibliography</a>
        </li>
        <li class="nav_item <?= in_array($page, ['info', 'criterion']) ? '-active' : '' ?>">
            <a href="<?= Request::makeURL('info') ?>" class="nav_link -no-icon">Info</a>
        </li>
    </ul>
</nav>
