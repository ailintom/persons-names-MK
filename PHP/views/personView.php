<?php
/*
 * Description of personView
 * Class used to render a page representing a single person
 */

namespace PNM\views;

use \PNM\Request;

/*
 *     ['persons_id', 'title', 'gender', 'title_string', 'personal_name', 'dating', 'dating_note', 'region', 'region_note', 'note']
 */

class personView extends View
{

    public function echoRender(&$data)
    {
        $placesMV = new placesMicroView();
        $personMV = new personsMicroView();
        $attView = new attestationsMicroView();
        $insView = new inscriptionsMicroView();
        $spellView = new spellingsMicroView();
        $titlesView = new titlesMicroView();
        (new HeadView())->render(HeadView::HEADERSLIM, 'Person ' . $data->get('title'));
        ?><table class="name-box"><tr><th></th>
        <?= (empty($data->get('title_string')) ? null : '<th>Title</th>') ?>
                <th>Name</th></tr>
            <tr>
                <td><?= $this->renderGender($data->get('gender')) ?></td>
                <?= (empty($data->get('title_string')) ? null : '<td><span class="tit">' . $data->get('title_string') . '</span></td>') ?>
                <td><span class="pn"><?= $data->get('personal_name') ?></span></td>
            </tr>
        </table><dl>
            <?php
            echo $this->descriptionElement('Date', $data->get('dating'), $data->get('dating_note'), 'period'),
            $this->descriptionElement('Origin', $placesMV->render($data->get('region')), $data->get('region_note'), 'place'),
            $this->descriptionElement('Note', $data->get('note'), null, 'note'),
            $this->descriptionElement('Bibliography', $this->renderBiblio($data->get('bibliography')));
            ?>
        </dl><?php
        if ($data->get('bonds')->count > 0) {
            echo '<h2>Bonds</h2>';
            echo $this->renderBonds($data->get('bonds')->data, $personMV);
        }
        $objAtt = $data->get('attestations');
        ?>
        <h2>Attestations</h2>
        <ul><?php
            foreach ($objAtt->data as $Att) {
                $attView->setInscription($Att['inscriptions_id']);
                echo '<li><h3 id="' . \PNM\ID::shorten($Att['attestations_id']) . '">',
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
                            $attestationRender = [];
      //Render table data
            $renderGender = '<span class="gender" title="' . self::genderTitle($Att['gender']) . '">' . $Att['gender'] . '</span>';
            $this->pushAttetastionElement($attestationRender, $renderGender, self::genderHead);

            if (!empty($titles)) {
                $renderTitles = '';

                $titleCount = 0;
                foreach ($titles as $title) {
                    if ($titleCount++ > 0) {
                        $renderTitles .= '; ';
                    }
                    $renderTitles .= $titlesView->render($title['title'], $title['titles_id']);
                }
                $this->pushAttetastionElement($attestationRender, $renderTitles, self::titleHead);
            }
            if (!empty($spellings) & !empty($spellings[0]['spellings'])) {

                $nameRender = '';
                $spellingCount = 0;
                foreach ($spellings as $name) {
                    if ($spellingCount++ > 0) {
                        $nameRender .= ' / ';
                    }
                    $spellingPerNameCount = 0;
                    foreach ($name['spellings'] as $spelling) {
                        if ($spellingPerNameCount > 0) {
                            $nameRender .= ', ';
                        }
                        $nameRender .= '<span class="name">';
                        $nameRender .= '<a href="' . Request::makeURL('name', [$name['personal_names_id'], $spelling['spellings_id']]) . '">';
                        if ($spellingPerNameCount++ == 0) {
                            $nameRender .= $name['personal_name'] . ' ';
                        }
                        $nameRender .= $spellView->render($spelling['spelling'], $spelling['spellings_id'])
                                . '</a>';
                        $nameRender .= $this->processAltReadings($spelling['alt_readings']);

                        $nameRender .= '</span>';
                        $curEpithet_mdc = $spelling['epithet_mdc'];
                        $curClassifier = $spelling['classifier'];
                        if (!empty($curEpithet_mdc) || !empty($curClassifier)) {
                            if (!empty($nameRender)) {
                                $this->pushAttetastionElement($attestationRender, $nameRender, self::nameHead);
                            }
                            $nameRender = '';
                            $spellingCount = 0;
                            $spellingPerNameCount = 0;
                        }
                        if (!empty($curEpithet_mdc)) {
                            $this->pushAttetastionElement($attestationRender, $this->render_mdc($curEpithet_mdc), self::epithetHead);
                        }

                        if (!empty($curClassifier)) {

                            $this->pushAttetastionElement($attestationRender, $this->render_mdc($curClassifier), self::classifierHead);
                        }
                    }
                }
                if (!empty($nameRender)) {
                    $this->pushAttetastionElement($attestationRender, $nameRender, self::nameHead);
                }
            }

            echo $this->attestationTable($attestationRender);
            
                if (!empty($Att['reasoning']) || !empty($Att['note'])) {
                    echo '<dl>';
                    echo $this->descriptionElement('Reasoning', $Att['reasoning'], null, 'reasoning'),
                    $this->descriptionElement('Note', $Att['note'], null, 'note');
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
    