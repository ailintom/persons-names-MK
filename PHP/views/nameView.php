<?php
/*
 * Description of nameView
 * Class used to render a page representing a single personal name
 */

namespace PNM\views;
use \PNM\Request;

class nameView extends View
{

    protected $spellView;

    public function echoRender(&$data)
    {
        $placesMV = new placesMicroView();
        $subtitle = null;
        if (!empty($data->get('translation_en'))) {
            $subtitle = '<span class="translation" lang="en">“' . $data->get('translation_en') . '”</span>' . (empty($data->get('translation_de')) ? null : ', ');
        }
        if (!empty($data->get('translation_de'))) {
            $subtitle .= '<span class="translation" lang="de">“' . $data->get('translation_de') . '”</span>';
        }
        (new HeadView())->render(HeadView::HEADERSLIM, $data->get('personal_name'));
        ?><div class="toc">
            <h2>Spellings</h2>
            <ul class="toc_list">
                <?php
                $this->spellView = new spellingsMicroView();
                foreach ($data->get("spellings")->data as $spelling) {
                    echo '<li><a href = "#', \PNM\ID::shorten($spelling['spellings_id']), '">', $this->spellView->render($spelling['spelling'], $spelling['spellings_id']), ' (', $spelling['count_attestations'], ')</a></li>';
                }
                if ($data->get("persons")->count > 0) {
                    echo '<li><a href = "#persons">Dossiers (', $data->get("persons")->count, ')</a></li>';
                }
                ?>
            </ul>
        </div>
        <p>
            <?= $subtitle ?>
        </p>
        <dl>
            <?php
            $typeMV = new name_typesMicroView();
            $renderedTypes = array_map([$typeMV, 'render'], array_column($data->get('name_types')->data, 'title'), array_column($data->get('name_types')->data, 'name_types_id'));
            echo $this->descriptionElement('Name types', implode(', ', $renderedTypes), null, 'note'),
            $this->descriptionElement('Note', $data->get('note'), null, 'note'),
            $this->descriptionElement('Usage area', $placesMV->render($data->get('usage_area')), $data->get('usage_area_note'), 'place'),
            $this->descriptionElement('Usage period', $data->get('usage_period'), $data->get('usage_period_note'), 'period'),
            $this->descriptionElement('Gender', $data->get('gender'), null, 'gender');
            $altReadings = implode(', ', array_map([$this, 'renderAltReading'], $data->get('alt_readings')->data));
            echo $this->descriptionElement('Is a possible reading of', $altReadings);
            echo $this->descriptionElement('Bibliography', $this->renderBiblio($data->get('bibliography')), null, 'biblio-ref');
            $ref = $this->addReference('Ranke no.', $data->get('ranke'));
            $ref = $this->addReference('TLA no.', $data->get('tla'), \PNM\ExternalLinks::TLA, $ref);
            $ref = $this->addReference('AGÉA no.', $data->get('agea'), \PNM\ExternalLinks::AGEA, $ref);
            $ref = $this->addReference('Scheele-Schweitzer no.', $data->get('scheele-schweitzer'), null, $ref);
            echo( $this->descriptionElement('References', $ref));
            //  ['titles_id', 'title', 'gender', 'count_attestations', 'usage_period', 'usage_area', 'usage_period_note', 'usage_area_note', 'note', 'ward_fischer', 'hannig', 'tla', 'translation_en', 'translation_de']
            ?>
        </dl>
        <h2>Spellings &amp; Attestations</h2>
        <div class="spellings">
            <?php
            $j = 0;
            $inscrMV = new inscriptionsMicroView();
            foreach ($data->get("spellings")->data as $spelling) {
                echo ' <div class="spellings_item" id="', \PNM\ID::shorten($spelling['spellings_id']), '"><h3>', $this->spellView->render($spelling['spelling'], $spelling['spellings_id']), $this->processAltReadings($spelling['alt_readings']), '</h3><ol start="', $spelling['first_no'], '">';
                foreach ($spelling['attestations']->data as $att) {
                    echo '<li id="att', ++$j, '"><p>', $this->renderGender($att['gender']), ' ';
                    $inscrMV->echoRender($this->renderObjectType($att['object_type']) . ' ' . $att['title'], $att['inscriptions_id']);
                    echo '</p><dl class="-inline">';
                    echo $this->descriptionElement('Title', $att['title_string'], null, 'title'),
                    $this->descriptionElement('Provenance', $placesMV->render($att['provenance']), null, 'place'),
                    $this->descriptionElement('Installation place', $placesMV->render($att['installation_place']), null, 'place'),
                    $this->descriptionElement('Origin', $placesMV->render($att['origin']), null, 'place'),
                    $this->descriptionElement('Production place', $placesMV->render($att['production_place']), null, 'place'),
                    $this->descriptionElement('Date', $att['dating'], null, 'period');
                    if (!empty($att['persons'])) {
                        echo $this->descriptionElement('Dossier', $this->renderPersons($att['persons']), null, null);
                    }
                    echo '</dl></li>';
                }
                echo '</ol></div>';
            }
            ?>
        </div>
        <?php
        if ($data->get("persons")->count > 0) {
            echo ' <div id="persons"><h3>Dossiers (multiple attestations of the same persons)</h3>';
            $personsMV = new personsMicroView();
            foreach ($data->get("persons")->data as $person) {
                echo '<p>', $personsMV->render(($person['title']), $person['persons_id']);
                if (!empty($person['attestations'])) {
                    echo ': attestations ';
                    for ($i = 0; $i < count($person['attestations']); $i++) {
                        echo ($i > 0 ? ', ' : null), '<a href="#att', $person['attestations'][$i]['att_no'], '">', $person['attestations'][$i]['att_no'], '</a>';
                    }
                }
                echo '</p>';
            }
            echo '</div>';
        }
        // ['inscriptions_id', 'attestations_id', 'personal_name', 'gender', 'title_string', 'title', 'dating', 'origin', 'count_persons']
    }

    protected function renderAltReading($altReading)
    {
        return $this->renderSpelling($altReading['personal_names_id'], $altReading['personal_name'], $altReading['spellings_id'], $altReading['spelling']);
    }

    protected function renderSpelling($personal_names_id, $personal_name, $spellings_id, $spelling)
    {
        $res = '<span class="name">';
        $res .= '<a href="' . Request::makeURL('name', [$personal_names_id, $spellings_id]) . '">';
        $res .= $personal_name . ' ';
        $res .= $this->spellView->render($spelling, $spellings_id)
                . '</a>';
        $res .= '</span>';
        return $res;
    }
}
