<?php
if(!defined("XA_PREFIX") || !main::isLoggedIn()) {
	die;
}
if(isset($_GET['nyheter'])) {
	echo "<div id=\"xnews\">";
	echo main::getNews("http://xcoders.info/xn.php", 1);
	echo "</div>";
	echo "<p><a href=\"./\">Tillbaka</a></p>";
}
else {
	echo "<p><a href=\"./\">Tillbaka</a></p>";
}
