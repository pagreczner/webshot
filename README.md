## pre-requirement

### imagemagick


### firefox

    yum install firefox

### xfs

### xvfb

## Configurations

### configure the site url

In file `system/application/config/config.php` set 

    $config['base_url']     = "http://imageq.isocket.com/";

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

    $config['image_directory'] = '/var/www/imageq/images';
       

    


