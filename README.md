## pre-requirement

### imagemagick

    yum install ImageMagick

### firefox

    yum install firefox

### xfs
   
    yum install xfs

### xvfb
    
    yum install Xvfb
    yum install xorg-x11-fonts*
    yum install xorg-x11-server-Xorg

### cron

    yum -y install vixie-cron
    /sbin/service crond start
    /sbin/chkconfig crond on

## Configurations

### configure the site url

In file `system/application/config/config.php` set 

    $config['base_url']     = "http://iq.isocket.com/";

### configure database 

In file `system/application/config/database.php` set

   ```php
$db['default']['hostname'] = "localhost";
$db['default']['username'] = "user";
$db['default']['password'] = "some_pass";
$db['default']['database'] = "db_name";
$db['default']['dbdriver'] = "mysql";
   ```

### configure where to store the screen-shots

In file `system/application/config/image.php` set the directory and make sure the default images exists.

    $config['default_directory'] = '/var/www/imageq/defaults';
    $config['image_directory'] = '/var/www/imageq/images';

configure the list of sizes you need as well.

    $config['sizes']=array();
    $config['sizes']['50_50']= '50_50_default_image_name';
    ...
    

## Overview

This project is made up of two parts

1. The CodeIgniter PHP application that handles web requests
2. The script that actually takes the screenshots at `./screenshot_taker.sh`

### CodeIgniter app

This app is really just an interface to the MySQL database that acts as a queue for all of the pending and completed URLs. The API controller allows a client to retrieve URLs to be processed and mark URLs as having been processed. The image controller acts as GET-only API that will, if necessary, queue an MD5-encoded URL to be processed and return an screenshot file.

### Screenshot script

This script is run on a cron once every minute and polls the CodeIgniter API for any URLs that need to be processed. If so, a screenshot is taken of that URL and a few different sizes are saved to a folder, mounted to S3. When complete, the script hits the CodeIgniter API again to signal that the URL's screenshots have been successfully save to S3.

### Queuing a URL

The following request will queue the encoded URL to be processed and return a screenshot:

    https://<webshot host>/image/full/<MD5 of URL, including protocol>.jpg

### Default screenshots

When a URL is initally queued via the image controller, if a screenshot has not already been taken a [default image](https://github.com/isocket/webshot/tree/master/defaults) will be returned.

### Refreshing screenshots

Appending `/refresh` to a queueing URL will replace the existing screenshot with a new one

    https://<webshot host>/image/full/<MD5 of URL, including protocol>.jpg/refresh
