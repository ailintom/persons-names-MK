# Database structure
The data is stored in a MySQL database. For the sake of compatibility with other relational database management systems the following datatypes are used:
* `CHAR` with varying lengths (standard SQL data type `NATIONAL CHARACTER`) for short attributes;
* `VARCHAR(255)` (standard SQL data type `NATIONAL CHARACTER VARYING (255)`) for short text values;
* `VARCHAR(4000)` (standard SQL data type `NATIONAL CHARACTER VARYING (255)`) for longer text values;
* `INT (11)` (standard SQL data type `INTEGER`) for IDs.
The collation utf8mb4_unicode_ci is used for all CHAR and VARCHAR fields.

Table **publications**
Each record in this table describes a printed or online publication (a bibliographic entry).


| Field name | Type | Description |
| --- | :---: | :--- |
| biblio_entries_id | INTEGER | Unique record ID |
| csl_json | TEXT | Bibliographical data in the CSL-JSON format |
| author_year | VARCHAR(255) | The author-year handle for refererring |
| html_entry | VARCHAR(4000) | Precomposed bibliographical entry in the Chicago Manual of Art Style format (HTML) |
| oeb_id | VARCHAR(4000) | The ID of the corresponding record in the Online Egyptological Bibliography |


