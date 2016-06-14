#!/usr/bin/env python

import json
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
                    map = json.loads(data_file.read())
            else:
                map = []
            print ("use the following map now: ")
            print  json.dumps(map, sort_keys=True, indent=4, separators=(',', ': '))

            # lets go through and create the mapping
            # for each entry in data

    else:
        print "Usage: read <name of your instrument file from REDCap> <output file name>"
    sys.exit(2)