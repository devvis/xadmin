<?php
if(!defined("XA_PREFIX") || !main::isLoggedIn()) {
	die;
}
?>

			<h1>Medlemmar</h1>

<?php
if(isset($_GET['add'])) {
/*
 * Om användaren vill lägga till en medlem
*/
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
	<form action="?p=medlem" method="post">
		<fieldset>
			<legend>L&auml;gg till en medlem</legend>
			<label for="nick" onclick="nick.focus()">Nick</label>
				<input type="text" name="nick" tabindex="1" value="{$_SESSION['me_nick']}" />
			<label for="alder" onclick="alder.focus()">&Aring;lder</label>
				<input type="text" name="alder" tabindex="2" value="{$_SESSION['me_alder']}" />
			<label for="position" onclick="position.focus()">Position</label>
				<input type="text" name="position" tabindex="3" value="{$_SESSION['me_position']}" />
			<label for="mus" onclick="mus.focus()">Mus</label>
				<input type="text" name="mus" tabindex="4" value="{$_SESSION['me_mus']}" />
			<label for="musmatta" onclick="musmatta.focus()">Musmatta</label>
				<input type="text" name="musmatta" tabindex="5" value="{$_SESSION['me_musmatta']}" />
			<label for="upplosning" onclick="upplosning.focus()">Uppl&ouml;sning</label>
				<input type="text" name="upplosning" tabindex="6" value="{$_SESSION['me_upplosning']}" />
			<label for="karta" onclick="karta.focus()">Favoritkarta</label>
				<input type="text" name="karta" tabindex="7" value="{$_SESSION['me_karta']}" />
			<label for="email" onclick="email.focus()">Email</label>
				<input type="text" name="email" tabindex="8" value="{$_SESSION['me_email']}" />
			<label for="citat" onclick="citat.focus()">Citat</label>
				<input type="text" name="citat" tabindex="9" value="{$_SESSION['me_citat']}" />
			<label for="info" onclick="info.focus()">Info</label>
				<textarea name="info" class="note" tabindex="10">{$_SESSION['me_info']}</textarea>
			<input type="submit" name="skicka" value="Posta medlem &raquo;" tabindex="11" />
		</fieldset>
	</form>
		<p class="right"><a href="?s=medlemmar">Tillbaka</a></p>
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
echo <<<FORM
	<form action="?p=medlem" method="post">
		<fieldset>
			<legend>L&auml;gg till en medlem</legend>
			<label for="nick" onclick="nick.focus()">Nick</label>
				<input type="text" name="nick" tabindex="1" />
			<label for="alder" onclick="alder.focus()">&Aring;lder</label>
				<input type="text" name="alder" tabindex="2" />
			<label for="position" onclick="position.focus()">Position</label>
				<input type="text" name="position" tabindex="3"></textarea>
			<label for="mus" onclick="mus.focus()">Mus</label>
				<input type="text" name="mus" tabindex="4"></textarea>
			<label for="musmatta" onclick="musmatta.focus()">Musmatta</label>
				<input type="text" name="musmatta" tabindex="5"></textarea>
			<label for="upplosning" onclick="upplosning.focus()">Uppl&ouml;sning</label>
				<input type="text" name="upplosning" tabindex="6"></textarea>
			<label for="karta" onclick="karta.focus()">Favoritkarta</label>
				<input type="text" name="karta" tabindex="7"></textarea>
			<label for="email" onclick="email.focus()">Email</label>
				<input type="text" name="email" tabindex="8"></textarea>
			<label for="citat" onclick="citat.focus()">Citat</label>
				<input type="text" name="citat" tabindex="9"></textarea>
			<label for="info" onclick="info.focus()">Info</label>
				<textarea name="info" class="note" tabindex="10"></textarea>
			<input type="submit" name="skicka" value="Posta medlem &raquo;" tabindex="11" />
		</fieldset>
	</form>
		<p class="right"><a href="?s=medlemmar">Tillbaka</a></p>
FORM;
	} # inget-fel-else-slut
} # add-check-if-slut

elseif(isset($_GET['v']) && $_GET['v'] = $_GET['v'] = intval($_GET['v'])) {
/*
 * Visar specifierad medlem
*/
	$sql = mysql_query("SELECT nick, alder, position, mus, musmatta, upplosning, karta, email, info, citat FROM ".XA_PREFIX."medlemmar WHERE id = '".$_GET['v']."'") or exit(mysql_error());
	$data = mysql_fetch_assoc($sql);
	
echo <<<TEXT
	<h2 class="medlem">{$data['nick']}</h2>
	<ul>
		<li>&Aring;lder: {$data['alder']}</li>
		<li>Position: {$data['position']}</li>
		<li>Mus: {$data['mus']}</li>
		<li>Musmatta: {$data['musmatta']}</li>
		<li>Uppl&ouml;sning: {$data['upplosning']}</li>
		<li>Karta: {$data['karta']}</li>
		<li>Email: {$data['email']}</li>
		<li>Citat: {$data['citat']}</li>
		<li>Info: {$data['info']}</li>
	</ul>
	<p class="right"><a href="?s=medlemmar">Tillbaka</a></p>

TEXT;
}

