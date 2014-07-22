<?php

// home routes

// avoid duplicate code
$route = function($request, $response, $service, $app) {
	if(isset($request->user) && $request->user)
		return $response->redirect('/dash');

	$users = Model::factory('User')
		->find_array();

	$service->render('views/index.phtml', array(
		'title' => 'Home',
		'users' => $users
	));
};

// home
$klein->respond('GET', '/', $route);

// home
$klein->respond('GET', '/home', $route);