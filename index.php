<?php

namespace Microsite;

include 'microsite.phar';

$app = new App();

// Assign a directory in which templates can be found for rendering
$app->template_dirs = [
	__DIR__ . '/views',
];

/**
 * Basic home page.
 * Set the view to a home.php view provided in the view directory
 */
$app->route('home', '/', function(Response $response) {
	$response['project'] = 'TinyDocs';
	$response['page'] = $response->render('default.php');
	return $response->render('home.php');
});

$app->route('page', '/page/:page', function(Response $response, Request $request) {
	$response['project'] = 'TinyDocs';
	$response['page'] = $response->render($request['page'] . '.php');
	return $response->render('home.php');
});


/**
 * Run the app to match and dispatch routes
 */
$app();

?>
