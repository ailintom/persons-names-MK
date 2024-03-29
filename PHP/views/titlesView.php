<?php
/*
 * Description of titlesView
 * This class renders the titles page
 */

namespace PNM\views;

use \PNM\Request;

class titlesView extends View {

    public function echoRender(&$data) {
        (new HeadView())->render(HeadView::HEADERSLIM, "Titles");
        ?>
        <form action="<?= Request::makeURL('titles') ?>" method="get">
            <div class="row">
                <div class="column">
        <?= (new TextInput('title', 'Title', 'MdC (jmj-rA pr) or Unicode (jmj-rꜣ pr)'))->render() ?>
                </div>
                <div class="column">
        <?= (new TextInput('translation', 'Translation', 'English or German translation'))->render() ?>
                </div>
            </div>
            <p>
                <span id="match-label">Return titles</span>
                <input type="radio" id="inexact" name="match" value="inexact" aria-labelledby="match-label"<?= View::oldValueRadio('match', 'inexact', true) ?>>
                <label for="inexact" title="Match any title containing the search term">
                    containing
                </label>
                /
                <input type="radio" id="exact" name="match" value="exact" aria-labelledby="match-label"<?= View::oldValueRadio('match', 'exact') ?>>
                <label for="exact" title="Match any title equal to the search term ">
                    equalling
                </label>
                /
                <input type="radio" id="startswith" name="match" value="startswith" aria-labelledby="match-label"<?= View::oldValueRadio('match', 'startswith') ?>>
                <label for="startswith" title="Match any title beginning with the search term ">
                    starting with
                </label>
                /
                <input type="radio" id="endswith" name="match" value="endswith" aria-labelledby="match-label"<?= View::oldValueRadio('match', 'endswith') ?>>
                <label for="endswith" title="Match any title ending with the search term ">
                    ending with
                </label>
                the search term
            </p>
            <div class="filters">
                <h2 class="sr-only">Filters</h2>
                <div class="filters_selection">
                    <button class="filters_button" aria-controls="region-filter" aria-expanded="false" onclick="MK.toggleFilter('region-filter')" title="Toggle region filter" type="button">
        <?= Icon::get('plus') . Icon::get('minus') ?>
                        Region
                    </button>
                    <button class="filters_button" aria-controls="period-filter" aria-expanded="false" onclick="MK.toggleFilter('period-filter')" title="Toggle period filter" type="button">
        <?= Icon::get('plus') . Icon::get('minus') ?>
                        Period
                    </button>
                    <button class="filters_button" aria-controls="gender-filter" aria-expanded="false" onclick="MK.toggleFilter('gender-filter')" title="Toggle gender filter" type="button">
        <?= Icon::get('plus') . Icon::get('minus') ?>
                        Gender
                    </button>
                    <button class="filters_button" aria-controls="ward-filter" aria-expanded="false" onclick="MK.toggleFilter('ward-filter')" title="Toggle Ward/Fischer number filter" type="button">
        <?= Icon::get('plus') . Icon::get('minus') ?>
                        Ward/Fischer no.
                    </button>
                    <button class="filters_button" aria-controls="hannig-filter" aria-expanded="false" onclick="MK.toggleFilter('hannig-filter')" title="Toggle Hannig number filter" type="button">
        <?= Icon::get('plus') . Icon::get('minus') ?>
                        Hannig no.
                    </button>
                    <button class="filters_button" aria-controls="taylor-filter" aria-expanded="false" onclick="MK.toggleFilter('taylor-filter')" title="Toggle Taylor number filter" type="button">
        <?= Icon::get('plus') . Icon::get('minus') ?>
                        Taylor no.
                    </button>
                    <button class="filters_button" aria-controls="ayedi-filter" aria-expanded="false" onclick="MK.toggleFilter('ayedi-filter')" title="Toggle al-Ayedi number filter" type="button">
        <?= Icon::get('plus') . Icon::get('minus') ?>
                        al-Ayedi no.
                    </button>
                </div>
                <div class="filter" id="region-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('region-filter')" title="Remove region filter" type="button">
        <?= Icon::get('minus', 'Remove region filter') ?>
                        </button>
                        <span id="region-label">Region</span>
                    </div>
                    <div class="filter_content">
                        <input id="region-attested" name="match-region" type="radio" value="attested" aria-labelledby="region-label"<?= View::oldValueRadio('match-region', 'attested', true) ?>>
                        <label for="region-attested" title="Match any title attested in the given region">
                            Attested in
                        </label>
                        /
                        <input id="region-characteristic" name="match-region" type="radio" value="characteristic" aria-labelledby="region-label"<?= View::oldValueRadio('match-region', 'characteristic') ?>>
                        <label for="region-characteristic" title="Match any title characteristic of the given region">
                            characteristic of
                        </label>
                        the region
        <?= (new TextInput('place', 'Region', 'Enter the region', 'region or locality', 'places', true))->render() ?>
                    </div>
                </div>
                <div class="filter" id="period-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('period-filter')" title="Remove period filter" type="button">
        <?= Icon::get('minus', 'Remove period filter') ?>
                        </button>
                        <span id="period-label">Period</span>
                    </div>
                    <div class="filter_content">
                        <input id="period-strictly" name="match-date" type="radio" value="strictly" aria-labelledby="period-label"<?= View::oldValueRadio('match-date', 'strictly', true) ?>>
                        <label for="period-strictly" title="Match any title attested strictly in the given period">
                            Attested strictly
                        </label>
                        /
                        <input id="period-attested" name="match-date" type="radio" value="attested" aria-labelledby="period-label"<?= View::oldValueRadio('match-date', 'attested') ?>>
                        <label for="period-attested" title="Match any title possibly attested in the given period">
                            ca. in
                        </label>
                        /
                        <input id="period-characteristic" name="match-date" type="radio" value="characteristic" aria-labelledby="period-label"<?= View::oldValueRadio('match-date', 'characteristic') ?>>
                        <label for="period-characteristic" title="Match any title characteristic of the given period ">
                            characteristic of
                        </label>
                        the period
        <?= (new TextInput('period', 'Period', 'Enter the period', 'period or reign', 'periods', true))->render() ?>
                    </div>
                </div>
                <div class="filter" id="gender-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('gender-filter')" title="Remove gender filter" type="button">
        <?= Icon::get('minus', 'Remove gender filter') ?>
                        </button>
                        <span id="gender-label">Gender</span>
                    </div>
                    <div class="filter_content">
                        <input type="radio" id="any" name="gender" value="any" aria-labelledby="gender-label"<?= View::oldValueRadio('gender', 'any', true) ?>>
                        <label for="any" title="Match titles regardless of gender">
                            Regardless of gender
                        </label>
                        /
                        <input type="radio" id="female" name="gender" value="f" aria-labelledby="gender-label"<?= View::oldValueRadio('gender', 'f') ?>>
                        <label for="female" title="Match titles borne only by women">
                            female titles
                        </label>
                        /
                        <input type="radio" id="male" name="gender" value="m" aria-labelledby="gender-label"<?= View::oldValueRadio('gender', 'm') ?>>
                        <label for="male" title="Match titles borne only by men ">
                            male titles
                        </label>
                        /
                        <input type="radio" id="both" name="gender" value="both" aria-labelledby="gender-label"<?= View::oldValueRadio('gender', 'both') ?>>
                        <label for="both" title="Match titles borne by both men and women">
                            unisex titles
                        </label>
                    </div>
                </div>
                <div class="filter" id="ward-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('ward-filter')" title="Remove Ward/Fischer number filter" type="button">
        <?= Icon::get('minus', 'Remove Ward/Fischer number filter') ?>
                        </button>
                        Ward/Fischer number
                    </div>
                    <div class="filter_content">
                        <label for="ward" class="sr-only">Ward/Fischer number</label>
                        <input id="ward" name="ward" placeholder="Example: 1346" title="Enter the entry number in Ward, Index of Egyptian Administrative and Religious Titles of the Middle Kingdom, or Fischer, Egyptian Titles of the Middle Kingdom. A Supplement to Wm. Ward's Index" type="text"<?= View::oldValue('ward') ?>>
                    </div>
                </div>
                <div class="filter" id="hannig-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('hannig-filter')" title="Remove Hannig number filter" type="button">
        <?= Icon::get('minus', 'Remove Hannig number filter') ?>
                        </button>
                        Hannig number
                    </div>
                    <div class="filter_content">
                        <label for="hannig" class="sr-only">Hannig number</label>
                        <input id="hannig" name="hannig" placeholder="Example: 1950" title="Enter the entry number in Hannig, Ägyptisches Wörterbuch II: Mittleres Reich und Zweite Zwischenzeit" type="text"<?= View::oldValue('hannig') ?>>
                    </div>
                </div>
                <div class="filter" id="taylor-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('taylor-filter')" title="Remove Taylor number filter" type="button">
        <?= Icon::get('minus', 'Remove Taylor number filter') ?>
                        </button>
                        Taylor number
                    </div>
                    <div class="filter_content">
                        <label for="taylor" class="sr-only">Taylor number</label>
                        <input id="taylor" name="taylor" placeholder="Example: 387" title="Enter the entry number in Taylor, An Index of Male Non-Royal Egyptian Titles, Epithets and Phrases of the 18th Dynasty" type="text"<?= View::oldValue('taylor') ?>>
                    </div>
                </div>
                <div class="filter" id="ayedi-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('ayedi-filter')" title="Remove al-Ayedi number filter" type="button">
        <?= Icon::get('minus', 'Remove al-Ayedi number filter') ?>
                        </button>
                        al-Ayedi number
                    </div>
                    <div class="filter_content">
                        <label for="ayedi" class="sr-only">al-Ayedi number</label>
                        <input id="ayedi" name="ayedi" placeholder="Example: 1950" title="Enter the entry number in al-Ayedi, Index of Egyptian administrative, religious and military titles of the New Kingdom" type="text"<?= View::oldValue('ayedi') ?>>
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
            $tableCo = new TableView($data, 'titles_id', 'title', 'sort');
            $tableCo->renderTable(['title', 'gender', 'count_attestations', 'usage_period', 'usage_area', 'ward_fischer', 'taylor', 'ayedi', 'translation_en'], ['Title', 'Gender', 'Atts.', 'Period', 'Area', 'Ward/Fischer no.', 'Taylor no.', 'Ayedi no.', 'Translation'], true);
            /*
              ['titles_id', 'title', 'gender', 'count_attestations', 'usage_period', 'usage_area', 'ward_fischer', 'hannig', 'translation_en']
             */
        }
        /*
         *
         * Process filters
         *
         */
        $this->toggleFilters([['place', 'region-filter'], ['period', 'period-filter'], ['gender', 'gender-filter', 'any'], ['ward', 'ward-filter'], ['hannig', 'hannig-filter'], ['taylor', 'taylor-filter'], ['ayedi', 'ayedi-filter']]);
    }

}
