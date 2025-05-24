<?php

require_once __DIR__ . "/../vendor/autoload.php";

Pickles\Kernel::bootstrap(dirname(__DIR__))
    ->run();
