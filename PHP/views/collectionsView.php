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
class collectionsView extends View {

    //put your code here

    public function __construct() {
        
    }

    public function echoRender(&$data) {
    
        ?>     
        <h1>Collections</h1>
        <p class="info-box">
            <?= icon('info') ?>
            You can use <b>%</b> or <b>*</b> as wildcards.
            “Mar*” will match “Mariemont” or “Marseille”.
        </p>
        <form action="<?= Request::makeURL('collections') ?>" method="get">
            <div class="row">
                <div class="column">
                    <label for="title">Short name</label>
                    <input id="title" name="title" list="collections" title="Enter the short name of the museum or its part" type="text" <?= $this->oldValue('title') ?>>
                </div>
                <div class="column">
                    <label for="full_name">Full name</label>
                    <input id="full_name" name="full_name" list="full-names" title="Enter the full name of the museum or its part" type="text" <?= $this->oldValue('full_name') ?>>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <label for="location">Location</label>
                    <input id="location" name="location" list="locations" title="Enter the city where the collection is located" type="text" <?= $this->oldValue('location') ?>>
                </div>
                <div class="column">
                    <label for="tm_coll_id">Trismegistos Collections ID</label>
                    <input id="tm_coll_id" name="tm_coll_id" title="" placeholder="Example: 188" type="text"<?= $this->oldValue('tm_coll_id') ?>>
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
            $tableCo = new Table($data, 'collections_id', 'collection', 'sort', '#results');
            $tableCo->render_table(['title', 'full_name', 'location', 'count_inscriptions', 'url', 'online_collection'], ['Short name', 'Full name', 'Location', 'Objects', 'Website', 'Online catalogue'], TRUE);

            /*
             * ['collections_id', 'title', 'full_name_en', 'full_name_national_language', 'location', 'url', 'online_collection', 'tm_coll_id',
              'count_inscriptions'])
             */
        }
    }

}

/*
      <p>Displaying titles <?= $data->start ?>&ndash;<?= ($data->start + $data->count - 1) ?> out of <?= ($data->start + $data->total_count ) ?></p>
      <?php
      //$res = null;
      foreach ($data->data as $row) {
      echo("<a href='" . BASE . "collection/" . $row[$data->getFieldName(0)] . "'>" . $row[$data->getFieldName(1)] . ' ' .  $row['count_inscriptions'] . '<br>');
      }
      //return $res;
      }

      }
     */
    