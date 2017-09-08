# Database structure
The data is stored in a MySQL database. For the sake of compatibility with other relational database management systems only the following datatypes are used:  
* `CHAR` (standard SQL data type `NATIONAL CHARACTER`) for short attributes;  
* `VARCHAR(255)` (standard SQL data type `NATIONAL CHARACTER VARYING (255)`) for short text values;  
* `VARCHAR(4000)` (standard SQL data type `NATIONAL CHARACTER VARYING (4000)`) for longer text values (length restricted for compatibility with MS SQL Server);  
* `INT (11)` (standard SQL data type `INTEGER`) for IDs;    
* `DATE` (standard SQL data type `DATE`) for dates.  
The collation `utf8mb4_unicode_ci` is used for all `CHAR` and `VARCHAR` fields.

## The ID numbers
The database uses a system of ID numbers that ensures that each ID uniquely identifies an entity within the whole database and thus contains information on the table where the record is stored.
The IDs are stored as signed 32-bit integers, which are used as bit fields, whereby the table is coded in bits 4 to 9, and bits 10 to 32 are used for the number of the record in the table, allowing for 8388607
 records per table. Bits 1 to 3 are reserved. The table ID can be extracted from the record ID with two simple arithmetic operations    `$table_id = (($id & 0x1F800000) / 0x800000);` in PHP 5 or in javascript.

## Tables

### thesauri (table_id: 2)  
This is a supporting table containing keys and values of own and third-party thesauri used in the database.  

