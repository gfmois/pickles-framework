<?php

use Pickles\Routing\Route;

Route::get('/', function () {
    return json(['message' => 'Welcome to Pickles Framework!']);
});