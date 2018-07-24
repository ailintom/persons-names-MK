<?php
/*
 * MIT License
 *
 * Copyright (c) 2017 Alexander Ilin-Tomich (unless specified otherwise for individual source files and documents)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace PNM;

/*
 *
 *
 */

class placeView extends View
{

//put your code here
    public function echoRender(&$data)
    {
        if (empty($data->get('places_id'))) {
            ?>
            <p class="info-box">
                <?= Icon::get('info') ?>
                Not found in the selected version of the database.
            </p>
            <?php
            return null;
        }
        (new Head())->render(Head::HEADERSLIM, $data->get('place_name'));
        if ($data->get('count_find_groups') + $data->get('count_workshops') > 0) {
            ?>
            <div class="toc">
                <h2>Contents</h2>
                <ul class="toc_list">
                    <?php
                    if ($data->get('count_find_groups') > 0) {
                        echo('<li><a href="#find-groups">find-groups (' . $data->get('count_find_groups') . ')</a></li>');
                    }
                    if ($data->get('count_workshops') > 0) {
                        echo('<li><a href="#workshops">workshops (' . $data->get('count_workshops') . ')</a></li>');
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
        ?>
        <dl>
            <?php
            $placesMV = new placesMicroView();
            echo( $this->descriptionElement('Full name', $data->get('long_place_name'), null, 'place'));
            echo( $this->descriptionElement('Location', $data->get('relative_location'), null, 'place'));
            echo( $this->descriptionElement('Macro-region', $placesMV->render($data->get('macro_region')), null, 'place'));
            echo( $this->descriptionElement('Latitude', $this->renderLat($data->get('latitude')), null, 'latitude'));
            $ref = $this->addReference('Topographical Bibliography ID', $data->get('topbib_id'), ExternalLinks::TOP_BIB);
            $ref = $this->addReference('Trismegistos Geo ID', $data->get('tm_geoid'), ExternalLinks::TRISMEGISTOS_GEO, $ref);
            $ref = $this->addReference('Artefacts of Excavations', $data->get('artefacts_url'), null, $ref);
            echo( $this->descriptionElement('References', $ref));
            ?>
        </dl>
        <dl class="-free">
            <dt>Inscribed objects found or purchased at this place:</dt>
            <dd><a href="<?= Request::makeURL('inscriptions') ?>?geo-filter=provenance&amp;place=<?php echo( $data->get('place_name')) ?>"><?php echo( $data->get('count_provenance') ?: '0') ?></a></dd>
            <dt>Inscribed objects that should have been installed at this place:</dt>
            <dd><a href="<?= Request::makeURL('inscriptions') ?>?geo-filter=installation&amp;place=<?php echo( $data->get('place_name')) ?>"><?php echo( $data->get('count_installation_place') ?: '0') ?></a></dd>
            <dt>Inscribed objects owned by people from this place:</dt>
            <dd><a href="<?= Request::makeURL('inscriptions') ?>?geo-filter=origin&amp;place=<?php echo( $data->get('place_name')) ?>"><?php echo( $data->get('count_origin') ?: '0') ?></a></dd>
            <dt>Inscribed objects produced at this place:</dt>
            <dd><a href="<?= Request::makeURL('inscriptions') ?>?geo-filter=production&amp;place=<?php echo( $data->get('place_name')) ?>"><?php echo( $data->get('count_production_place') ?: '0') ?></a></dd>
            <dt>All inscribed objects related to this place:</dt>
            <dd><a href="<?= Request::makeURL('inscriptions') ?>?geo-filter=all&amp;place=<?php echo( $data->get('place_name')) ?>"><?php echo( $data->get('count_total') ?: '0') ?></a></dd>
        </dl>
        <?php
        if (intval($data->get('count_find_groups')) > 0) {
            ?>
            <h2 id="find-groups">Find-groups with inscribed objects</h2>
            <p><?php echo( $data->get('count_find_groups')) ?> in total</p>
            <?php
            $tableFG = new Table($data->get('find_groups'), 'find_groups_id', 'group', 'find_groups_sort', '#find_groups');
            $tableFG->renderTable(['title', 'dating', 'find_group_type', 'inscriptions_count'], ['Find group', 'Date', 'Type', 'Inscribed objects']);
        }
        if (intval($data->get('count_workshops')) > 0) {
            ?>
            <h2 id="workshops">Workshops</h2>
            <p><?php echo( $data->get('count_workshops')) ?> in total</p>
            <?php
            $tableWk = new Table($data->get('workshops'), 'workshops_id', 'workshop', 'workshops_sort', '#workshops');
            $tableWk->renderTable(['title', 'dating', 'inscriptions_count'], ['Find group', 'Date', 'Inscribed objects']);
        }
    }
}

/*
 *
 * [
            'places_id', 'place_name', 'long_place_name', 'relative_location', 'latitude', 'topbib_id', 'tm_geoid', 'pleiades_id', 'artefacts_url',
            'count_provenance', 'count_installation_place', 'count_origin', 'count_production_place']
 *
 */