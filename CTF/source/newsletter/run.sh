#!/bin/bash

cd /home/newsletter
exec java -cp "bin/:ArnoldC.jar:scala.jar" Newsletter 8449
