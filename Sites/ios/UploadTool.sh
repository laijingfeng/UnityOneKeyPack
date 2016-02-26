#!/bin/sh

if [ $# != 2 ];then  
    echo "Params error!"
	echo "Need two params: isDesPathFamily(T or F) fileName"	
    exit      
fi

DES_PATH_IS_FAMILY=$1  
FILE_NAME=$2

function fileserverCopy() {
    SMB=`df -h | grep ftp_address/ipa`
    echo "$SMB"
    # 如果共享连接了，那么拷贝到文件服务器
    if [ -n "$SMB" ]; then
		echo "link ftp_address/ipa success"
		cp $FILE_NAME /Volumes/ipa/auto_build/
    else
        echo "can not link to ftp_address/ipa"
        return 1
    fi    
}   

function familyFtpUpload() {
    local ftpCmd="put $FILE_NAME"
    # 能连上family的内网ftp，必须有机房vpn连接
    ping -c 3 -t 3 family_address
    if [ $? -eq 0 ]; then
        lftp << EOF
        open family_address
        user family_user family_password
        cd testing/rexue
        $ftpCmd
        bye
EOF
    else
        return 1
    fi
}

if [ "$DES_PATH_IS_FAMILY" = "T" ]; then
	familyFtpUpload
elif [ "$DES_PATH_IS_FAMILY" = "F" ]; then
	fileserverCopy
else
	echo "First param should be T or F"
fi









