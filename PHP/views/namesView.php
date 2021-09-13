<?php
/*
 * Description of namesView
 * This class renders the names page
 */

namespace PNM\views;

use \PNM\Request;

class namesView extends View {

    public function echoRender(&$data) {
        (new HeadView())->render(HeadView::HEADERSLIM, 'Personal Names');
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
                <input id="inexact" name="match" type="radio" value="inexact" aria-labelledby="match-label"<?= View::oldValueRadio('match', 'inexact', true) ?>>
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
                the search term
            </p>
            <?php
            $regioInputs = (new RadioGroup('match-region', [['attested', 'Attested in', 'Match any name attested in the given region']
                        , ['characteristic', 'characteristic of', 'Match any title characteristic of the given region']], 'attested', 'region-filter'))->render()
                    . '  the region ' . (new TextInput('place', 'Region', 'Enter the region', 'region or locality', 'places', true))->render();
            $filters[] = new FormFilter('region-filter', 'Region', $regioInputs, 'place');

            $periodInputs = (new RadioGroup('match-date', [['strictly', 'Attested strictly', 'Match any name attested only in the given period'],
                        ['attested', 'ca. in', 'Match any name possibly attested in the given period '],
                        ['characteristic', 'characteristic of', 'Match any name characteristic of the given period ']], 'strictly', 'period-filter'))->render()
                    . ' the period ' . (new TextInput('period', 'Period:', 'Enter the period', 'Example: 17th Dyn.', 'periods', true))->render();

            $filters[] = new FormFilter('period-filter', 'Period', $periodInputs, 'period');
            $genInputs = (new RadioGroup('gender',
                            [['any', 'Regardless of gender', 'Match names regardless of gender'],
                        ['f', 'male', 'Match names borne only by women'],
                        ['m', 'female', 'Match names borne only by men'],
                        ['both', 'unisex', 'Match names borne by both men and women'],
                        ['a', 'animal names', 'Match names borne by animals']],
                            'any', 'gender-filter'))->render();
            $filters[] = new FormFilter('gender-filter', 'Gender', $genInputs, 'gender', null, 'any');

            $refInputs = [(new TextInput('ranke', 'Ranke reference:', 'Enter the Ranke entry number', 'Example: I, 293.9', null))->render(),
                (new TextInput('tla', 'TLA ID:', 'Enter the TLA entry number', 'Example: 400186', null))->render(),
                (new TextInput('pnmid', 'PNM Name ID:', 'Enter the PNM name number', 'Example: 294', null))->render()];
            $filters[] = new FormFilter('ranke-filter', 'References', $refInputs, ['ranke', 'tla', 'pnmid']);

            $pattInputs = (new TextInput('form_type', 'Name pattern:', 'Select a formal name pattern', 'Example: GN-ḥtp', 'name-types-formal', true))->render();
            $filters[] = new FormFilter('pattern-filter', 'Name pattern', $pattInputs, 'form_type');
            $classInputs = (new TextInput('sem_type', 'Semantic class', 'Select a semantic class', 'Example: theophoric name', 'name-types-semantic', true))->render();
            $filters[] = new FormFilter('class-filter', 'Semantic class', $classInputs, 'sem_type');
            FormFilter::renderFilters($filters);
            ?>
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
            $tableCo = new TableView($data, 'personal_names_id', 'name', 'sort');
            $tableCo->renderTable(['personal_name', 'gender', 'count_attestations', 'usage_period', 'usage_area', 'ranke', 'translation_en'], ['Personal name', 'Gender', 'Atts.', 'Period', 'Area', 'Ranke no.', 'Translation'], true);
        }
        /*
         *
         * Process filters
         *
         */
        $this->toggleFilters(FormFilter::renderToggle($filters));
    }

}
