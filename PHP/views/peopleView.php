<?php
/*
 * Description of peopleView
 * This class renders the people page
 */

namespace PNM\views;

use \PNM\Request;

class peopleView extends View
{

    public function echoRender(&$data)
    {
        (new HeadView())->render(HeadView::HEADERSLIM, 'People');
        ?>
        <p class="info-box">
            <?= Icon::get('info') ?>
            You can use <b>%</b> or <b>*</b> as wildcards.
            “nfr*” will match “nfr.wj” or “nfr-ḥtp”. “*nfr*” will also match “snfr.wj”.
        </p>
        <form action="<?= Request::makeURL('people') ?>" method="get">
            <div class="row -border">
                <div class="column">
                    <h2>Person A</h2>
                    <div class="row -small">
                        <div class="column">
                            <label id="gender-label-a">Gender:</label>
                        </div>
                        <div class="column -wide">
                            <input id="Aany" name="Agender" value="any" type="radio" aria-labelledby="gender-label-a"<?= View::oldValueRadio('Agender', 'any', true) ?>>
                            <label for="Aany" title="Match people regardless of gender">any</label>
                            /
                            <input id="Afemale" name="Agender" value="f" type="radio" aria-labelledby="gender-label-a"<?= View::oldValueRadio('Agender', 'f') ?>>
                            <label for="Afemale" title="Match women">female</label>
                            /
                            <input id="Amale" name="Agender" value="m" type="radio" aria-labelledby="gender-label-a"<?= View::oldValueRadio('Agender', 'm') ?>>
                            <label for="Amale" title="Match men">male</label>
                            /
                            <input id="AAnimal" name="Agender" value="a" type="radio" aria-labelledby="gender-label-a"<?= View::oldValueRadio('Agender', 'a') ?>>
                            <label for="AAnimal" title="Match animals">animal</label>
                        </div>
                    </div>
                    <div class="row -small">
                        <div class="column">
                            <label for="Atitle">Title:</label>
                        </div>
                        <div class="column -wide">
                            <input name="Atitle" id="Atitle" placeholder="MdC (jmj-rA pr) / Unicode (jmj-rꜣ pr)" type="text" <?= View::oldValue('Atitle') ?>>
                        </div>
                    </div>
                    <div class="row -small">
                        <div class="column">
                            <label for="Aname">Name:</label>
                        </div>
                        <div class="column -wide">
                            <input name="Aname" id="Aname" placeholder="MdC (ra-Htp) / Unicode (rꜥ-ḥtp)" type="text" <?= View::oldValue('Aname') ?>>
                        </div>
                    </div>
                    <div class="row -small">
                        <div class="column">
                            <label for="Aform_type">Name pattern:</label>
                        </div>
                        <div class="column -wide">
                            <?= (new TextInput('Aform_type', 'Name pattern', 'Select a formal name pattern', 'Example: GN-ḥtp', 'name-types-formal', true))->render() ?>
                        </div>
                    </div>
                    <div class="row -small">
                        <div class="column">
                            <label for="Asem_type">Semantic class:</label>
                        </div>
                        <div class="column -wide">
                            <?= (new TextInput('Asem_type', 'Semantic class', 'Select a semantic class', 'Example: theophoric name', 'name-types-semantic', true))->render() ?>
                        </div>
                    </div>
                </div>
                <div class="column -border">
                    <h2>Person B</h2>
                    <div class="row -small">
                        <div class="column">
                            <label id="gender-label-b">Gender:</label>
                        </div>
                        <div class="column -wide">
                            <input id="Bany" name="Bgender" value="any" type="radio" aria-labelledby="gender-label-b"<?= View::oldValueRadio('Bgender', 'any', true) ?>>
                            <label for="Bany" title="Match names regardless of gender">any</label>
                            /
                            <input id="Bfemale" name="Bgender" value="f" type="radio" aria-labelledby="gender-label-b"<?= View::oldValueRadio('Bgender', 'f') ?>>
                            <label for="Bfemale" title="Match names borne only by women">female</label>
                            /
                            <input id="Bmale" name="Bgender" value="m" type="radio" aria-labelledby="gender-label-b"<?= View::oldValueRadio('Bgender', 'm') ?>>
                            <label for="Bmale" title="Match names borne only by men">male</label>
                        </div>
                    </div>
                    <div class="row -small">
                        <div class="column">
                            <label for="Btitle">Title:</label>
                        </div>
                        <div class="column -wide">
                            <input name="Btitle" id="Btitle" placeholder="MdC (jmj-rA pr) / Unicode (jmj-rꜣ pr)" type="text" <?= View::oldValue('Btitle') ?>>
                        </div>
                    </div>
                    <div class="row -small">
                        <div class="column">
                            <label for="Bname">Name:</label>
                        </div>
                        <div class="column -wide">
                            <input name="Bname" id="Bname" placeholder="MdC (ra-Htp) / Unicode (rꜥ-ḥtp)" type="text" <?= View::oldValue('Bname') ?>>
                        </div>
                    </div>
                    <div class="row -small">
                        <div class="column">
                            <label for="Bform_type">Name pattern:</label>
                        </div>
                        <div class="column -wide">
                            <?= (new TextInput('Bform_type', 'Name pattern', 'Select a formal name pattern', 'Example: GN-ḥtp', 'name-types-formal', true))->render() ?>
                        </div>
                    </div>
                    <div class="row -small">
                        <div class="column">
                            <label for="Bsem_type">Semantic class:</label>
                        </div>
                        <div class="column -wide">
                            <?= (new TextInput('Bsem_type', 'Semantic class', 'Select a semantic class', 'Example: theophoric name', 'name-types-semantic', true))->render() ?>
                        </div>
                    </div>
                </div>
            </div>
            <p>
                <label for="relation">Relation between A and B:</label>
                <select name="relation" id="relation">
                    <option value="same_inscription"<?= View::oldValueSelect('relation', 'same_inscription', true) ?>>A and B appear in the same source</option>
                    <option value="child"<?= View::oldValueSelect('relation', 'child') ?>>A is a child of B</option>
                    <option value="parent"<?= View::oldValueSelect('relation', 'parent') ?>>A is a parent of B</option>
                    <option value="spouses"<?= View::oldValueSelect('relation', 'spouses') ?>>A and B are spouses</option>
                    <option value="siblings"<?= View::oldValueSelect('relation', 'siblings') ?>>A and B are siblings</option>
                </select>
            </p>
            <p>
                <input id="only_persons" name="only_persons" value="true" type="checkbox" <?= View::oldValueRadio('only_persons', 'true') ?>>
                <label for="only_persons" title="Only show dossiers of persons with multiple attestations">Only dossiers of persons with multiple attestations</label>
            </p>
            <div class="filters">
                <h3 class="sr-only">Filters</h3>
                <div class="filters_selection">
                    <button class="filters_button" aria-controls="region-filter" aria-expanded="false" onclick="MK.toggleFilter('region-filter')" title="Toggle region filter" type="button">
                        <?= Icon::get('plus') . Icon::get('minus') ?>
                        Region or locality
                    </button>
                    <button class="filters_button" aria-controls="period-filter" aria-expanded="false" onclick="MK.toggleFilter('period-filter')" title="Toggle period filter" type="button">
                        <?= Icon::get('plus') . Icon::get('minus') ?>
                        Period or reign
                    </button>
                </div>
                <div class="filter" id="region-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('region-filter')" title="Remove region filter" type="button">
                            <?= Icon::get('minus', 'Remove region filter') ?>
                        </button>
                        <span id="region-label">Region or locality</span>
                    </div>
                    <div class="filter_content">
                        <input id="provenance" name="geo-filter" type="radio" value="provenance" aria-labelledby="region-label" <?= View::oldValueRadio('geo-filter', 'provenance') ?>>
                        <label for="provenance" title="Attestations in sources found in the certain region">
                            Provenance
                        </label>
                        /
                        <input id="installation-place" name="geo-filter" type="radio" value="installation-place" aria-labelledby="region-label" <?= View::oldValueRadio('geo-filter', 'installation-place') ?>>
                        <label for="installation-place" title="Attestations on monuments installed in certain region">
                            installation place
                        </label>
                        /
                        <input id="origin" name="geo-filter" type="radio" value="origin" aria-labelledby="region-label" <?= View::oldValueRadio('geo-filter', 'origin') ?>>
                        <label for="origin" title="Attestations on monuments owned by people from a certain region">
                            origin
                        </label>
                        /
                        <input id="production" name="geo-filter" type="radio" value="production" aria-labelledby="region-label" <?= View::oldValueRadio('geo-filter', 'production') ?>>
                        <label for="production" title="Attestations on monuments produced in a certain refino">
                            production
                        </label>
                        /
                        <input id="all" name="geo-filter" type="radio" value="all" aria-labelledby="region-label" <?= View::oldValueRadio('geo-filter', 'all', true) ?>>
                        <label for="all" title="Attestations in sources anyhow related to a certain region">
                            all
                        </label>
                        in the region
                        <?= (new TextInput('place', 'Region', 'Enter the region', 'region or locality', 'places', true))->render() ?>
                    </div>
                </div>
                <div class="filter" id="period-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('period-filter')" title="Remove period filter" type="button">
                            <?= Icon::get('minus', 'Remove period filter') ?>
                        </button>
                        <span id="period-label">Period or reign</span>
                    </div>
                    <div class="filter_content">
                        <input id="strictly" name="chrono-filter" type="radio" value="strictly" aria-labelledby="period-label"<?= View::oldValueRadio('chrono-filter', 'strictly', true) ?>>
                        <label for="strictly" title="Attestations in sources stricly beloging to a certain period">
                            Strictly
                        </label>
                        /
                        <input id="during" name="chrono-filter" type="radio" value="during" aria-labelledby="period-label"<?= View::oldValueRadio('chrono-filter', 'during') ?>>
                        <label for="during" title="Attestations in sources beloging to a certain period">
                            ca. during
                        </label>
                        /
                        <input id="not-later" name="chrono-filter" type="radio" value="not-later" aria-labelledby="period-label"<?= View::oldValueRadio('chrono-filter', 'not-later') ?>>
                        <label for="not-later" title="Attestations in sources dating not (demonstrably) later than">
                            not later
                        </label>
                        /
                        <input id="not-earlier" name="chrono-filter" type="radio" value="not-earlier" aria-labelledby="period-label"<?= View::oldValueRadio('chrono-filter', 'not-earlier') ?>>
                        <label for="not-earlier" title="Attestations in sources dating not (demonstrably) earlier than">
                            not earlier than
                        </label>
                        the period
                        <?= (new TextInput('period', 'Period', 'Enter the period', 'period or reign', 'periods', true))->render() ?>
                    </div>
                </div>
            </div>
            <button type="submit" class="submit">
                Search
            </button>
            <button type="submit" title="Clear search and display all records" name="action" value="reset">
                Reset
            </button>
        </form>
        <?php
        if (empty($data) || $data->count == 0) {
            ?>
            <h2 class="sr-only">Nothing found</h2>&nbsp;
            <?php
        } else {
            ?>
            <h2 class="sr-only" id="results">Results</h2>
            <?php
            //$this->renderObjectType($att['object_type']) . ' ' . $att['title']
            $total = $data->count;
            for ($i = 0; $i < $total; $i++) {
                $data->data[$i]['title'] = $this->renderObjectType($data->data[$i]['object_type']) . ' ' . $data->data[$i]['title'];
            }
            if ($data->type == "double") {
                $tableCo = new TableView($data, ['inscriptions_id', 'id'], 'auto', 'sort', '#results');
                $tableCo->addHeader('        <div class="tr -no-border" role="row">
            <div class="th" role="gridcell">Person A</div>
            <div role="gridcell" class="th">&nbsp;</div>
            <div role="gridcell" class="th">&nbsp;</div>
            <div class="th -border-left" role="gridcell">Person B</div>
            <div class="th" role="gridcell">&nbsp;</div>
            <div class="th" role="gridcell">&nbsp;</div>
            <div class="th -border-left" role="gridcell">Common</div>
            <div class="th" role="gridcell">&nbsp;</div>
            <div class="th" role="gridcell">&nbsp;</div>
        </div>');
                $tableCo->addLeftBorder([3, 6]);
                $tableCo->renderTable(['gender', 'title_string', 'personal_name', 'gender_b', 'title_string_b', 'personal_name_b', 'title', 'dating', 'region'], ['Gender', 'Title', 'Name', 'Gender', 'Title', 'Name', 'Source or dossier', 'Date', 'Region'], true);
            } else {
                $tableCo = new TableView($data, ['inscriptions_id', 'id'], 'auto', 'sort', '#results');
                $tableCo->renderTable(['gender', 'title_string', 'personal_name', 'title', 'dating', 'region'], ['Gender', 'Title', 'Name', 'Source or dossier', 'Date', 'Region'], true);
            }
            /*
             * ['id', 'inscriptions_id', 'gender', 'title_string', 'title_string_sort', 'personal_name', 'personal_name_sort', 'title', 'title_sort', 'dating', 'dating_sort', 'region']
             */
        }
        $this->toggleFilters([['period', 'period-filter'], ['place', 'region-filter']]);
    }
}
