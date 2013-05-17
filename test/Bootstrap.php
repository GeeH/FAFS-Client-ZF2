<?php
// change directory to project root
chdir(dirname(__DIR__ . '/../../../'));

/* @var $loader \Composer\Autoload\ClassLoader */
$loader = @include(__DIR__ . '/../../../vendor/autoload.php');

if (!$loader) {
    die('Could not load the vendor autoload file');
}

$config = @include(__DIR__ . '/../../../config/application.config.php');
if (!$config) {
    die('Could not load the application config');
}

// grab a list of auto loaded vendor modules
$prefixes = array_keys($loader->getPrefixes());
$loaded = array();
foreach ($prefixes as $key) {
    $key = rtrim($key, '\\');
    $loaded[] = $key;
}


// autoload anything not currently loaded from module dir
$modulePath = realpath(__DIR__ . '/../../../module');
foreach ($config['modules'] as $module) {
    if (!in_array($module, $loaded)) {
        $loader->add($module . '\\', $modulePath . '/' . $module . '/src');
    }
}





