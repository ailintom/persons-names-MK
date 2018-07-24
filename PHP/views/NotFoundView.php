<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PNM\views;

/**
 * This class is used to display the "Not found" message when an EntryController requests a record that is not existent.
 */
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
