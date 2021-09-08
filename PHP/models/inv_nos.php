<?php

/*
 * Description of inv_nos
 * A model representing database records for inventory numbers   
 */

namespace PNM\models;

class inv_nos extends ListModel
{

    protected $tablename = 'objects INNER JOIN inv_nos on inv_nos.objects_id = objects.objects_id';
    public $defaultsort = 'inv_no';

    protected function initFieldNames()
    {
        $this->field_names = new FieldList(['inv_no', 'status', 'objects.objects_id', 'object_type', 'title', 'material', 
            ' GREATEST(IFNULL(length,0), IFNULL(height,0), IFNULL(width,0), IFNULL(thickness,0))', 
            'SELECT text_content FROM inscriptions INNER JOIN objects_inscriptions_xref ON inscriptions.inscriptions_id = objects_inscriptions_xref.inscriptions_id WHERE objects_inscriptions_xref.objects_id  = objects.objects_id LIMIT 1', 
            'SELECT dating FROM inscriptions INNER JOIN objects_inscriptions_xref ON inscriptions.inscriptions_id = objects_inscriptions_xref.inscriptions_id WHERE objects_inscriptions_xref.objects_id  = objects.objects_id LIMIT 1', 
            'SELECT inst_prov_temp FROM inscriptions INNER JOIN objects_inscriptions_xref ON inscriptions.inscriptions_id = objects_inscriptions_xref.inscriptions_id WHERE objects_inscriptions_xref.objects_id  = objects.objects_id LIMIT 1', 
            'SELECT orig_prod_temp FROM inscriptions INNER JOIN objects_inscriptions_xref ON inscriptions.inscriptions_id = objects_inscriptions_xref.inscriptions_id WHERE objects_inscriptions_xref.objects_id  = objects.objects_id LIMIT 1',
            'SELECT CONCAT_WS(" ", IF(CHAR_LENGTH(title_string)>IF(CHAR_LENGTH(personal_name)<14, 31 - CHAR_LENGTH(personal_name), 17), CONCAT("...", RIGHT(title_string, IF(CHAR_LENGTH(personal_name)<14, 31 - CHAR_LENGTH(personal_name), 17))), title_string), personal_name) FROM attestations INNER JOIN objects_inscriptions_xref ON attestations.inscriptions_id = objects_inscriptions_xref.inscriptions_id WHERE objects_inscriptions_xref.objects_id  = objects.objects_id LIMIT 1'],
                ['inv_no', 'status', 'inscriptions_id', 'object_type', 'title', 'material', 'size', 'text_content', 'dating', 'inst_prov_temp', 'orig_prod_temp', 'owner']);
    }

    protected function getSortField($sortField = null)
    {
        if (empty($sortField)) {
            $sortField = $this->defaultsort;
        }
        return $this->replaceSortField($sortField, ['inv_no', 'title', 'dating', 'object_type',
                    'inst_prov_temp', 'orig_prod_temp', 'owner', 'size'], ['inv_no_sort', 'title_sort', 'dating_sort_start+dating_sort_end',
                    'FIELD(object_type, "Unspecified","Architectural element", "Block", "Written document", "Rock inscription",'
                    . '"Scarab, seal, scaraboid, intaglio and similar objects", "Shabti", "Sculpture in the round", "Stela", "Offering table",'
                    . '"Tomb", "Tomb equipment")',
                    'inst_prov_temp_sort', 'orig_prod_temp_sort',
                    '(SELECT personal_name_sort FROM attestations INNER JOIN objects_inscriptions_xref ON attestations.inscriptions_id = objects_inscriptions_xref.inscriptions_id WHERE objects_inscriptions_xref.objects_id  = objects.objects_id LIMIT 1)', 
                        ' GREATEST(IFNULL(length,0), IFNULL(height,0), IFNULL(width,0), IFNULL(thickness,0))']);
    }
    
}
