<?php
/*
 * Description of inscriptionsView
 * This class renders the inscriptions page
 */

namespace PNM\views;

class inscriptionsView extends View
{

    public function echoRender(&$data)
    {
        //$res = null;
        (new HeadView())->render(HeadView::HEADERSLIM, 'Inscriptions');
        $total = $data->count;
        for ($i = 0; $i < $total; $i++) {
            $data->data[$i]['object_type'] = $this->renderObjectType($data->data[$i]['object_type']);
            $data->data[$i]['text_content'] = $this->renderTextContent($data->data[$i]['text_content']);
        }
        ?>
        <p class="info-box">
            <?= Icon::get('info') ?>
            You can use <b>%</b> or <b>*</b> as wildcards when searching for titles or inventory numbers. Thus,
            “CG 2011*” will match all stelae from “CG 20110” to “CG 20119”. Inventory numbers are searched for when a collection is selected.
        </p>
        <form action="<?= \PNM\Request::makeURL('inscriptions') ?>" method="get" onreset="MK.removeAllFilters()">
            <div class="row">
                <div class="column">
                    <?= (new TextInput('collection', 'Collection', 'The short name of the museum', 'Example: Bruxelles', 'collections'))->render() ?>
                </div>
                <div class="column">
                    <?= (new TextInput('title', 'Title or inventory number', 'The title of the insciption used in the database or the inventory number in the collection', 'Example: Adam, ASAE 56, 213 or JE 43104'))->render() ?>
                </div>
            </div>
            <?php
            $typeInputs = [(new TextInput('object_type', 'Object type:', 'The main type of the object', 'Example: Stela', 'object-types'))->render(),
                (new TextInput('object_subtype', 'Subtype:', 'The more more particular type of the object', 'Example: Round-topped stela', 'object-subtypes'))->render()];
            $filters[] = new FormFilter('type-filter', 'Type', $typeInputs, ['object_type', 'object_subtype']);
            $filters[] = new FormFilter('material-filter', 'Material', (new TextInput('material', 'Material:', 'The principal material the  object is made of', 'Example: Granite', 'materials'))->render(), 'material');
            $sizeInputs = (new RadioGroup('size-option', [['less', 'less than or equal to'], ['greater', 'greater than or equal to']], 'greater', 'size-filter'))->render()
                    . ' ' . (new TextInput('size', 'Size in mm:', 'Size in millimeters', null, null, true))->render();
            $filters[] = new FormFilter('size-filter', 'Size', $sizeInputs, 'size');
            $contentInputs = (new Select('text_content', 'Content:', 'The principal content of the inscription', \PNM\models\Lookup::getThesaurus(\PNM\models\Lookup::TEXT_CONTENT_THESAURUS), ''))->render();
            $filters[] = new FormFilter('content-filter', 'Content', $contentInputs, 'text_content');
            $scriptInputs = (new Select('script', 'Script:', 'The script of the inscription', \PNM\models\Lookup::getThesaurus(\PNM\models\Lookup::SCRIPT_THESAURUS), ''))->render();
            $filters[] = new FormFilter('script-filter', 'Script', $scriptInputs, 'script');
            /*
             *
              <input id="greater" name="size-option" value="greater" type="radio" checked aria-labelledby="size-label">
              <label for="greater">greater than or equal to</label>
              /
              <input id="less" name="size-option" value="less" type="radio" aria-labelledby="size-label">
              <label for="less">less than or equal to</label>
              <label for="size" class="sr-only">Size in mm</label>
              <input id="size" name="size" placeholder="size in mm" type="text">
             */
            $regioInputs = (new RadioGroup('geo-filter', [['provenance', 'Provenance', 'Attestations in sources found in the certain region']
                , ['installation', 'installation place', 'Attestations on monuments installed in certain region']
                , ['origin', 'origin', 'Attestations in sources found in the certain region']
                , ['production', 'production', 'Attestations in sources found in the certain region']
                , ['all', 'all', 'Attestations in sources anyhow related to a certain region']], 'all', 'region-filter'))->render()
                    . ' in the region ' . (new TextInput('place', 'Region', 'Enter the place', 'Example: Abydos', 'places', true))->render();
            $filters[] = new FormFilter('region-filter', 'Region', $regioInputs, 'place');
            $periodInputs = (new RadioGroup('chrono-filter', [['during', 'During', 'Objects beloging to a certain period']
                , ['not-later', 'not later than', 'Objects dating not (demonstrably) later than']
                , ['not-earlier', 'not earlier than', 'Objects dating not (demonstrably) earlier than']
                    ], 'during', 'period-filter'))->render()
                    . ' the period ' . (new TextInput('period', 'Period:', 'Enter the period', 'Example: 17th Dyn.', 'periods', true))->render();
            $filters[] = new FormFilter('period-filter', 'Period', $periodInputs, 'period');
            FormFilter::renderFilters($filters);
            ?>
            <button type="submit">
                Search
            </button>
            <button type="submit" title="Clear search and display all records" name="action" value="reset">
                Reset
            </button>
            <?php
            $dl = new Datalist();
            echo $dl->get('collections'), $dl->get('materials'), $dl->get('object-types'), $dl->get('object-subtypes'), $dl->get('periods'), $dl->get('places');
            ?>
        </form>
        <h2 class="sr-only">Results</h2>
        <?php
        $tableCo = new TableView($data, 'inscriptions_id', 'inscription', 'sort', '#results');
        $tableCo->renderTable(['object_type', 'title', 'material',
            'size', 'text_content', 'dating', 'inst_prov_temp', 'orig_prod_temp', 'owner'], ['Type', 'Object', 'Material', 'Size, mm',
            'Text', 'Date', 'Provenance', 'Origin/Prod.', 'Owner'], true, '',['Type', 'Object', 'Material', 'Size in mm',
            'Text type', 'Date', 'Provenance or installation place', 'the origin of the owner or the place of production', 'Owner’s name']);
      
        // ['inscriptions_id', 'object_type', 'title', 'material', 'size', 'text_content', 'dating', 'inst_prov_temp', 'orig_prod_temp', 'owner']
        $this->toggleFilters(FormFilter::renderToggle($filters));
    }
}
