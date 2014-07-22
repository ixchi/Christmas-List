<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';

require __DIR__ . '/models/item.php';
require __DIR__ . '/models/user.php';

$klein = new \Klein\Klein();

ORM::configure("sqlite:{$database['name']}");

require __DIR__ . '/helpers.php';

require __DIR__ . '/routes/home.php';       // GET /, GET /home
require __DIR__ . '/routes/dashboard.php';  // GET /dash
require __DIR__ . '/routes/user.php';       // GET /user/new, POST /user/new, GET /user/[i:id], GET /user/auth/[i:id], POST /user/auth/[i:id], GET /logout
require __DIR__ . '/routes/item.php';       // GET /item/[i:id], POST /item/[i:id], DELETE /item/[i:id]
require __DIR__ . '/routes/purchase.php';   // POST /user/[i:u]/add, GET /user/[i:u]/[i:id], GET /user/[i:u]/[i:id]/nope
require __DIR__ . '/routes/print.php';      // GET /print

$klein->dispatch();
