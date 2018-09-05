<?php
/*
 * Description of publicationView
 * Class used to render a page representing a single publication (with all referenced entities)
 */

namespace PNM\views;

class publicationView extends View
{

    protected $view;

    public function echoRender(&$data)
    {
        (new HeadView())->render(HeadView::HEADERSLIM, $data->get('author_year'));
        ?>
        <?= $data->get('html_entry') ?>
        <dl>
            <?php
            $ref = $this->addReference('OEB ID', $data->get('oeb_id'), \PNM\ExternalLinks::OEB);
            echo( $this->descriptionElement('References', $ref));
            ?>
        </dl>
        <?php
        if ($data->get('refs_count') > 0) {
            ?><h2>Entities referred to in this publication</h2><?php
            foreach ($data->tables as $table) {
                if (!empty($data->get($table[0]))) {
                    $ViewClass = 'PNM\\views\\' . $table[0] . 'MicroView';
                    $this->view = new $ViewClass();
                    ?>
                    <h3><?= (empty($table[1]) ? ucfirst($table[0]) : $table[1]) ?></h3>
                    <ul><?php array_map([$this, 'renderRef'], $data->get($table[0]));
                    ?></ul>
                    <?php
                }
            }
        }
    }

    protected function renderRef($record)
    {
        echo '<li>', (empty($record['pages']) ? null : $record['pages'] . ': '),
        $this->view->render($record['title'], $record['object_id']),
        (empty($record['reference_type']) ? null : ' [' . $record['reference_type'] . ']'), '</li>';
    }
}
