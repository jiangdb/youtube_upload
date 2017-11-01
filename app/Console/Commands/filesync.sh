#!/bin/bash

if [ $# -ne 3 ]; then
    echo 0
    exit
fi


videoDir='/home/vagrant/youtube_upload/storage/app/video/'

if [ ! -d "$videoDir$1" ]; then
  mkdir "$videoDir$1"
fi

if [ -d "$videoDir$1" ]; then
   if [ -f "$2" ]; then
      cp -u $2 $videoDir$1/$3
      echo 1
   else
      echo 0
   fi
fi

exit
