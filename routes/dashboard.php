<?php

// user dashboard

// logged in user dashboard
$klein->respond('GET', '/dash', function($request, $response, $service, $app) {
	if(!$app->requireAuth('/dash'))
		return $response->redirect('/home');

	$users = Model::factory('User')
		->find_array();

	$purchased = Model::factory('Item')
		->where('purchased', $request->user->id)
		->find_many();

	$service->render('views/dashboard.phtml', array(
		'title' => 'Dashboard',
		'me' =>  $request->user,
		'users' => $users,
		'items' => $request->user->items()
			->where('nol', 0)
			->order_by_asc('position')
			->find_array(),
		'purchased' => $purchased
	));
});

$klein->respond('POST', '/position', function($request, $response, $service, $app) {
	foreach($request->positions as $key => $item) {
		$i = Model::factory('Item')
			->where('id', $key)
			->where('user', $request->user->id)
			->find_one();

		$i->position = $item;

		$i->save();
	}
});
