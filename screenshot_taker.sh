#!/bin/bash

api_server="http://screenshot.isocket.com/api" 
path="/tmp"

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
rm -rf $path/temp_275.jpg
rm -rf $path/temp_275.png
rm -rf $path/temp_275b.jpg
rm -rf $path/temp_275b.png

##call the api to get what's next
wget -O $path/temp.url $api_server/next_simple_url

url=`cat $path/temp.url`
base_url=$url

##if there is something to get
if [ "$base_url" != "" ]; then

  echo '* getting '$base_url

  #clean firefox
  killall -9 firefox
  rm -rf /root/.mozilla/firefox/*
  
  echo '** clean'

  #call it again passing the url
  DISPLAY=:5.0 firefox -no-remote -width 900 -height 768 $base_url &

  echo '*** opening page '

  sleep 45
  DISPLAY=:5.0 import -window root $path/temp.png
  
  if [ -f $path/temp.png ]
  then
 
    file_size=$(stat -c%s "$path/temp.png")
 
    echo '**** saved print '$file_size' bytes'

    ## < 10k => blank image 
    if [ $file_size -gt 10000 ]; then

      ##crop the top
      mogrify -crop 885x572+0+115 $path/temp.png

      echo '***** cropped'

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
      cp $path/temp.png $path/temp_275.png
      cp $path/temp.png $path/temp_275b.png
      mogrify -resize 508x345 $path/temp_508.png
      mogrify -resize 268x182 $path/temp_268.png
      mogrify -resize 275x175! $path/temp_275.png
      mogrify -resize 275x165! $path/temp_275b.png


      ##convert them to jpg
      convert $path/temp.png $path/temp.jpg
      convert $path/temp_508.png $path/temp_508.jpg
      convert $path/temp_268.png $path/temp_268.jpg
      convert $path/temp_250.png $path/temp_250.jpg
      convert $path/temp_200.png $path/temp_200.jpg
      convert $path/temp_275.png $path/temp_275.jpg
      convert $path/temp_275b.png $path/temp_275b.jpg
      convert $path/temp_50.png $path/temp_50.jpg

      ##post to the api
      echo '****** OK'
      wget -O /dev/null --post-data="&url=$url&status=200&full=$path/temp.jpg&t50=$path/temp_50.jpg&t508=$path/temp_508.jpg&t268=$path/temp_268.jpg&t250=$path/temp_250.jpg&t200=$path/temp_200.jpg&t275=$path/temp_275.jpg&tt275=$path/temp_275b.jpg" $api_server/completed_no_windows
    else
      echo '****** NOT good'
      wget -O /dev/null --post-data="&url=$url&status=500" $api_server/completed_no_windows
    fi
  fi
fi

echo '******* end'
