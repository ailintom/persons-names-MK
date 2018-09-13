<?php
/*
 * Description of titleView
 * Class used to render a page representing a single title (with all attestations)
 */

namespace PNM\views;

use \PNM\Request;

class titleView extends View
{
    /*
      ['collections_id', 'title', 'full_name_en', 'full_name_national_language',  'location', 'url', 'online_collection', 'tm_coll_id',
      'inscriptions_count']);
     */

    public function echoRender(&$data)
    {
        $subtitle = null;
        if (!empty($data->get('translation_en'))) {
            $subtitle = '<span class="translation" lang="en">“' . $data->get('translation_en') . '”</span>' . (empty($data->get('translation_de')) ? null : ', ');
        }
        if (!empty($data->get('translation_de'))) {
            $subtitle .= '<span class="translation" lang="de">“' . $data->get('translation_de') . '”</span>';
        }
        (new HeadView())->render(HeadView::HEADERSLIM, $data->get('title'));
        ?>
        <p>
            <?= $subtitle ?>
        </p>
        <dl>
            <?php
            echo $this->descriptionElement('Note', $data->get('note'), null, 'note'),
            $this->descriptionElement('Usage area', $data->get('usage_area'), $data->get('usage_area_note'), 'place'), $this->descriptionElement('Usage period', $data->get('usage_period'), $data->get('usage_period_note'), 'period'), $this->descriptionElement('Gender', $data->get('gender'), null, 'gender');
            $predicate = null;
            $pred_string = null;
            $titlesMV = new titlesMicroView();
            foreach ($data->get('relations')->data as $relation) {
                if ($relation['predicate'] !== $predicate) {
                    $this->echoRelation($predicate, $pred_string);
                    $predicate = $relation['predicate'];
                    $pred_string = null;
                }
                $pred_string .= (empty($pred_string) ? null : ', ' ) . $titlesMV->render($relation['title'], $relation['titles_id']);
            }
            $this->echoRelation($predicate, $pred_string);
            echo $this->descriptionElement('Bibliography', $this->renderBiblio($data->get('bibliography')), null, 'biblio-ref');
            $ref = $this->addReference('Ward and Fischer no.', $data->get('ward_fischer'));
            $ref = $this->addReference('TLA no.', $data->get('tla'), \PNM\ExternalLinks::TLA, $ref);
            $ref = $this->addReference('Hannig no.', $data->get('hannig'), null, $ref);
            echo( $this->descriptionElement('References', $ref));
            //  ['titles_id', 'title', 'gender', 'count_attestations', 'usage_period', 'usage_area', 'usage_period_note', 'usage_area_note', 'note', 'ward_fischer', 'hannig', 'tla', 'translation_en', 'translation_de']
            ?>
        </dl>
        <h2 id="attestations">Attestations</h2>
        <form action="<?= Request::makeURL('title', Request::get('id')) ?>" method="get" onreset="MK.removeAllFilters()">
            <h3 class="sr-only">Filters</h3>
            <p>
                <span id="region-label">Geographic filter:</span>
                <input id="provenance" name="geo-filter" type="radio" value="provenance" aria-labelledby="region-label"<?= View::oldValueRadio('geo-filter', 'provenance') ?>>
                <label for="provenance" title="Attestations in sources found in the certain region">
                    Provenance
                </label>
                /
                <input id="installation-place" name="geo-filter" type="radio" value="installation_place" aria-labelledby="region-label"<?= View::oldValueRadio('geo-filter', 'installation_place') ?>>
                <label for="installation-place" title="Attestations on monuments installed in certain region">
                    installation place
                </label>
                /
                <input id="origin" name="geo-filter" type="radio" value="origin" aria-labelledby="region-label"<?= View::oldValueRadio('geo-filter', 'origin') ?>>
                <label for="origin" title="Attestations on monuments owned by people from a certain region">
                    origin
                </label>
                /
                <input id="production" name="geo-filter" type="radio" value="production_place" aria-labelledby="region-label"<?= View::oldValueRadio('geo-filter', 'production_place') ?>>
                <label for="production" title="Attestations on monuments produced in a certain refino">
                    production
                </label>
                /
                <input id="any" name="geo-filter" type="radio" value="any" aria-labelledby="region-label"<?= View::oldValueRadio('geo-filter', 'any', true) ?>>
                <label for="any" title="Attestations in sources anyhow related to a certain region">
                    any
                </label>
                in the region
                <?= (new TextInput('place', 'Region', 'Enter the region', 'region or locality', 'places', true))->render() ?>
            </p>
            <p>
                <span id="period-label">Chronological filter:</span>
                <input id="during" name="chrono-filter" type="radio" value="exact" aria-labelledby="period-label"<?= View::oldValueRadio('chrono-filter', 'exact', true) ?>>
                <label for="during" title="Attestations in sources beloging to a certain period">
                    During
                </label>
                /
                <input id="not-later" name="chrono-filter" type="radio" value="not-later" aria-labelledby="period-label"<?= View::oldValueRadio('chrono-filter', 'not-later') ?>>
                <label for="not-later" title="Attestations in sources dating not (demonstrably) later than">
                    not later than
                </label>
                /
                <input id="not-earlier" name="chrono-filter" type="radio" value="not-earlier" aria-labelledby="period-label"<?= View::oldValueRadio('chrono-filter', 'not-earlier') ?>>
                <label for="not-earlier" title="Attestations in sources dating not (demonstrably) earlier than">
                    not earlier than
                </label>
                the period
                <?= (new TextInput('period', 'Period', 'Enter the period', 'period or reign', 'periods', true))->render() ?>
            </p>
            <button type="submit" class="submit">
                Filter
            </button>
            <button type="submit" title="Clear filters and display all records" name="action" value="reset">
                Reset
            </button>
        </form><?php
        if (empty($data->get('attestations')) || $data->get('attestations')->count == 0) {
            ?>
            <h2 class="sr-only">Nothing found</h2>&nbsp;
            <?php
        } else {
            ?>
            <h2 class="sr-only" id="results">Results</h2><?php
            $tableAtt = new TableView($data->get('attestations'), ['inscriptions_id', 'attestations_id'], 'inscription');
            $tableAtt->renderTable(['personal_name', 'gender', 'title_string', 'title', 'dating', 'region', 'persons'], ['Name', 'Gender', 'Titles', 'Object', 'Date', 'Region', 'Person'], true, 'attestations');
            // ['inscriptions_id', 'attestations_id', 'personal_name', 'gender', 'title_string', 'title', 'dating', 'origin', 'count_persons']
        }
    }

    protected function echoRelation($predicate, $pred_string)
    {
        if (!empty($predicate) && !empty($pred_string)) {
            echo $this->descriptionElement(ucfirst($predicate), $pred_string, null, 'note');
        }
    }
}
