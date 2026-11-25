<?php
$dictionary=array(
'XIAOMI_MIOT_AUTO_ENDPOINT'=>'Адрес API',
'XIAOMI_MIOT_AUTO_METHOD'=>'HTTP-метод',
'XIAOMI_MIOT_AUTO_TOKEN'=>'Токен доступа',
'XIAOMI_MIOT_AUTO_REGION'=>'Регион / маршрут',
'XIAOMI_MIOT_AUTO_TRACKING'=>'Номер отправления',
'XIAOMI_MIOT_AUTO_PAYLOAD'=>'Шаблон POST-запроса',
'XIAOMI_MIOT_AUTO_HINT'=>'Локальный модуль. Данные не отправляются, пока сценарий не вызовет api().',
'XIAOMI_MIOT_AUTO_LAST'=>'Последний результат'
);
foreach($dictionary as $k=>$v){if(!defined('LANG_'.$k))define('LANG_'.$k,$v);}