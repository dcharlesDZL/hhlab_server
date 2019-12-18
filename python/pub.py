import paho.mqtt.client as mqtt
#import redis_client # redis client login
# import json
import threading
import time
import requests
import json

import redis
red = redis.Redis(host='localhost',port=6379,db=0)

def send_to_server(topic, message):
    json_data = {"topic":topic,"message":message}
    print("aaaaaa")
    print(json.dumps(json_data))
    r11 = requests.post("http://121.41.16.81/internal/mqtt_sub", data=json.dumps(json_data))
    print(r11.content)

def pop_message(topic):
    message = red.lpop(topic)
    if message:
        return message.decode('ascii') # format message layout
    return message

def on_connect(client, userdata, flags, rc):
    pass
    # print("Connected with result code "+str(rc))

def on_message(client, userdata, msg):
    topic = msg.topic
    message = msg.payload.decode('utf-8')
    print(msg.topic+" "+message)
    send_to_server(topic, message)

def talk():
    client = mqtt.Client()
    client.on_connect = on_connect
    client.on_message = on_message
    client.connect(host="47.98.143.246", port=2568, keepalive=60)
    client.subscribe("test", qos=0)
    # for i in range(redis.llen(topic)):
    # client.loop_start()
    def loop():
        while True:
            msg_a = pop_message("mqtt_subscribe")
            if msg_a :
                print(msg_a)
                client.subscribe(msg_a, qos=0)
            msg_b = pop_message("mqtt_unsubscribe")
            if msg_b :
                print(msg_b)
                client.unsubscribe(msg_a)
            msg_c = pop_message("mqtt_publich")
            if msg_c :
                print(msg_c)
                ms_json = json.loads(msg_c)
                print(ms_json)
                client.publish(ms_json["topic"], payload=ms_json["message"], qos=0)
                #client.unsubscribe(msg_a)
            #time.sleep()

    t1 = threading.Thread(target=loop)
    t1.start()
    print('point')
    client.loop_forever()

    # client.loop_forever()

if __name__ == "__main__":
    talk()


