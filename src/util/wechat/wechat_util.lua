local _util = {}

--接受的mqtt消息处理路由
_util.message_route = function(topic, message)
    local topic_arr = common_util.string_split(topic, "/")
    --redis_util.redis_cmd('session',"set", "we2", cjson_safe.encode(topic_arr))
    if 3 == #topic_arr then  
        if "123456" == topic_arr[2] then   
            local openid = topic_arr[1]
            local json = wechat_util.message_decode(message)
            --redis_util.redis_cmd('session',"set", "wejsoeen", topic_arr[3])
            if string.find(topic_arr[3] , "appub")then
                --redis_util.redis_cmd('session',"set", "wejson", "se")
                local device_id = json.f
                --redis_util.redis_cmd('session',"set", "dev_id", device_id)
                --redis_util.redis_cmd('session',"set", "we5", json.c)
                if "设备已绑定成功!" == json.c then
                    --处理设备绑定消息
                    --redis_util.redis_cmd('session',"set", "we3", json.c)
                    wechat_util.handle_band_device(openid, device_id)
                end

                if "ack" == json.t then
                   local str = json.c
                   if "string" == type(str) then
                      --设备实时数据处理
                      if string.find(str, "当前数据") then
                          --
                          wechat_util.handle_run_data(openid, device_id, str)
                      end
                   end
                end
                --处理设备报警
                if 23 == tonumber(json.mt) then
                    wechat_util.handle_alarm_data(openid, device_id, json.c)
                end
                
                --if json.c then
                   -- if string.find(json.c, "报警") then
                    --    wechat_util.handle_alarm_data(openid, device_id, json.c)
                    --end 
                --end
            end
        end
    end
end

--处理设备实时数据(mqtt消息)
_util.handle_run_data = function(openid, device_id, str)
    local result_arr = {0,0,0}
    local index = {
        ["温度"] = 1,
        ["湿度"] = 2,
        ["速度"] = 3,
    }
    local name_arr = {}
    for s in string.gmatch(str, "([温]*[湿]*[速]*度)") do 
        table.insert(name_arr, s)                                  
    end
    local value_arr = {}
    for s in string.gmatch(str, "([%d]+[.]*[%d]+)") do 
        table.insert(value_arr, tonumber(s))                                   
    end

    for i=1, #name_arr do
        result_arr[index[name_arr[i]]] = value_arr[i]
    end
    
    local key = "dev_data_" .. ngx.md5(openid .. device_id)
    local cookie_data,boo=redis_util.redis_cmd('session',"set", key, cjson_safe.encode(result_arr))
end

--处理设备绑定(mqtt消息)
_util.handle_band_device = function(openid, device_id)
    local key = "band_key_" .. ngx.md5(openid .. device_id)
    local device_name,boo = redis_util.redis_cmd('session', "get", key)
    if device_name then
        local data = {
            ["name"] = device_name,
            ["device_id"] = device_id,
            ["openid"] = openid,
        }
        local res = ngx.location.capture("/device/device_operation",
        {
            args = {
                op=1
            },
            method = ngx.HTTP_POST,
            body = cjson.encode(data)
        })
        if 200 == res.status then
            redis_util.redis_cmd('session',"set", key, "true")
        end
    end
    --result,boo=redis_util.redis_cmd('device_key',"setex",device_key,86400,ngx.time())
end

--处理设备报警消息
_util.handle_alarm_data = function(openid, device_id, str)
    --报警信息
    local content = str
    local sql = "select name,status from device_info_0 where openid="..ngx.quote_sql_str(openid).." and device_id=".. ngx.quote_sql_str(device_id)
    local data,bool = common_util.db_query_common(sql,common_config.db_user_info_addr)
	if false == bool then
		return false, nil
    end
    if "table" ==type(data) then
        if 0 < #data then
            --status为2,说明该设备已设备为微信报警
            if 2 == tonumber(data[1]["status"]) then
               return wechat_util.template_message(openid, device_id, data[1]["name"], content)
            end
        end
    end
    return false, nil
end

--对发送的mqtt消息编码
_util.data_encode = function(data)
    local str = cjson.encode(data)
    str = ngx.encode_base64(str)
    return string.sub(str, 1, 1)..str
end

--对mqtt消息解码
_util.message_decode = function(message)
    local str = string.sub(message, 1, 1) .. string.sub(message, 3, string.len(message))
    str = string.gsub(str, "\n", "")
    return cjson.decode(ngx.decode_base64(str))
end

--发布mqtt主题消息
_util.publish = function(topic, message)
    local tal = {
        ["topic"] = topic,
        ["message"] = message,
    }
    local result,boo = redis_util.redis_cmd('session',"lpush", "mqtt_publich", cjson.encode(tal))
 
end

--订阅mqtt主题
_util.subscribe = function(topic)
    local result,boo = redis_util.redis_cmd('session',"lpush", "mqtt_subscribe",topic)
end

--取消订阅mqtt主题
_util.unsubscribe = function(topic)
    local result,boo = redis_util.redis_cmd('session',"lpush", "mqtt_unsubscribe",topic)
end

--推送微信模板消息
_util.template_message = function(openid, device_id, device_name, content)
    local dd = os.date("*t", ngx.time()) 
    local alarmDate = dd.year .."年 "..dd.month.."月 "..dd.day.."日 "..dd.hour.."时 "..dd.min.."分 "..dd.sec.."秒"
    local templatePush={ 
        ["touser"]=openid, 
        ["template_id"]="ban2SRoIEZmSP9iDUzTcIXtcTDc6Nd-Oz4L8U2vHYN0", 
        ["url"] = "http://php.yckz003.top/history1.php?id="..device_id,
        ["topcolor"] = "#FF0000", 
        ["data"] = { 
               ["first"]={
                   ["value"]=alarmDate,
                   ["color"]="#173177",
                },
                ["keyword1"] = { 
                    ["value"]=device_id, 
                    ["color"]="#173177",
                }, 
                ["keyword2"]={ 
                    ["value"]=device_name, 
                    ["color"]="#173177",
                },
                ["keyword3"]={ 
                    ["value"]=content, 
                    ["color"]="#173177",
                },
                ["remark"]={
                   ["value"]="",
                   ["color"]="#173177",
               }
        } 
     };
    local res = ngx.location.capture("/proxy/wechat_api/template",
    {
        args = {
            ["access_token"] = wechat_util.get_wechat_accesstoken()
        },
        method = ngx.HTTP_POST,
        body=cjson.encode(templatePush)
    })
    if 200 == res.status then
        local res_json = cjson.decode(res.body)
        if "ok" == res_json.errmsg then
            return true, res.body
        end
    end
    return false, res.body
end

--获取微信accesstoken
_util.get_wechat_accesstoken = function()
    local _data,boo=redis_util.redis_cmd('session',"get","root_wechat_access_token")
    return _data
end

return _util