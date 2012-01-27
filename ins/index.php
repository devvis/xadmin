<?php
/**
*  XAdmin - Installation
*  Version 2.0
*  Skapad av Gustav Eklundh från XCoders för XAdmin.
*  Se licens.txt för mer information.
**/
session_start();
ini_set("display_errors", 1);
define("XA_VER", "2.0b4");		# Uppdaterad version - 21/03 2009
define("XA_BLD", "1005");		# Uppdaterad build - 21/03 2009
define("XA_INSTALL_VERSION", "2.0"); # Ny installer, Gustav E. 12/04 2009
define("XA_INSTALL_BUILD", "2000");
define("XA_INCLUDED", true);
require("class.install.php");
$ins = new install(XA_VER, XA_BLD, XA_INSTALL_VERSION, XA_INSTALL_BUILD);

if(isset($_GET['make'], $_POST['start'])) {
	# Vi ska nu skapa config.php
	$_POST = array_map("trim", $_POST);
	if(empty($_POST['my_user']) || empty($_POST['my_server']) || empty($_POST['my_db']) || empty($_POST['prefix'])) {
		install::error("För att kunna påbörja installationen krävs det att du fyller i alla fält. Vänligen gå tillbaka och se till att alla fält är korrekt ifyllda.");
		echo '<a href="javascript:history.go(-1)">Tillbaka</a>';
		die;
	}
	if(!$ins->makeConfig($_POST['prefix'], $_POST['my_user'], $_POST['my_pass'], $_POST['my_server'], $_POST['my_db'])) {
		install::error("Vänligen åtgärda de fel som uppstod. Syns inga fel försök igen och se till att /adm/lib/config.php har skrivrättigheter och att all MySQL-information är korrekt.");
		echo '<a href="javascript:history.go(-1)">Tillbaka</a>';
		die;
	}
	header("Location:./?steg=3");
	die;
}

if(isset($_GET['sql'], $_POST['install'])) {
	# Det är dags för SQL-setupen.
	if(file_exists("../adm/lib/config.php")) {
	# pleeeaaase do we has file..?
		require("../adm/lib/config.php");
	}
	else {
	# what the flux, it's gone man!?
		install::error("Filen /adm/lib/config.php har på något märkligt sätt försvunnit mellan steg 2 och steg 3. Vänligen starta om installationen och se till att filen existerar.");
		echo '<a href="./">B&ouml;rja om</a>';
		die;
	}
	if(!defined("XA_PREFIX")) {
	# rawr >:(
		install::error("Det verkar som att något gick galet vid skrivningen till config.php. Filen existerar men saknar viss information som krävs för att avsluta installationen. Vänligen starta om och försök igen.");
		echo '<a href="./">B&ouml;rja om</a>';
		die;
	}
	$_POST = array_map("trim", $_POST);
	$_POST = array_map("mysql_real_escape_string", $_POST);
	$_POST = array_map("htmlspecialchars", $_POST);
	
	if($_POST['admin_user'] == "" || $_POST['admin_pw'] == "" || $_POST['admin_pw2'] == "" || $_POST['admin_name'] == "" || $_POST['admin_email'] == "" || $_POST['sidnamn'] == "") {
		install::error("Du måste fylla i alla fält för att kunna slutföra installationen.");
		echo '<a href="javascript:history.go(-1)">Tillbaka</a>';
		die;
	}
	if($_POST['admin_pw'] != $_POST['admin_pw2']) {
		install::error("De två lösenorden stämmer inte överrens. Vänligen skilj på stora och små bokstäver.");
		echo '<a href="javascript:history.go(-1)">Tillbaka</a>';
		die;
	}
	
	if($ins->setupSQL($_POST['admin_user'], $_POST['admin_pw'], $_POST['admin_name'], $_POST['admin_email'], $_POST['sidnamn'], $_POST['kontaktamail'])) {
		$_SESSION['tmp_user'] = $_POST['admin_user'];
		$_SESSION['tmp_pass'] = $_POST['admin_pw'];
		header("Location:./?steg=4");
		die;
	}
	else {
		install::error("Det uppstod ett eller flera fel under installationen som gjorde att denna inte kunde fortsätta. Vänligen se felmeddelanden och försök sedan igen.");
		die;
	}
}

#  || empty($_POST['sidnamn'])
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Installera XAdmin v<?php echo XA_VER; ?></title>
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
	<h1>Välkommen till installationen av XAdmin <?php echo XA_VER; ?>.</h1>
<?php
if(isset($_POST['avboj'])) {
	install::error("Du kan inte installera XAdmin utan att först godkänna avtalet.");
	echo <<<HTML
	</div>
	</body>
	</html>
HTML;
die;
}

