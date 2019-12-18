local args = ngx.req.get_uri_args()

local set_type = args.set_type
local openid = common_util.get_user_openid()
local set_value = tonumber(args.set_value)
local device_id = args.dev_id

--必要的参数检查
if nil == set_type or "" == set_type  or "string" ~= type(device_id) or nil == openid or "" == openid then
    common_util.http_return(406,"parm data is error"..openid)
end

--发送mqtt设置指令
local send_command = function(commond)
    local commond_index = {
        ["run_time"] = "设置时间",
        ["wd"] = "设置温度",
        ["sd"] = "设置湿度",
        ["ud"] = "设置速度",
    }
    local tal ={
        ["t"] = "txt",
        ["i"] = string.sub(openid, 1, 8),
        ["mid"] = ngx.time(),
        ["c"] = commond_index[commond]..set_value,
    }
    local pub_topic = openid.."/"..device_id.."/123456/apsub"
    wechat_util.publish(pub_topic, wechat_util.data_encode(tal))
end

local wechat_alram = function()
    local tal_name = common_util.get_devicetable_name(openid)

    local sql = "update "..tal_name.." set status=2  where openid="..ngx.quote_sql_str(openid)..
    " and device_id="..ngx.quote_sql_str(device_id)
    local data,bool = common_util.db_query_common(sql,common_config.db_user_info_addr)
    if false == bool then
        common_util.http_return(500,"database is error")
    end
    common_util.http_return(200,"设置微信报警成功")
end

local cancel_alarm = function()
    local tal_name = common_util.get_devicetable_name(openid)

    local sql = "update "..tal_name.." set status=1  where openid="..ngx.quote_sql_str(openid)..
    " and device_id="..ngx.quote_sql_str(device_id)
    local data,bool = common_util.db_query_common(sql,common_config.db_user_info_addr)
    if false == bool then
        common_util.http_return(500,"database is error")
    end
    common_util.http_return(200,"取消微信报警成功")
end

if "wechat_alram" == set_type then
    wechat_alram()
elseif "cancel_alarm" == set_type then
    cancel_alarm()
end
send_command(set_type)