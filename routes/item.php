<?php

// routes for editing items

// edit item view
$klein->respond('GET', '/item/[i:id]', function($request, $response, $service, $app) {
	if(!$app->requireAuth('/item/' . $request->id))
		return $response->redirect('/home');

	$item = Model::factory('Item')
		->where('id', $request->id)
		->where('user', $request->user->id)
		->find_one();

	if(!$item) {
		return "Item not found!<br>\n<a href='/dash'>Return home</a>";
	}

	$service->render('views/edit.phtml', array(
		'title' => 'Item Editor',
		'user' => $request->user,
		'item' => $item
	));
});

// edit item
$klein->respond('POST', '/item/[i:id]', function($request, $response, $service, $app) {
	if(!$app->requireAuth('/item/' . $request->id))
		return $response->redirect('/home');

	$item = null;
	if($request->id == 0) { // new item
		$item = Model::factory('Item')
			->create();

		$item->user = $request->user->id;
	} else {
		$item = Model::factory('Item')
			->where('id', $request->id)
			->where('user', $request->user->id)
			->find_one();
	}

	$item->name = $request->name;
	$item->description = $request->description;
	$item->price = $request->price;
	$item->link = $request->link;
	$item->nol = false;
	$item->position = 1;

	$item->save();

	return $response->redirect('/dash');
});

$klein->respond('POST', '/item/[i:u]/nol', function($request, $response, $service, $app) {
	if(!$app->requireAuth('/user/' , $request->user))
		return $response->redirect('/home');

	$item = Model::factory('Item')
		->create();

	$item->name = $request->name;
	$item->description = $request->description;
	$item->nol = true;
	$item->user = $request->u;
	$item->purchased = $request->user->id;
	$item->position = 1;

	$item->save();

	return $response->redirect('/user/' . $request->u);
});

// remove item
$klein->respond('DELETE', '/item/[i:id]', function($request, $response, $service, $app) {
	if(!$app->requireAuth('/item/' . $request->id))
		return $response->redirect('/home');

	Model::factory('Item')
		->where('id', $request->id)
		->where('user', $request->user->id)
		->find_one()
		->delete();

	return $response->redirect('/dash');
});
