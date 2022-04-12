<?php

/*
 * Description of peopleController
 * This controller is used to search for people
 *
 */

namespace PNM\controllers;

use \PNM\Request,
    \PNM\models\Rule,
    \PNM\models\RuleExists,
    \PNM\models\Filter,
    \PNM\Config;

class peopleController
{

    public function load()
    {
        $Arules = [];
        if (!empty(Request::get('Agender')) && Request::get('Agender') != 'any') {
            array_push($Arules, new Rule('gender', 'exact', Request::get('Agender')));
        }
        if (!empty(Request::get('Atitle'))) {
            array_push($Arules, new Rule('title_string_search', 'exactlike', Translit::searchVal(Request::get('Atitle'))));
        }
        if (!empty(Request::get('Aname'))) {
            array_push($Arules, new Rule('personal_name_search', 'exactlike', Translit::searchVal(Request::get('Aname'))));
        }
        if (Request::get('only_persons') == 'true') {
            $persons = 'persons_only';
        } else {
            $persons = null;
        }
        if (!empty(Request::get('Aform_type'))) {
            $nt = \PNM\models\Lookup::name_types_idGet(Request::get('Aform_type'));
            if (!empty($nt)) {
                array_push($Arules, new RuleExists('(((names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id) '
                        . 'INNER JOIN personal_names ON names_types_xref.personal_names_id = personal_names.personal_names_id) '
                        . 'INNER JOIN spellings ON personal_names.personal_names_id = spellings.personal_names_id) '
                        . 'INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id'
                        . ' WHERE spellings_attestations_xref.attestations_id=attestations.attestations_id AND '
                        . ' name_types_temp.parent_id = ?', $nt, 'i'));
            } else {
                array_push($Arules, new Rule(1, 'exactlike', 0, 'i'));
            }
        }
        if (!empty(Request::get('Asem_type'))) {
            $nt = \PNM\models\Lookup::name_types_idGet(Request::get('Asem_type'));
            if (!empty($nt)) {
                array_push($Arules, new RuleExists('(((names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id) '
                        . 'INNER JOIN personal_names ON names_types_xref.personal_names_id = personal_names.personal_names_id) '
                        . 'INNER JOIN spellings ON personal_names.personal_names_id = spellings.personal_names_id) '
                        . 'INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id'
                        . ' WHERE spellings_attestations_xref.attestations_id=attestations.attestations_id AND '
                        . ' name_types_temp.parent_id = ?', $nt, 'i'));
            } else {
                array_push($Arules, new Rule(1, 'exactlike', 0, 'i'));
            }
        }
        if (!empty(Request::get('period'))) {
            $periodEnd = \PNM\models\Lookup::dateEnd(Request::get('period'));
            $periodStart = \PNM\models\Lookup::dateStart(Request::get('period'));
            $periodType = \PNM\models\Lookup::get('SELECT thesaurus FROM thesauri WHERE item_name = ?', Request::get('period'));
            if (empty($periodStart) || empty($periodEnd)) {
                array_push($Arules, new Rule('0', 'exact', 1, 'i'));
            } else {
                switch (Request::get('chrono-filter')) {
                    case 'during':
                        array_push($Arules, new RuleExists('inscriptions '
                                . ' WHERE attestations.inscriptions_id=inscriptions.inscriptions_id AND inscriptions.dating_sort_end >= ?'
                                . ' AND inscriptions.dating_sort_start <= ?', [$periodStart, $periodEnd], 'ii'));
                        break;
                    case 'strictly':
                        if ($periodType == 6) {
                            array_push($Arules, new RuleExists('inscriptions '
                                    . ' WHERE attestations.inscriptions_id=inscriptions.inscriptions_id AND inscriptions.dating = ?', Request::get('period'), 's'));
                        } else {
                            array_push($Arules, new RuleExists('inscriptions '
                                    . ' WHERE attestations.inscriptions_id=inscriptions.inscriptions_id AND inscriptions.dating_sort_end <= ?'
                                    . ' AND inscriptions.dating_sort_start >= ?', [$periodEnd, $periodStart], 'ii'));
                        }
                        break;
                    case 'not-later':
                        array_push($Arules, new RuleExists('inscriptions '
                                . ' WHERE attestations.inscriptions_id=inscriptions.inscriptions_id AND inscriptions.dating_sort_start <= ?', $periodEnd, 'i'));
                        break;
                    case 'not-earlier':
                        array_push($Arules, new RuleExists('inscriptions '
                                . ' WHERE attestations.inscriptions_id=inscriptions.inscriptions_id AND inscriptions.dating_sort_end >= ?', $periodStart, 'i'));
                        break;
                }
            }
        }

        if (!empty(Request::get('place'))) {
            switch (Request::get('geo-filter')) {
                case 'production':
                    array_push($Arules, new RuleExists('inscriptions '
                            . ' WHERE attestations.inscriptions_id=inscriptions.inscriptions_id AND (SELECT objects.production_place FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1) = ?', Request::get('place'), 's'));
                    break;
                case 'provenance':
                    array_push($Arules, new RuleExists('inscriptions '
                            . ' WHERE attestations.inscriptions_id=inscriptions.inscriptions_id AND (SELECT objects.provenance FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1) = ?', Request::get('place'), 's'));
                    break;
                case 'installation-place':
                    array_push($Arules, new RuleExists('inscriptions '
                            . ' WHERE attestations.inscriptions_id=inscriptions.inscriptions_id AND (SELECT objects.installation_place FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1) = ?', Request::get('place'), 's'));
                    break;
                case 'origin':
                    array_push($Arules, new RuleExists('inscriptions '
                            . ' WHERE attestations.inscriptions_id=inscriptions.inscriptions_id AND inscriptions.origin = ?', Request::get('place'), 's'));
                    break;
                default:
                    array_push($Arules, new RuleExists('inscriptions '
                            . ' WHERE attestations.inscriptions_id=inscriptions.inscriptions_id AND'
                            . ' ((SELECT objects.provenance FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1) = ? OR (SELECT objects.production_place FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1) = ? OR inscriptions.origin = ? OR (SELECT objects.installation_place FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE objects_inscriptions_xref.inscriptions_id=inscriptions.inscriptions_id LIMIT 1) = ?)', [Request::get('place'), Request::get('place'), Request::get('place'), Request::get('place')], 'ssss'));
            }
        }
        $filter = new Filter($Arules);
        $Bempty = empty(Request::get('Bname')) && empty(Request::get('Btitle')) && empty(Request::get('Bform_type')) && empty(Request::get('Bsem_type')) && (empty(Request::get('Bgender')) || Request::get('Bgender') == 'any');
        $pat = '/[^? *%\[\]]+/';
        $EmptyPair = (Request::get('relation') == 'same_inscription' || Request::get('relation') == 'siblings') && !( preg_match($pat, Request::get('Aname')) || preg_match($pat, Request::get('Atitle')) || preg_match($pat, Request::get('Bname')) || preg_match($pat, Request::get('Btitle')) || !empty(Request::get('Aform_type')) || !empty(Request::get('Asem_type')) || !empty(Request::get('Bform_type')) || !empty(Request::get('Bsem_type')));
        if ($Bempty || $EmptyPair) {
            // second part of the request is not used
            $model = new \PNM\models\people(Request::get('sort'), (Request::get('start') ?: 0), Config::ROWS_ON_PAGE, $filter, null, $persons);
        } else {
            // second part of the request is used
            $Brules = [];
            if (!empty(Request::get('Bgender')) && Request::get('Bgender') != 'any') {
                array_push($Brules, new Rule('gender', 'exact', Request::get('Bgender')));
            }
            if (!empty(Request::get('Btitle'))) {
                array_push($Brules, new Rule('title_string_search', 'exactlike', Translit::searchVal(Request::get('Btitle'))));
            }
            if (!empty(Request::get('Bname'))) {
                array_push($Brules, new Rule('personal_name_search', 'exactlike', Translit::searchVal(Request::get('Bname'))));
            }
            if (!empty(Request::get('Bform_type'))) {
                $nt = \PNM\models\Lookup::name_types_idGet(Request::get('Bform_type'));
                if (!empty($nt)) {

                    array_push($Brules, new RuleExists('(((names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id) '
                            . 'INNER JOIN personal_names ON names_types_xref.personal_names_id = personal_names.personal_names_id) '
                            . 'INNER JOIN spellings ON personal_names.personal_names_id = spellings.personal_names_id) '
                            . 'INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id'
                            . ' WHERE spellings_attestations_xref.attestations_id=attestations.attestations_id AND '
                            . ' name_types_temp.parent_id = ?', $nt, 'i'));
                } else {
                    array_push($Brules, new Rule(1, 'exactlike', 0, 'i'));
                }
            }
            if (!empty(Request::get('Bsem_type'))) {
                $nt = \PNM\models\Lookup::name_types_idGet(Request::get('Bsem_type'));
                if (!empty($nt)) {
                    array_push($Brules, new RuleExists('(((names_types_xref INNER JOIN name_types_temp ON names_types_xref.name_types_id = name_types_temp.child_id) '
                            . 'INNER JOIN personal_names ON names_types_xref.personal_names_id = personal_names.personal_names_id) '
                            . 'INNER JOIN spellings ON personal_names.personal_names_id = spellings.personal_names_id) '
                            . 'INNER JOIN spellings_attestations_xref ON spellings.spellings_id = spellings_attestations_xref.spellings_id'
                            . ' WHERE spellings_attestations_xref.attestations_id=attestations.attestations_id AND '
                            . ' name_types_temp.parent_id = ?', $nt, 'i'));
                } else {
                    array_push($Brules, new Rule(1, 'exactlike', 0, 'i'));
                }
            }
            $Bfilter = new Filter($Brules);
            switch (Request::get('relation')) {
                case 'child':
                    $model = new \PNM\models\peopleChild(Request::get('sort'), (Request::get('start') ?: 0), Config::ROWS_ON_PAGE, $filter, $Bfilter, $persons);
                    break;
                case 'parent':
                    $model = new \PNM\models\peopleParent(Request::get('sort'), (Request::get('start') ?: 0), Config::ROWS_ON_PAGE, $filter, $Bfilter, $persons);
                    break;
                case 'spouses':
                    $model = new \PNM\models\peopleSpouse(Request::get('sort'), (Request::get('start') ?: 0), Config::ROWS_ON_PAGE, $filter, $Bfilter, $persons);
                    break;
                case 'siblings':
                    $model = new \PNM\models\peopleSibling(Request::get('sort'), (Request::get('start') ?: 0), Config::ROWS_ON_PAGE, $filter, $Bfilter, $persons);
                    break;
                case 'same_inscription':
                default:
                    $model = new \PNM\models\peopleSameInscr(Request::get('sort'), (Request::get('start') ?: 0), Config::ROWS_ON_PAGE, $filter, $Bfilter, $persons);
                    break;
            }
        }
        $view = new \PNM\views\peopleView();
        $view->echoRender($model);
    }
}
