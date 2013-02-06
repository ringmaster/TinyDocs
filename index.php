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
		if(is_integer($page_name)) {
			$page = $this->db()->row('SELECT * FROM pages WHERE id = :id', ['id' => $page_name]);
		}
		else {
			$page = $this->db()->row('SELECT * FROM pages WHERE slug = :slug', ['slug' => $page_name]);
		}
		$rev = $this->db()->row('SELECT * FROM revisions WHERE page_id = :page_id AND rev = (SELECT MAX(rev) FROM revisions WHERE page_id = :page_id);', ['page_id' => $page['id']]);
		if($rev != false) {
			$page = array_merge($page->ary(), $rev->ary());
			$page['rev_id'] = $page['id'];
			$page['id'] = $page['page_id'];
		}
		$page['url'] = $this->get_url('page', ['page' => $page['slug']]);
		return $page;
	}

	function slug($text) {
		return strtolower(preg_replace('#[^a-z0-9_\-]+#i', '-', $text));
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

	$response['prev_page'] = $app->get_page((int)$app->db()->val('SELECT id FROM pages WHERE sort_order < :sort_order AND chapter_type="page" ORDER BY sort_order DESC LIMIT 1', ['sort_order' => $page['sort_order']]));
	$response['next_page'] = $app->get_page((int)$app->db()->val('SELECT id FROM pages WHERE sort_order > :sort_order AND chapter_type="page" ORDER BY sort_order ASC LIMIT 1', ['sort_order' => $page['sort_order']]));

	$response['title'] = $page['title'];
	$response['page'] = $page_renderer->render($page['content']);
	$response['edit_url'] = $app->get_url('page_edit', $request);
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
)->get();

$app->route(
	'page',
	'/page/:page',
	$generate_page
)->type('text/html')->get();

$app->route(
	'page_edit',
	'/page/:page/edit',
	function(Response $response, Request $request, App $app) {
		$response['project'] = 'TinyDocs';
		$page_renderer = PlainRenderer::create($app->template_dirs(), $app);

		$page = $app->get_page($request['page']);

		$response['content'] = $page['content'];
		$response['title'] = $page['title'];
		$response['edit_url'] = $app->get_url('page_edit', $request);
		$response['post_url'] = $app->get_url('page_post', $request);
		return $response->render('edit.page.php');
	}
)->get();

$app->route(
	'page_post',
	'/page/:page/post',
	function(Response $response, Request $request, TinyDocsApp $app) {
		$page = $app->get_page($request['page']);

		if($page['content'] != $_POST['content'] || $page['title'] != $_POST['title']) {

			$newrev = isset($page['rev']) ? intval($page['rev']) + 1 : 1;

			$app->db()->query(
				'INSERT INTO revisions (page_id, rev, rev_title, rev_slug, content, user_id) VALUES (:page_id, :rev, :rev_title, :rev_slug, :content, :user_id);',
				[
					'page_id' => $page['id'],
					'rev' => $newrev,
					'rev_title' => $_POST['title'],
					'rev_slug' => $app->slug($_POST['title']),
					'content' => $_POST['content'],
					'user_id' => 1,
				]
			);

			$app->db()->query(
				'UPDATE pages SET slug = :slug, title = :title WHERE id = :id',
				[
					'slug' => $app->slug($_POST['title']),
					'title' => $_POST['title'],
					'id' => $page['id'],
				]
			);
		}

		$response->redirect($app->get_url('page', $request));
	}
)->post();

/**
 * Run the app to match and dispatch routes
 */
$app();

?>
