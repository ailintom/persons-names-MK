# Technical details on the database

This documents various stages of developing the database and the website. These records may be useful for maintainig the website.

## Unicode fonts
The free font Roboto by Google is used to display Egyptian transliteration and all other textual contents in the online database. The hinted version obtainable under [releases](https://github.com/google/roboto/releases) is used, for otherwise the font looks ugly in some browsers (Firefox). The Apache License 2.0 allows modifying the font preserving its name. A subset of the font was produced by [https://transfonter.org/](https://transfonter.org/). Only the following Unicode ranges were included: 0000-052F, 1D80-206F, 2E17, A720-A725. This includes Latin, Greek, Cyrillic, and Egyptian Unicode Transliteration. 

To encode Unicode the following chars are used: 

Ꜣꜣ Jj Ꜥꜥ Ww Bb Pp Ff Mm Nn Rr Hh Ḥḥ Ḫḫ H̱ẖ Ss Šš Qq Kk Gg Tt Ṯṯ Dd Ḏd ⸗ 

