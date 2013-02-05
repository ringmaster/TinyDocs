<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?= $project ?> - TinyDocs</title>
	<link rel="stylesheet" href="http://necolas.github.com/normalize.css/2.0.1/normalize.css" type="text/css" media="screen">
	<!-- link href="//fonts.googleapis.com/css?family=Duru+Sans" rel="stylesheet" type="text/css" -->
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700' rel='stylesheet' type='text/css'>
	<link href="/css/ss-standard/ss-standard.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="/css/master.css" type="text/css" media="screen">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
	<script src="/js/master.js"></script>
</head>
<body>

<div id="wrapper">
	<div id="navigation">
		<div id="masthead">
			<h1><?= $project ?></h1>
		</div>
		<div id="tools">

		</div>
		<nav id="contents">
			<ol>
				<?php
				$doc = new DOMDocument();
				foreach($pages as $page_link) {
					switch($page_link['chapter_type']) {
						case 'chapter':
							$current_chapter_li = $doc->createElement('li');
							$current_chapter_li->setAttribute('class', 'chapter');
							$doc->appendChild($current_chapter_li);
							$a = $doc->createElement('a');
							$a->setAttribute('href', '#' . $page_link['slug']);
							$a->appendChild(new DOMText($page_link['title']));
							$current_chapter_li->appendChild($a);
							$current_chapter = $doc->createElement('ol');
							$current_chapter_li->appendChild($current_chapter);
							break;
						case 'unit':
							$unit = $doc->createElement('li');
							$unit->setAttribute('class', 'unit');
							$unit->appendChild(new DOMText($page_link['title']));
							$doc->appendChild($unit);
							break;
						case 'page':
							$current_page_li = $doc->createElement('li');
							$current_page_li->setAttribute('class', 'section');
							$current_chapter->appendChild($current_page_li);
							$a = $doc->createElement('a');
							$a->setAttribute('href', $page_link['url']);
							$a->appendChild(new DOMText($page_link['title']));
							$current_page_li->appendChild($a);
							$current_page = $doc->createElement('ol');
							$current_page_li->appendChild($current_page);
							break;
						case 'section':
							$section_li = $doc->createElement('li');
							$current_page->appendChild($section_li);
							$section = $doc->createElement('a');
							$section->setAttribute('href', $page_link['url']);
							$section->appendChild(new DOMText($page_link['title']));
							break;
					}
				}
				echo $doc->saveHTML();
				?>
			</ol>
		</div>
	</nav>
	<div id="content">

		<div id="prev_page"></div>

		<div id="page_content">
			<a class="editlink ss-write" href="<?= $editlink ?>"></a>
			<div id="editable">
				<h1><?= $title ?></h1>
				<?= $page ?>
			</div>
		</div>

		<div id="comments">
		</div>

		<div id="next_page"></div>

	</div>
</div>

</body>
</html>