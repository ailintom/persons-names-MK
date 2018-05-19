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
class peopleView extends View {

    //put your code here

    public function __construct() {
        
    }

    public function echoRender(&$data) {
        ?>     
        <h1>People</h1>
        <p class="info-box">
            <?= icon('info') ?>
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
                            <input id="Aany" name="Agender" value="any" type="radio" aria-labelledby="gender-label-a"<?= $this->oldValueRadio('Agender', 'any', TRUE) ?>>
                            <label for="Aany" title="Match people regardless of gender">any</label>
                            /
                            <input id="Afemale" name="Agender" value="f" type="radio" aria-labelledby="gender-label-a"<?= $this->oldValueRadio('Agender', 'f') ?>>
                            <label for="Afemale" title="Match women">female</label>
                            /
                            <input id="Amale" name="Agender" value="m" type="radio" aria-labelledby="gender-label-a"<?= $this->oldValueRadio('Agender', 'm') ?>>
                            <label for="Amale" title="Match men">male</label>
                            /
                            <input id="AAnimal" name="Agender" value="a" type="radio" aria-labelledby="gender-label-a"<?= $this->oldValueRadio('Agender', 'a') ?>>
                            <label for="AAnimal" title="Match animals">animal</label>
                        </div>
                    </div>
                    <div class="row -small">
                        <div class="column">
                            <label for="Atitle">Title:</label>
                        </div>
                        <div class="column -wide">
                            <input name="Atitle" id="Atitle" placeholder="MdC (jmj-rA pr) or Unicode (jmj-rꜣ pr)" type="text" <?= $this->oldValue('Atitle') ?>>
                        </div>
                    </div>
                    <div class="row -small">
                        <div class="column">
                            <label for="Aname">Name:</label>
                        </div>
                        <div class="column -wide">
                            <input name="Aname" id="Aname" placeholder="MdC (ra-Htp) or Unicode (rꜥ-ḥtp)" type="text" <?= $this->oldValue('Aname') ?>>
                        </div>
                    </div>
                    <div class="row -small">
                        <div class="column">
                            <label for="Aform_type">Name pattern:</label>
                        </div>
                        <div class="column -wide">
                            <input id="Aform_type" name="Aform_type" title="" placeholder="Example: DN (m)+ḥtp.w" list="name-types-formal" type="text" <?= $this->oldValue('Aform_type') ?>>
                        </div>
                    </div>
                    <div class="row -small">
                        <div class="column">
                            <label for="Asem_type">Semantic class:</label>
                        </div>
                        <div class="column -wide">
                            <input id="Asem_type" name="Asem_type" title="" placeholder="Example: theophoric names" list="name-types-semantic" type="text" <?= $this->oldValue('Asem_type') ?>>
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
                            <input id="Bany" name="Bgender" value="any" type="radio" aria-labelledby="gender-label-b"<?= $this->oldValueRadio('Bgender', 'any', TRUE) ?>>
                            <label for="Bany" title="Match names regardless of gender">any</label>
                            /
                            <input id="Bfemale" name="Bgender" value="f" type="radio" aria-labelledby="gender-label-b"<?= $this->oldValueRadio('Bgender', 'f') ?>>
                            <label for="Bfemale" title="Match names borne only by women">female</label>
                            /
                            <input id="Bmale" name="Bgender" value="m" type="radio" aria-labelledby="gender-label-b"<?= $this->oldValueRadio('Bgender', 'm') ?>>
                            <label for="Bmale" title="Match names borne only by men">male</label>
                        </div>
                    </div>
                    <div class="row -small">
                        <div class="column">
                            <label for="Btitle">Title:</label>
                        </div>
                        <div class="column -wide">
                            <input name="Btitle" id="Btitle" placeholder="MdC (jmj-rB pr) or Unicode (jmj-rꜣ pr)" type="text" <?= $this->oldValue('Btitle') ?>>
                        </div>
                    </div>
                    <div class="row -small">
                        <div class="column">
                            <label for="personal_nameB">Name:</label>
                        </div>
                        <div class="column -wide">
                            <input name="personal_nameB" id="personal_nameB" placeholder="MdC (ra-Htp) or Unicode (rꜥ-ḥtp)" type="text" <?= $this->oldValue('personal_nameB') ?>>
                        </div>
                    </div>
                    <div class="row -small">
                        <div class="column">
                            <label for="Bform_type">Name pattern:</label>
                        </div>
                        <div class="column -wide">
                            <input id="Bform_type" name="Bform_type" title="" placeholder="Example: DN (m)+ḥtp.w" list="name-types-formal" type="text" <?= $this->oldValue('Bform_type') ?>>
                        </div>
                    </div>
                    <div class="row -small">
                        <div class="column">
                            <label for="Bsem_type">Semantic class:</label>
                        </div>
                        <div class="column -wide">
                            <input id="Bsem_type" name="Bsem_type" title="" placeholder="Example: theophoric names" list="name-types-semantic" type="text" <?= $this->oldValue('Bsem_type') ?>>
                        </div>
                    </div>
                </div>
            </div>

            <p>
                <label for="relation">Relation between A and B:</label>
                <select name="relation" id="relation">
                    <option value="same_inscription"<?= $this->oldValueSelect('relation', 'same_inscription', TRUE) ?>>A and B appear in the same source</option>
                    <option value="child"<?= $this->oldValueSelect('relation', 'child') ?>>A is a child of B</option>
                    <option value="parent"<?= $this->oldValueSelect('relation', 'parent') ?>>A is a parent of B</option>
                    <option value="spouses"<?= $this->oldValueSelect('relation', 'spouses') ?>>A and B are spouses</option>
                    <option value="siblings"<?= $this->oldValueSelect('relation', 'siblings') ?>>A and B are siblings</option>
                </select>
            </p>

            <p>
                <input id="only_persons" name="only_persons" value="true" type="checkbox" <?= $this->oldValueRadio('only_persons', 'true') ?>>
                <label for="only_persons" title="Only show dossiers of persons with multiple attestations">Only dossiers of persons with multiple attestations</label>
            </p>

            <div class="filters">
                <h3 class="sr-only">Filters</h3>

                <div class="filters_selection">
                    <button class="filters_button" aria-controls="region-filter" aria-expanded="false" onclick="MK.toggleFilter('region-filter')" title="Toggle region filter" type="button">
                        <?= icon('plus') . icon('minus') ?>
                        Region or locality
                    </button>
                    <button class="filters_button" aria-controls="period-filter" aria-expanded="false" onclick="MK.toggleFilter('period-filter')" title="Toggle period filter" type="button">
                        <?= icon('plus') . icon('minus') ?>
                        Period or reign
                    </button>
                </div>

                <div class="filter" id="region-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('region-filter')" title="Remove region filter" type="button">
                            <?= icon('minus', 'Remove region filter') ?>
                        </button>
                        <span id="region-label">Region or locality</span>
                    </div>
                    <div class="filter_content">
                        <input id="provenance" name="geo-filter" type="radio" value="provenance" aria-labelledby="region-label" <?= $this->oldValueRadio('geo-filter', 'provenance') ?>>
                        <label for="provenance" title="Attestations in sources found in the certain region">
                            Provenance
                        </label>
                        /
                        <input id="installation-place" name="geo-filter" type="radio" value="installation-place" aria-labelledby="region-label" <?= $this->oldValueRadio('geo-filter', 'installation-place') ?>>
                        <label for="installation-place" title="Attestations on monuments installed in certain region">
                            installation place
                        </label>
                        /
                        <input id="origin" name="geo-filter" type="radio" value="origin" aria-labelledby="region-label" <?= $this->oldValueRadio('geo-filter', 'origin') ?>>
                        <label for="origin" title="Attestations on monuments owned by people from a certain region">
                            origin
                        </label>
                        /
                        <input id="production" name="geo-filter" type="radio" value="production" aria-labelledby="region-label" <?= $this->oldValueRadio('geo-filter', 'production') ?>>
                        <label for="production" title="Attestations on monuments produced in a certain refino">
                            production
                        </label>
                        /
                        <input id="all" name="geo-filter" type="radio" value="all" aria-labelledby="region-label" <?= $this->oldValueRadio('geo-filter', 'all', TRUE) ?>>
                        <label for="all" title="Attestations in sources anyhow related to a certain region">
                            all
                        </label>
                        in the region
                        <label for="place" class="sr-only">Region</label>
                        <input id="place" list="places" name="place" placeholder="region or locality" title="Enter the region" type="text" <?= $this->oldValue('place') ?>>
                    </div>
                </div>

                <div class="filter" id="period-filter">
                    <div class="filter_label">
                        <button class="filter_remove" onclick="MK.toggleFilter('period-filter')" title="Remove period filter" type="button">
                            <?= icon('minus', 'Remove period filter') ?>
                        </button>
                        <span id="period-label">Period or reign</span>
                    </div>
                    <div class="filter_content">
                        <input id="during" name="chrono-filter" type="radio" value="during" aria-labelledby="period-label"<?= $this->oldValueRadio('chrono-filter', 'during', TRUE) ?>>
                        <label for="during" title="Attestations in sources beloging to a certain period">
                            During
                        </label>
                        /
                        <input id="not-later" name="chrono-filter" type="radio" value="not-later" aria-labelledby="period-label"<?= $this->oldValueRadio('chrono-filter', 'not-later', TRUE) ?>>
                        <label for="not-later" title="Attestations in sources dating not (demonstrably) later than">
                            not later than
                        </label>
                        /
                        <input id="not-earlier" name="chrono-filter" type="radio" value="not-earlier" aria-labelledby="period-label"<?= $this->oldValueRadio('chrono-filter', 'not-earlier', TRUE) ?>>
                        <label for="not-earlier" title="Attestations in sources dating not (demonstrably) earlier than">
                            not earlier than
                        </label>
                        the period
                        <label for="period" class="sr-only">Period</label>
                        <input id="period" list="periods" name="period" placeholder="period or reign" title="Enter the period" type="text" <?= $this->oldValue('period') ?>>
                    </div>
                </div>
            </div>

            <button type="submit">
                Search
            </button>
            <button type="submit" title="Clear search and display all records" name="action" value="reset">
                Reset
            </button>

            <?php
            $dl = new Datalist();
            echo $dl->get('name-types-formal'),
            $dl->get('name-types-semantic'),
            $dl->get('periods'),
            $dl->get('places');
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
            if ($data->type == "double") {
                $tableCo = new Table($data, 'id', 'auto', 'sort', '#results');
                $tableCo->addHeader('<tr class="-no-border"><th colspan="3">Person A</th><th class="-border" colspan="3">Person B</th><th class="-border" colspan="3">Common</th></tr>');
                $tableCo->render_table(['gender', 'title_string', 'personal_name', 'gender_b', 'title_string_b', 'personal_name_b', 'title', 'dating', 'region'], ['Gender', 'Title', 'Name', 'Gender', 'Title', 'Name', 'Source or dossier', 'Date', 'Region'], TRUE);
            } else {
                $tableCo = new Table($data, 'id', 'auto', 'sort', '#results');

                $tableCo->render_table(['gender', 'title_string', 'personal_name', 'title', 'dating', 'region'], ['Gender', 'Title', 'Name', 'Source or dossier', 'Date', 'Region'], TRUE);
            }
            /*
             * ['id', 'inscriptions_id', 'gender', 'title_string', 'title_string_sort', 'personal_name', 'personal_name_sort', 'title', 'title_sort', 'dating', 'dating_sort', 'region']
             */
        }
    }

}

/*
      <p>Displaying titles <?= $data->start ?>&ndash;<?= ($data->start + $data->count - 1) ?> out of <?= ($data->start + $data->total_count ) ?></p>
      <?php
      //$res = null;
      foreach ($data->data as $row) {
      echo("<a href='" . BASE . "collection/" . $row[$data->getFieldName(0)] . "'>" . $row[$data->getFieldName(1)] . ' ' .  $row['inscriptions_count'] . '<br>');
      }
      //return $res;
      }

      }
     */
    