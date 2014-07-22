<?php

// print view

// print view
$klein->respond('GET', '/print', function($request, $response, $service, $app) {
	if(!$app->requireAuth('/print'))
		return $response->redirect('/home');

	$users = Model::factory('User')
		->where_not_equal('id', $request->user->id)
		->find_many();

	usort($users, function($a, $b) {
		$a = $a->items()->find_array();
		$b = $b->items()->find_array();

		return (count($a) > count($b)) ? -1 : 1;
	});

	$service->render('views/print.phtml', array(
		'users' => $users,
		'me' => $request->user
	));
});