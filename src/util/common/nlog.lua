--[[
	function: 日志打印工具库
	author: jim_sun
	wiki: 
]]
local nlog = {}
--打印错误日志
nlog.error = function(message)
    local msg = string.char(0x1b) .. "[1;33m" .. ngx.localtime() .. " ERROR ".. message
            .. " \"" ..  ngx.var.request .. "\"" .. string.char(0x1b) .. "[0m\n"
    sock:send(msg)
end
--打印调试日志
nlog.debug = function(message)
    local msg = string.char(0x1b) .. "[1;33m" .. ngx.localtime() .. "debug:" .. message
            .. " \"" ..  ngx.var.request .. "\"" .. string.char(0x1b) .. "[0m\n"
    dsock:send(msg)
end
--打印sql日志
nlog.sql = function(message)
    local msg = string.char(0x1b) .. "[1;33m" .. ngx.localtime() .. "sql:" .. message
            .. " \"" ..  ngx.var.request .. "\"" .. string.char(0x1b) .. "[0m\n"
    dsock:send(msg)
end

return nlog