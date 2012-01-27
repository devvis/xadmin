<?php
# XAdmin v2
# Exempellayout av Robin
require("./adm/lib/config.php");	# Vi inkluderar config.php som inneh�ller alla anslutningar till MySQL-databasen
if(!defined("XA_PREFIX")) {			# Vi kontrollerar om installationen �r klar eller inte
	header("Location:./ins/");
	die;
}
require("./adm/lib/core.php");		# Vi inkluderar �ven core.php som inneh�ller alla funktioner vi kommer att anv�nda oss av f�r att h�mta data ifr�n v�r databas
addComment(); # L�gger till en kommentar om den �r postad.
addGuestbookMessage(); # L�gger till ett g�stboksinl�gg om det �r postat.
sendContactMessage(); # Skickar ett kontakta oss-mail om ett s�dant �r postat.
# F�r mer information om hur man anv�nder XAdmin och dess funktioner, v�nligen se filerna i /inc/-mappen.
# D�r finns kommentarer kring hur man anv�nder och integrerar XAdmin i en sida.
# Notera �ven att /adm/lib/core.php samt /adm/lib/config.php beh�ver inkluderas p� varje sida som anv�nder XAdmins funktioner.
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="inc/stilmall.css" />
<title><?php echo getClanName(); ?> - Drivs av XAdmin v<?php echo XA_VERSION; ?></title>
<!-- Som synes ovan anv�nder vi funktionen getClanName() f�r att h�mta klannamnet som �r inst�llt i admindelen (och vid installationen) -->
<!-- Vi anv�nder �ven oss utav konstanten XA_VERSION som finns definerad i config.php, f�r att tala om vilken version av XAdmin som anv�nds (detta �r naturligtvis inte n�dv�ndigt, utan bara ett exempel) -->
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
	# here goes the include-k�d
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
