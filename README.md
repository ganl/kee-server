# 控制台API

[说明WiKi](https://code.info2soft.com/web/BE/i2cc/wikis/home)

## Install Dependencies

```
cd application
composer install
```

### 手动安装Composer
下载https://getcomposer.org/composer.phar

在 composer.phar 同目录下,创建 composer.bat :

``C:\composer>echo @php "%~dp0composer.phar" %*>composer.bat```

将composer.bat所在的目录路径加到系统环境变量PATH里，新开命令窗口查看版本:

```
C:\Users\ganl>composer -V
Composer version 1.6-dev (edece864e7e4c668dcad6601df70777882d22116) 2017-09-19 08:42:10
```
遇到错误下面错误请执行：```composer config -g secure-http false```
Your configuration does not allow connection to http://ooxx/xxoo.git. See https://getcomposer.org/doc/06-config.md#secure-http for details.


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
