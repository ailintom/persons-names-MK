<?php
/*
 * This script renders the page header with a narrow title image
 */

namespace PNM\views;
const MAX_STABLE_URL_LENGTH = 35;
?><header class="header">
    <div class="header_content">
        <div class="header_title">
            <a href="<?= \PNM\Request::makeURL('info', null, null, null, true, -1, false, \PNM\Request::maxVer()); ?>">
                Persons <span>and</span> Names <span>of the</span> Middle Kingdom
            </a>
        </div>
        <button class="header_nav" type="button" aria-controls="nav" aria-expanded="false" onclick="MK.toggleNav(this)">
            <?= Icon::get('menu') . Icon::get('cross') ?>
            Menu
        </button>
        <div class="header_aside">
            <?php
            $stableURL = \PNM\Request::stableURL();
            if (!empty($stableURL)) :
                $stableURLDisplayed = \PNM\Config::HOST . ( strlen($stableURL) < MAX_STABLE_URL_LENGTH ? $stableURL : substr($stableURL, 0, MAX_STABLE_URL_LENGTH-3) . '...')
                ?>
                <a href="<?= $stableURL ?>">Stable URL<span class="print_only">: <?= $stableURLDisplayed ?></span></a>
            <?php endif; ?>
            <label for="version" class="sr-only">Change version:</label>
            <select id="version" onchange ="window.location.href = this.value;" class="header_version">
                <?php
                $vers = \PNM\Config::VERSIONS;

                $curver = $vers[array_search(\PNM\Request::get('used_ver'), array_column($vers, 0))];
                foreach ($vers as $version) {
                    echo '<option value="' . ($version[0] == \PNM\Request::get('used_ver') ? '#' : \PNM\Request::changeVer($version[0])) . '" ' . ($version[0] == \PNM\Request::get('used_ver') ? 'selected' : null) . '>Version ' . $version[0] . ' (' . $version[1] . ')' . '</option>';
                }
                ?>
            </select>
        </div>
    </div>
</header>
<main class="main">
    <?php require 'views/nav.php'; ?>
    <div class="main_content" id="content">
        <?php // closed in footer.php   ?>
