#!/bin/bash

# this script helps to keep our datastore clean
# because storage is so expensive, we can only keep approx. 1000 notes
# if there are more, we just delete old notes ...

cd /home/keepmesecret/datastore

cnt=0
ls -t | while read line; do 
    cnt=$(($cnt + 1))
#    echo "$cnt -- $line"
    if [ $cnt -gt 1000 ]
    then
#	echo "  del $line";
	rm $line
    fi;
done


