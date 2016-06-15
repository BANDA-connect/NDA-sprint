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

### convert a redcap dictionary into NDAR

The conversion needs a mapping_keys.json file that contains the mappings from redcap to ndar names for data dictionaries. You can apply this transform by:
```
./redcap2ndar.sh read cache/instrument_bisbas_for_children_score.json output

use the following map now: 
[
    {
        "nameA": "field_name",
        "nameB": "ElementName"
    },
    {
        "nameA": "field_type",
        "nameB": "DataType",
        "t": "t_datatype.sh"
    },
    {
        "nameA": "unknown",
        "nameB": "Size"
    },
    {
        "nameA": "unknown",
        "nameB": "Required"
    },
    {
        "nameA": "field_label",
        "nameB": "ElementDescription"
    },
    {
        "nameA": "unknown",
        "nameB": "Required"
    },
    {
        "nameA": "unknown",
        "nameB": "ValidRange"
    },
    {
        "nameA": "select_choices_or_calculations",
        "nameB": "Notes"
    }
]
we found a translator t_datatype.sh
we found a translator t_datatype.sh
we found a translator t_datatype.sh
we found a translator t_datatype.sh
we found a translator t_datatype.sh
we found a translator t_datatype.sh
Cannot convert these keys (make sure you supply mappings for them): 

[
    "field_note",
    "matrix_ranking",
    "field_type",
    "custom_alignment",
    "field_annotation",
    "matrix_group_name",
    "section_header",
    "text_validation_min",
    "branching_logic",
    "question_number",
    "form_name",
    "text_validation_type_or_show_slider_number",
    "identifier",
    "required_field",
    "text_validation_max"
]
We used keys: 

{
    "DataType": 0,
    "ElementDescription": 6,
    "ElementName": 6,
    "Notes": 6
}
Check output file "output.csv" for results

```

## NDA dictionary viewer

The NDA directory contains a viewer component that parses informations from NDA.