<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PNM;

/**
 * Description of startView
 *
 * @author Tomich
 */
class startView extends View {

    public function echoRender(&$data) {
        (New Head)->render(Head::HEADERFULL);
        ?>

        <p>The online database “Persons and Names of the Middle Kingdom” (PNM) is developed as part of the project <a href="https://www.aegyptologie.uni-mainz.de/umformung-und-variabilitaet-1/">“Umformung und Variabilität im Korpus altägyptischer Personennamen 2055–1550 v.&nbsp;Chr.”</a>, funded by the <a href="http://www.fritz-thyssen-stiftung.de">Fritz Thyssen Foundation</a>. The database is currently under development and will include data on Egyptian Middle Kingdom personal names, people, written sources, titles, and dossiers of persons attested in different sources.</p>

        <h2>Start your search</h2>

        <div class="cards">
            <a class="cards_link" href="<?= Request::makeURL('names') ?>"><?= icon('name', '') ?> Personal Names</a>
            <a class="cards_link" href="<?= Request::makeURL('titles') ?>"><?= icon('title', '') ?> Titles</a>
            <a class="cards_link" href="<?= Request::makeURL('people') ?>"><?= icon('people', '') ?> People</a>
            <a class="cards_link" href="<?= Request::makeURL('inscriptions') ?>"><?= icon('object', '') ?> Inscribed Objects</a>
            <a class="cards_link" href="<?= Request::makeURL('places') ?>"><?= icon('place', '') ?> Places</a>
            <a class="cards_link" href="<?= Request::makeURL('collections') ?>"><?= icon('collection', '') ?> Collections</a>
        </div>

        <p>Additionally, have a look at <a href="types.php">the list of all name types</a> the <a href="bibliography.php">the bibliography</a>.</p>

        <h2>Information about the database</h2>
        <ul>
            <?php
            foreach ($data as $entry) {
                echo '<li><h3><a href="' . Request::makeURL('info') . '/' . urldecode($entry[0]) . '">', $entry[0], '</h3></li>';
            }
            ?></ul><?php
    }

}

/*
 * <p>During development, documentation and source code are made available on <a href="https://github.com/ailintom/persons-names-MK">GitHub</a>. A <a href="https://github.com/ailintom/persons-names-MK/blob/master/Database%20structure.md">predraft of the database structure</a> has already been put online.</p>

<h2>Resources</h2>
<p>The <a href="http://www.orientalstudies.ru/eng/index.php?option=com_content&amp;task=view&amp;id=2882&amp;Itemid=138">slip-index of Middle Kingdom names from the archive of Oleg D. Berlev</a> is a crucial resource for scholars of Middle Kingdom anthroponymy and prosopography. Besides, the slips of the Berlin Wörterbuch project with attestations of personal names are accessible in the <a href="http://aaew.bbaw.de/tla/servlet/DzaBrowser?newpid%3DDZA%2B40.000.000%26show%3Danzeigen!%26dispscale%3D100%26wn%3D0%26wid%3D0">Digitized Slip Archive (DZA)</a> under numbers 40.000.000–40.677.960. The <a href="http://www.griffith.ox.ac.uk/gri/3biblist.html">working files of the Topographical Bibliography</a> (2011–2012) are a valuable supplement to the <a href="http://topbib.griffith.ox.ac.uk//pdf.html">published volumes</a> and the <a href="http://topbib.griffith.ox.ac.uk//dtb.html?topbib=intro">online database</a>. Increasingly many museums are making metadata and photographs of Egyptian objects accessible in online museum databases. Most of these are searchable with <a href="http://static.egyptology.ru/varia/mus.php">Egyptological Museum Search</a>.</p>

<h2>Tools</h2>
<p>Tools tangentially related to this project include <a href="http://static.egyptology.ru/varia/mus.php">Egyptological Museum Search</a>, <a href="https://pnm.uni-mainz.de/tools/unicode/">Transliteration to Unicode Converter</a>, and <a href="http://static.egyptology.ru/varia/oeb2zotero.html">OEB to Zotero</a>.</p>

 */