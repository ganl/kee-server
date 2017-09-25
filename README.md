# 控制台API

## init

```
cd application
composer install
```
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
    ErrorLog logs/api-error_log
    CustomLog logs/api-access_log common
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

### API response

```
{
  "ret": 200,
  "msg": "",
  "data": {
    "code": "10001104",
    "message": "Account is not exist, or password invalid",
    "returnCode": "10001104",
    "returnMsg": "Account is not exist, or password invalid"
  }
}
```
ret 为Server（http code）状态，可能的值：200， 400，401，404 500
msg http code 产生的可能原因解释

data API返回的具体内容，code 接口错误码，message 接口错误提示描述
returnCode 、 returnMsg 兼容旧版返回的字段，已处理不用关心

正常处理请求，返回结果处理调用 ```[Api_Controller]->success($api_content = array(), $code = 0, $message = null)```

出现异常，需要更改http code可调用 ```error_bad_request```  ```error_unauthorized``` ```created```  ```accepted```  ```error``` …
自定义http code可调用 ```[Api_Controller]->i2_response()```

示例：
```php
    public function token_post()
    {

        //check params and check user info
        $identity = $this->post('username');
        $password = $this->post('pwd');

        if ($this->i2_auth->login($identity, $password)) {
            // Build a new key
            $key = $this->_generate_key();

            // If no key level provided, provide a generic key
            $level = $this->post('level') ? $this->post('level') : 1;
            $ignore_limits = ctype_digit($this->post('ignore_limits')) ? (int)$this->post('ignore_limits') : 1;

            // Insert the new key
            if ($this->_insert_key($key, ['user_id' => $identity, 'level' => $level, 'ignore_limits' => $ignore_limits])) {
                $this->success(array('token' => $key));
            }
        } else {
            $this->success(array(), Err::$errCodes['user.name_or_pwd_invalid'], $this->i2_auth->errors());
        }
    }
```
