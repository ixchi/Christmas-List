<?php

// script to add some test data

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php';

ORM::configure("sqlite:{$database['name']}");

require __DIR__ . '/../models/item.php';
require __DIR__ . '/../models/user.php';

$faker = Faker\Factory::create();

// add some test users

for($i = 0; $i < 15; $i++) {
	$user = Model::factory('User')
		->create();

	$user->name = $faker->name;
	$user->pin = '0';

	$user->save();
}

// add some test items

for($i = 0; $i < 5*15; $i++) {
	$item = Model::factory('Item')
		->create();

	$item->name = $faker->text(25);
	$item->description = $faker->text(60);
	$item->user = $faker->numberBetween(1, 14);
	$item->link = $faker->url;
	$item->price = $faker->numberBetween(0, 200);
	$item->nol = $faker->boolean(20);
	$item->purchased = ($faker->boolean(30)) ? $faker->numberBetween(1, 14) : 0;
	$item->position = 1;

	$item->save();
}
