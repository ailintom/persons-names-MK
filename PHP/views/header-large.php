<?php
/*
 * This script renders the page header with a large title image
 */

namespace PNM\views;
use \PNM\Request;
?><header class="header -large">
    <div class="header_content" data-nosnippet>
        <h1 class="header_title">
            <a href="<?= Request::makeURL('info') ?>">
                 <?= 
(Request::get('used_ver') >=5) ?
     "Persons <span>and</span> Names <span>of the</span> Middle Kingdom <span>and</span> early New Kingdom"
:
     "Persons <span>and</span> Names <span>of the</span> Middle Kingdom"
;
 ?>
            </a>
        </h1>
    </div>
</header>
<main class="main">
    <div class="main_content -centered" id="content">
        <?php // closed in ./footer.php ?>
