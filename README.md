# ValveWhitelist
A php library that can be used to verify client->server connections based on a server IP whitelist. Useful to filter out any fake requests.

## Example usage
```php
require_once("valveWhitelist.php");

//.....

if (ValveWhiteList::verifyIp($_SERVER["REMOTE_ADDR"]) == true) {
    // Legit request, do something...
}
```
