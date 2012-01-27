<?php
# XAdmin v2
# Exempellayout av Robin
require("./adm/lib/config.php");	# Vi inkluderar config.php som innehåller alla anslutningar till MySQL-databasen
if(!defined("XA_PREFIX")) {			# Vi kontrollerar om installationen är klar eller inte
	header("Location:./ins/");
	die;
}
require("./adm/lib/core.php");		# Vi inkluderar även core.php som innehåller alla funktioner vi kommer att använda oss av för att hämta data ifrån vår databas
addComment(); # Lägger till en kommentar om den är postad.
addGuestbookMessage(); # Lägger till ett gästboksinlägg om det är postat.
sendContactMessage(); # Skickar ett kontakta oss-mail om ett sådant är postat.
# För mer information om hur man använder XAdmin och dess funktioner, vänligen se filerna i /inc/-mappen.
# Där finns kommentarer kring hur man använder och integrerar XAdmin i en sida.
# Notera även att /adm/lib/core.php samt /adm/lib/config.php behöver inkluderas på varje sida som använder XAdmins funktioner.
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="inc/stilmall.css" />
<title><?php echo getClanName(); ?> - Drivs av XAdmin v<?php echo XA_VERSION; ?></title>
<!-- Som synes ovan använder vi funktionen getClanName() för att hämta klannamnet som är inställt i admindelen (och vid installationen) -->
<!-- Vi använder även oss utav konstanten XA_VERSION som finns definerad i config.php, för att tala om vilken version av XAdmin som används (detta är naturligtvis inte nödvändigt, utan bara ett exempel) -->
</head>
<body>
<!-- Denna demolayout drivs av XAdmin v<?php echo XA_VERSION; ?> build <?php echo XA_BUILD; ?> - http://xcoders.info -->
<div id="top"><h1><a href="./">xadmin</a></h1></div>


<ul id="meny">
	<li><a class="gul" href="?s=nyheter" >Nyheter</a></li>
	<li><a class="rod" href="?s=medlemmar">Medlemmar</a></li>
	<li><a class="bla" href="?s=matcher">Matcher</a></li>
	<li><a class="gron" href="?s=gastbok">G&auml;stbok</a></li>
	<li><a class="orange" href="?s=historia">Historia</a></li>
	<li><a class="rod" href="?s=info">Om XCoders/XAdmin</a></li>
	<li><a class="bla" href="?s=kontakta">Kontakta</a></li>
	<li><a class="gron" href="./adm/">Admin</a></li>
</ul>
<div id="container">
<?php
	showCommentText();
	# here goes the include-kåd
	$inc = array("nyheter", "matcher", "medlemmar", "historia", "info", "kontakta", "gastbok");
	if(isset($_GET['s']) && in_array($_GET['s'], $inc) && is_file("./inc/".$_GET['s'].".php")) {
		# Se funktionen av detta lite som en iframe, fast utan frame-delen ;)
		require("./inc/".$_GET['s'].".php");
	}
	else {
		require("./inc/main.php");
	}

?>
</div>
<div id="footer">
	<p>Copyleft 2008 Robin (p&aring; designen) &mdash; <a href="http://xcoders.info/">http://xcoders.info/</a></p>
</div>
</body>
</html>
