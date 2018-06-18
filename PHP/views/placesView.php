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
 * Description of placesView
 *
 * @author Tomich
 */
class placesView extends View
{

    public function echoRender(&$data)
    {
        (new Head())->render(Head::HEADERSLIM, 'Places');
        ?>
        <p class="info-box">
            <?= Icon::get('info') ?>
            You can use <b>%</b> or <b>*</b> as wildcards.
            “Ab*” will match “Abydos” and “Abusir”.
        </p>
        <form action="<?= Request::makeURL('places') ?>" method="get">
            <div class="row">
                <div class="column">
                    <label for="place">Place name</label>
                    <input id="place" name="place" list="places" title="Enter the region" placeholder="Region or locality" type="text" <?= View::oldValue('place') ?>>
                </div>
                <div class="column">
                    <label for="macroregion">Macroregion</label>
                    <select id="macroregion" name="macroregion">
                        <option value=""<?= View::oldValueSelect('macroregion', '', 'true') ?>>&nbsp;</option>
                        <option value="Eastern Desert" <?= View::oldValueSelect('macroregion', 'Eastern Desert') ?>>Eastern Desert</option>
                        <option value="Levant" <?= View::oldValueSelect('macroregion', 'Levant') ?>>Levant</option>
                        <option value="LE" <?= View::oldValueSelect('macroregion', 'LE') ?>>Lower Egypt</option>
                        <option value="MFR" <?= View::oldValueSelect('macroregion', 'MFR') ?>>Memphis-Faiyum Region</option>
                        <option value="Nile Valley" <?= View::oldValueSelect('macroregion', 'Nile Valley') ?>>Nile Valley</option>
                        <option value="NUE" <?= View::oldValueSelect('macroregion', 'NUE') ?>>Northern Upper Egypt</option>
                        <option value="Nubia" <?= View::oldValueSelect('macroregion', 'Nubia') ?>>Nubian Nile Valley</option>
                        <option value="SUE" <?= View::oldValueSelect('macroregion', 'SUE') ?>>Southern Upper Egypt</option>
                        <option value="Western Desert" <?= View::oldValueSelect('macroregion', 'Western Desert') ?>>Western Desert</option>
                    </select>
                </div>
            </div>
            <div class="filters">
                <h2 class="sr-only">Filters</h2>
                <div class="filters_selection">
                    <button class="filters_button" aria-controls="northof-filter" aria-expanded="false" onclick="MK.toggleFilter('northof-filter')" title="Toggle 'north of' filter" type="button">
                        <?= Icon::get('plus') . Icon::get('minus') ?>
                        Places north of
                    </button>
                    <button class="filters_button" aria-controls="southof-filter" aria-expanded="false" onclick="MK.toggleFilter('southof-filter')" title="Toggle 'south of' filter" type="button">
                        <?= Icon::get('plus') . Icon::get('minus') ?>
                        Places south of
                    </button>
                    <button class="filters_button" aria-controls="near-filter" aria-expanded="false" onclick="MK.toggleFilter('near-filter')" title="Toggle 'near' filter" type="button">
                        <?= Icon::get('plus') . Icon::get('minus') ?>
                        Places near
                    </button>
                    <button class="filters_button" aria-controls="tm_geoid-filter" aria-expanded="false" onclick="MK.toggleFilter('tm_geoid-filter')" title="Toggle Trismegistos GEO ID filter" type="button">
                        <?= Icon::get('plus') . Icon::get('minus') ?>
                        Trismegistos GEO ID
                    </button>
                    <button class="filters_button" aria-controls="topbib_id-filter" aria-expanded="false" onclick="MK.toggleFilter('topbib_id-filter')" title="Toggle TopBib ID filter" type="button">
                        <?= Icon::get('plus') . Icon::get('minus') ?>
                        TopBib ID
                    </button>
                </div>
                <div class="filter" id="northof-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('northof-filter')" title="Remove 'north of' filter" type="button">
                            <?= Icon::get('minus', 'Remove "north of" filter') ?>
                        </button>
                        Places north of
                    </div>
                    <div class="filter_content">
                        <label for="northof" class="sr-only">Places north of</label>
                        <input id="northof" list="places" name="northof" placeholder="Region or locality" type="text" <?= View::oldValue('northof') ?>>
                    </div>
                </div>
                <div class="filter" id="southof-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('southof-filter')" title="Remove 'south of' filter" type="button">
                            <?= Icon::get('minus', 'Remove "south of" filter') ?>
                        </button>
                        Places south of
                    </div>
                    <div class="filter_content">
                        <label for="southof" class="sr-only">Places south of</label>
                        <input id="southof" list="places" name="southof" placeholder="Region or locality" type="text" <?= View::oldValue('southof') ?>>
                    </div>
                </div>
                <div class="filter" id="near-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('near-filter')" title="Remove 'near' filter" type="button">
                            <?= Icon::get('minus', 'Remove "near" filter') ?>
                        </button>
                        Places near
                    </div>
                    <div class="filter_content">
                        <label for="near" class="sr-only">Places near</label>
                        <input id="near" list="places" name="near" placeholder="Region or locality" type="text" <?= View::oldValue('near') ?>>
                    </div>
                </div>
                <div class="filter" id="tm_geoid-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('tm_geoid-filter')" title="Remove Trismegistos GEO ID filter" type="button">
                            <?= Icon::get('minus', 'Remove Trismegistos GEO ID filter') ?>
                        </button>
                        Trismegistos GEO ID
                    </div>
                    <div class="filter_content">
                        <label for="tm_geoid" class="sr-only">Trismegistos GEO ID</label>
                        <input id="tm_geoid" name="tm_geoid" placeholder="Example: 188" type="text" <?= View::oldValue('tm_geoid') ?>>
                    </div>
                </div>
                <div class="filter" id="topbib_id-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('topbib_id-filter')" title="Remove TopBib ID filter" type="button">
                            <?= Icon::get('minus', 'Remove TopBib ID filter') ?>
                        </button>
                        TopBib ID
                    </div>
                    <div class="filter_content">
                        <label for="topbib_id" class="sr-only">Topographical bibliography place id</label>
                        <input id="topbib_id" name="topbib_id" placeholder="Example: 501-180" type="text" <?= View::oldValue('topbib_id') ?>>
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
            $dl = new Datalist();
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
            <h2 class="sr-only" id="results">Places</h2>
            <?php
            //renderLat($data->get('latitude')
            $total = $data->count;
            for ($i = 0; $i < $total; $i++) {
                $data->data[$i]['latitude'] = $this->renderLat($data->data[$i]['latitude']);
            }
            $tableCo = new Table($data, 'places_id', 'place', 'sort', '#results');
            $tableCo->renderTable(['place', 'region', 'latitude',
                'inscriptions_count'], ['Place name', 'Region', 'Latitude', 'Number of inscriptions'], true);
        }
        $this->toggleFilters([['northof', 'northof-filter'], ['southof', 'southof-filter'], ['near', 'near-filter'], ['tm_geoid', 'tm_geoid-filter'], ['topbib_id', 'topbib_id-filter']]);
    }
}