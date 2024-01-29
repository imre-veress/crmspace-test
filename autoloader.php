<?php

spl_autoload_register(function ($class) {
    $class_path = str_replace('\\', '/', substr($class, 3));

    $file = __DIR__.'/src/'.$class_path.'.php';

    if (file_exists($file)) {
        require $file;
    }
});
