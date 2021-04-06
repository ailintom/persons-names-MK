<?php
/*
 * Description of idView
 * This class renders the id page
 */

namespace PNM\views;

use \PNM\Request;

class idView extends View
{

    public function echoRender(&$data)
    {
        //$res = null;
        (new HeadView())->render(HeadView::HEADERSLIM, 'ID');
        
        ?>

        <form action="<?= Request::makeURL('id') ?>" method="get" onreset="MK.removeAllFilters()">
            <div class="row">
                <div class="column">
                    <?= (new TextInput('id', 'Enter record ID', 'The ID number', 'Example: 192938309'))->render() ?>
                </div>
                <div class="column">
                    
                </div>
            </div>
            <button type="submit">
                Search
            </button>
            <button type="submit" title="Clear search and display all records" name="action" value="reset">
                Reset
            </button>
        </form>                
        <?php

    }
}
