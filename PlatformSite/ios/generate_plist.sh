#!/bin/sh

#---
#tmp_bundle_id
#---

#---------------Config---------------#
SCRIPT_PATH=/Users/thomasmeng/Sites/ios
#---------------Config---------------#

#---------------Par---------------#
if [ $# != 1 ];then  
    echo "Params error!"
	echo "Need PACK_NAME"	
    exit      
fi

PACK_NAME=$1
VERSION="1.0"
BUNDLE_ID="tmp_bundle_id"
#---------------Par---------------#

FILE_NAME=$SCRIPT_PATH/"hotblood_ios_test_${PACK_NAME}.plist"
TMP_FILE_NAME=$SCRIPT_PATH/"tmp.plist"

cp $SCRIPT_PATH/hotgame.plist.template $FILE_NAME

sed "s/{VERSION}/${VERSION}/g" ${FILE_NAME} > ${TMP_FILE_NAME}
rm ${FILE_NAME}
mv ${TMP_FILE_NAME} ${FILE_NAME}

sed "s/{PACK_NAME}/${PACK_NAME}/g" ${FILE_NAME} > ${TMP_FILE_NAME}
rm ${FILE_NAME}
mv ${TMP_FILE_NAME} ${FILE_NAME}

sed "s/{BUNDLE_ID}/${BUNDLE_ID}/g" ${FILE_NAME} > ${TMP_FILE_NAME}
rm ${FILE_NAME}
mv ${TMP_FILE_NAME} ${FILE_NAME}






