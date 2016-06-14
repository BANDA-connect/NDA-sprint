## REDCap conversion to NDA data structures

###How to use

You need access to a REDCap system. We provide a role account for this BANDA.

```
url: https://abcd-rc.ucsd.edu/redcap/api/
token: 62618EB58AFE1F480F5505483CFD1FA7
```

Provide this information in your tokens.json file:
```
cp tokens.json_master tokens.json
```
And run the pull data script:
```
php queryREDCap.php
```

This should create a directory structure with the information of each instrument in this project (see cache/.cache*).