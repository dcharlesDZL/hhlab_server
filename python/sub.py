
import json
import requests
json_data = {"topic":"omB5-1uo8_AXhnc_4R3h4Hr6IhB8/123456/appub","message":"ejyJtdCI6MSwiZiI6IkNMRjAyMkMzQUU4NEJGN0Y2IiwiYyI6IuiuvuWkh+W3sue7keWumuaIkOWKnyEifQ=="}
r11 = requests.post("http://121.41.16.81/internal/mqtt_sub", data=json.dumps(json_data))
print(r11.content)



