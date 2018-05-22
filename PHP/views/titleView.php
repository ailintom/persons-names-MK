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

class titleView extends View {
    /*
     * 
     *   $this->field_names = new FieldList(['collections_id', 'title', 'full_name_en', 'full_name_national_language', 'location', 'IFNULL(url, online_collection)', 'IF(online_collection>"", "available", "")', 'tm_coll_id',
      'SELECT COUNT(DISTINCT inscriptions_id) FROM inv_nos WHERE inv_nos.collections_id = collections.collections_id and status<>"erroneous"'],
      ['collections_id', 'title', 'full_name_en', 'full_name_national_language',  'location', 'url', 'online_collection', 'tm_coll_id',
      'inscriptions_count']);
     */

    public function echoRender(&$data) {
        $subtitle = NULL;
        if (!empty($data->get('translation_en'))) {
            $subtitle = '<span class="translation" lang="en">“' . $data->get('translation_en') . '”</span>' . (empty($data->get('translation_de')) ? NULL : ', ');
        }
        if (!empty($data->get('translation_de'))) {
            $subtitle .= '<span class="translation" lang="de">“' . $data->get('translation_de') . '”</span>';
        }
        (New Head)->render(Head::HEADERSLIM, $data->get('title'));
        ?>
        <p>
            <?= $subtitle ?>
        </p>
        <dl>
            <?php
            echo $this->descriptionElement('Note', $data->get('note'), NULL, 'note'),
            $this->descriptionElement('Usage area', $data->get('usage_area'), $data->get('usage_area_note'), 'place'),
            $this->descriptionElement('Usage period', $data->get('usage_period'), $data->get('usage_period_note'), 'period'),
            $this->descriptionElement('Gender', $data->get('gender'), NULL, 'gender');

            $predicate = NULL;
            $pred_string = NULL;
            $titlesMV = New titlesMicroView;

            foreach ($data->get('relations')->data as $relation) {
                if ($relation['predicate'] !== $predicate) {
                    $this->echoRelation($predicate, $pred_string);
                    $predicate = $relation['predicate'];
                    $pred_string = NULL;
                }
                $pred_string .= (empty($pred_string) ? NULL : ', ' ) . $titlesMV->render($relation['title'], $relation['titles_id']);
            }
            $this->echoRelation($predicate, $pred_string);

            echo $this->descriptionElement('Bibliography', $data->get('bibliography'), NULL, 'biblio-ref');
            $ref = $this->addReference('Ward and Fischer no.', $data->get('ward_fischer'));
            $ref = $this->addReference('TLA no.', $data->get('tla'), 'http://aaew.bbaw.de/tla/servlet/GetWcnDetails?wn=', $ref);
            $ref = $this->addReference('Hannig no.', $data->get('hannig'), NULL, $ref);
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
                <input id="any" name="geo-filter" type="radio" value="any" aria-labelledby="region-label"<?= View::oldValueRadio('geo-filter', 'any', TRUE) ?>>
                <label for="any" title="Attestations in sources anyhow related to a certain region">
                    any
                </label>
                in the region
                <label for="place" class="sr-only">Region</label>
                <input id="place" list="places" name="place" placeholder="region or locality" title="Enter the region" type="text" <?= View::oldValue('place') ?>>
            </p>

            <p>
                <span id="period-label">Chronological filter:</span>
                <input id="during" name="chrono-filter" type="radio" value="exact" aria-labelledby="period-label"<?= View::oldValueRadio('chrono-filter', 'exact', TRUE) ?>>
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
                <label for="period" class="sr-only">Period</label>
                <input id="period" list="periods" name="period" placeholder="period or reign" title="Enter the period" type="text" <?= View::oldValue('period') ?>>
            </p>

            <button type="submit" class="submit">
                Filter
            </button>
            <button type="submit" title="Clear filters and display all records" name="action" value="reset">
                Reset
            </button>

            <?php
            $dl = new Datalist();
            echo $dl->get('periods');
            echo $dl->get('places');
            ?>
        </form>

        <h2 class="sr-only">Results</h2>
        <?php
        $tableAtt = new Table($data->get('attestations'), 'inscriptions_id', 'inscription');
        $tableAtt->render_table(['personal_name', 'gender', 'title_string', 'title', 'dating', 'region', 'persons'], ['Name', 'Gender', 'Titles', 'Object', 'Date', 'Region', 'Person'], TRUE, 'attestations');


        // ['inscriptions_id', 'attestations_id', 'personal_name', 'gender', 'title_string', 'title', 'dating', 'origin', 'count_persons']
    }

    protected function echoRelation($predicate, $pred_string) {
        if (!empty($predicate) && !empty($pred_string)) {
            echo $this->descriptionElement(ucfirst($predicate), $pred_string, NULL, 'note');
        }
    }

}
