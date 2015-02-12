#!/usr/bin/php
<?php
chdir(dirname(__DIR__));
require './vendor/autoload.php';

// Create interpreter
$interpreter = new \GrabQL\Interpreter\Interpreter();

// Execute interpreter
$interpreter->run(array_slice($argv, 1));
