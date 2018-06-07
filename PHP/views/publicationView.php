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
 * Description of publicationView
 *
 * @author Tomich
 */
class publicationView extends View {

    protected $View;

    public function echoRender(&$data) {
        (New Head)->render(Head::HEADERSLIM, $data->get('author_year'));
        ?>
        <p class="csl-entry">
            <?= $data->get('html_entry') ?>
        </p>
        <dl>
            <?php
            $ref = $this->addReference('OEB ID', $data->get('oeb_id'), 'http://oeb.griffith.ox.ac.uk/oeb_entry.aspx?item=');
            echo( $this->descriptionElement('References', $ref));
            ?>
        </dl>

        <?php
        if ($data->get('refs_count') > 0) {
            ?><h2>Entities referred to in this publication</h2><?php
            foreach ($data->tables as $table) {
                if (!empty($data->get($table[0]))) {
                    $ViewClass = 'PNM\\' . $table[0] . 'MicroView';

                    $this->View = New $ViewClass();
                    ?>
                    <h3><?= (empty($table[1]) ? ucfirst($table[0]) : $table[1]) ?></h3>
                    <ul><?php array_map([$this, 'renderRef'], $data->get($table[0]));
                    ?></ul>
                    <?php
                }
            }
        }
    }

    protected function renderRef($record) {
        echo '<li>', (empty($record['pages']) ? NULL : $record['pages'] . (empty($record['reference_type']) ? NULL : ' [' . $record['reference_type'] . ']') . ': '),
        $this->View->render($record['title'], $record['object_id']), '</li>';
    }

}
