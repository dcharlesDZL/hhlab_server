mysql_config = require "db_config.db_config"
mysql_ml = require "util.db_util.db_ml" 
mysql = require "resty.mysql"

redis = require "thirty_lib.redis"
redis_util = require "util.db_util.redis_util_2"
redis_conf = require "db_config.redis_conf_2"

cjson = require "cjson" --json解析编码库
cjson_safe = require "cjson.safe"

wechat_util = require "util.wechat.wechat_util"
mqtt_util = require "util.mqtt.mqtt_util"

common_util = require "common_util" --常用工具库

common_config = require "common_config.common_config"--常用配置信息

http = require "http"

ck = require "cookie"


socket = require("socket")


MQTT = require("mqtt_library")

--mqtt_client = MQTT.client.create(common_config.mqtt_config.host, common_config.mqtt_config.port, mqtt_util.callback)
--mqtt_client:connect(common_config.mqtt_config.client_id)

--aes对称加密工具
--aes = require "aes"
--_aes = aes:new("jim_sun")



--安全服务工具类
safe_util = require "util.safe_util.safe_util"

--ck = require "resty.cookie"
--[[
socket = require("socket")
sock = socket.udp() --UDP连接日志服务器，错误日志
sock:setoption("reuseaddr",true) 
sock:setsockname("0.0.0.0",5003)
sock:setpeername("127.0.0.1",5151)

dsocket = require("socket")--UDP连接日志服务器，debug日志
dsock = dsocket.udp()
dsock:setoption("reuseaddr",true)
dsock:setsockname("0.0.0.0",5004)
dsock:setpeername("127.0.0.1",5151)

nlog = require "nlog"--打印日志工具库
]]