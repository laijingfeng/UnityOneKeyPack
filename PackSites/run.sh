#!/bin/sh

#---------------Config---------------#
BUILD_SCRIPT="./ios/build_ios.py"
#---------------Config---------------#

#---------------Par---------------#
if [ $# != 2 ];then  
    echo "Params error!"
    exit      
fi

UPLOAD_FAMILY=$1
UPLOAD_FTP=$2
#---------------Par---------------#

python $BUILD_SCRIPT $UPLOAD_FAMILY $UPLOAD_FTP








