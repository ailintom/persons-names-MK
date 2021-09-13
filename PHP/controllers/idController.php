<?php

namespace PNM\controllers;

use \PNM\ID, 
    \PNM\Request,
    \PNM\models\Lookup,
    \PNM\Config;

class idController extends EntryController
{

    const NAME = 'id';

    public function load()
    {
        $strid = (int)Request::get('id');
        if ($strid >0){
        $id = new ID($strid);
        
        $controller = $id->getDefaultController();
        $short_id = $id->getShortID();
        if ($controller == 'attestation'){
            $controller = self::getParentController($strid,'SELECT inscriptions_id from attestations where attestations_id =  ?',$short_id);
        }elseif  ($controller == 'spelling'){
            $controller = self::getParentController($strid,'SELECT personal_names_id from spellings where spellings_id =  ?',$short_id);
        }elseif  ($controller == 'object'){
            $controller = self::getParentController($strid,'SELECT inscriptions_id from objects_inscriptions_xref where objects_id = ? ',$short_id);            
        }elseif ($controller == 'persons_attestations_xref'){
            $att_strid = Lookup::get('SELECT attestations_id from persons_attestations_xref where persons_attestations_xref_id =  ?', (int)$strid, 'i');
            if (strlen($att_strid)>0){
                $att_id = new ID((int)$att_strid);
                $short_id = $att_id->getShortID();
            $controller = self::getParentController($strid,'SELECT persons_id from persons_attestations_xref where persons_attestations_xref_id =  ?',$short_id);
            }
        }elseif ($controller == 'biblio_ref'){
            $controller = self::getParentController($strid,'SELECT object_id from biblio_refs where biblio_refs_id =  ?',$short_id);
        }elseif ($controller == 'inv_no'){
            $controller = self::getParentController($strid,'SELECT inscriptions_id from inv_nos where inv_nos_id =  ?',$short_id);
        }
        if (strlen($controller)>0){
        header('Location: ' . Config::HOST . '/'.$controller . "/" . $short_id, true, 302);
exit;
        }}
        $data = null;
        $view = new \PNM\views\idView();
        $view->echoRender($data);
    }
    function getParentController($strid,  $sql, &$short_id)
    {
            $parent_strid = Lookup::get($sql, (int)$strid, 'i');
            if (strlen($parent_strid)>0){
                $parent_id = new ID((int)$parent_strid);
                $short_id = $parent_id->getShortID() . "#" . $short_id;
                return $parent_id->getDefaultController();
            }else{
                return '';
            }
    }
}
