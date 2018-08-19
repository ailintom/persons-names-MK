<?php
/*
 * Description of placeView
 * Class used to render a page representing a single place (with workshops and find groups)
 */

namespace PNM\views;

class placeView extends View
{

    public function echoRender(&$data)
    {
        if (empty($data->get('places_id'))) {
            ?>
            <p class="info-box">
                <?= IconView::get('info') ?>
                Not found in the selected version of the database.
            </p>
            <?php
            return null;
        }
        (new HeadView())->render(HeadView::HEADERSLIM, $data->get('place_name'));
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
            $ref = $this->addReference('Topographical Bibliography ID', $data->get('topbib_id'), \PNM\ExternalLinks::TOP_BIB);
            $ref = $this->addReference('Trismegistos Geo ID', $data->get('tm_geoid'), \PNM\ExternalLinks::TRISMEGISTOS_GEO, $ref);
            $ref = $this->addReference('Artefacts of Excavations', $data->get('artefacts_url'), null, $ref);
            echo( $this->descriptionElement('References', $ref));
            ?>
        </dl>
        <dl class="-free">
            <dt>Inscribed objects found or purchased at this place:</dt>
            <dd><a href="<?= \PNM\Request::makeURL('inscriptions') ?>?geo-filter=provenance&amp;place=<?php echo( $data->get('place_name')) ?>"><?php echo( $data->get('count_provenance') ?: '0') ?></a></dd>
            <dt>Inscribed objects that should have been installed at this place:</dt>
            <dd><a href="<?= \PNM\Request::makeURL('inscriptions') ?>?geo-filter=installation&amp;place=<?php echo( $data->get('place_name')) ?>"><?php echo( $data->get('count_installation_place') ?: '0') ?></a></dd>
            <dt>Inscribed objects owned by people from this place:</dt>
            <dd><a href="<?= \PNM\Request::makeURL('inscriptions') ?>?geo-filter=origin&amp;place=<?php echo( $data->get('place_name')) ?>"><?php echo( $data->get('count_origin') ?: '0') ?></a></dd>
            <dt>Inscribed objects produced at this place:</dt>
            <dd><a href="<?= \PNM\Request::makeURL('inscriptions') ?>?geo-filter=production&amp;place=<?php echo( $data->get('place_name')) ?>"><?php echo( $data->get('count_production_place') ?: '0') ?></a></dd>
            <dt>All inscribed objects related to this place:</dt>
            <dd><a href="<?= \PNM\Request::makeURL('inscriptions') ?>?geo-filter=all&amp;place=<?php echo( $data->get('place_name')) ?>"><?php echo( $data->get('count_total') ?: '0') ?></a></dd>
        </dl>
        <?php
        if (intval($data->get('count_find_groups')) > 0) {
            ?>
            <h2 id="find-groups">Find-groups with inscribed objects</h2>
            <p><?php echo( $data->get('count_find_groups')) ?> in total</p>
            <?php
            $tableFG = new TableView($data->get('find_groups'), 'find_groups_id', 'group', 'find_groups_sort', '#find_groups');
            $tableFG->renderTable(['title', 'dating', 'find_group_type', 'inscriptions_count'], ['Find group', 'Date', 'Type', 'Inscribed objects']);
        }
        if (intval($data->get('count_workshops')) > 0) {
            ?>
            <h2 id="workshops">Workshops</h2>
            <p><?php echo( $data->get('count_workshops')) ?> in total</p>
            <?php
            $tableWk = new TableView($data->get('workshops'), 'workshops_id', 'workshop', 'workshops_sort', '#workshops');
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