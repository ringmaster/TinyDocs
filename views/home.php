<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?= $project ?> - TinyDocs</title>
	<link rel="stylesheet" href="http://necolas.github.com/normalize.css/2.0.1/normalize.css" type="text/css" media="screen">
	<link href="//fonts.googleapis.com/css?family=Duru+Sans" rel="stylesheet" type="text/css">
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
				<li class="chapter active">
					<a href="#">Introducing TinyDocs</a>
					<ol>
						<li class="section active">
							<a href="/page/default">What is TinyDocs?</a>
						</li>
					</ol>
				</li>
				<li class="unit">User Documentation</li>
				<li class="chapter">
					<a href="#">Installing TinyDocs</a>
					<ol>
						<li class="section">
							<a href="#">Installation</a>
							<ol>
								<li><a href="#">Prerequisites</a></li>
								<li><a href="#">Obtaining TinyDocs</a></li>
								<li><a href="#">Configuration</a></li>
							</ol>
						</li>
					</ol>
				</li>
				<li class="chapter">
					<a href="#">Using TinyDocs</a>
					<ol>
						<li class="section">
							<a href="/page/creating-pages">Creating Pages</a>
							<ol>
								<li><a href="/page/creating-pages#enabling-comments">Enabling Comments</a></li>
								<li><a href="/page/creating-pages#setting-permissions">Setting Permissions</a></li>
							</ol>
						</li>
						<li class="section">
							<a href="#">Editing Pages</a>
						</li>
						<li class="section">
							<a href="#">Comment Contributions</a>
							<ol>
								<li><a href="#">Up/Down-Voting</a></li>
								<li><a href="#">Promoting Comments</a></li>
							</ol>
						</li>
					</ol>
				</li>

			</ol>
		</div>
	</nav>
	<div id="content">
		<div id="page">

			<div id="prev_page"></div>

			<div id="page_content">
				<?= $page ?>
			</div>

			<div id="next_page"></div>
		</div>
		<div id="comments">

		</div>
	</div>
</div>

</body>
</html>