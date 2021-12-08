-- This SQL code updates the datasets ver. 1 and ver. 2 to the new structure,
-- which is used starting from ver. 3. The SQL code changes the database structure;
-- the data itself remains intact. 


CREATE TABLE IF NOT EXISTS `objects` (
  `objects_id` int(11) NOT NULL COMMENT 'Unique record ID, primary key	',
  `date_created` date DEFAULT NULL COMMENT 'Date when the record was created in the published version of the database',
  `date_changed` date DEFAULT NULL COMMENT 'Date when the last change to the record was published	',
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The title under which the object is referred to in the database (short museum name and main inventory number for objects in the museums or the reference to the most relevant (usually first) publication for other objects)',
  `title_sort` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '`Title` converted for natural sort',
  `topbib_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The reference to the Topographical Bibliography or a list of such references divided by semicolons',
  `object_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The `item_name` of the inscription type in the object_type thesaurus (thesaurus 1); *example: stela*',
  `object_subtype` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The `item_name` of the inscription subtype in the object_subtype thesaurus (thesaurus 2); *example: block-statue*',
  `material` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The `item_name` of the material type in the material  thesaurus (thesaurus 3), *based on a subset of the [THOT Material thesaurus](http://thot.philo.ulg.ac.be/concept/thot-6200)*',
  `length` int(11) DEFAULT NULL COMMENT 'Preserved length of the object (for scarabs) in mm.',
  `height` int(11) DEFAULT NULL COMMENT 'Preserved height of the object in mm.',
  `width` int(11) DEFAULT NULL COMMENT 'Preserved width of the object in mm.',
  `thickness` int(11) DEFAULT NULL COMMENT 'Preserved thickness of the object in mm.',
  `find_groups_id` int(11) DEFAULT NULL COMMENT 'The ID of the archaeological find_group to which the inscribed object belongs in the table `find_groups`',
  `provenance` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The `place_name` of the record in the table `places` corresponding to the place where the object was found or purchased',
  `provenance_sort` int(11) DEFAULT NULL COMMENT 'The `latitude` of the record in the table `places` corresponding to the `provenance`',
  `provenance_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Note related to the `provenance`',
  `installation_place` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The `place_name` of the record in the table `places` corresponding to the place where the object should have been installed (when different from the provenance or when the provenance is unknown or unreliable, as in case of purchases)',
  `installation_place_sort` int(11) DEFAULT NULL COMMENT 'The `latitude` of the record in the table `places` corresponding to the `installation_place`',
  `installation_place_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Note related to the `installation_place`',
  `production_place` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The `place_name` of the record in the table `places` corresponding to the place where the object should have been produced',
  `production_place_sort` int(11) DEFAULT NULL COMMENT 'The `latitude` of the record in the table `places` corresponding to the `production_place`',
  `production_place_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The reasoning behind the `production_place` with relevant bibliographical references whenever possible',
  PRIMARY KEY (`objects_id`) USING BTREE,
  KEY `title_sort` (`title_sort`),
  KEY `object_type` (`object_type`),
  KEY `object_subtype` (`object_subtype`),
  KEY `title` (`title`),
  KEY `material` (`material`),
  KEY `provenance` (`provenance`),
  KEY `provenance_sort` (`provenance_sort`),
  KEY `installation_place` (`installation_place`),
  KEY `installation_place_sort` (`installation_place_sort`),
  KEY `production_place` (`production_place`),
  KEY `production_place_sort` (`production_place_sort`),
  KEY `objects_find_groups` (`find_groups_id`),
  CONSTRAINT `objects_find_groups` FOREIGN KEY (`find_groups_id`) REFERENCES `find_groups` (`find_groups_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='__table_id: 10__  \nEach record in this table represents a physical object with an Egyptian inscription. This can be an object now located in a museum or a private collection or known from a publication, archival document, or sale catalogue (such as a stela, statue, offering table, coffin, seal, papyrus, etc.), a rock inscription, an inscribed tomb, or another structure. Objects originally belonging to the same structure that has a different type than the objects themselves (e. g., stelae originally installed in the same offering chapel) are considered different objects, but objects that are parts of an originally integral object of the same type, now decomposed, (e. g., two parts of the same statue, now stored in different museums) are considered the same object.';

CREATE TABLE IF NOT EXISTS `objects_inscriptions_xref` (
  `objects_inscriptions_xref_id` int(11) NOT NULL COMMENT 'Unique record ID, primary key',
  `date_created` date DEFAULT NULL COMMENT 'Date when the record was created in the published version of the database',
  `date_changed` date DEFAULT NULL COMMENT 'Date when the last change to the record was published	',
  `objects_id` int(11) NOT NULL COMMENT 'ID of the inscribed object, which carries an inscription',
  `inscriptions_id` int(11) NOT NULL COMMENT 'ID of the inscription, which is carried by an object',
  PRIMARY KEY (`objects_inscriptions_xref_id`),
  KEY `objects_id` (`objects_id`),
  KEY `inscriptions_id` (`inscriptions_id`),
  CONSTRAINT `FK_objects_inscriptions_xref_inscriptions` FOREIGN KEY (`inscriptions_id`) REFERENCES `inscriptions` (`inscriptions_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `objects` FOREIGN KEY (`objects_id`) REFERENCES `objects` (`objects_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='__table_id: 12__  An associative table for linking inscribed objects with inscriptions they carry. Several inscribed objects may carry one an the same inscription as in the case of multiple impressions of the same seal or multiple funerary cones stamped with the same inscription. On the other side one and the same object can carry several inscriptions created in different periods.\r\n\r\n';

INSERT INTO `objects` ( `objects_id`, `date_created`, `date_changed`, `title`, `title_sort`, `topbib_id`, `object_type`,`object_subtype`,`material`,`length`,`height`,`width`,`thickness`,`find_groups_id`,`provenance`,`provenance_sort`,`provenance_note`,`installation_place`,`installation_place_sort`,`installation_place_note`,`production_place`,`production_place_sort`,`production_place_note`)
SELECT make_id(10,record_id_from_id(`inscriptions_id`)), `date_created`, `date_changed`, `title`, `title_sort`, `topbib_id`, `object_type`,`object_subtype`,`material`,`length`,`height`,`width`,`thickness`,`find_groups_id`,`provenance`,`provenance_sort`,`provenance_note`,`installation_place`,`installation_place_sort`,`installation_place_note`,`production_place`,`production_place_sort`,`production_place_note`
from inscriptions;

INSERT INTO `objects_inscriptions_xref` ( `objects_inscriptions_xref_id`, `objects_id`, `inscriptions_id`)
SELECT make_id(12,record_id_from_id(`inscriptions_id`)), make_id(10,record_id_from_id(`inscriptions_id`)), inscriptions_id
from inscriptions;

ALTER TABLE `inscriptions` DROP COLUMN `topbib_id`,
DROP COLUMN `object_type`,
DROP COLUMN `object_subtype`,
DROP COLUMN `material`,
DROP COLUMN `length`,
DROP COLUMN `height`,
DROP COLUMN `width`,
DROP COLUMN `thickness`,
DROP FOREIGN KEY `key-inscriptions-find_groups`,
DROP COLUMN `find_groups_id`,
DROP COLUMN `provenance`,
DROP COLUMN `provenance_sort`,
DROP COLUMN `provenance_note`,
DROP COLUMN `installation_place`,
DROP COLUMN `installation_place_sort`,
DROP COLUMN `installation_place_note`,
DROP COLUMN `production_place`,
DROP COLUMN `production_place_sort`,
DROP COLUMN `production_place_note`;
ALTER TABLE `inscriptions` ADD COLUMN `tla` int(11) DEFAULT NULL COMMENT 'The reference to the text in the Thesaurus Linguae Aegyptiae (http://aaew.bbaw.de/tla/servlet/TlaLogin) database' AFTER `tmtexts_id`;

OPTIMIZE TABLE `inscriptions`;

ALTER TABLE `name_types` ADD COLUMN `usage_area` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The `place_name` of the record in the table `places` corresponding to the region where the name type was predominantly used' AFTER `category`;
ALTER TABLE `name_types` ADD COLUMN `usage_area_sort` int(11) DEFAULT NULL COMMENT 'The `latitude` of the record in the table `places` corresponding to the `usage_area`' AFTER `usage_area`;
ALTER TABLE `name_types` ADD COLUMN `usage_area_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Explanatory notes and bibliographic references to the `usage_area`' AFTER `usage_area_sort`;
ALTER TABLE `name_types` ADD COLUMN `usage_period` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'The `item_name` of the period when the name type was predominantly used in the dating thesaurus (thesaurus 5), *loosely based on a subset of the [THOT Dates and dating systems thesaurus](http://thot.philo.ulg.ac.be/concept/thot-114)*' AFTER `usage_area_note`;
ALTER TABLE `name_types` ADD COLUMN `usage_period_sort` int(11) DEFAULT NULL COMMENT 'The sort value of the period to which the name type can be dated ' AFTER `usage_period`;
ALTER TABLE `name_types` ADD COLUMN `usage_period_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Explanatory notes and bibliographic references to the `usage_period`' AFTER `usage_period_sort`;
ALTER TABLE `name_types` 
ADD KEY `usage_area_idx` (`usage_area`),
ADD KEY `usage_area_sort_idx` (`usage_area_sort`),
ADD KEY `usage_period_idx` (`usage_period`),
ADD KEY `usage_period_sort_idx` (`usage_period_sort`);
OPTIMIZE TABLE `name_types`;

ALTER TABLE `inscriptions_workshops_xref`
COMMENT 'An associative table for linking workshops to objects (assuming that contradictory opinions can be expressed in scholarly literature).',
DROP FOREIGN KEY `inscriptions_workshops_xref_inscriptions`,
DROP KEY `inscriptions_workshops_xref_inscriptions_idx`;
ALTER TABLE `inscriptions_workshops_xref`
RENAME COLUMN inscriptions_id TO objects_id;
UPDATE inscriptions_workshops_xref SET objects_id = make_id(10,record_id_from_id(`objects_id`));
ALTER TABLE `inscriptions_workshops_xref`
ADD KEY `inscriptions_workshops_xref_objects_idx` (`objects_id`), 
ADD CONSTRAINT `inscriptions_workshops_xref_objects` FOREIGN KEY (`objects_id`) REFERENCES `objects` (`objects_id`) ON UPDATE NO ACTION ON DELETE NO ACTION;

OPTIMIZE TABLE `inscriptions_workshops_xref`;

ALTER TABLE `inv_nos`
DROP FOREIGN KEY `key-inv_nos-inscriptions`,
DROP KEY `key-inv_nos-inscriptions_idx`;
ALTER TABLE `inv_nos`
RENAME COLUMN inscriptions_id TO objects_id;
UPDATE inv_nos SET objects_id = make_id(10,record_id_from_id(`objects_id`));
ALTER TABLE `inv_nos`
ADD KEY `key-inv_nos-objects_idx` (`objects_id`), 
ADD CONSTRAINT `key-inv_nos-objects` FOREIGN KEY (`objects_id`) REFERENCES `objects` (`objects_id`) ON UPDATE NO ACTION ON DELETE NO ACTION;

OPTIMIZE TABLE `inv_nos`;

ALTER TABLE `titles` ADD COLUMN `taylor` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'List of corresponding lemma numbers in Taylor, An Index of Male Non-Royal Egyptian Titles, Epithets and Phrases of the 18th Dynasty' AFTER `ward_fischer_sort`;
ALTER TABLE `titles` ADD COLUMN `taylor_sort` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Temporary field with the `taylor` number converted for natural sort' AFTER `taylor`;
ALTER TABLE `titles` ADD COLUMN `ayedi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'List of corresponding lemma numbers in al-Ayedi, Index of Egyptian administrative, religious and military titles of the New Kingdom' AFTER `taylor_sort`;
ALTER TABLE `titles` ADD COLUMN `ayedi_sort` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Temporary field with the `ayedi` number converted for natural sort' AFTER `ayedi`;
ALTER TABLE `titles` 
ADD KEY `taylor_idx` (`taylor`),
ADD KEY `taylor_sort_idx` (`taylor_sort`),
ADD KEY `ayedi_idx` (`ayedi`),
ADD KEY `ayedi_sort_idx` (`ayedi_sort`);
OPTIMIZE TABLE `titles`;


ALTER TABLE `attestations` ADD COLUMN `epithet` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'An epithet (Beiwort) characterizing the age or the gender of the person' AFTER `location`;
ALTER TABLE `attestations` ADD COLUMN `representation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Whether the person is represented by a human figure' AFTER `epithet`;
ALTER TABLE `spellings_attestations_xref` ADD COLUMN `classifier` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Gardiner codes of classifier(s) standing after the name in the inscription' AFTER `spellings_id`;
ALTER TABLE `spellings_attestations_xref` ADD COLUMN `epithet_mdc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'An epithet (Beiwort) characterizing the age or the gender of the person, which stands after the name, in JSesh-compatible MdC codes' AFTER `classifier`;

DROP TRIGGER IF EXISTS `name_types_BEFORE_INSERT`;

DELIMITER //
CREATE TRIGGER `name_types_BEFORE_INSERT` BEFORE INSERT ON `name_types` FOR EACH ROW BEGIN
IF  IFNULL(  NEW.name_types_id, 0) = 0 THEN 
	SET NEW.name_types_id = make_id(table_id_from_name("name_types"),(SELECT MAX(record_id_from_id(name_types_id)) + 1 FROM name_types));
END IF;
SET NEW.usage_area_sort = (SELECT latitude from places where place_name = NEW.usage_area);
SET NEW.usage_period_sort = (SELECT sort_date_range_start+sort_date_range_end from thesauri where item_name = NEW.usage_period);
SET NEW.title_sort = sort_mixed(NEW.title_raw);
SET NEW.title = REPLACE(NEW.title_raw, "#", "");
END//
DELIMITER ;

DROP TRIGGER IF EXISTS `name_types_before_update`;
DELIMITER //
CREATE TRIGGER `name_types_before_update` BEFORE UPDATE ON `name_types` FOR EACH ROW BEGIN
SET NEW.title_sort = sort_mixed(NEW.title_raw);
SET NEW.title = REPLACE(NEW.title_raw, "#", "");
SET NEW.usage_area_sort = (SELECT latitude from places where place_name = NEW.usage_area);
SET NEW.usage_period_sort = (SELECT sort_date_range_start+sort_date_range_end from thesauri where item_name = NEW.usage_period);
END//
DELIMITER ;

DROP FUNCTION IF EXISTS `table_id_from_name`;

DELIMITER //
CREATE FUNCTION `table_id_from_name`(`table_name` VARCHAR(255)
) RETURNS int(11)
    DETERMINISTIC
    COMMENT 'returns table_id based on table name'
BEGIN
DECLARE result INT;

CASE
WHEN table_name =  "thesauri" THEN SET result = 0;
WHEN table_name =  "criteria" THEN SET result = 14;
WHEN table_name =  "publications" THEN SET result = 2;
WHEN table_name =  "biblio_refs" THEN SET result = 7;
WHEN table_name =  "inscriptions" THEN SET result = 4;
WHEN table_name =  "find_groups" THEN SET result = 23;
WHEN table_name =  "workshops" THEN SET result = 20;
WHEN table_name =  "inscriptions_workshops_xref" THEN SET result = 21;
WHEN table_name =  "places" THEN SET result = 22;
WHEN table_name =  "inv_nos" THEN SET result = 25;
WHEN table_name =  "collections" THEN SET result = 26;
WHEN table_name =  "attestations" THEN SET result = 8;
WHEN table_name =  "spellings_attestations_xref" THEN SET result = 15;
WHEN table_name =  "persons_attestations_xref" THEN SET result = 1;
WHEN table_name =  "persons" THEN SET result = 27;
WHEN table_name =  "titles_att" THEN SET result = 28;
WHEN table_name =  "titles" THEN SET result = 5;
WHEN table_name =  "spellings" THEN SET result = 29;
WHEN table_name =  "alternative_readings" THEN SET result = 9;
WHEN table_name =  "personal_names" THEN SET result = 17;
WHEN table_name =  "name_types" THEN SET result = 30;
WHEN table_name =  "names_types_xref" THEN SET result = 31;
WHEN table_name =  "bonds" THEN SET result = 24;
WHEN table_name =  "persons_bonds" THEN SET result = 11;
WHEN table_name =  "title_relations" THEN SET result = 3;
WHEN table_name =  "objects_inscriptions_xref" THEN SET result = 12;
WHEN table_name =  "objects" THEN SET result = 10;

ELSE SET result = -1;
END CASE;

RETURN result;
END//
DELIMITER ;

DROP FUNCTION IF EXISTS `table_name_from_id`;

DELIMITER //
CREATE FUNCTION `table_name_from_id`(`table_id` INT
) RETURNS varchar(255) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci
    DETERMINISTIC
    COMMENT 'Returns table name based on table_id'
BEGIN
DECLARE result VARCHAR(255);
CASE
WHEN table_id = 0 THEN SET result = "thesauri" ;
WHEN table_id = 14 THEN SET result = "criteria" ;
WHEN table_id = 2 THEN SET result = "publications";
WHEN table_id = 4 THEN SET result = "inscriptions";
WHEN table_id = 23 THEN SET result = "find_groups";
WHEN table_id = 20 THEN SET result = "workshops";
WHEN table_id = 21 THEN SET result = "inscriptions_workshops_xref";
WHEN table_id = 22 THEN SET result = "places";
WHEN table_id = 25 THEN SET result = "inv_nos";
WHEN table_id = 26 THEN SET result = "collections";
WHEN table_id = 8 THEN SET result = "attestations";
WHEN table_id = 15 THEN SET result = "spellings_attestations_xref";
WHEN table_id = 1 THEN SET result = "persons_attestations_xref";
WHEN table_id = 27 THEN SET result = "persons";
WHEN table_id = 28 THEN SET result = "titles_att";
WHEN table_id = 5 THEN SET result = "titles";
WHEN table_id = 29 THEN SET result = "spellings";
WHEN table_id = 9 THEN SET result = "alternative_readings";
WHEN table_id = 17 THEN SET result = "personal_names";
WHEN table_id = 30 THEN SET result = "name_types";
WHEN table_id = 31 THEN SET result = "names_types_xref";
WHEN table_id = 24 THEN SET result = "bonds";
WHEN table_id = 11 THEN SET result = "persons_bonds";
WHEN table_id = 3 THEN SET result = "title_relations";
WHEN table_id = 10 THEN SET result = "objects";
WHEN table_id = 12 THEN SET result = "objects_inscriptions_xref";

ELSE SET result = "";
END CASE;
RETURN result;
END//
DELIMITER ;

DROP FUNCTION IF EXISTS `biblio_refs_order`;

DELIMITER //
CREATE FUNCTION `biblio_refs_order`(`reference_type` VARCHAR(191),
	`source_id` INT
) RETURNS int(11)
    DETERMINISTIC
BEGIN
 DECLARE res INT;

    IF (reference_type LIKE "cp%" OR reference_type LIKE "% cp,%" OR reference_type LIKE "% cp") AND NOT reference_type LIKE "%cp (i)%" AND NOT reference_type LIKE "%illegible%"  THEN SET res = 10;
    ELSEIF  (reference_type LIKE "bp%" OR reference_type LIKE "% bp,%" OR reference_type LIKE "% bp") AND NOT reference_type LIKE "%bp (i)%" AND NOT reference_type LIKE "%illegible%"  THEN SET res = 20;
    ELSEIF  (reference_type LIKE "s%" OR reference_type LIKE "% s,%" OR reference_type LIKE "% s") AND NOT reference_type LIKE "%s (i)%"  THEN SET res = 30;
    ELSEIF  (reference_type LIKE "h%" OR reference_type LIKE "% h,%" OR reference_type LIKE "% h") AND NOT reference_type LIKE "%h (i)%"  THEN SET res = 40;   
    ELSEIF reference_type LIKE "%cp (i)%" OR  (reference_type LIKE "cp%" OR reference_type LIKE "% cp,%" OR reference_type LIKE "% cp")  THEN SET res = 50;   
    ELSEIF reference_type LIKE "%bp (i)%" OR (reference_type LIKE "bp%" OR reference_type LIKE "% bp,%" OR reference_type LIKE "% bp")  THEN SET res = 60;   
    ELSEIF reference_type LIKE "%s (i)%" THEN SET res = 70;       
    ELSEIF reference_type LIKE "%h (i)%" THEN SET res = 80;       
    ELSEIF(reference_type LIKE "t%" OR reference_type LIKE "% t,%" OR reference_type LIKE "% t") THEN SET res = 90;       
    ELSEIF(reference_type LIKE "d%" OR reference_type LIKE "% d,%" OR reference_type LIKE "% d") THEN SET res = 100;           
    ELSEIF(reference_type LIKE "m%" OR reference_type LIKE "% m,%" OR reference_type LIKE "% m") THEN SET res = 110;              
    ELSE SET res = 120;
    END IF;
 IF (reference_type LIKE "%back%") THEN SET res = res - 2; 
 END IF;
 IF (reference_type LIKE "%side%") THEN SET res = res - 4; 
   END IF;
    IF (source_id >0 ) THEN SET res = res - 1; 
      END IF;
   
    RETURN res;

RETURN 0;
END//
DELIMITER ;

DROP FUNCTION IF EXISTS `table_id_from_id`;

DELIMITER //
CREATE FUNCTION `table_id_from_id`(`id` INT
) RETURNS int(11)
    DETERMINISTIC
BEGIN

RETURN CAST((id & 0x1F800000) >> 23 AS SIGNED);

END//
DELIMITER ;

DROP FUNCTION IF EXISTS `record_id_from_id`;

DELIMITER //
CREATE FUNCTION `record_id_from_id`(`id` INT
) RETURNS int(11)
    DETERMINISTIC
    COMMENT 'returns the record_id based on id'
BEGIN

RETURN CAST((id & 0x7FFFFF) AS SIGNED);

END//
DELIMITER ;

DROP TRIGGER IF EXISTS `publications_BEFORE_INSERT`;

DELIMITER //
CREATE TRIGGER `publications_BEFORE_INSERT` BEFORE INSERT ON `publications` FOR EACH ROW BEGIN
IF  IFNULL(  NEW.publications_id, 0) = 0 THEN 
	SET NEW.publications_id = make_id(table_id_from_name("publications"),(SELECT MAX(record_id_from_id(publications_id)) + 1 FROM publications));
END IF;
	SET NEW.author_year_sort  = invert_author_year(NEW.author_year);
	SET NEW.year  = CAST(REGEXP_SUBSTR(NEW.author_year, "\d+") AS SIGNED);
END//
DELIMITER ;

DROP TRIGGER IF EXISTS `publications_before_update`;

DELIMITER //
CREATE TRIGGER `publications_before_update` BEFORE UPDATE ON `publications` FOR EACH ROW BEGIN
	SET NEW.author_year_sort  = invert_author_year(NEW.author_year);
	SET NEW.year  = CAST(REGEXP_SUBSTR(NEW.author_year, "\d+") AS SIGNED);
END//
DELIMITER ;

DROP TRIGGER IF EXISTS `titles_BEFORE_UPDATE`;

DELIMITER //
CREATE TRIGGER `titles_BEFORE_UPDATE` BEFORE UPDATE ON `titles` FOR EACH ROW BEGIN
SET NEW.title_sort = sort_transl(NEW.title);
SET NEW.title_search = search_transl(NEW.title);
SET NEW.ward_fischer_sort = LEFT(natural_sort_format(NEW.ward_fischer,7, ""),190);
SET NEW.hannig_sort = LEFT(natural_sort_format(NEW.hannig,7, ""),190);
SET NEW.taylor_sort = LEFT(natural_sort_format(NEW.taylor,7, ""),190);
SET NEW.ayedi_sort = LEFT(natural_sort_format(NEW.ayedi,7, ""),190);
SET NEW.usage_area_sort = (SELECT latitude from places where place_name = NEW.usage_area);
SET NEW.usage_period_sort = (SELECT sort_date_range_start+sort_date_range_end from thesauri where item_name = NEW.usage_period);
END//
DELIMITER ;

DROP TRIGGER IF EXISTS `titles_before_insert`;

DELIMITER //
CREATE TRIGGER `titles_before_insert` BEFORE INSERT ON `titles` FOR EACH ROW BEGIN
SET NEW.title_sort = sort_transl(NEW.title);
SET NEW.title_search = search_transl(NEW.title);
SET NEW.ward_fischer_sort = LEFT(natural_sort_format(NEW.ward_fischer,7, ""),190);
SET NEW.hannig_sort = LEFT(natural_sort_format(NEW.hannig,7, ""),190);
SET NEW.taylor_sort = LEFT(natural_sort_format(NEW.taylor,7, ""),190);
SET NEW.ayedi_sort = LEFT(natural_sort_format(NEW.ayedi,7, ""),190);
	SET NEW.usage_area_sort = (SELECT latitude from places where place_name = NEW.usage_area);
	SET NEW.usage_period_sort = (SELECT sort_date_range_start+sort_date_range_end from thesauri where item_name = NEW.usage_period);

IF  IFNULL(  NEW.titles_id, 0) = 0 THEN 
	SET NEW.titles_id = make_id(table_id_from_name("titles"),(SELECT MAX(record_id_from_id(titles_id)) + 1 FROM titles));
END IF;
END//
DELIMITER ;


DELIMITER //
CREATE TRIGGER `objects_before_insert` BEFORE INSERT ON `objects` FOR EACH ROW BEGIN
IF  IFNULL(  NEW.objects_id, 0) = 0 THEN 
	SET NEW.objects_id = make_id(table_id_from_name("objects"),(SELECT MAX(record_id_from_id(objects_id)) + 1 FROM objects));
END IF;
	SET NEW.title_sort = natural_sort_format(NEW.title,7, "");
	SET NEW.provenance_sort = (SELECT latitude from places where place_name = NEW.provenance);
	SET NEW.installation_place_sort = (SELECT latitude from places where place_name = NEW.installation_place);
	SET NEW.production_place_sort = (SELECT latitude from places where place_name = NEW.production_place);
UPDATE inscriptions INNER JOIN objects_inscriptions_xref ON inscriptions.inscriptions_id = objects_inscriptions_xref.inscriptions_id 
SET inst_prov_temp = COALESCE(NEW.installation_place, NEW.provenance), 
inst_prov_temp_sort = COALESCE(NEW.installation_place_sort , NEW.provenance_sort),
orig_prod_temp = COALESCE(inscriptions.origin, NEW.production_place ), 
orig_prod_temp_sort = COALESCE(inscriptions.origin_sort, NEW.production_place)
 WHERE objects_inscriptions_xref.objects_id = NEW.objects_id;  
 
UPDATE inscriptions INNER JOIN objects_inscriptions_xref ON inscriptions.inscriptions_id = objects_inscriptions_xref.inscriptions_id 
SET inscriptions.region_temp = COALESCE(inscriptions.orig_prod_temp, inscriptions.inst_prov_temp),
region_temp_sort = COALESCE(inscriptions.orig_prod_temp_sort, inscriptions.inst_prov_temp_sort)
 WHERE objects_inscriptions_xref.objects_id = NEW.objects_id;  
END//
DELIMITER ;

DELIMITER //
CREATE TRIGGER `objects_before_update` BEFORE UPDATE ON `objects` FOR EACH ROW BEGIN
	SET NEW.title_sort = natural_sort_format(NEW.title,7, "");
	SET NEW.provenance_sort = (SELECT latitude from places where place_name = NEW.provenance);
	SET NEW.installation_place_sort = (SELECT latitude from places where place_name = NEW.installation_place);
	SET NEW.production_place_sort = (SELECT latitude from places where place_name = NEW.production_place);
	UPDATE inscriptions INNER JOIN objects_inscriptions_xref ON inscriptions.inscriptions_id = objects_inscriptions_xref.inscriptions_id 
	SET inst_prov_temp = COALESCE(NEW.installation_place, NEW.provenance), 
	inst_prov_temp_sort = COALESCE(NEW.installation_place_sort , NEW.provenance_sort),
	orig_prod_temp = COALESCE(inscriptions.origin, NEW.production_place ), 
	orig_prod_temp_sort = COALESCE(inscriptions.origin_sort, NEW.production_place)
 	WHERE objects_inscriptions_xref.objects_id = NEW.objects_id;  
 
	UPDATE inscriptions INNER JOIN objects_inscriptions_xref ON inscriptions.inscriptions_id = objects_inscriptions_xref.inscriptions_id 
	SET inscriptions.region_temp = COALESCE(inscriptions.orig_prod_temp, inscriptions.inst_prov_temp),
	region_temp_sort = COALESCE(inscriptions.orig_prod_temp_sort, inscriptions.inst_prov_temp_sort)
 WHERE objects_inscriptions_xref.objects_id = NEW.objects_id;  
END//
DELIMITER ;

DELIMITER //
CREATE TRIGGER `objects_inscriptions_xref_before_insert` BEFORE INSERT ON `objects_inscriptions_xref` FOR EACH ROW BEGIN
IF  IFNULL(  NEW.objects_inscriptions_xref_id, 0) = 0 THEN 
	SET NEW.objects_inscriptions_xref_id = make_id(table_id_from_name("objects_inscriptions_xref"),(SELECT MAX(record_id_from_id(objects_inscriptions_xref_id)) + 1 FROM objects_inscriptions_xref));
END IF;
	UPDATE inscriptions SET inst_prov_temp = COALESCE((SELECT installation_place FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE installation_place  <> '' AND  installation_place IS NOT NULL and objects_inscriptions_xref.inscriptions_id = NEW.inscriptions_id LIMIT 1 ), (SELECT provenance FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE provenance  <> '' AND  provenance IS NOT NULL and objects_inscriptions_xref.inscriptions_id = NEW.inscriptions_id LIMIT 1 )), 
	inst_prov_temp_sort = COALESCE((SELECT installation_place_sort FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE installation_place_sort IS NOT NULL and objects_inscriptions_xref.inscriptions_id = NEW.inscriptions_id LIMIT 1 ), (SELECT provenance_sort FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE provenance_sort IS NOT NULL and objects_inscriptions_xref.inscriptions_id = NEW.inscriptions_id LIMIT 1 )),
	orig_prod_temp = COALESCE(inscriptions.origin, (SELECT production_place FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE production_place  <> '' AND  production_place IS NOT NULL and objects_inscriptions_xref.inscriptions_id = NEW.inscriptions_id LIMIT 1 )), 
	orig_prod_temp_sort = COALESCE(inscriptions.origin_sort, (SELECT production_place_sort FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE production_place_sort IS NOT NULL and objects_inscriptions_xref.inscriptions_id = NEW.inscriptions_id LIMIT 1 ))
 	WHERE inscriptions.inscriptions_id = NEW.inscriptions_id; 
	UPDATE inscriptions SET region_temp = COALESCE(inscriptions.orig_prod_temp, inscriptions.inst_prov_temp),
	region_temp_sort = COALESCE(inscriptions.orig_prod_temp_sort, inscriptions.inst_prov_temp_sort)
 	WHERE inscriptions.inscriptions_id = NEW.inscriptions_id;  
END//
DELIMITER ;

DELIMITER //
CREATE TRIGGER `objects_inscriptions_xref_before_update` BEFORE UPDATE ON `objects_inscriptions_xref` FOR EACH ROW BEGIN
UPDATE inscriptions SET inst_prov_temp = COALESCE((SELECT installation_place FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE installation_place  <> '' AND  installation_place IS NOT NULL and objects_inscriptions_xref.inscriptions_id = NEW.inscriptions_id LIMIT 1 ), (SELECT provenance FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE provenance  <> '' AND  provenance IS NOT NULL and objects_inscriptions_xref.inscriptions_id = NEW.inscriptions_id LIMIT 1 )), 
inst_prov_temp_sort = COALESCE((SELECT installation_place_sort FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE installation_place_sort IS NOT NULL and objects_inscriptions_xref.inscriptions_id = NEW.inscriptions_id LIMIT 1 ), (SELECT provenance_sort FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE provenance_sort IS NOT NULL and objects_inscriptions_xref.inscriptions_id = NEW.inscriptions_id LIMIT 1 )),
orig_prod_temp = COALESCE(inscriptions.origin, (SELECT production_place FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE production_place  <> '' AND  production_place IS NOT NULL and objects_inscriptions_xref.inscriptions_id = NEW.inscriptions_id LIMIT 1 )), 
orig_prod_temp_sort = COALESCE(inscriptions.origin_sort, (SELECT production_place_sort FROM objects INNER JOIN objects_inscriptions_xref ON objects.objects_id = objects_inscriptions_xref.objects_id WHERE production_place_sort IS NOT NULL and objects_inscriptions_xref.inscriptions_id = NEW.inscriptions_id LIMIT 1 ))
 WHERE inscriptions.inscriptions_id = NEW.inscriptions_id; 
UPDATE inscriptions SET region_temp = COALESCE(inscriptions.orig_prod_temp, inscriptions.inst_prov_temp),
region_temp_sort = COALESCE(inscriptions.orig_prod_temp_sort, inscriptions.inst_prov_temp_sort)
 WHERE inscriptions.inscriptions_id = NEW.inscriptions_id;  
END//
DELIMITER ;

DROP TRIGGER IF EXISTS `inscriptions_before_update`;

DELIMITER //
CREATE TRIGGER `inscriptions_before_update` BEFORE UPDATE ON `inscriptions` FOR EACH ROW BEGIN
	SET NEW.title_sort = natural_sort_format(NEW.title,7, "");
	SET NEW.origin_sort = (SELECT latitude from places where place_name = NEW.origin);
	SET NEW.dating_sort_start = (SELECT sort_date_range_start from thesauri where item_name = NEW.dating);
	SET NEW.dating_sort_end = (SELECT sort_date_range_end from thesauri where item_name = NEW.dating);
END//
DELIMITER ;

DROP TRIGGER IF EXISTS `inscriptions_before_insert`;

DELIMITER //
CREATE TRIGGER `inscriptions_before_insert` BEFORE INSERT ON `inscriptions` FOR EACH ROW BEGIN
IF  IFNULL(  NEW.inscriptions_id, 0) = 0 THEN 
	SET NEW.inscriptions_id = make_id(table_id_from_name("inscriptions"),(SELECT MAX(record_id_from_id(inscriptions_id)) + 1 FROM inscriptions));
END IF;
	SET NEW.title_sort = natural_sort_format(NEW.title,7, "");
	SET NEW.origin_sort = (SELECT latitude from places where place_name = NEW.origin);
	SET NEW.dating_sort_start = (SELECT sort_date_range_start from thesauri where item_name = NEW.dating);
	SET NEW.dating_sort_end = (SELECT sort_date_range_end from thesauri where item_name = NEW.dating);
END//
DELIMITER ;