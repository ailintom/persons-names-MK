<?php
/*
 * This script renders the menu
 */

namespace PNM\views;

$page = \PNM\Request::get('controller');
?>
<nav class="nav" id="nav">
    <ul class="nav_list">
        <li class="nav_item <?= in_array($page, ['names', 'name']) ? '-active' : '' ?>">
            <a href="<?= \PNM\Request::makeURL('names') ?>" class="nav_link"><?= IconView::get('name', '') ?> Personal Names</a>
        </li>
        <li class="nav_item <?= in_array($page, ['titles', 'title']) ? '-active' : '' ?>">
            <a href="<?= \PNM\Request::makeURL('titles') ?>" class="nav_link"><?= IconView::get('title', '') ?> Titles</a>
        </li>
        <li class="nav_item <?= in_array($page, ['people', 'person', 'attestation']) ? '-active' : '' ?>">
            <a href="<?= \PNM\Request::makeURL('people') ?>" class="nav_link"><?= IconView::get('people', '') ?> People</a>
        </li>
        <li class="nav_item <?= in_array($page, ['inscriptions', 'inscription']) ? '-active' : '' ?>">
            <a href="<?= \PNM\Request::makeURL('inscriptions') ?>" class="nav_link"><?= IconView::get('object', '') ?> Inscribed Objects</a>
        </li>
        <li class="nav_item <?= in_array($page, ['places', 'place', 'group', 'workshop']) ? '-active' : '' ?>">
            <a href="<?= \PNM\Request::makeURL('places') ?>" class="nav_link"><?= IconView::get('place', '') ?> Places</a>
        </li>
        <li class="nav_item <?= in_array($page, ['collections', 'collection']) ? '-active' : '' ?>">
            <a href="<?= \PNM\Request::makeURL('collections') ?>" class="nav_link"><?= IconView::get('collection', '') ?> Collections</a>
        </li>
    </ul>
    <ul class="nav_list -aside">
        <li class="nav_item <?= in_array($page, ['types', 'type']) ? '-active' : '' ?>">
            <a href="<?= \PNM\Request::makeURL('types') ?>" class="nav_link -no-icon">Name Types</a>
        </li>
        <li class="nav_item <?= in_array($page, ['bibliography', 'publication']) ? '-active' : '' ?>">
            <a href="<?= \PNM\Request::makeURL('bibliography') ?>" class="nav_link -no-icon">Bibliography</a>
        </li>
        <li class="nav_item <?= in_array($page, ['info', 'criterion']) ? '-active' : '' ?>">
            <a href="<?= \PNM\Request::makeURL('info') ?>" class="nav_link -no-icon">Info</a>
        </li>
    </ul>
</nav>
