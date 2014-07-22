<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php';

ORM::configure("sqlite:{$database['name']}");

$db = ORM::get_db();

$sql = file_get_contents(__DIR__ . '/../tables.sql');

$db->exec($sql);

echo 'Done!' . "\n";
