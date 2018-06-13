<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PNM;

/**
 * This class is used to display the "Not found" message when an EntryController requests a record that is not existent.
 */
class NotFound
{

    public function echoRender()
    {
        http_response_code(404);
        (new Head())->render(Head::HEADERSLIM, 'Not found');
        ?>
        <span>The <?= Request::get('controller') ?> with id <?= Request::get('id') ?> is not found in the current version of the database.
            You may try to change the database version in the top right corner of the page.</span>
        <?php
    }
}
