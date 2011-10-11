#!/bin/bash

api_server="http://localhost/api" 
path="/usr/local/screenshot"

##clean everything
rm -rf $path/temp.url
rm -rf $path/temp.png
rm -rf $path/temp.jpg
rm -rf $path/temp_508.jpg
rm -rf $path/temp_508.png
rm -rf $path/temp_268.jpg
rm -rf $path/temp_268.png
rm -rf $path/temp_250.jpg
rm -rf $path/temp_250.png
rm -rf $path/temp_200.jpg
rm -rf $path/temp_200.png
rm -rf $path/temp_50.jpg
rm -rf $path/temp_50.png

##call the api to get what's next
wget -O $path/temp.url $api_server/next_simple_url

url=`cat $path/temp.url`
base_url=$url

##if there is something to get
if [ "$base_url" != "" ]; then

  ##fixing lack of http
  if [[ $base_url != http* ]]; then
     base_url="http://"$base_url
  fi

  ##get the screenshot 
  killall -9 Xvfb
  killall -9 firefox
  rm -rf /tmp/.X5-lock
  rm -rf /root/.mozilla/firefox/*
  Xvfb :5 -screen 0 1024x768x24 &
  DISPLAY=:5.0 firefox -no-remote -width 900 -height 768 -extension GLX $url &
  sleep 15
  DISPLAY=:5.0 import -window root $path/temp.png
   
  file_size=$(stat -c%s "$path/temp.png")

  ## < 10k => blank image 
  if [ $file_size -gt 10000 ]; then

    ##crop the top
    mogrify -crop 885x587+0+157 $path/temp.png

    ##create the squared ones
    cp $path/temp.png $path/temp_250.png
    cp $path/temp.png $path/temp_200.png
    cp $path/temp.png $path/temp_50.png
    mogrify -resize 250x250 $path/temp_250.png
    mogrify -resize 200x200 $path/temp_200.png
    mogrify -resize 50x50 $path/temp_50.png

    ##create the rectangular ones
    mogrify -crop 1024x768+0+0 $path/temp.png
    cp $path/temp.png $path/temp_508.png
    cp $path/temp.png $path/temp_268.png
    mogrify -resize 508x345 $path/temp_508.png
    mogrify -resize 268x182 $path/temp_268.png

    ##convert them to jpg
    convert $path/temp.png $path/temp.jpg
    convert $path/temp_508.png $path/temp_508.jpg
    convert $path/temp_268.png $path/temp_268.jpg
    convert $path/temp_250.png $path/temp_250.jpg
    convert $path/temp_200.png $path/temp_200.jpg
    convert $path/temp_50.png $path/temp_50.jpg

    ##post to the api
    wget -O /dev/null --post-data="&url=$url&status=200&full=$path/temp.jpg&t50=$path/temp_50.jpg&t508=$path/temp_508.jpg&t268=$path/temp_268.jpg&t250=$path/temp_250.jpg&t200=$path/temp_200.jpg" $api_server/completed_no_windows
  else
    wget -O /dev/null --post-data="&url=$url&status=500" $api_server/completed_no_windows
  fi
fi

killall -9 Xvfb
killall -9 firefox



