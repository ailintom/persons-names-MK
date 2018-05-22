<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PNM;

/**
 * Description of FormFilter
 *
 * @author Tomich
 */
class FormFilter {

    public $name;
    protected $label;
    protected $content;
    public $mainFieldName;
    protected $fullLabel;

    public
            function __construct($name, $label, $content, $mainFieldName, $fullLabel = NULL) {
        $this->name = $name;
        $this->label = $label;
        $this->content = $content;
        $this->mainFieldName = $mainFieldName;
        $this->fullLabel = $fullLabel ?: $label;
    }

    public function renderSelection() {
        ?><button class="filters_button" aria-controls="<?= $this->name ?>" aria-expanded="false" onclick="MK.toggleFilter('<?= $this->name ?>')" title="Toggle <?= lcfirst($this->label) ?> filter" type="button">
        <?= icon('plus') . icon('minus') ?>
        <?= $this->label ?>
        </button>
        <?php
    }

    public function renderFilter() {
        ?><div class="filter" id="<?= $this->name ?>">
            <div class="filter_label">
                <button class="filter_remove" onclick="MK.toggleFilter('<?= $this->name ?>')" title="Remove <?= lcfirst($this->label) ?> filter" type="button">
                    <span id="<?= $this->name ?>-label"><?= icon('minus', 'Remove ' . lcfirst($this->label) . ' filter') ?></span>
                </button>
                <?= $this->fullLabel ?>
            </div>
            <div class="filter_content"><?php
                if (is_array($this->content)) {
                    foreach ($this->content as $block) {
                        ?>
                        <div class="filter_block">
                            <?= $block ?>
                        </div>
                        <?php
                    }
                } else {
                    echo $this->content;
                }
                ?></div>
        </div>
        <?php
    }

    static function getTogglePair(FormFilter $filter) {
        return [$filter->mainFieldName, $filter->name];
    }

    static function renderToggle(array $filters) {
        return array_map('self::getTogglePair', $filters);
    }

    static function renderFilters(array $filters) {
        ?>
        <div class="filters">
            <h3 class="sr-only">Filters</h3>

            <div class="filters_selection"><?php
                foreach ($filters as $filter) {
                    $filter->renderSelection();
                }
                ?></div><?php
            foreach ($filters as $filter) {
                $filter->renderFilter();
            }
            ?></div><?php
    }

}
