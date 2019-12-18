--[[
	function: 通用工具库
	author: jim_sun
	wiki: 
]]

local _util = {}

--检查参数是否存在或为""
function _util.check_args(n,...)
    local len = #{...}
    if n ~= len then
        return false
    end
    for i=1,n do
        local v = ({...})[i]
        if "string" == type(v) and "" == v then
            return false
        end
    end
    return true
end
--获取客户端真实ip
function _util.get_ip()
    if ngx.req.get_headers()["X-IS-CLIENT-IP"] then
        return ngx.req.get_headers()["X-IS-CLIENT-IP"]
    end
    if ngx.var.http_x_forwarded_for then -- 代理服务器添加，为连接该代理的客户端真实IP，remote_addr可能为代理机器的IP
        local array, ip = {}, nil
        for ip in string.gmatch(ngx.var.http_x_forwarded_for..",", "[:%x%.]+") do
            table.insert(array, ip)
        end
        return array[#array - 1] or array[1]
    end
    return ngx.req.get_headers()["X-Real-IP"] or ngx.var.remote_addr or ""
end

--将table转化为key1=value1,key2=value2...形式
function _util.str_joint(tal,sp_parm)
    local sp = sp_parm or ','
    local str = ''
    for k,v in pairs(tal) do
        if "string" == type(v) then
            --防sql注入
            str = str..k.."="..ngx.quote_sql_str(v)
        elseif "number" == type(v) then
            str = str..k.."="..v
        else
            str = str.."null"
        end
        str = str..sp
    end
    return string.sub(str, 1,#str-1)
end
--获取http请求body内容
function _util.get_data()
    local data = ngx.req.get_body_data()

    if nil == data then
        local fname = ngx.req.get_body_file()
        if nil == fname then
            return nil, false
        end

        local fp, err = io.open(fname, "rb")
        if nil == fp then
            return nil, false
        end
        data = fp:read "*a"
        fp:close()
    end
    
    if nil == data then
        return nil, false
    end

    return data, true
end

--http返回(status:返回状态码,body：返回主体内容，is_empty：是否返回空数组)
function _util.http_return(status , body , is_empty)
    local is_empty_boo = is_empty or false
    local resp = {}
    if 200 == status then
        if false == is_empty_boo then
            resp["status"] = 1 --为1表示正确返回
            local temp = nil
            if body then
                if "table" == type(body) then
                    resp["data"] = body
                elseif "string" == type(body)  then  
                    temp = cjson_safe.decode(body)
                    if temp then
                        resp["data"] = temp
                    else
                        resp["data"] = body
                    end
                end
            end
        else
            resp = body
        end
    else
        resp["status"] = 0 --为0表示非正确返回
        resp["message"] = body
    end
    ngx.header["Access-Control-Allow-Origin"] = "*"
	
    local str =nil
    --[[
	if is_empty_boso then
	    str= cjson.encode_empty_table_as_array(resp) 
	else
		str = cjson.encode(resp) or ""
    end
    ]]
    str = cjson.encode(resp) or ""
	ngx.header.Content_Type = "text/plain;charset=utf-8"
	ngx.header.Content_Length = string.len(str)
	ngx.status = status
	if str then ngx.print(str) end
	ngx.exit(status)
end

--返回用户openid所在的设备信息数据库表
function _util.get_devicetable_name(openid)
    if nil == openid and '' == openid then
        return nil
    end
    local md5_str = ngx.md5(openid)
    return 'device_info_0'
    --return 'device_info_'..string.sub(md5_str,-1,-1)
end

--返回用户openid所在的用户信息数据库表
function _util.get_usertable_name(openid)
    return "user_info"
end

--获取用户openid
function _util.get_user_openid()
    local openid_cookie = common_util.get_cookie_value("openid_cookie")
    local session_data,boo=redis_util.redis_cmd('session',"get",openid_cookie)

    if session_data then
        return session_data
    else
        return nil
    end
end

--获取指定cookie value
function _util.get_cookie_value(session_id)
    local cookie, err = ck:new()
    if not cookie  or nil == session_id or '' == session_id then
        return nil,false
    end
    local field, err = cookie:get(session_id)
    if not field then
        return nil,false
    end
    return field,true
end

function _util.string_split(str,pattern)
    local sub_str = {}
    while (true) do
        local i,j = string.find(str,pattern,1,true)
        if nil == i then
            sub_str[#sub_str+1] = str
            break;
        end
        local s = string.sub(str,1,i-1)
        sub_str[#sub_str+1] = s
        str = string.sub(str,j+1,#str)
    end
    return sub_str
end
--[[
    function:mysql数据库sql执行函数
]]
function _util.db_query_common(sql,ad_addr,is_lock)
    if ("string" ~= type(sql) and "table" ~= type(sql)) or 'string' ~= type(ad_addr) 
        or "" == sql then
        return nil,nil
    end
    local url ="http://"..ad_addr.."/proxsy/db_proxsy/query_common"
    local tal = {
        ["sql"] = sql
    }
    local httpc = http.new()
    local res, err = httpc:request_uri(url,{method="POST",body = cjson_safe.encode(tal)})
    if 200 ~= res.status then
        return nil,false 
    end
    return cjson_safe.decode(res.body),true
end
--将16进制串转换为字符串
local h2b = {
    ["0"] = 0,
    ["1"] = 1,
    ["2"] = 2,
    ["3"] = 3,
    ["4"] = 4,
    ["5"] = 5,
    ["6"] = 6,
    ["7"] = 7,
    ["8"] = 8,
    ["9"] = 9,
    ["A"] = 10,
    ["B"] = 11,
    ["C"] = 12,
    ["D"] = 13,
    ["E"] = 14,
    ["F"] = 15
}

_util.hex_str = function(hexstr)
    local s = string.gsub(hexstr,"(.)(.)",function ( h, l ) return string.char(h2b[h]*16+h2b[l]) end)
    return s
end
--将字符串按格式转为16进制串
_util.str_hex = function(s)
	s=string.gsub(s,"(.)",function (x) return string.format("%02X",string.byte(x)) end)
    return s
end

return _util

