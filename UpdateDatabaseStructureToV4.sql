-- This SQL code updates the datasets ver. 1,  ver. 2, and ver. 3 to the new structure,
-- which is used starting from ver. 4. The SQL code changes the database structure;
-- the data itself remains intact. This update should only be executed after converting the 
-- data to ver. 3 using UpdateDatabaseStructureToV3.sql

ALTER TABLE `places` ADD COLUMN `wgs84_latitude` DECIMAL(8,6) DEFAULT NULL COMMENT 'The latitude of the place (or of the central point of the region) in decimal degrees. This value is used for localizing places on a map' AFTER `latitude`;
ALTER TABLE `places` ADD COLUMN `wgs84_longitude` DECIMAL(9,6) DEFAULT NULL COMMENT 'The longitude of the place (or of the central point of the region) in decimal degrees. This value is used for localizing places on a map ' AFTER `wgs84_latitude`;
OPTIMIZE TABLE `titles`;
