<?php
if(!defined("XA_PREFIX") || !isHeadAdmin($_SESSION['xa_id']) || !main::isLoggedIn()) {
	die;
}
?>

<h1>Konton</h1>

<?php

if(isset($_GET['add'])) {
	if(isset($_GET['err'])) {
	/*
	 * Om ett felmeddelande existerars
	*/
		switch($_GET['err']) {
			case 1:
			# Användaren har inte skickat någon post-data (något blev knas :\)
				echo xa_error("Du m&aring;ste faktiskt skriva n&aring;got ocks&aring;.");
				break;
			case 2:
			# Användaren har inte fyllt i alla fält.
				echo xa_error("Du m&aring;ste fylla i alla f&auml;lt.");
echo <<<FORM
	<form action="?p=konto" method="post">
		<fieldset>
			<legend>Skapa konto</legend>
			<label for="uname">Anv&auml;ndarnamn</label>
				<input type="text" name="uname" value="{$_SESSION['ko_uname']}" />
			<label for="pass">L&ouml;senord</label>
				<input type="text" name="pass" value="{$_SESSION['ko_pass']}" />
			<label for="name">Namn</label>
				<input type="text" name="name" value="{$_SESSION['ko_name']}" />
			<label for="uclass">Anv&auml;ndarklass</label>
				<select name="uclass">
FORM;
					if($_SESSION['ko_uclass'] == "a") {
						echo '<option value="a" selected="selected">Huvudadministrat&ouml;r</option>';
						echo '<option value="n">Normal anv&auml;ndare</option>';
					}
					else {
						echo '<option value="n" selected="selected">Normal anv&auml;ndare</option>';
						echo '<option value="a">Huvudadministrat&ouml;r</option>';
					}
echo <<<FORM
				</select>
			<label for="email">Epost-adress</label>
				<input type="text" name="email" value="{$_SESSION['ko_email']}" />
			
			<input type="submit" name="skicka" value="Skapa konto &raquo;" />
		</fieldset>
	</form>
		<p class="right"><a href="?s=konton">Tillbaka</a></p>
FORM;
			break;
			
			default:
			# Användaren är dålig och gör egna felkoder
				echo xa_warning("Inte leka med egna felkoder.");
				break;
		}
	}
	else {
	/*
	 * Om inget felmeddelande existerar visar vi formuläret
	*/
	$pwgen = main::pwGen(); # Slumpar fram ett lösenord
echo <<<FORM
	<form action="?p=konto" method="post">
		<fieldset>
			<legend>Skapa konto</legend>
			<label for="uname">Anv&auml;ndarnamn</label>
				<input type="text" name="uname" />
			<label for="pass">L&ouml;senord</label>
				<input type="text" name="pass" value="{$pwgen}" />
			<label for="name">Namn</label>
				<input type="text" name="name" />
			<label for="uclass">Anv&auml;ndarklass</label>
				<select name="uclass">
					<option value="n" selected="selected">Normal anv&auml;ndare</option>
					<option value="a">Huvudadministrat&ouml;r</option>
				</select>
			<label for="email">Epost-adress</label>
				<input type="text" name="email" />
			
			<input type="submit" name="skicka" value="Skapa konto &raquo;" />
		</fieldset>
	</form>
		<p class="right"><a href="?s=konton">Tillbaka</a></p>
FORM;
	}
}
elseif(isset($_GET['e']) && $_GET['e'] = intval($_GET['e'])) {
	if(!isset($_GET['err'])) {
		$sql = mysql_query("SELECT name, uclass, email FROM ".XA_PREFIX."konton WHERE id = '".$_GET['e']."'") or exit(mysql_error());
		$data = mysql_fetch_assoc($sql);
echo <<<FORM
	<form action="?u=konto&amp;id={$_GET['e']}" method="post">
		<fieldset>
			<legend>&Auml;ndra konto</legend>
FORM;
# TODO: Fixa lösenordsbyte
#			<label for="pass">L&ouml;senord</label>
#				<input type="password" name="opass" />
#			<label for="pass">Nytt l&ouml;senord (l&auml;mnas tomt om inget skall &auml;ndras)</label>
#				<input type="password" name="npass" />
echo <<<FORM
			<label for="name">Namn</label>
				<input type="text" name="name" value="{$data['name']}" />
			<label for="uclass">Anv&auml;ndarklass</label>
				<select name="uclass">
FORM;
					if($data['uclass'] == "a") {
						echo '<option value="a" selected="selected">Huvudadministrat&ouml;r</option>';
						echo '<option value="n">Normal anv&auml;ndare</option>';
					}
					else {
						echo '<option value="n" selected="selected">Normal anv&auml;ndare</option>';
						echo '<option value="a">Huvudadministrat&ouml;r</option>';
					}
echo <<<FORM
				</select>
			<label for="email">Epost-adress</label>
				<input type="text" name="email" value="{$data['email']}" />
			
			<input type="submit" name="skicka" value="Uppdatera &raquo;" />
		</fieldset>
	</form>
		<p class="right"><a href="?s=konton">Tillbaka</a></p>
FORM;
	}
	else {
		switch($_GET['err']) {
			case 1:
			# Användaren har inte skickat någon post-data (något blev knas :\)
				echo xa_error("Du m&aring;ste faktiskt skriva n&aring;got ocks&aring;.");
				echo '<p class="right"><a href="?s=medlemmar&amp;add">Tillbaka</a></p>';
				break;
			case 2:
			# Användaren har inte fyllt i alla fält.
				echo xa_error("Du m&aring;ste fylla i alla f&auml;lt.");
echo <<<FORM
	<form action="?u=konto&amp;id={$_GET['e']}" method="post">
		<fieldset>
			<legend>&Auml;ndra konto</legend>
			<label for="name">Namn</label>
				<input type="text" name="name" value="{$_SESSION['ko_name']}" />
			<label for="uclass">Anv&auml;ndarklass</label>
				<select name="uclass">
FORM;
					if($_SESSION['ko_uclass'] == "a") {
						echo '<option value="a" selected="selected">Huvudadministrat&ouml;r</option>';
						echo '<option value="n">Normal anv&auml;ndare</option>';
					}
					else {
						echo '<option value="n" selected="selected">Normal anv&auml;ndare</option>';
						echo '<option value="a">Huvudadministrat&ouml;r</option>';
					}
echo <<<FORM
				</select>
			<label for="email">Epost-adress</label>
				<input type="text" name="email" value="{$_SESSION['ko_email']}" />
			
			<input type="submit" name="skicka" value="Uppdatera &raquo;" />
		</fieldset>
	</form>
		<p class="right"><a href="?s=konton">Tillbaka</a></p>
FORM;
			break;
			
			default:
			# Användaren är dålig och gör egna felkoder
				echo xa_warning("Inte leka med egna felkoder.");
				break;
		}
	}
}

