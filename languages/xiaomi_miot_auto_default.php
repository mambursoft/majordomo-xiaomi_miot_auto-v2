<?php
$dictionary=array(
'XIAOMI_MIOT_AUTO_ENDPOINT'=>'API endpoint',
'XIAOMI_MIOT_AUTO_METHOD'=>'HTTP method',
'XIAOMI_MIOT_AUTO_TOKEN'=>'Access token',
'XIAOMI_MIOT_AUTO_REGION'=>'Region / route',
'XIAOMI_MIOT_AUTO_TRACKING'=>'Tracking number',
'XIAOMI_MIOT_AUTO_PAYLOAD'=>'POST payload template',
'XIAOMI_MIOT_AUTO_HINT'=>'Local-first module. No data leaves the system until api() is called.',
'XIAOMI_MIOT_AUTO_LAST'=>'Last result'
);
foreach($dictionary as $k=>$v){if(!defined('LANG_'.$k))define('LANG_'.$k,$v);}