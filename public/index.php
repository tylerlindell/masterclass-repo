<?php
ini_set('display_errors', 1);

session_start();

$config = require_once('../config/config.php');
require_once '../vendor/autoload.php';
require_once '../config/diconfig.php';

$framework = $di->newInstance('Masterclass\FrontController\MasterController');
echo $framework->execute();