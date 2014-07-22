<?php

$klein->respond('POST', '/user/[i:u]/add', function($request, $response, $service, $app) {
	if(!$app->requireAuth('/user/' . $request->u))
		return $response->redirect('/user/' . $request->u);

	$item = Model::factory('Item')
		->create();

	$item->name = $request->name;
	$item->description = $request->description;
	$item->user = $request->u;
	$item->purchased = $request->user->id;
	$item->nol = true;

	$response->redirect('/user/' . $request->u);
});

$klein->respond('GET', '/user/[i:u]/[i:id]', function($request, $response, $service, $app) {
	if(!$app->requireAuth('/user/' . $request->u))
		return $response->redirect('/user/' . $request->u);

	$item = Model::factory('Item')
		->where('id', $request->id)
		->where('user', $request->u)
		->find_one();

	$item->purchased = $request->user->id;

	$item->save();

	$response->redirect('/user/' . $request->u);
});

$klein->respond('GET', '/user/[i:u]/[i:id]/nope', function($request, $response, $service, $app) {
	if(!$app->requireAuth('/dash'))
		return $response->redirect('/dash');

	$item = Model::factory('Item')
		->where('id', $request->id)
		->where('user', $request->u)
		->find_one();

	$item->purchased = 0;

	$item->save();

	$response->redirect('/dash');
});