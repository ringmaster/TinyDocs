<?php

namespace Microsite;

use Microsite\Renderers\JSONRenderer;
use Microsite\Renderers\MarkdownRenderer;
use Microsite\Renderers\PlainRenderer;

include 'microsite.phar';

class TinyDocsApp extends App
{
	/**
	 * @return \Microsite\DB\PDO\DB The database object
	 */
	public function db() {
		$args = func_get_args();
		return $this->dispatch_object('db', $args);
	}

	function get_page($page_name) {
		$page = $this->db()->row('SELECT * FROM pages WHERE slug = :slug', ['slug' => $page_name]);
		$rev = $this->db()->row('SELECT * FROM revisions WHERE page_id = :page_id AND rev = (SELECT MAX(rev) FROM revisions WHERE page_id = :page_id);', ['page_id' => $page['id']]);
		if($rev != false) {
			$page = array_merge($page->ary(), $rev->ary());
		}
		return $page;
	}

}

$app = new TinyDocsApp();

// Assign a directory in which templates can be found for rendering
$app->template_dirs = [
	__DIR__ . '/views',
];

$app->share('db', function() {
	$db = new \Microsite\DB\PDO\DB('sqlite:' . __DIR__ . '/tinydocs.db');
	return $db;
});

/**
 * Basic home page.
 * Set the view to a home.php view provided in the view directory
 */
$generate_page = function(Response $response, Request $request, TinyDocsApp $app) {
	$response['project'] = 'TinyDocs';
	$page_renderer = MarkdownRenderer::create($app->template_dirs(), $app);

	$page = $app->get_page($request['page']);

	$response['title'] = $page['title'];
	$response['page'] = $page_renderer->render($page['content']);
	$response['editlink'] = $app->get_url('page_edit', $request);
	$pages = $app->db()->results('SELECT * FROM pages ORDER BY sort_order;');
	foreach($pages as &$page) {
		$page['url'] = $app->get_url('page', ['page'=>$page['slug']]);
	}
	$response['pages'] = $pages;
	return $response->render('home.php');
};

$app->route(
	'home',
	'/',
	function(Request $request, TinyDocsApp $app) {
		$first_page = $app->db()->val('SELECT slug FROM pages WHERE chapter_type = "page" ORDER BY sort_order ASC LIMIT 1');
		$request['page'] = $first_page;
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

		$page = $app->get_page($request['page']);

		$response['page'] = $page['content'];
		$response['title'] = $page['title'];
		$response['editlink'] = $app->get_url('page_edit', $request);
		return $response->render('edit.page.php');
	}
);

/**
 * Run the app to match and dispatch routes
 */
$app();

?>
