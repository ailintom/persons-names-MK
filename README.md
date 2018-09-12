# Persons and Names of the Middle Kingdom

These are the source files and documentation related to the project 
Persons and Names of the Middle Kingdom  
Author: Alexander Ilin-Tomich (ailintom@uni-mainz.de)  
Johannes Gutenberg University Mainz  
Funded by the Fritz Thyssen Foundation as part of the project
[Umformung und Variabilität im Korpus altägyptischer Personennamen 2055-1550 v.Chr.](https://www.aegyptologie.uni-mainz.de/umformung-und-variabilitaet-1/)  

The project pursues the goal of making the complete dataset available for research. The data and the web interface can freely be republished elsewhere especially if the present website is no longer updated. For this end, the entire dataset is licensed under the Creative Commons Attribution 4.0 International (CC BY 4.0) license, and the source code is published here under the MIT License.

The website runs on an Apache 2.4 server under Debian, using PHP 7.0 and MariaDB 10.1.26 (also tested to work with PHP 7.2 and MariaDB 10.1.32). It uses no custom packages and can run without root privileges (it can basically be deployed on any shared web-hosting).

The database dump is published in an open-access repository at doi: [10.5281/zenodo.1411392](http://dx.doi.org/10.5281/zenodo.1411392). 

To run the online database you should create the file Config.php in the same root folder as index.php.

Config.php should include the following declarations:

```PHP

<?php

namespace PNM;

class Config
{

    const DB_CONFIG = ['host' => 'localhost',
        'port' => 3306,
        'username' => 'user',
        'password' => 'pass',
        'db_prefix' => 'dbver' 
    ];
// db_prefix is the prefix to which the number correspoding to the version of the dataset is added.
// The resulting name should be the valid name of a MySQL Database.
// Suppose your db_prefix is 'dbver', and you have versions 1 and 2 defined in const VERSIONS below. 
// Then you should have databases named dbver1 and dbver2

    const VERSIONS = [[0, "15.04.2018"], [1, "16.04.2018"]]; // The versions of the dataset displayed by the web-interface
    const ROWS_ON_PAGE = 50;
    const MAX_STABLE_URL_LENGTH = 35;
    const FORMAL_PATTERNS_ID = 251658605;
    const SEMANTIC_CLASSES_ID = 251658604;
    const START_PAGE_TEXT = "<p>The online database “Persons and Names of the Middle Kingdom” (PNM) is developed as part of the project <a href='https://www.aegyptologie.uni-mainz.de/umformung-und-variabilitaet-1/'>“Umformung und Variabilität im Korpus altägyptischer Personennamen 2055–1550 v.&nbsp;Chr.”</a>, funded by the <a href='http://www.fritz-thyssen-stiftung.de'>Fritz Thyssen Foundation</a>. The database is currently under development and will include data on Egyptian Middle Kingdom personal names, people, written sources, titles, and dossiers of persons attested in various sources.</p>";
    const IMPRESSUM = <<<EOT
Copyright (c) 2018 Alexander Ilin-Tomich. Content is licensed under <a href="https://creativecommons.org/licenses/by/4.0/" title="Creative Commons Attribution 4.0 International">Creative Commons Attribution 4.0 International (CC BY 4.0)</a> and can be freely reused, except for logos, which are the property of Johannes Gutenberg-Universität Mainz and Fritz Thyssen Foundation.
</p>            
<p>
Using <a href="http://thot.philo.ulg.ac.be/">Thot - Thesauri & Ontology for documenting Ancient Egyptian Resources</a> by the <a href="http://thot.philo.ulg.ac.be/project.html#partners">respective contributors</a>, licensed under  <a href="https://creativecommons.org/licenses/by/4.0/" title="Creative Commons Attribution 4.0 International">CC BY 4.0</a>.
</p>            
<p>
Webdesign by <a href="https://aspectis.net/">Aspectis</a> is licensed under the <a href="https://github.com/aspectis/middle-kingdom-templates/blob/master/LICENSE">MIT License</a>. Source-code available on <a href="https://github.com/aspectis/middle-kingdom-templates">Github</a>.
</p><p>            
PHP scripts and MySQL database by Alexander Ilin-Tomich are licensed under the <a href="https://github.com/ailintom/persons-names-MK/blob/master/LICENSE">MIT License</a>. Source-code available on <a href="https://github.com/ailintom/persons-names-MK">Github</a>.
</p>
<p>
The header image shows a detail of the 11th Dynasty <a href = "https://www.metmuseum.org/art/collection/search/545393">stela Metropolitan Museum of Arts 57.95</a> and is licensed under <a href = "https://creativecommons.org/publicdomain/zero/1.0/">CC0</a>.
</p>
<p>
Using icons from <a href = "https://linearicons.com">Linearicons</a> icon font by Perxis, licensed under <a href = "https://creativecommons.org/licenses/by-sa/4.0/">CC BY-SA 4.0</a>. </p><p>            
Using <a href="https://github.com/google/roboto/releases">Roboto</a> font by Google, licensed under the <a href="https://github.com/google/roboto/blob/master/LICENSE">Apache License</a>.
</p>  
EOT;
    const PRIVACY = <<<EOT
<h2>The PNM does not collect or use your information</h2>
The website Persons and Names of the Middle Kingdom does not knowingly collect, store, or share any personally identifiable information from the visitors.
The website does not use cookies or any other means to track the users.
It does not record internet protocol (IP) addresses, browser type, Internet Service Provider (ISP) of the users in the log files.
Search requests are logged for improving the database functionality without any personally identifiable information.
The website does not include any third-party components to track usage statistics.
<h2>The PNM links to other websites and is not responsible for their practices</h2>
Some pages of the website include links to other websites.
We are not responsible for the practices employed by websites linked to from the website Persons and Names of the Middle Kingdom,
including the information contained therein.
When you use a link to go to another website, our Privacy policy does not apply to third-party websites or services.
Your browsing and interaction on any third-party website or service are subject to that third party’s own rules and policies.
<h2>Personal information you find on the PNM</h2>
That being said, the website does collect, store, and publish personal information on people dwelling in Egypt between 2055 and 1550 BC without their consent.
This includes their names, titles, and family relationships, as recorded on stelae, scarabs, papyri, and in other sources, as well as estimated origin and period when they lived.
<h2>Changes to the Privacy Policy</h2>
If the Privacy Policy changes, alterations will be indicated on this page.
The first public version of the Privacy Policy dates from May 25, 2018. No changes have been made since that date.
EOT;
    const BASE = '/';
    const HOST = 'https://pnm.uni-mainz.de';
}


```

Before publishing an updated database version, the following statements should be executed to update auxiliary tables:
```
CALL name_types_temp_calc;
CALL children_temp_calc;
CALL siblings_temp_calc;
CALL spouses_temp_calc;
```