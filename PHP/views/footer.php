<?php

namespace PNM;
?></div><?php // .main_content   ?>
</main>
<footer class="footer">
    <div class="footer_content">
        <div class="row -fluid -centered-on-small">
            <div class="column">
                <p>
                    Copyright 2017 Alexander Ilin-Tomich.
                    Content licensed under <a href="https://creativecommons.org/licenses/by/4.0/" title="Creative Commons Attribution 4.0 International">CC BY 4.0</a>, except for logos.
                    Code <a href="https://github.com/ailintom/persons-names-MK">available on GitHub</a> under MIT license.
                </p>
                <p>
                    <a class="footer_link" href="<?= Request::makeURL('info') ?>/impressum">Impressum</a>
                    <a class="footer_link" href="<?= Request::makeURL('info') ?>/privacy">Privacy policy</a>
                </p>
            </div>
            <div class="column">
                <p class="footer_logos">
                    <a href="https://uni-mainz.de/">
                        <img src="<?= Config::BASE ?>assets/logos/johannes-gutenberg-universitaet-logo.svg" alt="Johannes Gutenberg University Mainz" width="145" height="75">
                    </a>
                    <a href="http://www.fritz-thyssen-stiftung.de">
                        <img src="<?= Config::BASE ?>assets/logos/fritz-thyssen-stiftung-logo.svg" alt="Fritz Thyssen Foundation" width="150" height="73">
                    </a>
                </p>
            </div>
        </div>
    </div>
</footer>
<?= Icon::echoSvgFooter() ?>
<script src="<?= Config::BASE ?>assets/script/script.js"></script>
<script src="<?= Config::BASE ?>assets/script/datalist.polyfill.min.js"></script>
<!--datalist polyfill for browsers such as Safari-->
</body>