elseif(isset($_GET['d']) && $_GET['d'] = intval($_GET['d'])) {
	if($_GET['d'] == $_SESSION['xa_id']) {
		echo xa_warning("Du kan inte ta bort ditt eget konto.");
		echo '<p class="right"><a href="?s=konton">Tillbaka</a></p>';
	}
	else {
echo <<<NOTICE
	<p class="question">Vill du verkligen ta bort det h&auml;r kontot?</p>
	<p><a href="?d=konto&amp;id={$_GET['d']}" class="good">Ja</a> &mdash; <a href="?s=konton" class="bad">Nej</a></p>
NOTICE;
	}
}

else {
	if(isset($_GET['klar'])) {
	# Har en konto precis postats kan vi meddela att allt gick bra
		echo xa_success("Kontot har lagts till.");
	}
	if(isset($_GET['borttagen'])) {
	# Har en konto precis tagits bort kan vi meddela att allt gick bra, även där
		echo xa_success("Kontot har tagits bort.");
	}
	if(isset($_GET['uppdaterad'])) {
	# Har en konto precis uppdaterats kan vi meddela att allt gick bra, även där
		echo xa_success("Kontot har uppdaterats.");
	}
	if(isset($_GET['fel'])) {
		echo xa_warning("Du kan inte byta anv&auml;ndarklass p&aring; dig sj&auml;lv.");
	}
	$sql = mysql_query("SELECT id, uname FROM ".XA_PREFIX."konton ORDER BY id DESC") or exit(mysql_error());
	echo "<ul>";
	while(($data = mysql_fetch_assoc($sql)) !== false) {
		echo "<li><a href=\"?s=konton&e=".$data['id']."\">".$data['uname']."</a><a href=\"?s=konton&amp;d=".$data['id']."\" class=\"delete\"><img src=\"./img/delete.png\" alt=\"Ta bort\" title=\"Ta bort konto\" /></a></li>";
	}
	echo "</ul>";
echo '<p class="right"><a href="?s=konton&amp;add">L&auml;gg till konto</a></p>';

}
