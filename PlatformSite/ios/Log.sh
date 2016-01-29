#!/bin/sh

#---------------Config---------------#

LOG_FILE_PATH=/Users/thomasmeng/Sites/msg_build.txt

#---------------Config---------------#

if [ $# != 1 ];then  
    exit      
fi

LOG_MSG=$1

echo "`date +%Y-%m-%d.%H:%M:%S`" $LOG_MSG >> $LOG_FILE_PATH










