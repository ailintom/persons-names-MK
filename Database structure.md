# Persons and Names of the Middle Kingdom  
# Database structure
The data is stored in a MySQL database. For the sake of compatibility with other relational database management systems only the following datatypes are used:  
* `CHAR` (standard SQL data type `NATIONAL CHARACTER`) for short attributes;  
* `VARCHAR(255)` (standard SQL data type `NATIONAL CHARACTER VARYING (255)`) for short text values;  
* `VARCHAR(4000)` (standard SQL data type `NATIONAL CHARACTER VARYING (4000)`) for longer text values (length restricted for compatibility with MS SQL Server);  
* `INT` (standard SQL data type `INTEGER`) for IDs;    
* `DATE` (standard SQL data type `DATE`) for dates.  
The collation `utf8mb4_unicode_ci` is used for all `CHAR` and `VARCHAR` fields.

## The ID numbers
The database uses a system of ID numbers that ensures that each ID uniquely identifies an entity within the whole database and thus contains information on the table where the record is stored.
The IDs are stored as signed 32-bit integers, which are used as bit fields, whereby the table is coded in bits 4 to 9, and bits 10 to 32 are used for the number of the record in the table, allowing for 8388607
 records per table. Bits 1 to 3 are reserved. The table ID can be extracted from the record ID with two simple arithmetic operations    `$table_id = (($id & 0x1F800000) / 0x800000);` in PHP 5 or in JavaScript.

## Tables

### thesauri (table_id: 2)  
This is a supporting table containing keys and values of own and third-party thesauri used in the database.  

