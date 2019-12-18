--[[
    function:提供数据的加密和解密等安全服务
    author:jim_sun
]]

local safe_util = {}

--local device_secret_key = "jim_sun"
--local _aes = aes:new(device_secret_key)

--[[
    对device_id进行加密，
]]
safe_util.encryption_device_id = function (device_id)
    if nil == device_id or "" == device_id then
        return ""
    end
    --return str.to_hex(_aes:encrypt(device_id))
    return  device_id--common_util.str_hex(_aes:encrypt(device_id))
end
--[[
    对加密后的device_id进行解密
]]
safe_util.decrypt_device_id = function (device_id)
    return device_id;
    --if nil == device_id or "" == device_id then
     --   return ""
    --end
   -- device_id = common_util.hex_str(device_id)
   -- return _aes:decrypt(device_id)
end

return safe_util