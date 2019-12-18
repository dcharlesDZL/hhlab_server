import paho.mqtt.client as mqtt
import thread

import redis_client # redis client login
redis = redis_client.redis

topic = 'emqtt11' # topic

def pop_message(topic):
    
    message = redis.lpop(topic)
    return str(message) # format message layout

def on_connect(client, userdata, flags, rc): # connect
  pass
  # print("Connected with result code "+str(rc))

def on_message(client, userdata, msg): # on connect to process message
  print(msg.payload) # print message
  # print(msg.topic+" "+str(msg.payload)) # print topic and message

def print_time( threadName, delay):
   count = 0
   while count < 5:
      time.sleep(delay)
      count += 1
      print ("3e3")


def main():
    client = mqtt.Client() 
    client.on_connect = on_connect
    client.on_message = on_message
    client.connect(host="47.98.143.246", port=2568, keepalive=60)
    client.subscribe(topic, qos=0) # subscribe
    try:
        thread.start_new_thread( print_time, ("Thread-1", 2, ) )
        thread.start_new_thread( print_time, ("Thread-2", 4, ) )
    except:
       print ("Error: unable to start thread")
    client.loop_forever()

if __name__ == "__main__":
    main()
    
