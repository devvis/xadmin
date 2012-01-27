<?php
if(!defined("XA_PREFIX") || !main::isLoggedIn()) {
	die;
}
?>

			<h1>Nyheter</h1>

<?php
if(isset($_GET['skriv'])) {
/*
 * Om användaren vill skriva en nyhet
*/
	if(isset($_GET['err'])) {
	/*
	 * Om ett felmeddelande existerar
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

<form action="?p=nyhet" method="post">
	<fieldset>
		<legend>Skriv en nyhet</legend>
		<label for="titel" onclick="titel.focus()">Titel</label>
			<input type="text" name="titel" value="{$_SESSION['n_titel']}" tabindex="1" />
		<label for="skribent" onclick="skribent.focus()">Skribent</label>
			<input type="text" name="skribent" readonly="readonly" value="{$_SESSION['xa_user']}" tabindex="2" />
		<label for="nyhet" onclick="nyhet.focus()">Nyhet</label>
			<textarea name="nyhet" tabindex="3">{$_SESSION['n_nyhet']}</textarea>
		<input type="submit" name="skicka" value="Posta nyhet &raquo;" tabindex="4" />
	</fieldset>
</form>
	<p class="right"><a href="?s=nyheter">Tillbaka</a></p>
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

<form action="?p=nyhet" method="post">
	<fieldset>
		<legend>Skriv en nyhet</legend>
		<label for="titel" onclick="titel.focus()">Titel</label>
		<input type="text" name="titel" tabindex="1" />
		<label for="skribent" onclick="skribent.focus()">Skribent</label>
		<input type="text" name="skribent" readonly="readonly" value="{$_SESSION['xa_user']}" tabindex="2" />
		<label for="nyhet" onclick="nyhet.focus()">Nyhet</label>
		<textarea name="nyhet" tabindex="3"></textarea>
		<input type="submit" name="skicka" value="Posta nyhet &raquo;" tabindex="4" />
	</fieldset>
</form>
	<p class="right"><a href="?s=nyheter">Tillbaka</a></p>
FORM;
	}
}

elseif(isset($_GET['v']) && $_GET['v'] = intval($_GET['v'])) {
/*
 * Visar specifierad nyhet
*/
	$sql = mysql_query("SELECT titel, nyhet, skribent, datum FROM ".XA_PREFIX."nyheter WHERE id = '".$_GET['v']."'") or exit(mysql_error());
	$data = mysql_fetch_assoc($sql);
#	$data['nyhet'] = nl2br($data['nyhet']);
echo <<<TEXT
	<h2 class="nyhet">{$data['titel']}</h2>
	<p class="right">Skriven av <em>{$data['skribent']}</em> den {$data['datum']}.</p>
	<div class="nyhet">{$data['nyhet']}</div>
TEXT;
	$sql = mysql_query("SELECT id, namn, kommentar, datum FROM ".XA_PREFIX."kommentarer WHERE nid = '".$_GET['v']."' ORDER BY id DESC");
	if(mysql_num_rows($sql) > 0) {
		while(($data = mysql_fetch_assoc($sql)) !== false) {
			$data['datum'] = formatTimestamp($data['datum']);
			$data['kommentar'] = nl2br($data['kommentar']);
echo <<<COMMENT
			<div class="kommentar">
				<p>Skriven av: <em>{$data['namn']}</em>, den <em>{$data['datum']}</em> <a href="?s=nyheter&amp;dc={$data['id']}"><img src="./img/delete2.png" alt="Ta bort" title="Ta bort kommentar" /></a></p>
				<p>{$data['kommentar']}</p>
			</div>
COMMENT;
		}
	}
	else {
		echo "<div class=\"kommentar\"><p>Det finns inga kommentarer.</p></div>";
	}
	echo '	<p class="right"><a href="?s=nyheter">Tillbaka</a></p>';
}

elseif(isset($_GET['e']) && $_GET['e'] = intval($_GET['e'])) {
/*
 * En nyhet skall ändras.
*/
	if(!isset($_GET['err'])) {
		$sql = mysql_query("SELECT titel, nyhet, skribent FROM ".XA_PREFIX."nyheter WHERE id = '".$_GET['e']."'") or exit(mysql_error());
		$data = mysql_fetch_assoc($sql);
echo <<<FORM
	<form action="?u=nyhet&id={$_GET['e']}" method="post">
		<fieldset>
			<legend>Skriv en nyhet</legend>
			<label for="titel" onclick="titel.focus()">Titel</label>
			<input type="text" name="titel" tabindex="1" value="{$data['titel']}" />
			<label for="skribent" onclick="skribent.focus()">Skribent</label>
			<input type="text" name="skribent" readonly="readonly" value="{$data['skribent']}" tabindex="2" />
			<label for="nyhet" onclick="nyhet.focus()">Nyhet</label>
			<textarea name="nyhet" tabindex="3">{$data['nyhet']}</textarea>
			<input type="submit" name="skicka" value="Uppdatera nyhet &raquo;" tabindex="4" />
		</fieldset>
	</form>
		<p class="right"><a href="?s=nyheter">Tillbaka</a></p>
FORM;
	}
	else {
		switch($_GET['err']) {
			case 1:
			# Användaren har inte skickat någon post-data (något blev knas :\)
				echo xa_error("Du m&aring;ste faktiskt skriva n&aring;got ocks&aring;.");
				break;
			case 2:
			# Användaren har inte fyllt i alla fält.
				echo xa_error("Du m&aring;ste fylla i alla f&auml;lt.");
				
echo <<<FORM

<form action="?u=nyhet&id={$_GET['e']}" method="post">
	<fieldset>
		<legend>Skriv en nyhet</legend>
		<label for="titel" onclick="titel.focus()">Titel</label>
		<input type="text" name="titel" value="{$_SESSION['n_titel']}" tabindex="1" />
		<label for="skribent" onclick="skribent.focus()">Skribent</label>
		<input type="text" name="skribent" readonly="readonly" value="{$_SESSION['xa_user']}" tabindex="2" />
		<label for="nyhet" onclick="nyhet.focus()">Nyhet</label>
		<textarea name="nyhet" tabindex="3">{$_SESSION['n_nyhet']}</textarea>
		<input type="submit" name="skicka" value="Posta nyhet &raquo;" tabindex="4" />
	</fieldset>
</form>
	<p class="right"><a href="?s=nyheter">Tillbaka</a></p>
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
 * Skall en nyhet tas bort? Bäst att vi frågar.
*/
echo <<<NOTICE
	<p class="question">Vill du verkligen ta bort den h&auml;r nyheten?</p>
	<p><a href="?d=nyhet&amp;id={$_GET['d']}" class="good">Ja</a> &mdash; <a href="?s=nyheter" class="bad">Nej</a></p>
NOTICE;
}

elseif(isset($_GET['dc']) && $_GET['dc'] = intval($_GET['dc'])) {
/*
 * Skall en kommentar tas bort? Bäst att vi frågar.
*/
echo <<<NOTICE
	<p class="question">Vill du verkligen ta bort den h&auml;r kommentaren?</p>
	<p><a href="?d=kommentar&amp;id={$_GET['dc']}" class="good">Ja</a> &mdash; <a href="?s=nyheter" class="bad">Nej</a></p>
NOTICE;
}

else {
/*
 * Om inget särskilt händer visar vi alla nyheter i databasen
*/
	if(isset($_GET['postad'])) {
	# Har en nyhet precis postats kan vi meddela att allt gick bra
		echo xa_success("Nyheten har lagts till.");
	}
	if(isset($_GET['borttagen'])) {
	# Har en nyhet precis tagits bort kan vi meddela att allt gick bra, även där
		echo xa_success("Nyheten har tagits bort.");
	}
	if(isset($_GET['cborttagen'])) {
	# Har en kommentar precis tagits bort kan vi meddela att allt gick bra, även där
		echo xa_success("Kommentaren har tagits bort.");
	}
	if(isset($_GET['uppdaterad'])) {
	# Har en nyhet precis uppdaterats kan vi meddela att allt gick bra, även där
		echo xa_success("Nyheten har uppdaterats.");
	}
	$sql = mysql_query("SELECT id, titel FROM ".XA_PREFIX."nyheter ORDER BY id DESC");
	if(mysql_num_rows($sql)) {
		echo "<ul>\n";
		while(($data = mysql_fetch_assoc($sql)) !== false) {
			echo '<li><a href="?s=nyheter&amp;v='.$data['id'].'">'.$data['titel'].'</a><a href="?s=nyheter&amp;e='.$data['id'].'" class="edit"><img src="./img/edit.png" alt="&Auml;ndra" title="&Auml;ndra nyhet" /></a><a href="?s=nyheter&amp;d='.$data['id'].'" class="delete"><img src="./img/delete.png" alt="Ta bort" title="Ta bort nyhet" /></a></li>';
		}
		echo "\n</ul>";
	}
	else {
		echo "<p>Inga nyheter funna.</p>";
	}
	echo '<p class="right"><a href="?s=nyheter&amp;skriv">Posta nyhet</a></p>';

}
