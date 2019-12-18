local args = ngx.req.get_uri_args()
ngx.req.read_body()
--获取请求体body数据
local data,is_ok = common_util.get_data()
--必要的参数验证
if not data then
    common_util.http_return(406,"body data is error")
end
local device_id = args.device_id
if nil == device_id or "" == device_id then
    common_util.http_return(406,"parm data is error")
end
 --将body数据放入list中
result,boo=redis_util.redis_cmd('async',"lpush",'history_list_0',data)

if result then
    common_util.http_return(200,{})
end

common_util.http_return(406,"lpush is failed")
