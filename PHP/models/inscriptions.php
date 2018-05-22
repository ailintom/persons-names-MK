<?php

/*
 * MIT License
 * 
 * Copyright (c) 2017 Alexander Ilin-Tomich (unless specified otherwise for individual source files and documents)
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace PNM;

/**
 * Description of Bibliography
 *
 */
class inscriptions extends ListModel {

    protected $tablename = 'inscriptions';
    public $defaultsort = 'title';

    protected function initFieldNames() {
        $this->field_names = new FieldList(['inscriptions_id', 'object_type', 'title', 'material', ' GREATEST(IFNULL(length,0), IFNULL(height,0), IFNULL(width,0), IFNULL(thickness,0))', 'text_content', 'dating', 'inst_prov_temp', 'orig_prod_temp',
            'SELECT CONCAT_WS(" ", IF(CHAR_LENGTH(title_string)>IF(CHAR_LENGTH(personal_name)<14, 31 - CHAR_LENGTH(personal_name), 17), CONCAT("...", RIGHT(title_string, IF(CHAR_LENGTH(personal_name)<14, 31 - CHAR_LENGTH(personal_name), 17))), title_string), personal_name) from attestations WHERE attestations.inscriptions_id  = inscriptions.inscriptions_id LIMIT 1'], ['inscriptions_id', 'object_type', 'title', 'material', 'size', 'text_content', 'dating', 'inst_prov_temp', 'orig_prod_temp', 'owner']);
    }

    protected function getSortField($sortField = NULL) {
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
      return 'Table';
      case 'Sculpture in the round':
      return 'Statue';
      case 'Unspecified':
      return '';
      case 'Written document':
      return 'Hieratic text';
     * 
     */
}
