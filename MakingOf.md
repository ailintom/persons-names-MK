# Technical details on the database

This documents various stages of developing the database and the website. These records may be useful for maintainig the website.

## Unicode fonts
The free font Roboto by Google is used to display Egyptian transliteration and all other textual contents in the online database. The hinted version obtainable under [Releases](https://github.com/google/roboto/releases) is used, for otherwise the font looks ugly in some browsers (Firefox). The Apache License 2.0 allows modifying the font preserving its name. A subset of the font was produced by [https://transfonter.org/](https://transfonter.org/). Only the following Unicode ranges were included: 0000-052F, 1D80-206F, 2E17, A720-A725. This includes Latin, Greek, Cyrillic, and Egyptian Unicode Transliteration. 

To encode Egyptian transliteration, the following Unicode chars are used: 

Ꜣꜣ Jj Ꜥꜥ Ww Bb Pp Ff Mm Nn Rr Hh Ḥḥ Ḫḫ H̱ẖ Ss Šš Qq Kk Gg Tt Ṯṯ Dd Ḏd ⸗ .

## Bibliography
Bibliography is produced with [citeproc-js-server](https://github.com/zotero/citeproc-js-server) using a custom [CSL chicago-author-date-initials.csl](https://github.com/ailintom/persons-names-MK/blob/master/chicago-author-date-initials.csl). It retains the case of bibliographic descriptions (for it is impossible to properly capitalise titles when an English article appears in a non-English journal/edited volume or vice versa.

In order to produce a bibliography with citeproc-js-server, one has to generate a JSON file with all bibliographical entries and with a citations cluster including all bibliographical entries and POST it to citeproc-js-server.
Sample data (forexport.json): 
```
{"items": [{"id": "16797526", "title": "Ecritures de l'Égypte ancienne", "type": "book", "editor": [{"family": "De Meulenaere", "given": "H."}],"issued": {"date-parts": [["1992"]]},"publisher-place": "Brussels", "publisher": "Musées royaux d'art et d'histoire", "collection-title": "Guides du Département égyptien","collection-number": "7","language": "fr"}, {"id": "16796217", "title": "Sealings", "type": "chapter", "author": [{"family": "Manzo", "given": "A."},{"family": "Pirelli", "given": "R."}],"container-title": "Harbor of the pharaohs to the Land of Punt: archaeological investigations at Mersa/Wadi Gawasis, Egypt, 2001-2005", "issued": {"date-parts": [["2007"]]},"page": "232-237", "publisher-place": "Naples", "editor": [{"family": "Bard", "given": "K. A."},{"family": "Fattovich", "given": "R."}]}, {"id": "16798045", "title": "The Sealings from Marsa Gawasis (sꜣw): Preliminary Considerations on the Administration of the Port", "type": "article-journal", "author": [{"family": "Manzo", "given": "A."},{"family": "Pirelli", "given": "R."}],"container-title": "Abgadiyat", "issued": {"date-parts": [["2016"]]},"volume": "11", "page": "92-126", "language": "en"}, {"id": "16797529", "title": "The symbolic world of Egyptian amulets: from the Jacques-Édouard Berger collection", "type": "book", "author": [{"family": "Germond", "given": "P."}],"issued": {"date-parts": [["2005"]]},"publisher-place": "Milan", "publisher": "5 Continents", "language": "fr"}],"citationClusters":[{"citationItems":[{"id": "16797526", "locator": "16797526"}, {"id": "16796217", "locator": "16796217"}, {"id": "16798045", "locator": "16798045"}, {"id": "16797529", "locator": "16797529"}],"properties":{"noteIndex":1}}]}
```

Posting the data:
```
curl --header "Content-Type: application/json; charset=utf-8" --request POST --data-binary @forexport.json "http://127.0.0.1:8085/?bibliography=1&linkwrap=1&citations=1&responseformat=json&style=chicago-author-date" 
```
The resulting JSON will contain the full bibliography; one can get shortened citations in the Author-Year style when one adds a second citation cluster to the JSON posted to citeproc-js-server.


## Egyptian hieroglyphs
Spellings are stored in the database as JSesh-compatible MdC codes. PNG files with transparent background are produced using [JSesh-Web](https://github.com/macleginn/jsesh-web) or a similar own Java program for batch producing graphical files using [JSesh](http://jsesh.qenherkhopeshef.org/).