if(isset($_GET['steg'])) {
# Fixar en php_warning om att 'steg' inte är satt
	switch($_GET['steg']) {
		case 1:
			echo <<<HTML
<p>Pre-installationskontroll:</p>
HTML;
			if(version_compare(PHP_VERSION, "5.1.0", "<")) {
				install::warning("Din version av PHP (".PHP_VERSION.") är lägre än rekommenderade 5.1.0. Om möjligheten finns rekommenderar vi en uppgradering.");
			}
			else {
				install::ok("Din version av PHP (".PHP_VERSION.") är 5.1.0 eller högre.");
			}
			echo <<<HTML
<p>Innan installationen kan b&ouml;rja s&aring; beh&ouml;ver du v&auml;lja vilken databas som ska anv&auml;ndas. I nul&auml;get har XAdmin2 st&ouml;d f&ouml;r f&ouml;ljande databaser:</p>

	<ul>
		<li><a href="?steg=2&amp;db=m">MySQL</a></li>
	</ul>
	<p>Klicka p&aring; den databas du vill anv&auml;nda dig utav. </p>
HTML;
			break;
		case 2:
			echo <<<HTML
<p>Nu s&aring; beh&ouml;ver du fylla i uppgifter som beh&ouml;vs innan filer kopieras och config-filen skrivs. V&auml;nligen fyll i dessa uppgifter och klicka d&auml;refter p&aring; &quot;Starta installationen&quot; s&aring; kommer allt att ske automatiskt efter det.</p>
<form action="?make" method="post" id="smurf">
	<p><input type="text" name="my_user" title="Det anv&auml;ndarnamnet du anv&auml;nder f&ouml;r att logga in p&aring; MySQL-servern, eller t.ex. PhpMyAdmin" />
		<label for="my_user">MySQL användarnamn</label></p>
	<p><input type="text" name="my_pass" title="Det l&ouml;senordet du anv&auml;nder f&ouml;r att logga in p&aring; MySQL-servern, eller t.ex. PhpMyAdmin" />
		<label for="my_pw">MySQL lösenord</label></p>
	<p><input type="text" name="my_server" value="localhost" title="Adressen till servern som MySQL-servern &auml;r p&aring;. Endera en IP-adress eller en url. Har du ingen s&aring; &auml;r det med st&ouml;rsta sannolikhet &quot;localhost&quot;." />
		<label for="my_server">MySQL server</label></p>
	<p><input type="text" name="my_db" title="Den databasen du vill installera XAdmin2 till." />
		<label for="my_db">MySQL databas</label></p>
	<p><input type="text" name="prefix" value="xa_" title="Ett prefix som l&auml;ggs till framf&ouml;r tabellernas namn, ex. xa_konton. Bra om du installerar flera olika installationer av XAdmin2 i samma databas." />
		<label for="prefix">Prefix f&ouml;r databasen</label></p>
	<input type="submit" name="start" value="Starta installationen" />
</form>
HTML;
			break;
		case 3:
			echo <<<HTML
<p>Grattis!</p>
<p>Har du kommit s&aring; h&auml;r l&aring;ngt s&aring; har du nu ett (n&auml;stan) fullt fungerande XAdmin2.</p>
<p>Filen &quot;config.php&quot; har skapats och all data har skrivits till den korrekt. Det som &aring;terst&aring;r nu &auml;r att fylla databasen med alla tabeller vilket g&ouml;rs per automatik efter att du har klickat p&aring; l&auml;nken nedan.</p>
<p>Innan du g&ouml;r det s&aring; m&aring;ste du dock skapa det f&ouml;rsta kontot i systemet. Kontot nedan kommer att vara ett s&aring;kallat huvudadministrat&ouml;rskonto, vilket inneb&auml;r lite ut&ouml;kade r&auml;ttigheter v&auml;l inne i systemet, exempelvis som att l&auml;gga till fler inloggningskonton.</p>
<p>Du kan &auml;ven passa p&aring; att fylla i den email som skall anv&auml;ndas till kontaktformul&auml;ret p&aring; er sida</p>

<form action="?sql" method="post">	
	<p><input type="text" name="admin_user" />
		<label for="admin_user">Användarnamn</label></p>
	<p><input type="password" name="admin_pw" />
		<label for="admin_pw">Lösenord</label></p>
	<p><input type="password" name="admin_pw2" />
		<label for="admin_pw2">Lösenord (igen)</label></p>
	<p><input type="text" name="admin_name" />
		<label for="admin_name">Namn</label></p>
	<p><input type="text" name="admin_email" />
		<label for="admin_email">Din email</label></p>
	<p><input type="text" name="sidnamn" title="Namnet p&aring; sidan." />
		<label for="sidnamn">Sidans/klanens namn</label></p>
	<p><input type="text" name="kontaktamail" />
		<label for="kontaktamail">Kontakta oss-mail</label></p>
	<input type="submit" name="install" value="Installera XAdmin &raquo;" />
</form>
HTML;
			break;
		case 4:
			echo <<<HTML
<p>Grattis. Nu &auml;r du helt klar med installationen av XAdmin2.</p>
<p>Gl&ouml;m inte att ta bort hela /ins/-mappen efter du har loggat in f&ouml;r f&ouml;rsta g&aring;ngen, detta s&aring; att andra inte kan g&ouml;ra en ominstallation.</p>
<p>F&ouml;r att vara s&auml;ker p&aring; att alla filer &auml;r som dom ska, klicka <a href="../verify.php" target="_blank">h&auml;r</a> f&ouml;r att g&ouml;ra en filkontroll.</p>
<p>Kom ih&aring;g f&ouml;ljande inloggningsinfo (g&aring;r inte att f&aring; tillbaka, i n&ouml;dfall g&aring;r det att skapa ett till):</p>
	<ul>
		<li>Anv&auml;ndarnamn - {$_SESSION['tmp_user']}</li>
		<li>L&ouml;senord - {$_SESSION['tmp_pass']}</li>
	</ul>
<p><a href="../adm/">Logga in &raquo;</a></p>
HTML;
			unset($_SESSION['tmp_user']);
			unset($_SESSION['tmp_pass']);
			session_unset();
			session_destroy();
			break;
	}
}
else {
?>
		<p>Denna guide kommer att guida dig genom tre steg som i slut&auml;ndan kommer att leda dig till en fungerande installation av XAdmin.</p>
		<p>Innan vi b&ouml;rjar s&aring; kr&auml;vs det att du har f&ouml;ljande uppgifter till hands:</p>
			<ol id="smurf">
				<li title="Det anv&auml;ndarnamnet du anv&auml;nder f&ouml;r att logga in p&aring; MySQL-servern, eller t.ex. PhpMyAdmin">
					<span class="tip">MySQL-anv&auml;ndare</span>
				</li>
				<li title="Det l&ouml;senordet du anv&auml;nder f&ouml;r att logga in p&aring; MySQL-servern, eller t.ex. PhpMyAdmin">
					<span class="tip">MySQL-l&ouml;senord</span>
				</li>
				<li title="Den databasen du vill installera XAdmin2 till.">
					<span class="tip">MySQL-databas</span>
				</li>
				<li title="Adressen till servern som MySQL-servern &auml;r p&aring;. Endera en IP-adress eller en url. Har du ingen s&aring; &auml;r det med st&ouml;rsta sannolikhet &quot;localhost&quot;.">
					<span class="tip">MySQL-server</span>
				</li>
			</ol>
		<p>Du m&aring;ste ocks&aring; ha l&auml;st igenom filen &quot;readme.txt&quot; som ligger i huvudmappen. I den filen s&aring; f&aring;r du f&ouml;rklarat f&ouml;r dig hur installationen g&aring;r till, hur du g&ouml;r om vissa fel uppst&aring;r och &auml;ven annan viktig information. Vi kommer inte att ge n&aring;gon support s&aring;vida du inte har l&auml;st igenom filen ordentligt. En annan bra sak som du b&ouml;r h&aring;lla utkik efter &auml;r texter som ser ut <span id="smurftips" class="tip" title="H&auml;r visas sedan nyttig info som &auml;r bra att veta.">s&aring; h&auml;r</span> d&aring; dom inneh&aring;ller ett litet tips om vad som menas. Vi kr&auml;ver ocks&aring; att du godk&auml;nner avtalet nedan innan du forts&auml;tter med installationen.</p>
		<form action="?steg=1" method="post">
			<p><textarea name="avtal" readonly="readonly" rows="10" cols="105">Copyright (c) 2006-<?php echo date("Y"); ?> Gustav Eklundh

All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
    * Neither the name of the XCoders nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
"AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.</textarea></p>

			<p>XAdmin2 &auml;r numera licensierat under BSD-licensen, som &aring;terfinns i textrutan ovan. F&ouml;r att kunna installera och anv&auml;nda XAdmin2 s&aring; kr&auml;vs det att du godk&auml;nner denna licens och dess villkor.</p>
			<p>V&auml;ljer du att inte godk&auml;nna det som st&aring;r i avtalet s&aring; har du ej m&ouml;jlighet att anv&auml;nda XAdmin2.</p>
			<p><input type="submit" name="godkand" value="Ja, jag godk&auml;nner avtalet, starta installationen." /> <input type="submit" name="avboj" value="Nej, jag godkänner inte avtalet." onclick="javascript:document.location('/')" /></p>
		</form>
<?php
	}
?>
</div>
</body>
</html>