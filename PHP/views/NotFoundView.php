<?php
/*
 * Description of NotFoundView
 * This class renders the not found page (when an EntryModel returns no records to the EntryController)
 */

namespace PNM\views;

class NotFoundView
{

    public function echoRender()
    {
        http_response_code(404);
        (new HeadView())->render(HeadView::HEADERSLIM, 'Not found');
        ?>
        <span>The <?= \PNM\Request::get('controller') ?> with id <?= \PNM\Request::get('id') ?> is not found in the current version of the database.
            You may try to change the database version in the top right corner of the page.</span>
        <?php
    }
}
