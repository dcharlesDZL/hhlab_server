local _util = {}

_util.callback = function(topic, message)  -- string
    --print("Topic: " .. topic .. ", message: '" .. message .. "'")
    --mqtt_client:publish(args.topic_p, message)
    redis_util.redis_cmd('session',"set","mqtt_message", "t:"..topic.." m:"..message)
end

return _util