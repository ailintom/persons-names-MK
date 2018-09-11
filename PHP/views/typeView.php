<?php
/*
 * Description of typeView
 * Class used to render a page representing a single name type (with all the names belonging to the type)
 */

namespace PNM\views;

use \PNM\Request;

class typeView extends View
{

    public function echoRender(&$data)
    {
        (new HeadView())->render(HeadView::HEADERSLIM, $data->get('title'));
        ?>
        <p><span class="note"><?= ($data->get('category') == 'formal' ? 'Formal name pattern' : 'Semantic name class') ?></span></p>
        <dl>
            <?php
            echo( $this->descriptionElement('Superordinate types', $this->superordinate($data->get('parents'))));
            echo( $this->descriptionElement('Subordinate types', null));
            if (!empty($data->get('subtypes')->data[0]['children'])) {
                echo('<dt>Subordinate type:</dt><dd>');
                $this->renderChildren($data->get('subtypes')->data[0], 0);
                echo('</dd>');
            }
            echo( $this->descriptionElement('Total number of attestations of names belonging to this type and its sub-types', '<a href="' . Request::makeURL('people') . '?A' . ($data->get('category') == 'formal' ? 'form' : 'sem' ) . '_type' . '=' . urlencode($data->get('title')) . '">' . $data->get('attestations_count') . '</a>'));
            echo $this->descriptionElement('Note', $data->get('note'), null, 'note');
            echo $this->descriptionElement('Bibliography', $this->renderBiblio($data->get('bibliography')), null, 'biblio-ref');
            //renderURL
            ?>
        </dl>
        <?php
        if ($data->get('names')->total_count > 0) {
            $tableCo = new TableView($data->get('names'), 'personal_names_id', 'name', 'sort');
            $tableCo->renderTable(['personal_name', 'gender', 'count_attestations', 'usage_period', 'usage_area', 'ranke', 'translation_en'], ['Personal name', 'Gender', 'Atts.', 'Period', 'Area', 'Ranke no.', 'Translation'], true);
        }
    }

    protected function superordinate($data)
    {
        if (empty($data)) {
            return null;
        }
        $res = null;
        $typesMV = new name_typesMicroView();
        for ($i = count($data) - 1; $i >= 0; $i--) {
            $res .= '<ul><li>' . $typesMV->render($data[$i][1], $data[$i][0]);
        }
        $res .= str_repeat('</li></ul>', count($data));
        return $res;
    }
}
