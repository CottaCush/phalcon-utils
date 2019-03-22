<?php

$loader = new \Phalcon\Loader();

$loader->registerDirs(
    array(
        ROOT_PATH,
        ROOT_PATH . DIRECTORY_SEPARATOR . 'stubs'
    )
);

$loader->registerNamespaces([
    'Tests\Stubs' => ROOT_PATH . DIRECTORY_SEPARATOR . 'stubs'
]);

$loader->register();
