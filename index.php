<?php

namespace Microsite;

use Microsite\Renderers\JSONRenderer;
use Microsite\Renderers\MarkdownRenderer;

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
$app->route('home', '/', function(Response $response, App $app) {
	$response['project'] = 'TinyDocs';
	$page_renderer = MarkdownRenderer::create($app->template_dirs(), $app);
	$response['page'] = $page_renderer->render('default.md');
	return $response->render('home.php');
});

$generate_page = function(Response $response, Request $request, App $app) {
	$response['project'] = 'TinyDocs';
	$page_renderer = MarkdownRenderer::create($app->template_dirs(), $app);
	$response['page'] = $page_renderer->render($request['page'] . '.md');
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