| Field name | Type | Description |
| --- | :---: | :--- |
| thesauri_id | INTEGER | Unique record ID, primary key |
| thesaurus   | INTEGER | The handle of the thesaurus to which this record belongs. Under thesaurus=0 all thesauri represented in this table are listed.  |
| parent      | INTEGER | The `thesauri_id` of the superordinate thesaurus entry |
| sort_value  | INTEGER | The value used for sorting thesaurus entries  |
| item_name  | VARCHAR(255) | The textual value of the thesaurus entry |
| external_key| VARCHAR(255) | The key of the corresponding thesaurus entry in a standard external thesaurus (such as the [THOT](http://thot.philo.ulg.ac.be/index.html) project)  |

<!--- | key_number  | INTEGER | The numeric key of the thesaurus entry | --->

### publications (table_id: 2)  
Each record in this table describes a printed or online publication (a bibliographic entry). Here goes everything published that can be cited using the author-year system.   
*Equivalent: <http://www.cidoc-crm.org/cidoc-crm/E31_document>*  

| Field name | Type | Description |
| --- | :---: | :--- |
| biblio_entries_id | INTEGER | Unique record ID, primary key |
| csl_json | TEXT | Bibliographical data in the [CSL-JSON](https://github.com/citation-style-language/schema/blob/master/csl-data.json) format |
| author_year | VARCHAR(255) | The author-year handle for referring |
| html_entry | VARCHAR(4000) | Precomposed bibliographical entry in the Chicago Manual of Art Style format (HTML) |
| oeb_id | VARCHAR(4000) | The ID of the corresponding record in the [Online Egyptological Bibliography](http://oeb.griffith.ox.ac.uk/) (not available for all records) |

*Note:* On the back end, CLS-JSON bibliographical descriptions are converted into HTML bibliographical entries using [citeproc-node](https://github.com/zotero/citeproc-node).

### biblio_refs (table_id: 7)  
Each record in this table describes a reference from a publication (if the `source_id` field is not empty)
or a webpage (if the `source_url` field is not empty) to an entity (an inscribed object, a person's dossier, 
a workshop, an archaeological assemblage, a personal name, or a title.   
*Equivalent: <http://www.cidoc-crm.org/cidoc-crm/P70i_is_documented_in> statements*  

| Field name     | Type | Description |
| ---            | :---: | :--- |
| biblio_refs_id | INTEGER | Unique record ID, primary key |
| reference_type | CHAR(20) | The type of the reference |
| source_id      | INTEGER | The ID of the referring publication in the table `publications` |
| source_url     | VARCHAR(4000) | URL for online sources that cannot be cited using the author-year system |
| source_title   | VARCHAR(4000) | Reference to an offline source that cannot be cited using the author-year system (an archival document, and offline museum database, etc.; this also includes the references to the Topographical Bibliography to keep references to published and unpublished TopBib entries in one place) or the title of the online source referred to in `source_url` |
| accessed_on    | DATE | The date when the online or offline source that cannot be cited using the author-year system was accessed |
| object_id      | INTEGER | The ID of the referred entity |
| pages          | VARCHAR(255) | Pages |
| note           | VARCHAR(4000) | Note related to the reference (for example, mistakes in the publication) |

### inscriptions (table_id: 7)  
Each record in this table represents a physical object with an Egyptian inscription. Objects coming from the same structure of a different type than the objects themselves (e. g., stelae originally installed in the same offering chapel) are considered different objects, but objects that are parts of an originally integral object of the same type, now decomposed, (e. g. two parts of the same statue, now stored in different museums) are considered one and the same object. 

| Field name        | Type | Description |
| ---               | :---: | :--- |
| inscriptions_id   | INTEGER | Unique record ID, primary key |
| title  | VARCHAR(255) | The title under which the object is referred to in the database (the most relevant pair of the museum name and inventory number for objects or the reference to the most relevant (usually first) publication )  |
| object_type  | VARCHAR(255) | The `item_name` of the inscription type in the object_type thesaurus (thesaurus 1) *example: stela*  |
| object_subtype | VARCHAR(255) | The `item_name` of the inscription type in the object_subtype thesaurus (thesaurus 2) *example: block-statue *  |
| material          | VARCHAR(255) | The `item_name` of the material type in the material  thesaurus (thesaurus 3) *based on a subset of the [THOT Material thesaurus](http://thot.philo.ulg.ac.be/concept/thot-6200)* |
| assemblages_id    | INTEGER | The ID of the archaeological assemblage to which the inscribed object belongs in the table `assemblages` |
| text_content      | VARCHAR(255) | The `item_name` of the inscription type in the text_content thesaurus (thesaurus 2) *based on a subset of the [THOT Text content thesaurus](http://thot.philo.ulg.ac.be/concept/thot-18634)* |
| script            | VARCHAR(255) | The `item_name` of the inscription type in the script thesaurus (thesaurus 4) *based on a subset of the [THOT Ancient Egyptian scripts thesaurus](http://thot.philo.ulg.ac.be/concept/thot-111)* |
| provenance        | VARCHAR(255) | The `place_name` of the record in the table `places` corresponding to the the place where the object was found or purchased |
| provenance_note   | VARCHAR(4000) | Note related to the `provenance` |
| installation_place| VARCHAR(255) | The `place_name` of the record in the table `places` corresponding to the the place where the object should have been installed (when different from the provenance or when the provenance is unknown or unreliable, as in case of purchases) |
| installation_place_note   | VARCHAR(4000) | Note related to the `installation_place` |
| origin            | VARCHAR(255) | The `place_name` of the record in the table `places` corresponding to the the place where the person(s) named in the inscription should have lived |
| origin_note       | VARCHAR(4000) | The reasoning behind the `origin` with relevant bibliographical references whenever possible |
| production_place  | VARCHAR(255) | The `place_name` of the record in the table `places` corresponding to the the place where the object should have been produced |
| production_place_note | VARCHAR(4000) | The reasoning behind the `production_place` with relevant bibliographical references whenever possible |
| dating            | VARCHAR(255) | The `item_name` of the period, to which the object can be dated, in the dating thesaurus (thesaurus 5) *loosely based on a subset of the [THOT Dates and dating systems thesaurus](http://thot.philo.ulg.ac.be/concept/thot-114)* |
| dating_note       | VARCHAR(255) | The reasoning behind the `dating`  |
| last_king_id      | VARCHAR(255) | The `id`  of the most recent king explicitly named in the inscription in the king thesaurus (thesaurus 6) *loosely based on a subset of the [THOT Dates and dating systems thesaurus](http://thot.philo.ulg.ac.be/concept/thot-114)* |
| note              | VARCHAR(4000) | General notes related to the object |

  
  
**Disclaimer: This is a work in progress**  
