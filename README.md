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

Results: 

Key is form name
```
Array
(
    [test1] => Field 1 text
    [test2] => Field 2 Text
)
```
Or with a file
```
Array
(
    [test1] => Field 1 text
    [test2] => Field 2 Text
    [files] => Array
        (
            [0] => Array
                (
                    [form_name] => file1
                    [file_name] => 712.png
                    [content-type] => image/png
                    [file] => �PNG


IHDR���>a�9tEXtSoftwareAni...
```