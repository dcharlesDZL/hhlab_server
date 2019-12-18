--[[
	function: 
	author: jim_sun
	wiki: 
]]
local arg = ngx.req.get_uri_args()

--必要的参数检查
if not common_util.check_args(1,arg.openid) then
    common_util.http_return(406,"parm is error")
end


local httpc = http.new()
local url ="http://127.0.0.1/user_operation/user_operation?openid="..arg.openid..'&op='
local res, err = httpc:request_uri(url,{method="POST"})
if nil == res then
    common_util.http_return(406,"parm is error")
end

if 200 ~= res.status then
    common_util.http_return(500,"server is error")
end

local cookie_id = ngx.md5(arg.openid)
local session_data,boo=redis_util.redis_cmd('session',"setex",cookie_id,86400,arg.openid)

if false == boo then
    common_util.http_return(500,"server is error")
else
	common_util.http_return(200,{openid=arg.openid,session_id=cookie_id})
end