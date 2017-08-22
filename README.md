# 控制台API

## add listen port and VirtualHost 

```
Listen 80
Listen 58080
```

### Apache 2.2

```
<VirtualHost *:80> # * , _default_
    ServerAdmin info@info2soft.com
    DocumentRoot "/var/www/html/public"
    ServerName dev.i2
    ErrorLog logs/pms-error_log
    CustomLog logs/pms-access_log common
  <Directory "/var/www/html/public">
    Options FollowSymLinks
    AllowOverride All
    Order allow,deny
    Allow from all
    DirectoryIndex index.html index.php
  </Directory>
</VirtualHost>
```

### Apache 2.4

```
<VirtualHost *:80>
  ServerAdmin admin@example.com
  DocumentRoot "${vhostdir}" #/var/www/html/public
  ServerName ${domain}
  ServerAlias ${Apache_Domain_alias}
  <Directory "${vhostdir}">
    SetOutputFilter DEFLATE
    Options FollowSymLinks ExecCGI
    Require all granted
    AllowOverride All
    Order allow,deny
    Allow from all
    DirectoryIndex index.html index.php
  </Directory>
</VirtualHost>
```