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

error_reporting(E_ALL);
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

// define('Config::BASE', '/test3/');
//mb_internal_encoding('UTF-8');
//mb_http_output('UTF-8');
require_once('Config.php');
/*
 * Config.php is not included in the source code for security reasons 
 * It should look as follows:
  Class Config {

  const DB_CONFIG = ['host' => 'host',
  'username' => 'user',
  'password' => 'password',
  'db' => 'db'
  ];
  const VERSIONS = [[1, "15.04.2018"], [2, "16.04.2018"]];
  const BASE = '/subpath/';

const HOST = 'https://pnm.uni-mainz.de';
const IMPRESSUM = "";
  const PRIVACY = "";

  static function maxVer() {
  return self::VERSIONS[count(self::VERSIONS) - 1][0];
  }

  }
 * 
 * 
 * 
 */

require_once('CriticalError.php');
require_once('Db.php');
require_once('Request.php');
require_once ('views/Head.php');
require_once('functions.php');
require_once('controllers/EntryController.php');
require_once('controllers/Translit.php');

require_once('models/Filter.php');
require_once('models/Rule.php');
require_once('models/RuleExists.php');
require_once('models/FieldList.php');
require_once('ID.php');

require_once('models/Lookup.php');
require_once('models/EntryModel.php');

require_once('models/ListModel.php');
require_once('models/ListModelTitleSort.php');

require_once('models/ObjectBibliography.php');

require_once('models/ObjectInv_nos.php');
require_once('models/ObjectAttestations.php');
require_once('models/ObjectBonds.php');
require_once('models/title_relations.php');
require_once('models/PersonBonds.php');
require_once('models/PersonAttestations.php');
require_once('models/inv_nos.php');
require_once('models/inscriptions.php');
require_once('models/WorkshopInscriptions.php');
require_once('models/InscriptionWorkshops.php');
require_once('models/ObjectSpellings.php');
require_once('models/ObjectTitles.php');
require_once('models/ObjectAltReadings.php');
require_once('models/titleAttestations.php');
require_once('models/NameSpellings.php');
require_once('models/SpellingAttestations.php');

require_once('models/NamePersons.php');
require_once('models/AttestationPersons.php');
require_once('models/NameTypes.php');
require_once('models/types.php');
require_once('models/names.php');
require_once('models/TypeNames.php');

require_once('models/peoplePairs.php');
require_once('models/peopleChild.php');
require_once('models/peopleParent.php');
require_once('models/peopleSameInscr.php');
require_once('models/peopleSibling.php');
require_once('models/peopleSpouse.php');
require_once('models/biblio_refs.php');
require_once('models/find_groups.php');

require_once('models/workshops.php');
require_once('models/infos.php');


require_once('models/placeMicroModel.php');


require_once('Note.php');
require_once('views/MicroView.php');
require_once('views/attestationsMicroView.php');

require_once('views/inscriptionsMicroView.php');
require_once('views/collectionsMicroView.php');
require_once('views/personal_namesMicroView.php');
require_once('views/spellingsMicroView.php');
require_once('views/titlesMicroView.php');
require_once('views/criteriaMicroView.php');
require_once('views/publicationsMicroView.php');
require_once('views/inv_nosMicroView.php');
require_once('views/placesMicroView.php');
require_once('views/name_typesMicroView.php');
require_once('views/personsMicroView.php');
require_once('views/workshopsMicroView.php');
require_once('views/find_groupsMicroView.php');


require_once('views/Table.php');
require_once('views/RadioGroup.php');
require_once('views/TextInput.php');
require_once('views/Select.php');
require_once('views/Datalist.php');
require_once('views/FormFilter.php');
require_once('views/View.php');
require_once('views/startView.php');

$ClassName = "PNM\\" . Request::get('controller') . "Controller";
$controllerClassPath = 'controllers/' . Request::get('controller') . 'Controller.php';
require_once($controllerClassPath);
$modelClassPath = 'models/' . Request::get('controller') . '.php';
require_once($modelClassPath);
$viewClassPath = 'views/' . Request::get('controller') . 'View.php';
require_once($viewClassPath);

$controllerobj = new $ClassName();



// call the action
$controllerobj->load();
require 'views/footer.php';
