<?php

$output = "";

if(isset($_REQUEST["input"])){
	$input = $_REQUEST["input"];
	$output = $input;
	
	// Remove spans; this may be overly aggressive
	$output = preg_replace('#</?span[^>]*>#is', '', $output);
	
	// Remove divs; this may be overly aggressive
	$output = preg_replace('#</?div[^>]*>#is', '', $output);
	
	// Remove br kix line-break
	$output = str_replace('<br class="kix-line-break" />','',$output);
	
	// Styles to delete
	$deletestyles = array(
		"/background-color: transparent;\s?/",
		"/color: black;\s?/",
		"/color: #1155cc;\s?/",
		"/font-size: [0-9.]+px;\s?/",
		"/font-weight: [0-9]+;\s?/",
		"/line-height: [0-9.]+p?x?;\s?/",
		"/list-style-type: disc;\s?/",
		"/margin-[A-Za-z\-]+: 0pt;\s?/",
		"/text-decoration: [A-Za-z\-]+;\s?/",
		"/vertical-align: baseline;\s?/",
		"/white-space: pre-wrap;\s?/",
		"/[A-Za-z\-]+: normal;\s?/",
		"/[A-Za-z\-]+: inherit;\s?/"
	);
	$output = preg_replace($deletestyles,"",$output);
	
	// Array of straightforward attributes to delete
	$deleteattrs = array(' dir="ltr"',' style=""');
	$output = str_replace($deleteattrs,"",$output);
	
	// Replace carriage return + space with carriage returns
	$output = preg_replace("/\r?\n\s/","\n",$output);
	
	// Replace 3+ carriage returns with 2
	$output = preg_replace("/\n{3,}/","\n\n",$output);
	
	// Remove single carriage return between adjacent tags
	$output = preg_replace("/>\r?\n</","><",$output);

	// Remove carriage return after <li>
	$output = preg_replace("/li>\r?\n/","li>",$output);
	
	// Add carriage return before <li>
	$output = preg_replace("/><li>/","><br><li>",$output);
	
}

?>

<html>

<head>
<link rel="stylesheet" type="text/css" href="/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap.min.css">
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>

<body>

<div id=lynx>
	<a class=lynx href="http://www.laurahughes.com/">main</a>
</div>

<h1 id="name">Blogger/GDocs HTML Fixer</h1>
<div id='bodytext'>
Ever write your blog entry in Google Docs, copy it over to Blogger, and end up with a mess of HTML? This tool removes the nonsense and returns clean HTML.

<h2>Instructions</h2>
<ol>
	<li>Write a blog entry in Google Docs and copy-paste it into the Compose tab in a Blogger post</li>
	<li>Go to the HTML tab and copy-paste the resulting HTML into the top box below</li>
	<li>Hit "Clean Up"</li>
	<li>Clean HTML will appear in the bottom box below. You can copy-paste this back into the HTML tab, overwriting the "dirty" HTML that is already there. If you click back to the Compose tab now, your content should look just the same.</li>
</ol>

<form method='POST' action='<?php echo $_SERVER['PHP_SELF']?>'>
	<div class='form-group'>
		<label for='input'>Input Dirty HTML Here:</label>
		<textarea id='input' name='input' class='form-control' rows=5><?php echo $input; ?></textarea>
	</div>

	<button class='btn btn-primary'>Clean Up!</button>

	<div class='form-group'>
		<label for='output'>Clean HTML Outputs Here:</label>
		<textarea id='output' class='form-control' rows=5><?php echo $output; ?></textarea>
	</div>
</form>

<h2>What does this tool actually do?</h2>
In this order:
<ul>
	<li>Removes all SPAN and DIV tags</li>
	<li>Removes &lt;br class="kix-line-break" /&gt;
	<li>Removes the following styles:
		<ul>
			<li>background-color: transparent</li>
			<li>color: black [default text color]</li>
			<li>color: #1155cc [default link color]</li>
			<li>font-size: (any)</li>
			<li>font-weight: (any)</li>
			<li>line-height: (any)</li>
			<li>list-style-type: disc [default]</li>
			<li>margin-(any): 0pt</li>
			<li>text-decoration: (any)</li>
			<li>vertical-align: baseline</li>
			<li>white-space: pre-wrap</li>
			<li>(any): inherit</li>
			<li>(any): normal</li>
		</ul>
	</li>
	<li>Removes the following attributes:
		<ul>
			<li>dir="ltr"</li>
		</ul>
	</li>
	<li>Fixes spacing on lists: each &lt;li&gt; starts on its own line and ends with a carriage return between bullets
	<li>Removes more than 3 carriage returns in a row</li>
</ul>

If you want to actually use any of these attributes for effect on a one-time basis, add them back in AFTER doing the cleanup. If you wish to set styles ongoing (e.g. all links have text-decoration-none), set it in the CSS. One major point of this tool is to prevent styles baked in by Google Docs to override your CSS styles.


</div>
</body>