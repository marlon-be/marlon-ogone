<?php
use Symfony\Component\ClassLoader\UniversalClassLoader;
require_once __DIR__ . '/vendor/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$loader = new UniversalClassLoader;
$loader->registerNamespaces(array(
    'Ogone' => __DIR__ . '/lib/'
));
$loader->register();