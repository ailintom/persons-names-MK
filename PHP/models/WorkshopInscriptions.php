<?php

namespace PNM\models;

/*
 * Description of WorkshopInscriptions
 * A model for inscriptions belonging to a particular workshop
 *
 */

class WorkshopInscriptions extends ListModel
{

    protected $tablename = ' ((inscriptions_workshops_xref INNER JOIN objects ON inscriptions_workshops_xref.objects_id = objects.objects_id) INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id) INNER JOIN inscriptions ON objects_inscriptions_xref.inscriptions_id = inscriptions.inscriptions_id';
    public $defaultsort = 'inscriptions.title_sort';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['inscriptions_workshops_xref.note', '`status`', 'inscriptions.inscriptions_id', 'object_type', 'inscriptions.title', 'material', ' GREATEST(IFNULL(length,0), IFNULL(height,0), IFNULL(width,0), IFNULL(thickness,0))', 'text_content', 'dating', 'inst_prov_temp', 'orig_prod_temp',
            'SELECT CONCAT_WS(" ", IF(CHAR_LENGTH(title_string)>IF(CHAR_LENGTH(personal_name)<14, 31 - CHAR_LENGTH(personal_name), 17), CONCAT("...", RIGHT(title_string, IF(CHAR_LENGTH(personal_name)<14, 31 - CHAR_LENGTH(personal_name), 17))), title_string), personal_name) from attestations WHERE `status` = "owner" and attestations.inscriptions_id  = inscriptions.inscriptions_id LIMIT 1'], ['note', '`status`', 'inscriptions_id', 'object_type', 'title', 'material', 'size', 'text_content', 'dating', 'inst_prov_temp', 'orig_prod_temp', 'owner']);
    }

    protected function getSortField($sortField = null)
    {
        if (empty($sortField)) {
            $sortField = $this->defaultsort;
        }
        return $this->replaceSortField($sortField, ['title', 'dating', 'object_type',
                    'inst_prov_temp', 'orig_prod_temp', 'owner', 'size'], ['inscriptions.title_sort', 'dating_sort_start+dating_sort_end',
                    'FIELD(object_type, "Unspecified","Architectural element", "Block", "Written document", "Rock inscription",'
                    . '"Scarab, seal, scaraboid, intaglio and similar objects", "Shabti", "Sculpture in the round", "Stela", "Offering table",'
                    . '"Tomb", "Tomb equipment")',
                    'inst_prov_temp_sort', 'orig_prod_temp_sort',
                    '(SELECT personal_name_sort FROM attestations WHERE `status` = "owner" and attestations.inscriptions_id  = inscriptions.inscriptions_id LIMIT 1)', ' GREATEST(IFNULL(length,0), IFNULL(height,0), IFNULL(width,0), IFNULL(thickness,0))']);
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
