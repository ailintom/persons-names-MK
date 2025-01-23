<?php
/*
 * This script renders the page header with a narrow title image
 */

namespace PNM\views;

use \PNM\Request,
    \PNM\Config;
?><header class="header">
    <div class="header_content" data-nosnippet>
        <div class="header_title">
            <a href="<?= Request::makeURL('info', null, null, null, true, -1, false, Request::maxVer()); ?>">
                <?= 
(Request::get('used_ver') >=5) ?
     "Persons <span>and</span> Names <span>of the</span> Middle Kingdom <span>and</span> early New Kingdom"
:
     "Persons <span>and</span> Names <span>of the</span> Middle Kingdom"
;
 ?>
            </a>
        </div>
        <button class="header_nav" type="button" aria-controls="nav" aria-expanded="false" onclick="MK.toggleNav(this)">
<?= Icon::get('menu') . Icon::get('cross') ?>
            Menu
        </button>
        <div class="header_aside">
            <?php
            $stableURL = Request::stableURL();
            if (!empty($stableURL)) :
                $stableURLDisplayed = Config::HOST . ( strlen($stableURL) < Config::MAX_STABLE_URL_LENGTH ? $stableURL : substr($stableURL, 0, Config::MAX_STABLE_URL_LENGTH - 3) . '...')
                ?>
                <a href="<?= $stableURL ?>">Stable URL<span class="print_only">: <?= $stableURLDisplayed ?></span></a>
<?php endif; ?>
            <label for="version" class="sr-only">Change version:</label>
            <select id="version" onchange ="window.location.href = this.value;" class="header_version">
                <?php
                $vers = Config::VERSIONS;

                $curver = $vers[array_search(Request::get('used_ver'), array_column($vers, 0))];
                foreach ($vers as $version) {
                    echo '<option value="' . ($version[0] == Request::get('used_ver') ? '#' : Request::changeVer($version[0])) . '" ' . ($version[0] == Request::get('used_ver') ? 'selected' : null) . '>Version ' . $version[0] . ' (' . $version[1] . ')' . '</option>';
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
