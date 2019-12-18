--[[
	function: 获取session
	author: jim_sun
	wiki: 
]]

local arg = ngx.req.get_uri_args()

--必要的参数检查
if not common_util.check_args(1,arg.cookie) then
    common_util.http_return(406,"parm is error")
end

local cookie = arg.cookie

local session_data,boo=redis_util.redis_cmd('session',"get",cookie)

if session_data then
    common_util.http_return(200,{session=session_data})
else
    common_util.http_return(406,"sesion is null")
end