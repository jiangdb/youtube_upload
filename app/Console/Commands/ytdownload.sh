#!/bin/bash
#download
#$1 远程文件目录  /A/B
#$2 远程文件名   a.xml  or /C/a.xml
#$3 本地文件目录   /D
#$4 YouTube账户
#$5 YouTube秘钥文件名
if [ $# -ne 5 ]; then
    echo "usage: sh ytdownload.sh [Destination Folder] [Destination File] [Origin Folder] [YouTube account name] [ssh key filename]"
    echo "       [Destination Folder]: / or /A/B"
    echo "       [Destination File]: a.xml or /C/a.xml"
    echo "       [Origin Folder]: / or /D"
    echo "       [YouTube account name]"
    echo "       [ssh key filename]"
    exit 1
fi
if [[ "$1" == "/" ]]; then
    ascp -P 33001 -i ~/.ssh/$5 --src-base=$1 $4@asia.aspera.googleusercontent.com:$1$2 $3
else
    ascp -P 33001 -i ~/.ssh/$5 --src-base=$1 $4@asia.aspera.googleusercontent.com:$1/$2 $3
fi
if [ $? -eq 0 ]; then
	echo $2
else
	echo 1
fi
exit 0
