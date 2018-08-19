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
            <?= IconView::get('info') ?>
            You can use <b>%</b> or <b>*</b> as wildcards.
            “Mar*” will match “Mariemont” or “Marseille”.
        </p>
        <form action="<?= \PNM\Request::makeURL('collections') ?>" method="get">
            <div class="row">
                <div class="column">
                    <label for="title">Short name</label>
                    <input id="title" name="title" list="collections" title="Enter the short name of the museum or its part" type="text" <?= View::oldValue('title') ?>>
                </div>
                <div class="column">
                    <label for="full_name">Full name</label>
                    <input id="full_name" name="full_name" list="full-names" title="Enter the full name of the museum or its part" type="text" <?= View::oldValue('full_name') ?>>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <label for="location">Location</label>
                    <input id="location" name="location" list="locations" title="Enter the city where the collection is located" type="text" <?= View::oldValue('location') ?>>
                </div>
                <div class="column">
                    <label for="tm_coll_id">Trismegistos Collections ID</label>
                    <input id="tm_coll_id" name="tm_coll_id" title="" placeholder="Example: 188" type="text"<?= View::oldValue('tm_coll_id') ?>>
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
    