| Field name | Type | Description |
| --- | :---: | :--- |
| thesauri_id | INT | Unique record ID, primary key |
| thesaurus   | INT | The handle of the thesaurus to which this record belongs. Under thesaurus=0 all thesauri represented in this table are listed.  |
| parent      | INT | The `thesauri_id` of the superordinate thesaurus entry |
| sort_value  | INT | The value used for sorting thesaurus entries  |
| item_name  | VARCHAR(255) | The textual value of the thesaurus entry |
| external_key| VARCHAR(255) | The key of the corresponding thesaurus entry in a standard external thesaurus (such as the [THOT](http://thot.philo.ulg.ac.be/index.html) project)  |

<!--- | key_number  | INT | The numeric key of the thesaurus entry | --->

### publications (table_id: 2)  
Each record in this table describes a printed or online publication (a bibliographic entry). Here goes everything published that can be cited using the author-year system.   
*Equivalent: <http://www.cidoc-crm.org/cidoc-crm/E31_document>*  

| Field name | Type | Description |
| --- | :---: | :--- |
| publications_id | INT | Unique record ID, primary key |
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
| biblio_refs_id | INT | Unique record ID, primary key |
| reference_type | CHAR(20) | The type of the reference |
| source_id      | INT | The ID of the referring publication in the table `publications` |
| source_url     | VARCHAR(4000) | URL for online sources that cannot be cited using the author-year system |
| source_title   | VARCHAR(4000) | Reference to an offline source that cannot be cited using the author-year system (an archival document, and offline museum database, etc.; this also includes the references to the Topographical Bibliography to keep references to published and unpublished TopBib entries in one place) or the title of the online source referred to in `source_url` |
| accessed_on    | DATE | The date when the online or offline source that cannot be cited using the author-year system was accessed |
| object_id      | INT | The ID of the referred entity |
| pages          | VARCHAR(255) | Pages |
| note           | VARCHAR(4000) | Note related to the reference (for example, mistakes in the publication) |

### inscriptions (table_id: 7)  
Each record in this table represents a physical object with an Egyptian inscription. This can be an object now located in a museum or a private collection or known from a publication, archival material, or a sale catalogue (such as a stela, statue, offering table, coffin, seal, papyrus, etc.), a rock inscription, an inscribed tomb, or another structure. Objects coming from the same structure of a different type than the objects themselves (e. g., stelae originally installed in the same offering chapel) are considered different objects, but objects that are parts of an originally integral object of the same type, now decomposed, (e. g. two parts of the same statue, now stored in different museums) are considered the same object. 

| Field name        | Type  | Description |
| ---               | :---: | :---        |
| inscriptions_id   | INT | Unique record ID, primary key |
| title  | VARCHAR(255) | The title under which the object is referred to in the database (the most relevant pair of the museum name and inventory number for objects or the reference to the most relevant (usually first) publication)  |
| object_type  | VARCHAR(255) | The `item_name` of the inscription type in the object_type thesaurus (thesaurus 1) *example: stela*  |
| object_subtype | VARCHAR(255) | The `item_name` of the inscription type in the object_subtype thesaurus (thesaurus 2) *example: block-statue *  |
| material          | VARCHAR(255) | The `item_name` of the material type in the material  thesaurus (thesaurus 3) *based on a subset of the [THOT Material thesaurus](http://thot.philo.ulg.ac.be/concept/thot-6200)* |
| assemblages_id    | INT | The ID of the archaeological assemblage to which the inscribed object belongs in the table `assemblages` |
| text_content      | VARCHAR(255) | The `item_name` of the inscription type in the text_content thesaurus (thesaurus 2) *based on a subset of the [THOT Text content thesaurus](http://thot.philo.ulg.ac.be/concept/thot-18634)* |
| script            | VARCHAR(255) | The `item_name` of the inscription type in the script thesaurus (thesaurus 4) *based on a subset of the [THOT Ancient Egyptian scripts thesaurus](http://thot.philo.ulg.ac.be/concept/thot-111)* |
| provenance        | VARCHAR(255) | The `place_name` of the record in the table `places` corresponding to the place where the object was found or purchased |
| provenance_note   | VARCHAR(4000) | Note related to the `provenance` |
| installation_place| VARCHAR(255) | The `place_name` of the record in the table `places` corresponding to the place where the object should have been installed (when different from the provenance or when the provenance is unknown or unreliable, as in case of purchases) |
| installation_place_note   | VARCHAR(4000) | Note related to the `installation_place` |
| origin            | VARCHAR(255) | The `place_name` of the record in the table `places` corresponding to the place where the person(s) named in the inscription should have lived |
| origin_note       | VARCHAR(4000) | The reasoning behind the `origin` with relevant bibliographical references whenever possible |
| production_place  | VARCHAR(255) | The `place_name` of the record in the table `places` corresponding to the place where the object should have been produced |
| production_place_note | VARCHAR(4000) | The reasoning behind the `production_place` with relevant bibliographical references whenever possible |
| dating            | VARCHAR(255) | The `item_name` of the period to which the object can be dated in the dating thesaurus (thesaurus 5) *loosely based on a subset of the [THOT Dates and dating systems thesaurus](http://thot.philo.ulg.ac.be/concept/thot-114)* |
| dating_note       | VARCHAR(4000) | The reasoning behind the `dating`  |
| last_king_id      | VARCHAR(255) | The `id`  of the most recent king explicitly named on the object in the king thesaurus (thesaurus 6) *loosely based on a subset of the [THOT Dates and dating systems thesaurus](http://thot.philo.ulg.ac.be/concept/thot-114)* |
| note              | VARCHAR(4000) | General notes related to the object |

### assemblages (table_id: 23)  
Each record in this table represents an archaeological assemblage (such as a burial or a memorial chapel) where one or more inscribed objects were found. This data is supplementary and is entered only to the extent that it can be relevant for dating and grouping together inscribed objects.  

| Field name        | Type  | Description |
| ---               | :---: | :---        |
| assemblages_id    | INT   | Unique record ID, primary key |
| site              | VARCHAR(255) | The `place_name` of the record in the table `places` corresponding to the place where the assemblage is located |
| site_area         | VARCHAR(255) | The part of the site where the assemblage is located |
| exact_location    | VARCHAR(4000)| A detailed description of the assemblage location |
| title             | VARCHAR(255) | The title under which the assemblage is referred to in the database |
| assemblage_type   | VARCHAR(255) | The `item_name` of the assemblage type in the assemblage_type thesaurus (thesaurus 7)  |
| architecture      | VARCHAR(4000)| Relevant information on the substructure and the superstructure |
| human_remains     | VARCHAR(4000)| Relevant information on the deceased in the assemblage |
| finds             | VARCHAR(4000)| Relevant information on the finds other than inscribed objects |
| disturbance       | VARCHAR(255) | The `item_name` of the assemblage type in the disturbance thesaurus (thesaurus 8)  |
| dating            | VARCHAR(255) | The `item_name` of the period to which the assemblage can be dated in the dating thesaurus (thesaurus 5) *loosely based on a subset of the [THOT Dates and dating systems thesaurus](http://thot.philo.ulg.ac.be/concept/thot-114)* |
| dating_note       | VARCHAR(4000) | The reasoning behind the `dating`  |
| note              | VARCHAR(4000) | General notes related to the assemblage |

### workshops (table_id: 20)  
Each record in this table represents a workshop producing inscribed objects that was discussed in scholarly literature. In other words, it represents a group of objects set off by several artistic and/or palaeographic peculiarities, which allow surmising that the objects come from the same timeframe and were produced at the same place.  

| Field name        | Type  | Description |
| ---               | :---: | :---        |
| workshops_id      | INT   | Unique record ID, primary key |
| title             | VARCHAR(255) | The title under which the workshop is referred to in the database |
| production_place  | VARCHAR(255) | The `place_name` of the record in the table `places` corresponding to the place where the objects should have been produced |
| production_place_note | VARCHAR(4000) | The reasoning behind the `production_place` with relevant bibliographical references whenever possible |
| dating            | VARCHAR(255) | The `item_name` of the period to which the workshop can be dated in the dating thesaurus (thesaurus 5) *loosely based on a subset of the [THOT Dates and dating systems thesaurus](http://thot.philo.ulg.ac.be/concept/thot-114)* |
| dating_note       | VARCHAR(4000) | The reasoning behind the `dating`  |
| note              | VARCHAR(4000) | General notes related to the assemblage |

### inscriptions_workshops_xref (table_id: 21)  
An associative table for linking workshops to inscriptions (assuming that contradictory opinions can be expressed in scholarly literature). 

| Field name        | Type  | Description |
| ---               | :---: | :---        |
| inscriptions_workshops_xref_id | INT   | Unique record ID, primary key |
| workshops_id                   | INT   | ID of the workshop |
| inscriptions_id                | INT   | ID of the workshop |

### places (table_id: 22)  
Each record in this table represents the name of a place or a region associated with inscriptions catalogues in this database. One location can be listed in this table several times under different names (modern and ancient).

| Field name        | Type  | Description |
| ---               | :---: | :---        |
| places_id         | INT   | Unique record ID, primary key |
| place_name        | VARCHAR(255) | The form of the name used in this database |
| relative_location | VARCHAR(255) | The `item_name` in the relative_location thesaurus (thesaurus 9), either "Eastern Desert", "Nile Valley", "Western Desert", or the "Levant" |
| latitude          | INT   | The latitude of the place (or of the central point of the region) in decimal degrees north of equator multiplied by 100. Thus 2572 stands for 25.72 N and 25° 43' N. This value is used for sorting the places in a north to south or south to north order.  |
| topbib_id         | VARCHAR(255) | The ID of the place name in the [Digital TopBib](http://topbib.griffith.ox.ac.uk) database, examples: [501-180](http://topbib.griffith.ox.ac.uk//dtb.html?topbib=501-180) and [901-210-003](http://topbib.griffith.ox.ac.uk//dtb.html?topbib=901-210-003) |
| tm_geoid          | INT   | The ID of the place in the Trismegistos Geo database, example: [188](http://www.trismegistos.org/place/188) |
| artefacts_url     | VARCHAR(255) | The URL of the site page in the [Artefacts of Excavation](http://egyptartefacts.griffith.ox.ac.uk) database, example: <http://egyptartefacts.griffith.ox.ac.uk/node/1149> |

### inv_nos (table_id: 25)  
Each record in this table represents an inventory number of an inscribed object in a museum.  
*Equivalent: <http://www.cidoc-crm.org/cidoc-crm/P1_is_identified_by> statement and <http://www.cidoc-crm.org/cidoc-crm/E42_Identifier> entity*   

| Field name        | Type  | Description |
| ---               | :---: | :---        |
| inv_nos_id        | INT   | Unique record ID, primary key |
| inscriptions_id   | INT   | ID of the inscribed object identified by the inventory number |
| collections_id    | INT   | ID of the museum |
| inv_no            | VARCHAR(255) | Inventory number *Equivalent: *<http://www.w3.org/2000/01/rdf-schema#label>* |
| status            | VARCHAR(255) | Status of the inventory number ("main", "alternative", "obsolete", "erroneous") |
| note              | VARCHAR(4000)| General notes related to the inventory number |

### collections (table_id: 26)  
Each record in this table represents a collection containing inscribed objects identified by inventory numbers.  
*Equivalent: <http://www.cidoc-crm.org/cidoc-crm/P1_is_identified_by> statement and <http://www.cidoc-crm.org/cidoc-crm/E42_Identifier> entity*   

| Field name        | Type  | Description |
| ---               | :---: | :---        |
| collections_id    | INT   | Unique record ID, primary key |
| full_name         | VARCHAR(4000) | Official full name of the collection |
| title             | VARCHAR(255) | Short title used in the database |
| location          | VARCHAR(255) | Locality and country |
| url               | VARCHAR(4000)| Official website of the collection (main page) |
| online_collection | VARCHAR(4000)| URL of the online collection |
| tm_coll_id        | INT    | [Trismegistos Collections](http://www.trismegistos.org/coll/index.php) ID  |
| thot_url        | VARCHAR(4000)    | URI of the collection in the [THOT Museums and private collections thesaurus](http://thot.philo.ulg.ac.be/concept/thot-6197) |
| artefacts_url | VARCHAR(4000)| URL of the collection page in the [Artefacts of Excavation](http://egyptartefacts.griffith.ox.ac.uk) database |

![Database structure](database_structure.png)

**Disclaimer: This is a work in progress. The database structure is subject to change before the database itself is published.**  
