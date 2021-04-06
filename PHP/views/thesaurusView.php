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
                if (intval($data->get('count_nested')) > 0) {
            ?>
            <h2 id="subordinate">Subordinate items</h2>
            <p><?php echo( $data->get('count_nested')) ?> in total</p>
            <?php
            $tableWk = new TableView($data->get('nested'), 'thesauri_id', 'thesaurus', 'sort', '#subordinate');
            $tableWk->renderTable(['item_name', 'external_key', 'explanation'], ['Item name', 'External key', 'Explanation']);
        }
                        if (intval($data->get('count_items')) > 0) {
            ?>
            <h2 id="items">Items</h2>
            <p><?php echo( $data->get('count_items')) ?> in total</p>
            <?php
            $tableWk = new TableView($data->get('items'), 'thesauri_id', 'thesaurus', 'sort', '#items');
            $tableWk->renderTable(['item_name', 'external_key', 'explanation'], ['Item name', 'External key', 'Explanation']);
        }
    }
}

//  ['thesauri_id', 'thesaurus', 'parent', 'sort_value', 'item_name', 'external_key', 'explanation'