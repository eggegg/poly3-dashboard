## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

## 安装步骤

```php
- composer install
- cp .env.example .env
- php artisian key:generate
```

设置.env里用来跳转poly的环境变量
APP_URL=
APP_POLY_LOGIN_URL=
APP_POLY_CHECK_URL=

设置.env文件里的数据库,执行数据库的初始化

```php
php artisan migrate
```


生成测试数据
```php
php artisan db:seed
```


All Done!