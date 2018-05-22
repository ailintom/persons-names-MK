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

/**
 * Description of bibliographyView
 *
 * @author Tomich
 */
class namesView extends View {

    //put your code here

    public function __construct() {
        
    }

    public function echoRender(&$data) {
        (New Head)->render(Head::HEADERSLIM, 'Personal names');
        ?>     
        <form action="<?= Request::makeURL("names") ?>" method="get" onreset="MK.removeAllFilters()">
            <div class="row">
                <div class="column">
                    <label for="personal_name">Personal name</label>
                    <input id="personal_name" name="name" placeholder="MdC (ra-Htp) or Unicode (rꜥ-ḥtp)" type="text"<?= View::oldValue('name') ?>>
                </div>
                <div class="column">
                    <label for="translation">Translation</label>
                    <input id="translation" name="translation" placeholder="English or German translation" type="text"<?= View::oldValue('translation') ?>>
                </div>
            </div>
            <p>
                <span id="match-label">Return names</span>
                <input id="inexact" name="match" type="radio" value="inexact" aria-labelledby="match-label"<?= View::oldValueRadio('match', 'inexact', TRUE) ?>>
                <label for="inexact" title="Match any name containing the search term">
                    containing
                </label>
                /
                <input id="exact" name="match" type="radio" value="exact" aria-labelledby="match-label"<?= View::oldValueRadio('match', 'exact') ?>>
                <label for="exact" title="Match any name equal to the search term">
                    equalling
                </label>
                /
                <input id="startswith" name="match" type="radio" value="startswith" aria-labelledby="match-label"<?= View::oldValueRadio('match', 'startswith') ?>>
                <label for="startswith" title="Match any name beginning with the search term">
                    starting with
                </label>
                /
                <input id="endswith" name="match" type="radio" value="endswith" aria-labelledby="match-label"<?= View::oldValueRadio('match', 'endswith') ?>>
                <label for="endswith" title="Match any name ending with the search term">
                    ending with
                </label>
                the search term.
            </p>

            <div class="filters">
                <h2 class="sr-only">Filters</h2>

                <div class="filters_selection">
                    <button class="filters_button" aria-controls="region-filter" aria-expanded="false" onclick="MK.toggleFilter('region-filter')" title="Toggle region filter" type="button">
                        <?= icon('plus') . icon('minus') ?>
                        Region
                    </button>
                    <button class="filters_button" aria-controls="period-filter" aria-expanded="false" onclick="MK.toggleFilter('period-filter')" title="Toggle period filter" type="button">
                        <?= icon('plus') . icon('minus') ?>
                        Period
                    </button>
                    <button class="filters_button" aria-controls="gender-filter" aria-expanded="false" onclick="MK.toggleFilter('gender-filter')" title="Toggle gender filter" type="button">
                        <?= icon('plus') . icon('minus') ?>
                        Gender
                    </button>
                    <button class="filters_button" aria-controls="ranke-filter" aria-expanded="false" onclick="MK.toggleFilter('ranke-filter')" title="Toggle Ranke number filter" type="button">
                        <?= icon('plus') . icon('minus') ?>
                        Ranke number
                    </button>
                    <button class="filters_button" aria-controls="pattern-filter" aria-expanded="false" onclick="MK.toggleFilter('pattern-filter')" title="Toggle name pattern filter" type="button">
                        <?= icon('plus') . icon('minus') ?>
                        Name pattern
                    </button>
                    <button class="filters_button" aria-controls="class-filter" aria-expanded="false" onclick="MK.toggleFilter('class-filter')" title="Toggle name pattern filter" type="button">
                        <?= icon('plus') . icon('minus') ?>
                        Semantic class
                    </button>
                </div>

                <div class="filter" id="region-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('region-filter')" title="Remove region filter" type="button">
                            <?= icon('minus', 'Remove region filter') ?>
                        </button>
                        <span id="region-label">Region</span>
                    </div>
                    <div class="filter_content">
                        <input id="region-attested" name="match-region" type="radio" value="attested" aria-labelledby="region-label"<?= View::oldValueRadio('match-region', 'attested', TRUE) ?>>
                        <label for="region-attested" title="Match any title attested in the given region">
                            Attested in
                        </label>
                        /
                        <input id="region-characteristic" name="match-region" type="radio" value="characteristic" aria-labelledby="region-label"<?= View::oldValueRadio('match-region', 'characteristic') ?>>
                        <label for="region-characteristic" title="Match any title characteristic of the given region">
                            characteristic of
                        </label>
                        the region
                        <label for="place" class="sr-only">Region</label>
                        <input id="place" list="places" name="place" placeholder="region or locality" title="Enter the region" type="text"<?= View::oldValue('place') ?>>
                    </div>
                </div>

                <div class="filter" id="period-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('period-filter')" title="Remove period filter" type="button">
                            <?= icon('minus', 'Remove period filter') ?>
                        </button>
                        <span id="period-label">Period</span>
                    </div>
                    <div class="filter_content">
                        <input id="period-attested" name="match-date" type="radio" value="attested" aria-labelledby="period-label"<?= View::oldValueRadio('match-date', 'attested', TRUE) ?>>
                        <label for="period-attested" title="Match any title attested in the given period">
                            Attested in
                        </label>
                        /
                        <input id="period-characteristic" name="match-date" type="radio" value="characteristic" aria-labelledby="period-label"<?= View::oldValueRadio('match-date', 'characteristic') ?>>
                        <label for="period-characteristic" title="Match any title characteristic of the given period ">
                            characteristic of
                        </label>
                        the period
                        <label for="period" class="sr-only">Period</label>
                        <input id="period" list="periods" name="period" placeholder="period or reign" title="Enter the period" type="text"<?= View::oldValue('period') ?>>
                    </div>
                </div>

                <div class="filter" id="gender-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('gender-filter')" title="Remove gender filter" type="button">
                            <?= icon('minus', 'Remove gender filter') ?>
                        </button>
                        <span id="gender-label">Gender</span>
                    </div>
                    <div class="filter_content">
                        <input type="radio" id="any" name="gender" value="any" aria-labelledby="gender-label"<?= View::oldValueRadio('gender', 'any', TRUE) ?>>
                        <label for="any" title="Match names regardless of gender">
                            Regardless of gender
                        </label>
                        /
                        <input type="radio" id="female" name="gender" value="f" aria-labelledby="gender-label"<?= View::oldValueRadio('gender', 'f') ?>>
                        <label for="female" title="Match names borne only by women">
                            female 
                        </label>
                        /
                        <input type="radio" id="male" name="gender" value="m" aria-labelledby="gender-label"<?= View::oldValueRadio('gender', 'm') ?>>
                        <label for="male" title="Match names borne only by men ">
                            male 
                        </label>
                        /
                        <input type="radio" id="both" name="gender" value="both" aria-labelledby="gender-label"<?= View::oldValueRadio('gender', 'both') ?>>
                        <label for="both" title="Match names borne by both men and women">
                            unisex names
                        </label>
                        /
                        <input type="radio" id="animal" name="gender" value="a" aria-labelledby="gender-label"<?= View::oldValueRadio('gender', 'a') ?>>
                        <label for="animal" title="Match names borne by animals">
                            animal names
                        </label>
                    </div>
                </div>

                <div class="filter" id="ranke-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('ranke-filter')" title="Remove Ranke number filter" type="button">
                            <?= icon('minus', 'Remove Ranke number filter') ?>
                        </button>
                        Ranke number
                    </div>
                    <div class="filter_content">
                        <label for="ranke" class="sr-only">Ranke number</label>
                        <input id="ranke" name="ranke" placeholder="Example: I, 139.1" title="Enter the Ranke entry number" type="text"<?= View::oldValue('ranke') ?>>
                    </div>
                </div>

                <div class="filter" id="pattern-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('pattern-filter')" title="Remove name pattern filter" type="button">
                            <?= icon('minus', 'Remove name pattern filter') ?>
                        </button>
                        Name pattern
                    </div>
                    <div class="filter_content">
                        <label for="form_type" class="sr-only">Name pattern</label>
                        <input id="form_type" list="name-types-formal" name="form_type" placeholder="Example: DN (m)+ḥtp.w" type="text"<?= View::oldValue('form_type') ?>>
                    </div>
                </div>

                <div class="filter" id="class-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('class-filter')" title="Remove semantic class filter" type="button">
                            <?= icon('minus', 'Remove semantic class filter') ?>
                        </button>
                        Semantic class
                    </div>
                    <div class="filter_content">
                        <label for="sem_type" class="sr-only">Semantic class</label>
                        <input id="sem_type" list="name-types-semantic" name="sem_type" placeholder="Example: theophoric names" type="text"<?= View::oldValue('sem_type') ?>>
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
        $dl = new Datalist();
        echo $dl->get('name-types-formal');
        echo $dl->get('name-types-semantic');
        echo $dl->get('periods');
        echo $dl->get('places');
        if (empty($data) || $data->count == 0) {
            ?>
            <h2 class="sr-only">Nothing found</h2>&nbsp;
            <?php
        } else {
            ?>
            <h2 class="sr-only" id="results">Results</h2>
            <?php
            $tableCo = new Table($data, 'personal_names_id', 'name', 'sort');
            $tableCo->render_table(['personal_name', 'gender', 'count_attestations', 'usage_period', 'usage_area', 'ranke', 'translation_en'], ['Personal name', 'Gender', 'Atts.', 'Period', 'Area', 'Ranke no.', 'Translation'], TRUE);
        }
        /*
         * 
         * Process filters
         * 
         */

        $this->toggleFilters([['place', 'region-filter'], ['period', 'period-filter'], ['gender', 'gender-filter', 'any'], ['ranke', 'ranke-filter'], ['form_type', 'pattern-filter'], ['sem_type', 'class-filter']]);
    }

}
