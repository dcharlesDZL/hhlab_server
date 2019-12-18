local args = ngx.req.get_uri_args()

local openid = common_util.get_user_openid()
local device_id = safe_util.decrypt_device_id(args.dev_id)

if nil == device_id or "" == device_id  or nil == openid or "" == openid then
    common_util.http_return(406,"parm data is error")
end

local tal_name = common_util.get_devicetable_name(openid)

local sql = "update "..tal_name.." set status=0  where openid="..ngx.quote_sql_str(openid)..
" and device_id="..ngx.quote_sql_str(device_id)
local data,bool = common_util.db_query_common(sql,common_config.db_user_info_addr)
if false == bool then
	--connection:over()
	common_util.http_return(500,"database is error")
end

common_util.http_return(200,"ok")