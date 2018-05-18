<header class="header">
    <div class="header_content">
        <div class="header_title">
            <a href="index.php">
                Persons <span>and</span> Names <span>of the</span> Middle Kingdom
            </a>
        </div>
        <button class="header_nav" type="button" aria-controls="nav" aria-expanded="false" onclick="MK.toggleNav(this)">
            <?=icon('menu') . icon('cross')?>
            Menu
        </button>
        <div class="header_aside">
            <?php if (isset($stableUrl)) : ?>
                <p>
                    <a href="<?=$stableUrl?>">Stable URL</a>
                </p>
            <?php endif; ?>
            <p>
                <label for="version">Version:</label>
                <select id="version">
                    <option value="current" selected>3 (2017-09-22) current</option>
                    <option value="2">2 (2017-07-22)</option>
                    <option value="1">1 (2016-09-18)</option>
                </select>
            </p>
        </div>
    </div>
</header>

<main class="main">
    <?php require 'views/nav.php'; ?>

    <div class="main_content" id="content">
        <?php // closed in ./footer.php ?>
