<?php
/*
 * This script renders the menu
 */

namespace PNM\views;

use \PNM\Request;

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
