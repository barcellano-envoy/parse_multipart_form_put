<?php
require __DIR__ . '/../vendor/autoload.php';

$put_data = array();
Envoy\MultipartForm\Processer::parse($put_data);
print_r($put_data);

