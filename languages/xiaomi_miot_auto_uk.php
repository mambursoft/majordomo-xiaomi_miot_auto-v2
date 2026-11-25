<?php
$dictionary=array(
'XIAOMI_MIOT_AUTO_ENDPOINT'=>'Адреса API',
'XIAOMI_MIOT_AUTO_METHOD'=>'HTTP-метод',
'XIAOMI_MIOT_AUTO_TOKEN'=>'Токен доступу',
'XIAOMI_MIOT_AUTO_REGION'=>'Регіон / маршрут',
'XIAOMI_MIOT_AUTO_TRACKING'=>'Номер відправлення',
'XIAOMI_MIOT_AUTO_PAYLOAD'=>'Шаблон POST-запиту',
'XIAOMI_MIOT_AUTO_HINT'=>'Локальний модуль. Дані не надсилаються, доки сценарій не викличе api().',
'XIAOMI_MIOT_AUTO_LAST'=>'Останній результат'
);
foreach($dictionary as $k=>$v){if(!defined('LANG_'.$k))define('LANG_'.$k,$v);}