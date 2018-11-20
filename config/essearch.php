<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-08-24
 * Time: 10:42
 */

return [
    'es_index'  => env('ELASTICSEARCH_INDEX','logos'),
    'es_type'   => env('ELASTICSEARCH_TYPE','logo_info'),
    'es_size'   => env('ELASTICSEARCH_SIZE',4),
    'es_host'   => env('ELASTICSEARCH_HOST','127.0.0.1'),

];