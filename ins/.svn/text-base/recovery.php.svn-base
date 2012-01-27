<?php
/**
*  XAdmin2 recovery-script.
*  Version 1.0
*  Skapad av Gustav E. från XCoders för XAdmin.
*  Se licens.txt för mer information.
**/
ini_set("display_errors", 1);
define("XA_RECOVERY_VERSION", "1.0");
define("XA_RECOVERY_BUILD", "1000");
define("XA_INCLUDED", true);
require("class.recovery.php");

if(isset($_GET['recover'])) {
	$_POST = array_map("trim", $_POST);
	if($_POST['my_user'] != "" && $_POST['my_server'] != "" && $_POST['my_db'] != "" && $_POST['prefix'] != "") {
		$rec = new recovery($_POST['prefix'], $_POST['my_user'], $_POST['my_pass'], $_POST['my_server'], $_POST['my_db'], $_POST['xa_version'], 1000, XA_RECOVERY_VERSION, XA_RECOVERY_BUILD);
		header("Location:./recovery.php?klart");
		die;
	}
	else {
		header("Location:./recovery.php?e=1");
		die;
	}
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>&Aring;terskapa config.php f&ouml;r XAdmin</title>
<script type="text/javascript" src="../adm/lib/jquery.js"></script>
<script type="text/javascript" src="../adm/lib/jquery.tooltip.js"></script>
<script type="text/javascript">
$(function() {
	$('#smurf *').tooltip();
});
</script>
<link rel="stylesheet" href="../adm/lib/jquery.tooltip.css" type="text/css" />
<style type="text/css">
body {
	background:#FFFFFF;
	color:#000000;
	font-family:"Trebuchet MS", Verdana, Tahoma, "Courier New", Courier, Arial, serif;
	font-size:0.75em;
}
div#wrapper {
	width:60%;
	margint-top:20%;
	margin:0 auto;
}
span.tip {
	text-decoration:none;
	border-bottom:1px #000000 dashed;
}
span.tooltip, a.tooltip {
	text-decoration:none;
	border-bottom:1px #000000 dashed;
}
p.info {
	font-family:"Courier New", Courier, serif;
}
	p.info span.ok {
		color:#00CC33;
	}
	p.info span.fel {
		color:#FF0033
	}
	p.info span.varning {
		color:#FF9900;
	}
</style>
</head>

<body>
	<div id="wrapper">
		<h1>Välkommen till recovery-guiden f&ouml;r XAdmin.</h1>	
<?php
if(isset($_GET['klart'])) {
	echo "<p>Det &auml;r nu klart! config.php har &aring;terskapats till vad som var tidigare och XAdmin ska nu fungera igen.</p>";
}
else if(isset($_GET['e'])) {
	switch($_GET['e']) {
		case 1:
			echo "<p>Du m&aring;ste fylla i alla f&auml;lt f&ouml;r att kunna &aring;terskapa config.php</p>";
			break;
	}
}
else {
?>
		<p>Denna guide kommer att hj&auml;lpa dig att &aring;terskapa config.php om denna skulle ha r&aring;kat skrivas &ouml;ver vid en uppdateringsprocess.</p>
		<p>F&ouml;r att &aring;terskapa config.php, v&auml;nligen fyll i uppgifterna nedan och tryck sedan p&aring; &quot;&Aring;terskapa&quot;.</p>
		<form action="?recover" method="post" id="smurf">
			<p><input type="text" name="my_user" title="Det anv&auml;ndarnamnet du anv&auml;nder f&ouml;r att logga in p&aring; MySQL-servern, eller t.ex. PhpMyAdmin" />
				<label for="my_user">MySQL användarnamn</label></p>
			<p><input type="text" name="my_pass" title="Det l&ouml;senordet du anv&auml;nder f&ouml;r att logga in p&aring; MySQL-servern, eller t.ex. PhpMyAdmin" />
				<label for="my_pw">MySQL lösenord</label></p>
			<p><input type="text" name="my_server" value="localhost" title="Adressen till servern som MySQL-servern &auml;r p&aring;. Endera en IP-adress eller en url. Har du ingen s&aring; &auml;r det med st&ouml;rsta sannolikhet &quot;localhost&quot;." />
				<label for="my_server">MySQL server</label></p>
			<p><input type="text" name="my_db" title="Den databas d&auml;r XAdmin finns installerat." />
				<label for="my_db">MySQL databas</label></p>
			<p><input type="text" name="prefix" value="xa_" title="Det prefix som anv&auml;ndes vid installationen. Notera att det m&aring;ste vara samma f&ouml;r att recovery-processen ska fungera." />
				<label for="prefix">Prefix f&ouml;r databasen</label></p>
			<p>
				<select name="xa_version" id="xa_version" title="Den version som du anv&auml;nde tidigare. Notera att den automatiskt valda inte n&ouml;dv&auml;ndigtvis &ouml;verrensst&auml;mmer med din tidigare version.">
					<option value="2.0b1">2.0b1</option>
					<option value="2.0b2">2.0b2</option>
					<option value="2.0b3" selected="selected">2.0b3</option>
					<option value="2.0b4">2.0b4</option>
				</select>
				<label for="xa_version">Tidigare version av XAdmin</label></p>
			<input type="submit" name="start" value="&Aring;terskapa &raquo;" />
		</form>
<?php
}
?>
	</div>
</body>
</html>