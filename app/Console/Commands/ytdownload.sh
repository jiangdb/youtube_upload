#!/bin/bash
#download
#$1 远程文件目录  /A/B
#$2 远程文件名   a.xml  or /C/a.xml
#$3 本地文件目录   /D
if [ $# -ne 3 ]; then
    echo "usage: sh ytdownload.sh [Destination Folder] [Destination File] [Origin Folder]"
    echo "       [Destination Folder]: / or /A/B"
    echo "       [Destination File]: a.xml or /C/a.xml"
    echo "       [Origin Folder]: / or /D"
    exit 1
fi
if [[ "$1" == "/" ]]; then
    ascp -P 33001 -i ~/.ssh/Youtube\ 8sian --src-base=$1 asp-8sian2@asia.aspera.googleusercontent.com:$1$2 $3
else
    ascp -P 33001 -i ~/.ssh/Youtube\ 8sian --src-base=$1 asp-8sian2@asia.aspera.googleusercontent.com:$1/$2 $3
fi
if [ $? -eq 0 ]; then
	echo $2
else
	echo 1
fi
exit 0
