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

namespace PNM\views;

/**
 * Description of bibliographyView
 *
 * @author Tomich
 */
class titlesView extends View
{

    public function echoRender(&$data)
    {
        (new HeadView())->render(HeadView::HEADERSLIM, "Titles");
        ?>
        <form action="<?= \PNM\Request::makeURL('titles') ?>" method="get">
            <div class="row">
                <div class="column">
                    <label for="title">Title</label>
                    <input id="title" name="title" placeholder="MdC (jmj-rA pr) or Unicode (jmj-rꜣ pr)" type="text" <?= View::oldValue('title') ?>>
                </div>
                <div class="column">
                    <label for="translation">Translation</label>
                    <input id="translation" name="translation" placeholder="English or German translation" type="text" <?= View::oldValue('personal_name') ?>>
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
                the search term.
            </p>
            <div class="filters">
                <h2 class="sr-only">Filters</h2>
                <div class="filters_selection">
                    <button class="filters_button" aria-controls="region-filter" aria-expanded="false" onclick="MK.toggleFilter('region-filter')" title="Toggle region filter" type="button">
                        <?= IconView::get('plus') . IconView::get('minus') ?>
                        Region
                    </button>
                    <button class="filters_button" aria-controls="period-filter" aria-expanded="false" onclick="MK.toggleFilter('period-filter')" title="Toggle period filter" type="button">
                        <?= IconView::get('plus') . IconView::get('minus') ?>
                        Period
                    </button>
                    <button class="filters_button" aria-controls="gender-filter" aria-expanded="false" onclick="MK.toggleFilter('gender-filter')" title="Toggle gender filter" type="button">
                        <?= IconView::get('plus') . IconView::get('minus') ?>
                        Gender
                    </button>
                    <button class="filters_button" aria-controls="ward-filter" aria-expanded="false" onclick="MK.toggleFilter('ward-filter')" title="Toggle Ward/Fischer number filter" type="button">
                        <?= IconView::get('plus') . IconView::get('minus') ?>
                        Ward/Fischer number
                    </button>
                    <button class="filters_button" aria-controls="hannig-filter" aria-expanded="false" onclick="MK.toggleFilter('hannig-filter')" title="Toggle Hannig number filter" type="button">
                        <?= IconView::get('plus') . IconView::get('minus') ?>
                        Hannig number
                    </button>
                </div>
                <div class="filter" id="region-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('region-filter')" title="Remove region filter" type="button">
                            <?= IconView::get('minus', 'Remove region filter') ?>
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
                        <label for="place" class="sr-only">Region</label>
                        <input id="place" list="places" name="place" placeholder="region or locality" title="Enter the region" type="text"<?= View::oldValue('place') ?>>
                    </div>
                </div>
                <div class="filter" id="period-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('period-filter')" title="Remove period filter" type="button">
                            <?= IconView::get('minus', 'Remove period filter') ?>
                        </button>
                        <span id="period-label">Period</span>
                    </div>
                    <div class="filter_content">
                        <input id="period-attested" name="match-date" type="radio" value="attested" aria-labelledby="period-label"<?= View::oldValueRadio('match-date', 'attested', true) ?>>
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
                            <?= IconView::get('minus', 'Remove gender filter') ?>
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
                            <?= IconView::get('minus', 'Remove Ward/Fischer number filter') ?>
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
                            <?= IconView::get('minus', 'Remove Hannig number filter') ?>
                        </button>
                        Hannig number
                    </div>
                    <div class="filter_content">
                        <label for="hannig" class="sr-only">Hannig number</label>
                        <input id="hannig" name="hannig" placeholder="Example: 2044" title="Enter the entry number Hannig, Ägyptisches Wörterbuch II: Mittleres Reich und Zweite Zwischenzeit" type="text"<?= View::oldValue('hannig') ?>>
                    </div>
                </div>
            </div>
            <button type="submit" class="submit">
                Search
            </button>
            <button type="submit" title="Clear search and display all records" name="action" value="reset">
                Reset
            </button>
            <?php
            $dl = new DatalistView();
            echo $dl->get('periods');
            echo $dl->get('places');
            ?>
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
            $tableCo->renderTable(['title', 'gender', 'count_attestations', 'usage_period', 'usage_area', 'ward_fischer', 'hannig', 'translation_en'], ['Title', 'Gender', 'Atts.', 'Period', 'Area', 'Ward/Fischer no.', 'Hannig no.', 'Translation'], true);
            /*
              ['titles_id', 'title', 'gender', 'count_attestations', 'usage_period', 'usage_area', 'ward_fischer', 'hannig', 'translation_en']
             */
        }
        /*
         *
         * Process filters
         *
         */
        $this->toggleFilters([['place', 'region-filter'], ['period', 'period-filter'], ['gender', 'gender-filter', 'any'], ['ward', 'ward-filter'], ['hannig', 'hannig-filter']]);
    }
}

/*
      <p>Displaying titles <?= $data->start ?>&ndash;<?= ($data->start + $data->count - 1) ?> out of <?= ($data->start + $data->total_count ) ?></p>
      <?php
      //$res = null;
      foreach ($data->data as $row) {
      echo("<a href='" . \PNM\Config::BASE . "collection/" . $row[$data->getFieldName(0)] . "'>" . $row[$data->getFieldName(1)] . ' ' .  $row['inscriptions_count'] . '<br>');
      }
      //return $res;
      }
      }
     */
    