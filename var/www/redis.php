<?php
$redis = new Redis();
$redis->connect('172.17.0.1', 6379);
$redis->set('time', time());
echo $redis->get('time');