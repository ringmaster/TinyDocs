<?php

namespace Microsite;

use Microsite\Renderers\JSONRenderer;
use Microsite\Renderers\MarkdownRenderer;
use Microsite\Renderers\PlainRenderer;

include 'microsite.phar';

$app = new App();

// Assign a directory in which templates can be found for rendering
$app->template_dirs = [
	__DIR__ . '/views',
];

$app->share('db', function() {
	$db = new DB('sqlite:' . __DIR__ . '/tinydocs.db');
	return $db;
});

/**
 * Basic home page.
 * Set the view to a home.php view provided in the view directory
 */
$generate_page = function(Response $response, Request $request, App $app) {
	$response['project'] = 'TinyDocs';
	$page_renderer = MarkdownRenderer::create($app->template_dirs(), $app);
	$response['page'] = $page_renderer->render($request['page'] . '.md');
	$response['editlink'] = $app->get_url('page_edit', $request);
	return $response->render('home.php');
};

$app->route(
	'home',
	'/',
	function(Request $request) {
		$request['page'] = 'default';
	},
	$generate_page
);

$app->route(
	'page',
	'/page/:page',
	$generate_page
)->type('text/html');

$app->route(
	'page_edit',
	'/page/:page/edit',
	function(Response $response, Request $request, App $app) {
		$response['project'] = 'TinyDocs';
		$page_renderer = PlainRenderer::create($app->template_dirs(), $app);
		$response['page'] = $page_renderer->render($request['page'] . '.md');
		$response['editlink'] = $app->get_url('page_edit', $request);
		return $response->render('edit.page.php');
	}
);

/**
 * Run the app to match and dispatch routes
 */
$app();

?>
