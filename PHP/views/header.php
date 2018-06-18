<?php

namespace PNM;
?><header class="header">
    <div class="header_content">
        <div class="header_title">
            <a href="<?= Request::makeURL('info', null, null, null, true, -1, false, Config::maxVer()); ?>">
                Persons <span>and</span> Names <span>of the</span> Middle Kingdom
            </a>
        </div>
        <button class="header_nav" type="button" aria-controls="nav" aria-expanded="false" onclick="MK.toggleNav(this)">
            <?= Icon::get('menu') . Icon::get('cross') ?>
            Menu
        </button>
        <div class="header_aside">
            <?php if (!empty(Request::stableURL())) : ?>
                <p>
                    <a href="<?= Request::stableURL() ?>">Stable URL<span class="print-only">: <?= Config::HOST . Request::stableURL() ?></span></a>
                </p>
            <?php endif; ?>
            <p>
                <label for="version">Version:</label> <select id="version" onchange ="window.location.href = this.value;">
                    <?php
                    $vers = Config::VERSIONS;

                    $curver = $vers[array_search(Request::get('used_ver'), array_column($vers, 0))];                           
                    foreach ($vers as $version) {
                        echo '<option value="' . ($version[0] == Request::get('used_ver') ? '#' : Request::changeVer($version[0])) . '" ' . ($version[0] == Request::get('used_ver') ? 'selected' : null) . '>' . $version[0] . ' (' . $version[1] . ')' . '</option>';
                    }
                    ?>
                </select><span class="print-only"><?= $curver[0] . ' (' . $curver[1] . ')' ?></span>
            </p>
        </div>
    </div>
</header>
<main class="main">
    <?php require 'views/nav.php'; ?>
    <div class="main_content" id="content">
        <?php // closed in ./footer.php  ?>
