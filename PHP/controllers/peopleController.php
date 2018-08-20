<?php

/*
 * Description of peopleController
 * This controller is used to search for people
 *
 */

namespace PNM\controllers;

class peopleController
{

    public function load()
    {
        $Arules = [];
        if (!empty(\PNM\Request::get('Agender')) && \PNM\Request::get('Agender') != 'any') {
            array_push($Arules, new \PNM\models\Rule('gender', 'exact', \PNM\Request::get('Agender')));
        }
        if (!empty(\PNM\Request::get('Atitle'))) {
            array_push($Arules, new \PNM\models\Rule('title_string_search', 'exactlike', Translit::searchVal(\PNM\Request::get('Atitle'))));
        }
        if (!empty(\PNM\Request::get('Aname'))) {
            array_push($Arules, new \PNM\models\Rule('personal_name_search', 'exactlike', Translit::searchVal(\PNM\Request::get('Aname'))));
        }
        if (\PNM\Request::get('only_persons') == 'true') {
            $persons = 'persons_only';
        } else {
            $persons = null;
        }
        if (!empty(\PNM\Request::get('Aform_type'))) {
            $nt = \PNM\models\Lookup::name_types_idGet(\PNM\Request::get('Aform_type'));
            if (!empty($nt)) {
                array_push($Arules, new \PNM\models\RuleExists('(((names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id) '
                        . 'INNER JOIN personal_names ON names_types_xref.personal_names_id = personal_names.personal_names_id) '
                        . 'INNER JOIN spellings ON personal_names.personal_names_id = spellings.personal_names_id) '
                        . 'INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id'
                        . ' WHERE spellings_attestations_xref.attestations_id=attestations.attestations_id AND '
                        . ' name_types_temp.parent_id = ?', $nt, 'i'));
            } else {
                array_push($Arules, new \PNM\models\Rule(1, 'exactlike', 0, 'i'));
            }
        }
        if (!empty(\PNM\Request::get('Asem_type'))) {
            $nt = \PNM\models\Lookup::name_types_idGet(\PNM\Request::get('Asem_type'));
            if (!empty($nt)) {
                array_push($Arules, new \PNM\models\RuleExists('(((names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id) '
                        . 'INNER JOIN personal_names ON names_types_xref.personal_names_id = personal_names.personal_names_id) '
                        . 'INNER JOIN spellings ON personal_names.personal_names_id = spellings.personal_names_id) '
                        . 'INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id'
                        . ' WHERE spellings_attestations_xref.attestations_id=attestations.attestations_id AND '
                        . ' name_types_temp.parent_id = ?', $nt, 'i'));
            } else {
                array_push($Arules, new \PNM\models\Rule(1, 'exactlike', 0, 'i'));
            }
        }
        if (!empty(\PNM\Request::get('period'))) {
            $periodEnd = \PNM\models\Lookup::dateEnd(\PNM\Request::get('period'));
            $periodStart = \PNM\models\Lookup::dateStart(\PNM\Request::get('period'));
            if (empty($periodStart) || empty($periodEnd)) {
                array_push($Arules, new \PNM\models\Rule('0', 'exact', 1, 'i'));
            } else {
                switch (\PNM\Request::get('chrono-filter')) {
                    case 'during':
                          array_push($Arules, new \PNM\models\RuleExists('inscriptions '
                                . ' WHERE attestations.inscriptions_id=inscriptions.inscriptions_id AND inscriptions.dating_sort_end >= ?'
                                . ' AND inscriptions.dating_sort_start <= ?', [$periodStart, $periodEnd], 'ii'));
                        break;
                    case 'not-later':
                        array_push($Arules, new \PNM\models\RuleExists('inscriptions '
                                . ' WHERE attestations.inscriptions_id=inscriptions.inscriptions_id AND inscriptions.dating_sort_start <= ?', $periodEnd, 'i'));
                        break;
                    case 'not-earlier':
                        array_push($Arules, new \PNM\models\RuleExists('inscriptions '
                                . ' WHERE attestations.inscriptions_id=inscriptions.inscriptions_id AND inscriptions.dating_sort_end >= ?', $periodStart, 'i'));
                        break;
                }
             
            }
        }
        $filter = new \PNM\models\Filter($Arules);
        $Bempty = empty(\PNM\Request::get('Bname')) && empty(\PNM\Request::get('Btitle')) && empty(\PNM\Request::get('Bform_type')) && empty(\PNM\Request::get('Bsem_type')) && (empty(\PNM\Request::get('Bgender')) || \PNM\Request::get('Bgender') == 'any');
        $pat = '/[^? *%\[\]]+/';
        $EmptyPair = (\PNM\Request::get('relation') == 'same_inscription' || \PNM\Request::get('relation') == 'siblings') && !( preg_match($pat, \PNM\Request::get('Aname')) || preg_match($pat, \PNM\Request::get('Atitle')) || preg_match($pat, \PNM\Request::get('Bname')) || preg_match($pat, \PNM\Request::get('Btitle')) || !empty(\PNM\Request::get('Aform_type')) || !empty(\PNM\Request::get('Asem_type')) || !empty(\PNM\Request::get('Bform_type')) || !empty(\PNM\Request::get('Bsem_type')));
        if ($Bempty || $EmptyPair) {
            // second part of the request is not used
            $model = new \PNM\models\people(\PNM\Request::get('sort'), (\PNM\Request::get('start') ?: 0), 50, $filter, null, $persons);
        } else {
            // second part of the request is used
            $Brules = [];
            if (!empty(\PNM\Request::get('Bgender')) && \PNM\Request::get('Bgender') != 'any') {
                array_push($Brules, new \PNM\models\Rule('gender', 'exact', \PNM\Request::get('Bgender')));
            }
            if (!empty(\PNM\Request::get('Btitle'))) {
                array_push($Brules, new \PNM\models\Rule('title_string_search', 'exactlike', Translit::searchVal(\PNM\Request::get('Btitle'))));
            }
            if (!empty(\PNM\Request::get('Bname'))) {
                array_push($Brules, new \PNM\models\Rule('personal_name_search', 'exactlike', Translit::searchVal(\PNM\Request::get('Bname'))));
            }
            if (!empty(\PNM\Request::get('Bform_type'))) {
                $nt = \PNM\models\Lookup::name_types_idGet(\PNM\Request::get('Bform_type'));
                if (!empty($nt)) {
                 
                    array_push($Brules, new \PNM\models\RuleExists('(((names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id) '
                            . 'INNER JOIN personal_names ON names_types_xref.personal_names_id = personal_names.personal_names_id) '
                            . 'INNER JOIN spellings ON personal_names.personal_names_id = spellings.personal_names_id) '
                            . 'INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id'
                            . ' WHERE spellings_attestations_xref.attestations_id=attestations.attestations_id AND '
                            . ' name_types_temp.parent_id = ?', $nt, 'i'));
                } else {
                    array_push($Brules, new \PNM\models\Rule(1, 'exactlike', 0, 'i'));
                }
            }
            if (!empty(\PNM\Request::get('Bsem_type'))) {
                $nt = \PNM\models\Lookup::name_types_idGet(\PNM\Request::get('Bsem_type'));
                if (!empty($nt)) {
                               array_push($Brules, new \PNM\models\RuleExists('(((names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id) '
                            . 'INNER JOIN personal_names ON names_types_xref.personal_names_id = personal_names.personal_names_id) '
                            . 'INNER JOIN spellings ON personal_names.personal_names_id = spellings.personal_names_id) '
                            . 'INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id'
                            . ' WHERE spellings_attestations_xref.attestations_id=attestations.attestations_id AND '
                            . ' name_types_temp.parent_id = ?', $nt, 'i'));
                } else {
                    array_push($Brules, new \PNM\models\Rule(1, 'exactlike', 0, 'i'));
                }
            }
            $Bfilter = new \PNM\models\Filter($Brules);
            switch (\PNM\Request::get('relation')) {
                case 'child':
                    $model = new \PNM\models\peopleChild(\PNM\Request::get('sort'), (\PNM\Request::get('start') ?: 0), 50, $filter, $Bfilter, $persons);
                    break;
                case 'parent':
                    $model = new \PNM\models\peopleParent(\PNM\Request::get('sort'), (\PNM\Request::get('start') ?: 0), 50, $filter, $Bfilter, $persons);
                    break;
                case 'spouses':
                    $model = new \PNM\models\peopleSpouse(\PNM\Request::get('sort'), (\PNM\Request::get('start') ?: 0), 50, $filter, $Bfilter, $persons);
                    break;
                case 'siblings':
                    $model = new \PNM\models\peopleSibling(\PNM\Request::get('sort'), (\PNM\Request::get('start') ?: 0), 50, $filter, $Bfilter, $persons);
                    break;
                case 'same_inscription':
                default:
                    $model = new \PNM\models\peopleSameInscr(\PNM\Request::get('sort'), (\PNM\Request::get('start') ?: 0), 50, $filter, $Bfilter, $persons);
                    break;
            }
        }
        $view = new \PNM\views\peopleView();
        $view->echoRender($model);
    }
}
