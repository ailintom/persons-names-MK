<?php

/*
 * This class handles the operations with ID numbers
 */

namespace PNM;

class ID
{

    private $ID = null;

    const TABLE_IDS = ['thesauri' => 0, 'criteria' => 14, 'publications' => 2, 'biblio_refs' => 7, 'inscriptions' => 4,
        'find_groups' => 23, 'workshops' => 20, 'inscriptions_workshops_xref' => 21, 'places' => 22, 'inv_nos' => 25,
        'collections' => 26, 'attestations' => 8, 'spellings_attestations_xref' => 15, 'persons_attestations_xref' => 1,
        'persons' => 27, 'titles_att' => 28, 'titles' => 5, 'spellings' => 29, 'alternative_readings' => 9,
        'personal_names' => 17, 'name_types' => 30, 'names_types_xref' => 31, 'bonds' => 24, 'persons_bonds' => 11, 'title_relations' => 3, 'objects'=>10, 'objects_inscriptions_xref'=>12];
    const TABLE_NAMES = [0 => 'thesauri', 14 => 'criteria', 2 => 'publications', 7 => 'biblio_refs', 4 => 'inscriptions',
        23 => 'find_groups', 20 => 'workshops', 21 => 'inscriptions_workshops_xref', 22 => 'places', 25 => 'inv_nos',
        26 => 'collections', 8 => 'attestations', 15 => 'spellings_attestations_xref', 1 => 'persons_attestations_xref',
        27 => 'persons', 28 => 'titles_att', 5 => 'titles', 29 => 'spellings', 9 => 'alternative_readings',
        17 => 'personal_names', 30 => 'name_types', 31 => 'names_types_xref', 24 => 'bonds', 11 => 'persons_bonds', 3 => 'title_relations', 12 => 'objects_inscriptions_xref', 10=>'objects'];
    const DEFAULT_CONTROLLERS = [1 => 'persons_attestations_xref', 7=>'biblio_ref', 14 => 'criterion', 2 => 'publication', 4 => 'inscription',
        23 => 'group', 20 => 'workshop', 22 => 'place',
        26 => 'collection', 8 => 'attestation',
        27 => 'person', 5 => 'title', 25 => 'inv_no', 29 => 'spelling',
        17 => 'name', 30 => 'type', 10 => 'object'];

    public function __construct($IDInput, $TableInput = null)
    {
        if (!is_int($IDInput)) {
            throw new \Exception('Non-numeric ID: ' . $IDInput . '.');
        } elseif (0 > $IDInput) {
            throw new \Exception('ID is negative: ' . $IDInput . '.');
        } else {
            if ($TableInput === null) {
                $this->ID = $IDInput;
            } elseif (is_int($TableInput)) {
                if (array_key_exists($TableInput, self::TABLE_NAMES)) {
                    $this->ID = ($TableInput << 23) | $IDInput;
                }
            } elseif (array_key_exists($TableInput, self::TABLE_IDS)) {
                $this->ID = (self::TABLE_IDS[$TableInput] << 23) | $IDInput;
            }
        }
    }

    public function getID()
    {
        if ($this->ID > 0) {
            return $this->ID;
        }
    }

    public function getTableID()
    {
        if ($this->ID > 0) {
            return ($this->ID & 0x1F800000) >> 23;
        }
    }

    public function getShortID()
    {
        if ($this->ID > 0) {
            return ($this->ID & 0x7FFFFF);
        }
    }

    public function getDefaultController()
    {
        if ($this->ID > 0) {
            $TableID = $this->getTableID();
            if (array_key_exists($TableID, self::DEFAULT_CONTROLLERS)) {
                return self::DEFAULT_CONTROLLERS[$TableID];
            }
        }
    }

    public function getTableName()
    {
        if ($this->ID > 0) {
            $TableID = $this->getTableID();
            if (array_key_exists($TableID, self::TABLE_NAMES)) {
                return self::TABLE_NAMES[$TableID];
            }
        }
    }

    public static function shorten($id)
    {
        $intid = (int) $id;
        if ($intid>0){
            $idObj = new ID($intid);
            return $idObj->getShortID();
        }else{
            return 0;
        }
    }
}
