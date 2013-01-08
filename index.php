<?php

namespace Microsite;

use Microsite\Renderers\JSONRenderer;

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

$generate_page = function(Response $response, Request $request, App $app) {
	$response['project'] = 'TinyDocs';
	$response['page'] = $response->render($request['page'] . '.php');
};

$app->route('page', '/page/:page', $generate_page, function(Response $response) {
	return $response->render('home.php');
})->type('text/html');

$app->route('page_json', '/page/:page', $generate_page, function(Response $response, App $app) {
	$response->set_renderer(JSONRenderer::create('', $app));
	return $response->render();
})->type('application/json');


/**
 * Run the app to match and dispatch routes
 */
$app();

?>
