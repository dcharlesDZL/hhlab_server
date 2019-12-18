--[[
	function: 常用配置信息
	author: jim_sun
	wiki: 
]]
local config = {}

config.openid_session_id = 'open_sesion_id'
config.openid_cookie_domain = 'hhlab.cn'
config.openid_cookie_path = '/'
config.appId ='wxa690edcf92ee8f2e'
config.appSecret ='7d4c2f2b7d014983cf1db8c9f34ac1e1'

--存放用户信息的数据库中间代理层地址
config.db_user_info_addr = '127.0.0.1:80'

--数据库ip集
config.db_ip_config = {
    ["0"] = "127.0.0.1:80",
    ["1"] = "127.0.0.1:80",
    ["2"] = "127.0.0.1:80",
    ["3"] = "127.0.0.1:80",
    ["4"] = "127.0.0.1:80",
    ["5"] = "127.0.0.1:80",
    ["6"] = "127.0.0.1:80",
    ["7"] = "127.0.0.1:80",
    ["8"] = "127.0.0.1:80",
    ["9"] = "127.0.0.1:80",
    ["a"] = "127.0.0.1:80",
    ["b"] = "127.0.0.1:80",
    ["c"] = "127.0.0.1:80",
    ["d"] = "127.0.0.1:80",
    ["e"] = "127.0.0.1:80",
    ["f"] = "127.0.0.1:80",
}

config.wechat_config = {
    ['wxoauth'] = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa690edcf92ee8f2e&redirect_uri=http://a3050311118.gicp.net/oauth2.php&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect',
    ['token'] = 'wechat',
    ['appid'] = 'wxa690edcf92ee8f2e',
    ['appsecret'] = '7d4c2f2b7d014983cf1db8c9f34ac1e1', 
    ['encodingAESKey'] = 'SmU0TqkFTpVeY2yvotC8pfQBUynY3FTV8vXxiSrsTeo',
    ['customUrl'] = '/cgi-bin/message/custom/send?access_token=',
    ['templateUrl'] = '/cgi-bin/message/template/send?access_token='
}

config.mqtt_config = {
    ["host"] = "47.98.143.246",
    ["port"] = 2568,
    ["client_id"] = "lua_server_007",
}

return config