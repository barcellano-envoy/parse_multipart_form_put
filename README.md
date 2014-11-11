PHP File PUT Requests
========================

Package to parse multipart form data from a PUT request. Useful for replacing photos using a RESTful api.

Example:

```php
$put_data = array();
Envoy\MultipartForm\Processer::parse($put_data);
print_r($put_data);
```

Not really tested very much. Let me know if you run into any issues with github issues.