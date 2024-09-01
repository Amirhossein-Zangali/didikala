<?php
session_start();
require_once '../vendor/autoload.php';
require_once 'config/config.php';
require_once 'libraries/Core.php';
include_once 'libraries/jdf.php';
include_once 'helpers/url_helper.php';
include_once 'helpers/session_helper.php';
include_once 'helpers/date_helper.php';

date_default_timezone_set(TIMEZONE);

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => DB_TYPE,
    'host'      => DB_HOST,
    'database'  => DB_NAME,
    'username'  => DB_USER,
    'password'  => DB_PASS,
    'charset'   => DB_CHAR,
    'collation' => DB_COLL,
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();