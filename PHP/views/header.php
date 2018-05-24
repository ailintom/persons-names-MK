<?php

namespace PNM;
?><header class="header">
    <div class="header_content">
        <div class="header_title">
            <a href="<?= Request::makeURL('info', NULL, NULL, NULL, TRUE, -1, FALSE, Config::maxVer());?>">
                Persons <span>and</span> Names <span>of the</span> Middle Kingdom
            </a>
        </div>
        <button class="header_nav" type="button" aria-controls="nav" aria-expanded="false" onclick="MK.toggleNav(this)">
<?= icon('menu') . icon('cross') ?>
            Menu
        </button>
        <div class="header_aside">
<?php if (!empty(Request::stableURL())) : ?>
                <p>
                    <a href="<?= Request::stableURL() ?>">Stable URL</a>
                </p>
<?php endif; ?>
            <p>
                <label for="version">Version:</label>
                <select id="version" onchange ="window.location.href = this.value;">
                    <?php
                    foreach (Config::VERSIONS as $version) {
                        echo '<option value="' . ($version[0] == Request::get('used_ver')  ? '#' : Request::changeVer( $version[0])) . '" ' . ($version[0] == Request::get('used_ver') ? 'selected' : NULL) . '>' . $version[1] . '</option>';
                    }
                    ?>
                </select>
            </p>
        </div>
    </div>
</header>

<main class="main">
        <?php require 'views/nav.php'; ?>

    <div class="main_content" id="content">
<?php // closed in ./footer.php  ?>
