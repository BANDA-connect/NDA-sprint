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

### Download Instruments from REDCap

Run the pull data script:
```
php queryREDCap.php
```
which will create a directory 'cache'. Inside you will find a json file for each instrument.

