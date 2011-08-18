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
