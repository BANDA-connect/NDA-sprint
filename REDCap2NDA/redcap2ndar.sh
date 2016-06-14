#!/usr/bin/env python

import json, csv
import sys, os.path

if __name__ == "__main__":

    if len(sys.argv) == 4:
        if 'read' == sys.argv[1]:
            w=sys.argv[2]
            if not os.path.isfile(w):
                print("Error: file could not be read %s" % w)
                sys.exit(2)
            with open(w) as data_file:
                data = json.loads(data_file.read())

            mapfname='mapping_keys.json'
            output=sys.argv[3]
            _, type = os.path.splitext(output)

            # define A as REDCap, B as NDA
            if os.path.isfile(mapfname): 
                with open(mapfname) as data_file:
                    mapR2N = json.loads(data_file.read())
            else:
                mapR2N = []

            print ("use the following map now: ")
            print  json.dumps(mapR2N, sort_keys=True, indent=4, separators=(',', ': '))

            # lets go through and create the mapping
            # for each entry in data (array of objects)
            cannotConvert = []
            erg=list()
            usedKeys={}
            for item in data:
                itemstring = json.dumps(item, sort_keys=True, indent=4, separators=(',', ': '))
                # which of the fields can we convert?
                # list of keys from REDCap
                m1=list(map((lambda x: x['nameA']), mapR2N))
                # matching list of keys from NDA
                m2=list(map((lambda x: x['nameB']), mapR2N))
                # list of translators
                ts=list(map((lambda x: "" if not 't' in x else x['t']), mapR2N))
                
                o={}
                for (key, value) in item.items():
                    try:
                        i=m1.index(key)
                        o[m2[i]] = value
                        if m2[i] in usedKeys:
                            # do we have a translator
                            val = value
                            if not ts[i] == "":
                                print("we found a translator %s" % ts[i])
                                # apply it to the current key
                                val = subprocess.check_output(['ts/', ts[i] ," \"", itemstring, "\""])
                                print("got this value \"%s\"" % val)
                            usedKeys[m2[i]] = usedKeys[m2[i]] + 1
                        else:
                            usedKeys[m2[i]] = 0
                    except:
                        cannotConvert.append(key)
                erg.append(o)

            #print("Can convert these keys: \n")
            #print json.dumps(erg, sort_keys=True, indent=4, separators=(',', ': '))
            with open(output, 'w') as csvfile:
                fieldnames = ['ElementName', 'DataType', 'Size', 'Required', 'ElementDescription', 'ValueRange', 'Notes', 'Aliases']
                writer = csv.DictWriter(csvfile, fieldnames=fieldnames)
                # write a row that identifies the dictionary?
                writer.writeheader()
                for value in erg:
                    writer.writerow(value)

            print("Cannot convert these keys (make sure you supply mappings for them): \n")
            print json.dumps(list(set(cannotConvert)), sort_keys=True, indent=4, separators=(',', ': '))
            print("We used keys: \n")
            print json.dumps(usedKeys, sort_keys=True, indent=4, separators=(',', ': '))
            print("Check output file \"%s\" for results" % output)
    else:
        print "Usage: read <name of your instrument file from REDCap> <output file name>"
        print " "
        print "Example: "
        print "    ./redcap2ndar.sh read cache/instrument_bisbas_for_children_score.json output.csv"
    sys.exit(2)