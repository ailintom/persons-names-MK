<?php
/*
 * This script renders the page footer
 */

namespace PNM\views;

use \PNM\Request,
    \PNM\Config;
?></div><?php // .main_content      ?>
</main>
<footer class="footer">
    <div class="footer_content">
        <div class="row -fluid -centered-on-small">
            <div class="column">
                <p>
                    <span style="display:inline-block">Copyright 2022 Alexander Ilin-Tomich.
                    Content licensed under <a href="https://creativecommons.org/licenses/by/4.0/" title="Creative Commons Attribution 4.0 International">CC BY 4.0</a>, except for logos.</span> <span style="display:inline-block">RDF version <a href="https://pnm.uni-mainz.de/sparql">via SPARQL endpoint</a>. Dataset under <a href="http://dx.doi.org/10.5281/zenodo.1411391">doi:10.5281/zenodo.1411391</a>. Source code <a href="https://github.com/ailintom/persons-names-MK">on GitHub</a>.</span>
                </p>
                <p>
                    <a class="footer_link" href="<?= Request::makeURL('info') ?>/impressum">Impressum</a>
                    <a class="footer_link" href="<?= Request::makeURL('info') ?>/privacy">Privacy Policy</a>
                </p>
            </div>
            <div class="column">
                <p class="footer_logos">
                    <a href="https://www.uni-mainz.de/">
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
</body></html>
