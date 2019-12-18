--[[
    func:对用户发送给微信公众平台的消息或触发的事件进行处理回复
    author:jim_sun
]]
local args = ngx.req.get_uri_args()


local response = function(str)
    ngx.header.content_length = string.len(str)
    ngx.print(str)
    ngx.exit(ngx.HTTP_OK)
end

local pic_arr = {
      {
        ["title"] = '远控二代操作说明',
        ["description"] = 'bbbbbbb',
        ["picurl"] ='http://www.moguzn.com/weixin/images/sms.png',
        ["url"] = 'https://mp.weixin.qq.com/s/YQT32aMBIfl9JqG4q7vA1w',
      },
       {
        ["title"] ='如何用微信报警',
        ["description"] = 'yyyyyy',
        ["picurl"] = 'http://www.moguzn.com/weixin/images/wechat.png',
        ["url"] = 'https://mp.weixin.qq.com/s/bCMvaTwdYURDe6zA4PdFsA',
      },
       {
        ["title"] = '如何用群发邮件报警',
        ["description"] = 'yyyyyy',
        ["picurl"] = 'http://www.moguzn.com/weixin/images/email.png',
        ["url"] = 'https://mp.weixin.qq.com/s/C1pwysQd8bvu3grRzBMSwA'
      },
       {
        ["title"] = '用邮件免费短信提醒的方法',
        ["description"] = 'yyyyyy',
        ["picurl"] = 'http://www.moguzn.com/weixin/images/email2.png',
        ["url"] = 'https://mp.weixin.qq.com/s/iSMBP3yyf6VMvxjc4XfCuA'
      },
       {
        ["title"] = '常见问题FAQ',
        ["description"] = 'yyyyyy',
        ["picurl"] = 'http://www.moguzn.com/weixin/images/faq.png',
        ["url"] = 'https://mp.weixin.qq.com/s/wTX_LWnmVE8pagrsIvNq6Q'
      }
}

--回复文本消息
local send_Text = function(toUser, fromUser, str_content) 
    --详见公众号xml文本消息模板
    local xmlContent = '<xml><ToUserName>'..toUser..'</ToUserName>'..
    '<FromUserName>'..fromUser..'</FromUserName>'..
    '<CreateTime>'..ngx.time()..'</CreateTime>'..
    '<MsgType><![CDATA[text]]></MsgType>'..
    '<Content>'.. str_content..'</Content></xml>`'
    response(xmlContent)
end

--回复图文消息
local send_pic_text = function(toUser, fromUser, arr)
    --详见公众号xml文本消息模板
    local xmlContent = '<xml><ToUserName>'..toUser..'</ToUserName>'..
    '<FromUserName>'..fromUser..'</FromUserName>'..
    '<CreateTime>'..ngx.time()..'</CreateTime>'..
    '<MsgType><![CDATA[news]]></MsgType>'..
    '<ArticleCount>'..#arr..'</ArticleCount>'..
    '<Articles>'
    local str = ''
    for i=1,#arr do 
        str = str ..'<item>'..
        '<Title><![CDATA['..arr[i]["title"]..']]></Title>'..
        '<Description><![CDATA['..arr[i]["description"]..']]></Description>'..
        '<PicUrl><![CDATA['..arr[i]["picurl"]..']]></PicUrl>'..
        '<Url><![CDATA['..arr[i]["url"]..']]></Url>'..
        '</item>'
    end
    xmlContent = xmlContent ..str.. '</Articles></xml>'
    response(xmlContent)
end

local get_value = function(str)
   -- str = string.gsub(str, "<!/[CDATA/[", "")
  --  str = string.gsub(str, "/]/]>", "")
    return str
end

ngx.req.read_body()
--获取请求体body数据
local data,is_ok = common_util.get_data()
local xml = require("xmlSimple").newParser()
local parsedXml = xml:ParseXmlText(data)

redis_util.redis_cmd('session',"set", "xml", data)
if parsedXml.xml then
    local MsgType = parsedXml.xml.MsgType:value()
    local ToUserName = get_value(parsedXml.xml.ToUserName:value())
    local openid = get_value(parsedXml.xml.FromUserName:value())
    if string.match(MsgType, "event") then
        local Event= get_value(parsedXml.xml.Event:value())
        if string.match(Event, "subscribe") then 
            send_pic_text(openid, ToUserName, pic_arr)
        elseif string.match(Event, "unsubscribe") then
            send_Text(openid, ToUserName, openid)
        end 

        if  string.match(parsedXml.xml.EventKey:value(), "device_data") then
            send_Text(openid, ToUserName, openid)
        end

        if  string.match(parsedXml.xml.EventKey:value(), "V1001_GOOD") then
            send_pic_text(openid, ToUserName, pic_arr)
        end

    elseif string.match(MsgType, "text") then
        local content = get_value(parsedXml.xml.Content:value())
        if string.match(content, "wxid") then
            send_Text(openid, ToUserName, openid)
        else
            send_Text(openid, ToUserName, "发送wxid获取您在实验平台的微信唯一ID")
        end

    else

    end
end 
--避免显示公众平台服务器错误
response("success")