<?php
/*
 * Description of criterionView
 * Class used to render a page representing a single criterion
 */

namespace PNM\views;

class thesaurusView extends View
{

    public function echoRender(&$data)
    {
        (new HeadView())->render(HeadView::HEADERSLIM, $data->get('item_name'));
        ?>

        <dl>
            <?php
            $thesauriMV = new thesauriMicroView();
            echo $this->descriptionElement('Thesaurus', $thesauriMV->render($data->get('thesaurus_name'), $data->get('thesaurus')), null, 'thesaurus'),
            $this->descriptionElement('Superordinate item', $thesauriMV->render($data->get('parent_name'), $data->get('parent')), null, 'parent'),
            $this->descriptionElement('Explanation', $data->get('explanation'), null, 'explanation');

            $ref = $this->addReference('THOT ID', $data->get('external_key'), \PNM\ExternalLinks::THOTH_CONCEPT);

            echo( $this->descriptionElement('References', $ref));
            ?>
        </dl>
        <?php
    }
}

//  $this->field_names = new FieldList(['thesauri_id', 'thesaurus', 'parent', 'sort_value', 'item_name', 'external_key', 'explanation']);