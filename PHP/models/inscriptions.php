<?php

/*
 * Description of inscriptions
 * A model representing database records for inscriptions   
 */

namespace PNM\models;

class inscriptions extends ListModel
{

    protected $tablename = 'inscriptions';
    public $defaultsort = 'title';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['inscriptions_id', 'object_type', 'title', 'material', ' GREATEST(IFNULL(length,0), IFNULL(height,0), IFNULL(width,0), IFNULL(thickness,0))', 'text_content', 'dating', 'inst_prov_temp', 'orig_prod_temp',
            'SELECT CONCAT_WS(" ", IF(CHAR_LENGTH(title_string)>IF(CHAR_LENGTH(personal_name)<14, 31 - CHAR_LENGTH(personal_name), 17), '
            . 'CONCAT("...", RIGHT(title_string, IF(CHAR_LENGTH(personal_name)<14, 31 - CHAR_LENGTH(personal_name), 17))), title_string), personal_name) '
            . 'FROM attestations '
            . 'WHERE attestations.inscriptions_id  = inscriptions.inscriptions_id LIMIT 1'], ['inscriptions_id', 'object_type', 'title', 'material', 'size', 'text_content', 'dating', 'inst_prov_temp', 'orig_prod_temp', 'owner']);
    }

    protected function getSortField($sortField = null)
    {
        if (empty($sortField)) {
            $sortField = $this->defaultsort;
        }
        return $this->replaceSortField($sortField, ['title', 'dating', 'object_type',
                    'inst_prov_temp', 'orig_prod_temp', 'owner', 'size'], ['title_sort', 'dating_sort_start+dating_sort_end',
                    'FIELD(object_type, "Unspecified","Architectural element", "Block", "Written document", "Rock inscription",'
                    . '"Scarab, seal, scaraboid, intaglio and similar objects", "Shabti", "Sculpture in the round", "Stela", "Offering table",'
                    . '"Tomb", "Tomb equipment")',
                    'inst_prov_temp_sort', 'orig_prod_temp_sort',
                    '(SELECT personal_name_sort FROM attestations WHERE attestations.inscriptions_id  = inscriptions.inscriptions_id LIMIT 1)', ' GREATEST(IFNULL(length,0), IFNULL(height,0), IFNULL(width,0), IFNULL(thickness,0))']);
    }
    /*       case 'Scarab, seal, scaraboid, intaglio and similar objects':
      return 'Seal/sealing';
      case 'Offering table':
      return 'TableView';
      case 'Sculpture in the round':
      return 'Statue';
      case 'Unspecified':
      return '';
      case 'Written document':
      return 'Hieratic text';
     *
     */
}
