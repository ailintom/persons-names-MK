<?php
/*
 * Description of FormFilter
 * This class is used to generate the HTML code for a collapsible filter group (a group of controls for setting a less important search criterion) on a search page
 */

namespace PNM\views;

class FormFilter {

    public $name;
    protected $label;
    protected $content;
    public $mainFieldName;
    protected $fullLabel;

    public function __construct($name, $label, $content, $mainFieldName, $fullLabel = null, $defaultVal = null) {
        $this->name = $name;
        $this->label = $label;
        $this->content = $content;
        $this->mainFieldName = $mainFieldName;
        $this->fullLabel = $fullLabel ?: $label;
        $this->defaultVal = $defaultVal;
    }

    public function renderSelection() {
        ?><button class="filters_button" aria-controls="<?= $this->name ?>" aria-expanded="false" onclick="MK.toggleFilter('<?= $this->name ?>')" title="Toggle <?= lcfirst($this->label) ?> filter" type="button">
        <?= Icon::get('plus') . Icon::get('minus') ?>
        <?= $this->label ?>
        </button>
        <?php
    }

    public function renderFilter() {
        ?><div class="filter" id="<?= $this->name ?>">
            <div class="filter_label">
                <button class="filter_remove" onclick="MK.toggleFilter('<?= $this->name ?>')" title="Remove <?= lcfirst($this->label) ?> filter" type="button">
                    <span id="<?= $this->name ?>-label"><?= Icon::get('minus', 'Remove ' . lcfirst($this->label) . ' filter') ?></span>
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

            public static function getTogglePair(FormFilter $filter) {
                if (isset($filter->defaultVal)) {
                    return [$filter->mainFieldName, $filter->name, $filter->defaultVal];
                } else {
                    return [$filter->mainFieldName, $filter->name];
                }
            }

            public static function renderToggle(array $filters) {
                return array_map('self::getTogglePair', $filters);
            }

            public static function renderFilters(array $filters) {
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
        