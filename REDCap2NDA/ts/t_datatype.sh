#!/bin/bash
#translate field type into DataType

#"field_type":"calc",
#"field_type":"checkbox",
#"field_type":"descriptive",
#"field_type":"notes",
#"field_type":"radio",
#"field_type":"slider",
#"field_type":"text",
#"field_type":"truefalse",
#"field_type":"yesno",

# we will get the whole json object, get one key here
key=`echo "$1" | jq ".field_type"`

case $key in
calc)
    echo "calc"
    ;;
checkbox)
    echo "checkbox"
    ;;
*)
    echo "unknown"
    ;;
esac