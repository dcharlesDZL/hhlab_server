from redis import StrictRedis as r
# import redis_client
# redis config
HOST = '121.0.0.1'
PORT = 6379
DB = 0
PASSWD = None

# connect redis server
redis = r(HOST, PORT, DB, PASSWD)
