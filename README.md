# nerv
God's in his Heaven - All's right with the world！

## 特性

- 轻量级的MVCS框架
- IDE友好，所以文件可跟踪定位
- URI, 控制器，文件目录直接对应
- 支持多应用入口

## 安装

- 框架本身并不依赖于composer, 可以直接git clone或下载zip使用
- 也支持通过composer安装 `composer create-project nerv/nerv`
- webserver入口为`app/public`目录

webserver/nginx
```nginxconfig
server {
    listen 80;
    server_name <domain>;
    root app/public;
    index index.php
    location / {
        try_files $uri $uri/ /index.php?$query_string
    }
    location ~ index.php {
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include        fastcgi_params;
    } 
}
```

- 访问`<domain>`，显示`Welcome to nerv.`则安装成功

## 生命周期
- `app/public/index.php`为统一入口，启动并发送响应`boot('app')->run()->send()`
- `boot`自带autoload, 同时兼容composer的autoload
 - `boot('app')`指示启动`app`域下的应用，事实上，我们可以创建多个和`app`同级的应用目录
 - `Boot::run`接收一个`Input`类（没有则基于`$GLOBALS`和`$_SERVER`新建一个），根据`Input->URI`解析并调用contoller中的执行方法，并返回`Output`结果
  - `Boot`将依次尝试执行controller中的`runBefore`, `run`, `runBehind`方法，当`runBefore`有返回时，将终止执行
  - `runBefore`和`run`返回的数据，将被封装入`Output`，待执行`Output::send`时再输出
  - 事实上，我们可以在任意地方通过`boot($domain)->run()`来调用控制器，并且可以自由控制输入和输出，这点在测试时非常有用

## 目录结构

    app/
    |- control/ 控制器
    |- public/ 公共入口
    config/
    core/ 框架核心
    .env 环境变量

## 配置

### 环境变量
- `.env` 为默认环境变量文件
- 当存在`$_SERVER['env']`时，将从`.env.$_SERVER[env]`中读取
- 指定当前运行环境
  - nginx: `fastcgi_params env dev`
  - cli: `env=dev php cmd`
  - phpunit: `<server name="env" value="testing"/>`

### 配置机制
- 配置分为多个`block`，由环境变量`CONFIG_BLOCK`指定优先顺序
- `block`中的`.ini`文件由`parse_ini_file`解析，支持section分段
- 建议只将`default`配置同步到版本库，本地自己保留`locale`或`testing`配置
- 以`config(path/file.section.item, deafult)`获取配置项，路径中请勿使用`.`
  - `path/file`为配置文件相对于`block`的路径
  - `section`, `item` 非必须，例如可通过`config(path/file)`以数组形式获取整个配置文件
- 可通过环境变量`CONFIG_CACHE`控制是否尝试读取缓存，或指定配置缓存路径
- 可通过`cmd/run core:/make-config`生成配置缓存
 
## 路由
 - `URI`中的filepath与control目录层级对应
 - `URI`中basename以`-`分隔，第一部分为controller文件名，剩余部分作为controller`run`方法的参数，当`URI`以`/`结尾时，basename默认为`_`，例：
   - `/` => `control\_.php`
   - `/home/` => `control\home\_.php`
   - `/info` => `control\info.php`


## 命令行

### cmd/run
Usage:    `cmd/run app:$control`

- core
  - `core:/make-config`: make config caceh