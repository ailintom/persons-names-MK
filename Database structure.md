# Database structure
The data is stored in a MySQL database. For the sake of compatibility with other relational database management systems the following datatypes are used:
* `CHAR` with varying lengths (standard SQL data type `NATIONAL CHARACTER`) for short attributes;
* `VARCHAR(255)` (standard SQL data type `NATIONAL CHARACTER VARYING (255)`) for short text values;
* `VARCHAR(4000)` (standard SQL data type `NATIONAL CHARACTER VARYING (255)`) for longer text values;
* `INT (11)` (standard SQL data type `INTEGER`) for IDs.  
* `DATE` (standard SQL data type `DATE`) for dates.  
The collation utf8mb4_unicode_ci is used for all CHAR and VARCHAR fields.

## The ID numbers
The database uses a system of ID numbers that ensures that each ID uniquely identifies an entity within a whole database and contains information on the table where the record is stored.
The IDs are stored as signed 32-bit integers, which are used as bit fields, whereby the table is coded in bits 4 to 9, and the bits 10 to 32 are used for record number in the table, allowing for 8388607
 records per table. The table ID can be extracted from the record ID with simple arithmetic operations    `$table_id = (($id & 0x1F800000) / 0x800000);` in PHP 5 or in javascript.

## Tables

### Table **publications** (table_id: 2)  
Each record in this table describes a printed or online publication (a bibliographic entry). Here goes everything published that can be cited using the author-year system.   
*Equivalent: <http://www.cidoc-crm.org/cidoc-crm/E31_document>*  

| Field name | Type | Description |
| --- | :---: | :--- |
| biblio_entries_id | INTEGER | Unique record ID |
| csl_json | TEXT | Bibliographical data in the [CSL-JSON](https://github.com/citation-style-language/schema/blob/master/csl-data.json) format |
| author_year | VARCHAR(255) | The author-year handle for refererring |
| html_entry | VARCHAR(4000) | Precomposed bibliographical entry in the Chicago Manual of Art Style format (HTML) |
| oeb_id | VARCHAR(4000) | The ID of the corresponding record in the [Online Egyptological Bibliography](http://oeb.griffith.ox.ac.uk/) (not available for all records) |


### Table **biblio_refs** (table_id: 7)  
Each record in this table describes a reference from a publication (if the `source_id` field is not empty)
or a webpage (if the `source_url` field is not empty) to an entity (an inscribed object, a person's dossier, 
a workshop, an archaeological assemblage, a personal name, or a title.   
*Equivalent: <http://www.cidoc-crm.org/cidoc-crm/P70i_is_documented_in> statements*  

| Field name     | Type | Description |
| ---            | :---: | :--- |
| biblio_refs_id | INTEGER | Unique database ID |
| reference_type | CHAR(20) | The type of the reference |
| source_id      | INTEGER | The ID of the referring publication in the table `publications` |
| source_url     | VARCHAR(4000) | URL for online sources that cannot be cited using the author-year system |
| source_title   | VARCHAR(4000) | Reference to an offline source that cannot be cited using the author-year system (an archival document, and offline museum database, etc.) or the title of the online source referred to in `source_url` |
| accessed_on    | DATE | The date when the online or offline source that cannot be cited using the author-year system was accessed |
| object_id      | INTEGER | The ID of the referred entity |
| pages          | VARCHAR(255) | Pages |
| note           | VARCHAR(4000) | Note related to the reference (for example, mistakes in the publication)
