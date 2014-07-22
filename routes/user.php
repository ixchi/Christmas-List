<?php

// user functions

// create new user view
$klein->respond('GET', '/user/new', function($request, $response, $service, $app) {
	$err = $service->flashes('name');
	$service->render('views/register.phtml', array(
		'title' => 'Register',
		'invalid_name' => isset($err[0]) ? true : false
	));
});

// create new user
$klein->respond('POST', '/user/new', function($request, $response, $service, $app) {
	$valid = Model::factory('User')
		->where('name', $request->name)
		->find_one();

	if($valid != false) {
		$service->flash('name', 'name');

		return $response->redirect('/n');
	}

	$user = Model::factory('User')
		->create();

	$user->name = $request->name;
	$user->pin = $request->pin;

	$user->save();

	$user = Model::factory('User')
		->where('name', $request->name)
		->where('pin', $request->pin)
		->find_one();

	$_SESSION['USER'] = $user->id;

	return $response->redirect('/dash');
});

// user page
$klein->respond('GET', '/user/[i:id]', function($request, $response, $service, $app) {
	if(!$request->user)
		return $response->redirect('/home');

	if($request->id == $request->user->id)
		return "Don't view your own list! :(<br>\n<a href='/dash'>Return home</a>";

	$user = Model::factory('User')
		->where('id', $request->id)
		->find_one();

	$service->render('views/list.phtml', array(
		'title' => $user->name . '\'s List',
		'authed' => $request->user,
		'user' => $user,
		'items' => $user
			->items()
			->order_by_asc('position')
			->find_many()
	));
});

// login page
$klein->respond('GET', '/user/auth/[i:id]', function($request, $response, $service, $app) {
	$users = Model::factory('User')
		->where('id', $request->id)
		->find_one();

	$service->render('views/login.phtml', array(
		'title' => 'Log in',
		'user' => $users
	));
});

// checks user authentication
$klein->respond('POST', '/user/auth/[i:id]', function($request, $response, $service, $app) {
	$users = Model::factory('User')
		->where('id', $request->id)
		->find_one();

	if($request->pin == $users->pin) {
		$_SESSION['USER'] = $request->id;
		if($request->form == 'true') {
			$page = $app->getRedirPage();
			if(!!$page)
				return $response->redirect($page);
			return $response->redirect('/home');
		}

		return $response->json(array('status' => 'success'));
	}

	if($request->form == 'true')
		return $response->redirect('/user/auth/' . $request->id);

	return $response->json(array('status' => 'error'));
});

// log user out
$klein->respond('GET', '/logout', function($request, $response, $service, $app) {
	session_destroy();

	return $response->redirect('/home');
});