<?php
namespace PNM;
?></div><?php // .main_content ?>
</main>

<footer class="footer">
    <div class="footer_content">
        <div class="row -fluid -centered-on-small">
            <div class="column">
                <p>
                    Copyright 2017 Alexander Ilin-Tomich.
                    Content licensed under <a href="https://creativecommons.org/licenses/by-sa/4.0/" title="Creative Commons Attribution-ShareAlike 4.0 International">CC BY-SA 4.0</a>, except for logos.
                    Code <a href="TODO">available on GitHub</a> under MIT license.
                </p>
                <p>
                    <a class="footer_link" href="<?=Config::BASE?>impressum.php">Impressum</a>
                    <a class="footer_link" href="TODO">Privacy policy</a>
                </p>
            </div>
            <div class="column">
                <p class="footer_logos">
                    <a href="https://uni-mainz.de/">
                        <img src="<?=Config::BASE?>assets/logos/johannes-gutenberg-universitaet-logo.svg" alt="Johannes Gutenberg University Mainz" width="145" height="75">
                    </a>
                    <a href="http://www.fritz-thyssen-stiftung.de">
                        <img src="<?=Config::BASE?>assets/logos/fritz-thyssen-stiftung-logo.svg" alt="Fritz Thyssen Foundation" width="150" height="73">
                    </a>
                </p>
            </div>
        </div>
    </div>
</footer>

<?php if ($svgSymbols) : ?>
    <svg xmlns="http://www.w3.org/2000/svg" class="hidden">
        <?php
        foreach ($svgSymbols as $svgSymbol) {
            echo $svgSymbol['symbol'];
        }
        ?>
    </svg>
<?php endif; ?>

<script src="<?=Config::BASE?>assets/script/script.js"></script>

</body>
