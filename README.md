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

## Latest tests
```
208.64.201.0    succesfully verified
208.64.201.255  succesfully verified
208.64.202.0    succesfully denied
208.64.200.255  succesfully denied
192.69.96.0     succesfully verified
192.69.99.255   succesfully verified
192.69.95.255   succesfully denied
192.69.100.0    succesfully denied
196.38.180.16   succesfully denied
196.38.180.17   succesfully verified
196.38.180.20   succesfully verified
196.38.180.22   succesfully verified
196.38.180.23   succesfully denied
```
