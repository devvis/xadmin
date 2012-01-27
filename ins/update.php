<?php
error_reporting(E_ALL);
if(file_exists("../adm/lib/config.php")) {
	require("../adm/lib/config.php");
}
else {
	$e = 102;
}
if(!defined("XA_PREFIX")) {
	$e = 200;
}
require("./class.upgrade.php");
$xu = new upgrade("2.0b4", "1005", XA_VERSION, XA_BUILD, XA_PREFIX);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Uppdatera till XAdmin v<?php echo $xu->version, "-", $xu->build; ?></title>
<script type="text/javascript" src="../adm/lib/jquery.js"></script>
<script type="text/javascript" src="../adm/lib/jquery.tooltip.js"></script>
<script type="text/javascript">
$(function() {
	$('#smurf *').tooltip();
	$('#smurftips').tooltip();
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
	color:
}
</style>
</head>

<body>
	<div id="wrapper">
		<h1>V&auml;lkommen till uppdateringen f&ouml;r XAdmin v<?php echo XA_VERSION,"-", XA_BUILD; ?> till v<?php echo $xu->version, "-", $xu->build; ?></h1>
		<?php
		if(isset($e)) {
			switch($e) {
				case 102:
					echo "<p>/adm/lib/config.php saknas, detta g&ouml;r att uppdateringen inte kan fullf&ouml;ljas. Se till att ha en fungerande installation av XAdmin och f&ouml;rs&ouml;k sedan igen.";
					break;
				case 200:
					echo "<p>/adm/lib/config.php existerar men det ser ut som att XAdmin inte &auml;r installerat. Se till att ha en fungerande installation av XAdmin och f&ouml;rso&ouml;k sedan igen.";
					break;
			}
		}
		else {
			if($xu->upgrade == true) {
				if(isset($_GET['go'])) {
					if($xu->doUpgrade()) {
						echo "<p class=\"success\">Klart! Din XAdmin-installation &auml;r nu uppdaterad till v{$xu->version}!</p>";
						echo "<p>F&ouml;r att vara s&auml;ker p&aring; att alla filer &auml;r som dom ska, klicka <a href=\"../verify.php\">h&auml;r</a> f&ouml;r att verifiera alla filer.</p>";
					}
					else {
						echo "<p class\"error\">N&aring;got gick fel. F&ouml;rhoppningsvis ser du lite felmeddelanden ovan. Kan du inte &aring;tg&auml;rda problemet sj&auml;lv, tveka inte att kontakta mig p&aring; gustav@xcoders.info!</p>";
					}
				}
				else {
					echo '<p>Detta script kommer att guida dig genom uppdateringen av XAdmin p&aring; ett s&aring; enkelt och sm&auml;rtfritt s&auml;tt som m&ouml;jligt.</p>';
					echo "<p>F&ouml;r att starta uppdateringen, klicka <a href=\"?go\">h&auml;r</a>!</p>";
				}
			}
			else {
				echo "<p>Du verkar redan ha den senaste versionen av XAdmin. Det finns ingen anledning att uppdatera i nul&auml;get.";
			}
		}
		?>
	</div>
</body>
</html>