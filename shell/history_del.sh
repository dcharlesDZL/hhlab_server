host='121.40.189.126'
port=3306
user='jim_sun'
password='123456abc'
database='test'

mysql -h $host -P $port -u $user -p$password -D $database <<EOF

show tables;