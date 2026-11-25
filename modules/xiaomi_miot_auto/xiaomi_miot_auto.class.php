<?php
/**
 * Xiaomi Miot Auto v2 - local-first MajorDoMo module.
 * PHP 7.3 compatible, no external runtime dependencies.
 */
class xiaomi_miot_auto extends module
{
    function __construct()
    {
        $this->name = 'xiaomi_miot_auto';
        $this->title = 'Xiaomi Miot Auto v2';
        $this->module_category = '<#LANG_SECTION_SYSTEM#>';
        $this->checkInstalled();
    }

    function getConfig()
    {
        include_once(ROOT . 'config.php');
        $this->config = array();
        $rec = SQLSelectOne("SELECT ID, DATA FROM modules WHERE NAME='" . $this->name . "'");
        $this->module_id = isset($rec['ID']) ? (int)$rec['ID'] : 0;
        if (!empty($rec['DATA'])) { $this->config = @unserialize($rec['DATA']); }
        if (!is_array($this->config)) { $this->config = array(); }
        if (!isset($this->config['ENDPOINT'])) { $this->config['ENDPOINT'] = 'http://127.0.0.1:8123/api/states'; }
        if (!isset($this->config['METHOD'])) { $this->config['METHOD'] = 'GET'; }
        if (!isset($this->config['PAYLOAD'])) { $this->config['PAYLOAD'] = ''; }
    }

    function saveConfig()
    {
        if (!$this->module_id) { $this->getConfig(); }
        if (!$this->module_id) { return; }
        SQLUpdate('modules', array('ID' => $this->module_id, 'DATA' => serialize($this->config)));
    }

    function admin(&$out)
    {
        $this->getConfig();
        foreach (array('ENDPOINT', 'METHOD', 'TOKEN', 'PAYLOAD', 'REGION', 'TRACKING', 'LAST_RESULT') as $k) {
            $out[$k] = isset($this->config[$k]) ? $this->config[$k] : '';
        }
        if ($this->mode == 'update') {
            foreach (array('endpoint', 'method', 'token', 'payload', 'region', 'tracking') as $f) {
                $key = strtoupper($f);
                $this->config[$key] = trim((string)gr($f));
            }
            $method = strtoupper($this->config['METHOD']);
            $this->config['METHOD'] = in_array($method, array('GET', 'POST'), true) ? $method : 'GET';
            $endpoint = $this->config['ENDPOINT'];
            if ($endpoint && !preg_match('/^https?:\/\//i', $endpoint)) { $endpoint = 'http://' . $endpoint; }
            $this->config['ENDPOINT'] = $endpoint;
            $this->saveConfig();
            $this->redirect('?');
        }
    }

    function usual(&$out)
    {
        $this->admin($out);
    }

    function api($params)
    {
        $this->getConfig();
        $op = isset($params['op']) ? (string)$params['op'] : 'fetch';
        if ($op === 'status') {
            return array('status' => 'ok', 'last' => isset($this->config['LAST_RESULT']) ? $this->config['LAST_RESULT'] : '');
        }
        $endpoint = $this->config['ENDPOINT'];
        if (isset($params['region']) && $params['region'] !== '') {
            $endpoint .= '/' . rawurlencode((string)$params['region']);
        }
        $payload = $this->config['PAYLOAD'];
        $payload = str_replace(array('{{token}}', '{{tracking}}', '{{region}}'), array(
            isset($this->config['TOKEN']) ? $this->config['TOKEN'] : '',
            isset($params['tracking']) ? (string)$params['tracking'] : (isset($this->config['TRACKING']) ? $this->config['TRACKING'] : ''),
            isset($params['region']) ? (string)$params['region'] : (isset($this->config['REGION']) ? $this->config['REGION'] : '')
        ), $payload);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        if ($this->config['METHOD'] === 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
        }
        if (!empty($this->config['TOKEN'])) {
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BEARER);
            if (function_exists('curl_set_bearer')) { curl_set_bearer($ch, $this->config['TOKEN']); }
            else { curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->config['TOKEN'])); }
        }
        $body = curl_exec($ch);
        $error = curl_error($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        mjdCurlClose($ch);
        $ok = (!$error && $code >= 200 && $code < 300);
        $result = array('status' => $ok ? 'ok' : 'error', 'http_code' => $code, 'msg' => $error ? $error : ('HTTP ' . $code), 'body' => $body);
        $this->config['LAST_RESULT'] = date('Y-m-d H:i:s') . ' ' . $result['status'] . ': ' . $result['msg'];
        $this->saveConfig();
        return $result;
    }

    function install($data = '') { parent::install(); }
    function dbInstall($data) { return ''; }
}