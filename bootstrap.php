<?php

require 'libs/ClassLoader.php';

$loader = new ClassLoader();
$loader->registerDir(dirname(__FILE__).'/libs');
$loader->registerDir(dirname(__FILE__).'/models');
$loader->register();