elseif(isset($_GET['e']) && intval($_GET['e'])) {
/*
 * En medlem skall ändras.
*/
	if(!isset($_GET['err'])) {
		$sql = mysql_query("SELECT nick, alder, position, mus, musmatta, upplosning, karta, email, info, citat FROM ".XA_PREFIX."medlemmar WHERE id = '".$_GET['e']."'") or exit(mysql_error());
		$data = mysql_fetch_assoc($sql);
echo <<<FORM
	<form action="?u=medlem&id={$_GET['e']}" method="post">
		<fieldset>
			<legend>Uppdatera en medlem</legend>
			<label for="nick" onclick="nick.focus()">Nick</label>
				<input type="text" name="nick" tabindex="1" value="{$data['nick']}" />
			<label for="alder" onclick="alder.focus()">&Aring;lder</label>
				<input type="text" name="alder" tabindex="2" value="{$data['alder']}" />
			<label for="position" onclick="position.focus()">Position</label>
				<input type="text" name="position" tabindex="3" value="{$data['position']}" />
			<label for="mus" onclick="mus.focus()">Mus</label>
				<input type="text" name="mus" tabindex="4" value="{$data['mus']}" />
			<label for="musmatta" onclick="musmatta.focus()">Musmatta</label>
				<input type="text" name="musmatta" tabindex="5" value="{$data['musmatta']}" />
			<label for="upplosning" onclick="upplosning.focus()">Uppl&ouml;sning</label>
				<input type="text" name="upplosning" tabindex="6" value="{$data['upplosning']}" />
			<label for="karta" onclick="karta.focus()">Favoritkarta</label>
				<input type="text" name="karta" tabindex="7" value="{$data['karta']}" />
			<label for="email" onclick="email.focus()">Email</label>
				<input type="text" name="email" tabindex="8" value="{$data['email']}" />
			<label for="citat" onclick="citat.focus()">Citat</label>
				<input type="text" name="citat" tabindex="9" value="{$data['citat']}" />
			<label for="info" onclick="info.focus()">Info</label>
				<textarea name="info" class="note" tabindex="10">{$data['info']}</textarea>
			<input type="submit" name="skicka" value="Uppdatera &raquo;" tabindex="11" />
		</fieldset>
	</form>
		<p class="right"><a href="?s=medlemmar">Tillbaka</a></p>
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
	<form action="?u=medlem&id={$_GET['e']}" method="post">
		<fieldset>
			<legend>L&auml;gg till en medlem</legend>
			<label for="nick" onclick="nick.focus()">Nick</label>
				<input type="text" name="nick" tabindex="1" value="{$_SESSION['me_nick']}" />
			<label for="alder" onclick="alder.focus()">&Aring;lder</label>
				<input type="text" name="alder" tabindex="2" value="{$_SESSION['me_alder']}" />
			<label for="position" onclick="position.focus()">Position</label>
				<input type="text" name="position" tabindex="3" value="{$_SESSION['me_position']}" />
			<label for="mus" onclick="mus.focus()">Mus</label>
				<input type="text" name="mus" tabindex="4" value="{$_SESSION['me_mus']}" />
			<label for="musmatta" onclick="musmatta.focus()">Musmatta</label>
				<input type="text" name="musmatta" tabindex="5" value="{$_SESSION['me_musmatta']}" />
			<label for="upplosning" onclick="upplosning.focus()">Uppl&ouml;sning</label>
				<input type="text" name="upplosning" tabindex="6" value="{$_SESSION['me_upplosning']}" />
			<label for="karta" onclick="karta.focus()">Favoritkarta</label>
				<input type="text" name="karta" tabindex="7" value="{$_SESSION['me_karta']}" />
			<label for="email" onclick="email.focus()">Email</label>
				<input type="text" name="email" tabindex="8" value="{$_SESSION['me_email']}" />
			<label for="citat" onclick="citat.focus()">Citat</label>
				<input type="text" name="citat" tabindex="9" value="{$_SESSION['me_citat']}" />
			<label for="info" onclick="info.focus()">Info</label>
				<textarea name="info" class="note" tabindex="10">{$_SESSION['me_info']}</textarea>
			<input type="submit" name="skicka" value="Uppdatera &raquo;" tabindex="11" />
		</fieldset>
	</form>
		<p class="right"><a href="?s=medlemmar">Tillbaka</a></p>
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
/*
 * Skall en medlem tas bort? Bäst att vi frågar.
*/
echo <<<NOTICE
	<p class="question">Vill du verkligen ta bort den h&auml;r medlemen?</p>
	<p><a href="?d=medlem&amp;id={$_GET['d']}" class="good">Ja</a> &mdash; <a href="?s=medlemmar" class="bad">Nej</a></p>
NOTICE;
}

else {
	if(isset($_GET['postad'])) {
	# Har en medlem precis postats kan vi meddela att allt gick bra
		echo xa_success("Medlemmen har lagts till.");
	}
	if(isset($_GET['borttagen'])) {
	# Har en medlem precis tagits bort kan vi meddela att allt gick bra, även där
		echo xa_success("Medlemmen har tagits bort.");
	}
	if(isset($_GET['uppdaterad'])) {
	# Har en medlem precis uppdaterats kan vi meddela att allt gick bra, även där
		echo xa_success("Medlemmen har uppdaterats.");
	}
	$sql = mysql_query("SELECT id, nick FROM ".XA_PREFIX."medlemmar ORDER BY id DESC");
	if(mysql_numrows($sql)) {
		echo "<ul>\n";
		while(($data = mysql_fetch_assoc($sql)) !== false) {
			echo '<li><a href="?s=medlemmar&amp;v='.$data['id'].'">'.$data['nick'].'</a><a href="?s=medlemmar&amp;e='.$data['id'].'" class="edit"><img src="./img/edit.png" alt="&Auml;ndra" title="&Auml;ndra nyhet" /></a><a href="?s=medlemmar&amp;d='.$data['id'].'" class="delete"><img src="./img/delete.png" alt="Ta bort" title="Ta bort nyhet" /></a></li>';
		}
		echo "\n</ul>";
	}
	else {
		echo "<p>Inga medlemmar funna.</p>";
	}
	echo '<p class="right"><a href="?s=medlemmar&amp;add">L&auml;gg till medlem</a></p>';

}
