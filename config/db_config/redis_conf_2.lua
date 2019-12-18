local redis_conf ={}


redis_conf.host             = "127.0.0.1"     
redis_conf.port             = 6379            
redis_conf.connect_timeout  = 2000          
redis_conf.password         =  nil      

redis_conf.keepalive = {
   pool_size = 300,
   idle_time = 1000000,            -- ms,1000 second
}

redis_conf.db = {
   ["session"]  = 0,   --储存session的数据库
   ["device_key"] = 1, --存储设备key
   ["async"]  = 2,--异步队列
}

return redis_conf
