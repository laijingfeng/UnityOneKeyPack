#!/bin/sh  

#---
#tmp_provisioning_profile_name
#---

#---------------Par---------------#
if [ $# != 2 ];then  
    echo "Params error!"  
    echo "Need two params: 1.path of project 2.name of ipa file"  
    exit  
elif [ ! -d $1 ];then  
    echo "The first param is not a dictionary."  
    exit      
fi  

PROJECT_PATH=$1  
IPA_NAME=$2
PROVISIONING_PROFILE_NAME="tmp_provisioning_profile_name"
#---------------Par---------------#

cd $PROJECT_PATH

xcodebuild clean -project Unity-iPhone.xcodeproj -configuration Release -alltargets

xcodebuild archive -project Unity-iPhone.xcodeproj -scheme Unity-iPhone -archivePath Unity-iPhone.xcarchive

xcodebuild -exportArchive -archivePath Unity-iPhone.xcarchive -exportPath ${PROJECT_PATH}/${IPA_NAME}.ipa -exportFormat ipa -exportProvisioningProfile "$PROVISIONING_PROFILE_NAME"
