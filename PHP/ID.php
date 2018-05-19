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

Class ID {

    private $ID = null;

    const TABLE_IDS = ['thesauri' => 0, 'criteria' => 14, 'publications' => 2, 'biblio_refs' => 7, 'inscriptions' => 4,
        'find_groups' => 23, 'workshops' => 20, 'inscriptions_workshops_xref' => 21, 'places' => 22, 'inv_nos' => 25,
        'collections' => 26, 'attestations' => 8, 'spellings_attestations_xref' => 15, 'persons_attestations_xref' => 1,
        'persons' => 27, 'titles_att' => 28, 'titles' => 5, 'spellings' => 29, 'alternative_readings' => 9,
        'personal_names' => 17, 'name_types' => 30, 'names_types_xref' => 31, 'bonds' => 24, 'persons_bonds' => 11, 'title_relations' => 3];
    const TABLE_NAMES = [0 => 'thesauri', 14 => 'criteria', 2 => 'publications', 7 => 'biblio_refs', 4 => 'inscriptions',
        23 => 'find_groups', 20 => 'workshops', 21 => 'inscriptions_workshops_xref', 22 => 'places', 25 => 'inv_nos',
        26 => 'collections', 8 => 'attestations', 15 => 'spellings_attestations_xref', 1 => 'persons_attestations_xref',
        27 => 'persons', 28 => 'titles_att', 5 => 'titles', 29 => 'spellings', 9 => 'alternative_readings',
        17 => 'personal_names', 30 => 'name_types', 31 => 'names_types_xref', 24 => 'bonds', 11 => 'persons_bonds', 3 => 'title_relations'];
    const DEFAULT_CONTROLLERS = [14 => 'criterion', 2 => 'publication', 4 => 'inscription',
        23 => 'groups', 20 => 'workshop', 22 => 'place',
        26 => 'collection', 8 => 'attestation',
        27 => 'person', 5 => 'title', 29 => 'spelling',
        17 => 'name', 30 => 'types'];

    public function __construct($IDInput, $TableInput = null) {
        if (!is_int($IDInput)) {
            throw new \Exception('Non-numeric ID: ' . $IDInput . '.');
        } elseif (0 >= is_int($IDInput)) {
            throw new \Exception('ID equals zero or negative: ' . $IDInput . '.');
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

    public function getID() {
        if ($this->ID > 0) {
            return $this->ID;
        }
    }

    public function getTableID() {
        if ($this->ID > 0) {
            return ($this->ID & 0x1F800000) >> 23;
        }
    }

    public function getShortID() {
        if ($this->ID > 0) {
            return ($this->ID & 0x7FFFFF);
        }
    }

    public function getDefaultController() {
        if ($this->ID > 0) {
            $TableID = $this->getTableID();
            if (array_key_exists($TableID, self::DEFAULT_CONTROLLERS)) {
                return self::DEFAULT_CONTROLLERS[$TableID];
            }
        }
    }

    public function getTableName() {
        if ($this->ID > 0) {
            $TableID = $this->getTableID();
            if (array_key_exists($TableID, self::TABLE_NAMES)) {
                return self::TABLE_NAMES[$TableID];
            }
        }
    }

}
