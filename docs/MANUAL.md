# Manual

1. Install the module and open its admin page.
2. Set the API endpoint and HTTP method. Use `{{token}}`, `{{region}}` and `{{tracking}}` placeholders in the POST payload where supported.
3. Store the token — it is masked in the interface.
4. Call from a scenario:

```php
$m = getModule('xiaomi_miot_auto');
$result = $m->api(array('op' => 'fetch'));
```

The module works locally: no external requests are made until `api()` is called.