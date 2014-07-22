<?php

// add information about the current user to the request
$klein->respond(function($request, $response, $service, $app) {
	$service->startSession();

	if(isset($_SESSION['USER'])) {
		$request->user = Model::factory('User')
			->where('id', $_SESSION['USER'])
			->find_one();
	}

	// redirection helper for when is logged in
	$app->requireAuth = function($page) use ($request, $service) {
		if(!isset($request->user) || !$request->user) {
			$service->flash($page, 'page');

			return false;
		}

		return true;
	};

	$app->getRedirPage = function() use ($request, $service) {
		$flash = $service->flashes('page');

		return (@isset($flash[0])) ? $flash[0] : false;
	};
});