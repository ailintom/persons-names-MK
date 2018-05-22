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

/*
 * 
 *     ['persons_id', 'title', 'gender', 'title_string', 'personal_name', 'dating', 'dating_note', 'region', 'region_note', 'note']
 */

class personView extends View {

    public function echoRender(&$data) {

        $placesMV = new placesMicroView();
        $personMV = new personsMicroView();
        $attView = new attestationsMicroView();
        $insView = new inscriptionsMicroView();
        $spellView = New spellingsMicroView();

        $titlesView = New titlesMicroView();
        (New Head)->render(Head::HEADERSLIM, $data->get('title'));
        ?><table class="name-box"><tr><th></th>
                <?= (empty($data->get('title_string')) ? NULL : '<th>Title</th>') ?>
                <th>Name</th></tr>
            <tr>
                <td><?= $this->renderGender($data->get('gender')) ?></td>
                <?= (empty($data->get('title_string')) ? NULL : '<td><span class="title">' . $data->get('title_string') . '</td>') ?>

                <td><span class="name"><?= $data->get('personal_name') ?></span></td>
            </tr>
        </table><dl>
            <?php
            echo $this->descriptionElement('Date', $data->get('dating'), $data->get('dating_note'), 'period'),
            $this->descriptionElement('Origin', $placesMV->render($data->get('region')), $data->get('region_note'), 'place'),
            $this->descriptionElement('Note', $data->get('note'), NULL, 'note'),
            $this->descriptionElement('Bibliography', $data->get('bibliography'));
            ?>
        </dl>
        <?php
        if ($data->get('bonds')->count > 0) {
            echo '<h2>Bonds</h2>';
            echo $this->renderBonds($data->get('bonds')->data, $personMV);
        }
        $objAtt = $data->get('attestations');
        ?>
        <h2>Attestations</h2>
        <ul>
            <?php
            foreach ($objAtt->data as $Att) {
                $attView->setInscription($Att['inscriptions_id']);
                echo '<li><h3 id="' . $Att['attestations_id'] . '">',
                $insView->render($this->renderObjectType($Att['object_type']) . ' ' . $Att['title'], $Att['inscriptions_id']), ': ',
                $attView->render($Att['title_string'], $Att['attestations_id'], $Att['personal_name']),
                ' (', $Att['status'], ')</h3>';
             
                $spellings = $Att['spellings']->getSpellings();
                $titles = $Att['titles']->data;

                echo '<table class="name-box"><tr><th></th>';
                if (!empty($titles)) {
                    echo '<th>Title</th>'; //if the attestation has associated titles, display the title heading
                }
                if (!empty($spellings) & !empty($spellings[0]['spellings'])) {
                    echo '<th>Name</th>'; //if the attestation has associated titles, display the name heading
                }
                echo '</tr><tr>';
                echo '<td><span class="gender" title="' . self::GenderTitle($Att['gender']) . '">' . $Att['gender'] . '</span></td>';

                if (!empty($titles)) {
                    echo '<td>';
                    $titleCount = 0;
                    foreach ($titles as $title) {
                        if ($titleCount++ > 0) {
                            echo'; ';
                        }
                        echo $titlesView->render($title['title'], $title['titles_id']);
                    }
                    echo '</td>';
                }



                if (!empty($spellings) & !empty($spellings[0]['spellings'])) {
                    echo'<td>';
                    $spellingCount = 0;
                    foreach ($spellings as $name) {
                        if ($spellingCount++ > 0) {
                            echo' / ';
                        }
                        $spellingPerNameCount = 0;
                        foreach ($name['spellings'] as $spelling) {
                            if ($spellingPerNameCount > 0) {
                                echo', ';
                            }
                            echo'<span class="name">';
                            echo'<a href="' . Config::BASE . 'name/' . $name['personal_names_id'] . '#' . $spelling['spellings_id'] . '">';
                            if ($spellingPerNameCount++ == 0) {
                                echo $name['personal_name'] . ' ';
                            }
                            echo $spellView->render($spelling['spelling'], $spelling['spellings_id'])
                            . '</a>';

                            echo $this->processAltReadings($spelling['alt_readings']);
                            echo'</span>';
                        }
                    }
                    echo '</td>';
                }

                echo'</tr></table>';
   if (!empty($Att['reasoning']) || !empty($Att['note'])) {
                    echo '<dl>';
                    echo $this->descriptionElement('Reasoning', $Att['reasoning'], NULL, 'reasoning'),
                    $this->descriptionElement('Note', $Att['note'], NULL, 'note');
                    echo '</dl>';
                }
                if (!empty($Att['bonds']->data)) {

                    echo $this->renderBonds($Att['bonds']->data, $attView);
                }

                echo '</li>';
            }


            echo '</ul>';
        }

    }
    