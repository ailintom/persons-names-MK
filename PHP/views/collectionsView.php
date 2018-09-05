<?php
/*
 * Description of collectionsView
 * A class used to render the collections page
 */

namespace PNM\views;

class collectionsView extends View
{

    public function echoRender(&$data)
    {
        (new HeadView())->render(HeadView::HEADERSLIM, 'Collections');
        ?>
        <p class="info-box">
            <?= Icon::get('info') ?>
            You can use <b>%</b> or <b>*</b> as wildcards.
            “Mar*” will match “Mariemont” or “Marseille”.
        </p>
        <form action="<?= \PNM\Request::makeURL('collections') ?>" method="get">
            <div class="row">
                <div class="column">
                    <?= (new TextInput('title', 'Short name', 'Enter the short name of the museum or its part', 'Example: Bruxelles', 'collections'))->render() ?>
                </div>
                <div class="column">
                    <?= (new TextInput('full_name', 'Full name', 'Enter the full name of the museum or its part','','full-names'))->render() ?>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <?= (new TextInput('location', 'Location', 'Enter the city where the collection is located', '', 'locations'))->render() ?>
                </div>
                <div class="column">
                  <?= (new TextInput('tm_coll_id', 'Trismegistos Collections ID', 'Enter the Trismegistos Collections ID', 'Example: 188'))->render() ?>  
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
            echo $dl->get('full-names');
            echo $dl->get('locations');
            echo $dl->get('collections');
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
            $tableCo = new TableView($data, 'collections_id', 'collection', 'sort', '#results');
            $tableCo->renderTable(['title', 'full_name', 'location', 'inscriptions_count', 'url', 'online_collection'], ['Short name', 'Full name', 'Location', 'Objects', 'Website', 'Online catalogue'], true);
            /*
             * ['collections_id', 'title', 'full_name_en', 'full_name_national_language', 'location', 'url', 'online_collection', 'tm_coll_id',
              'inscriptions_count'])
             */
        }
    }
}