# Jim_blue

#功能描述:为微信公众平台提供网页服务
#运行平台:Ubuntu 14.04

#PHP网页服务器：使用nginx加载管理js、css、img等静态资源，使用php-cgi插件管理解析php文件
#con/nginx.conf:nginx配置文件:
#   静态资源路径等配置
#   php相关配置

#run文件夹:start.bat
#   启动php-cgi服务

#pubic:静态资源存储路径

#主界面:home.php
#查看设备列表界面:list.php
#控制设备列表界面:clist.php
#地图模式显示界面:map.php
#添加/绑定设备界面:newband.php
#历史数据查看显示界面:hlist.php
#php配置查看界面:phpinfo.php