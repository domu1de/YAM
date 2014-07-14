<?php

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('YAM\\UnitTest\\', __DIR__ . '/Unit/');
$loader->addPsr4('YAM\\IntegrationTest\\', __DIR__ . '/Integration/');

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');