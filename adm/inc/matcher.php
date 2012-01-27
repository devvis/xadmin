<?php
if(!defined("XA_PREFIX") || !main::isLoggedIn()) {
	die;
}
?>
			<h1>Matcher</h1>

<?php
if(isset($_GET['add'])) {
/*
 * Om användaren vill lägga till en match
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
	<form action="?p=match" method="post">
		<fieldset>
			<legend>L&auml;gg till en match</legend>
			<label for="motstand" onclick="motstand.focus()">Motst&aring;nd</label>
				<input type="text" name="motstand" tabindex="1" value="{$_SESSION['ma_motstand']}" />
			<label for="karta" onclick="karta.focus()">Karta</label>
				<input type="text" name="karta" tabindex="2" value="{$_SESSION['ma_karta']}" />
			<label for="typ" onclick="typ.focus()">Typ av match</label>
				<input type="text" name="typ" tabindex="3" value="{$_SESSION['ma_typ']}" />
			<label for="date" onclick="date.focus()">Datum</label>
				<input type="text" name="date" id="date" value="{$_SESSION['ma_date']}" />
			<label for="tid" onclick="tid.focus()">Tid</label>
				<input type="text" name="tid" id="tid" value="{$_SESSION['ma_tid']}" />
			<label for="resultat1" onclick="resultat1.focus()">Resultat</label>
				<select name="resultat1" tabindex="9">
					<option value="{$_SESSION['ma_resultat1']}" selected="selected">{$_SESSION['ma_resultat1']}</option>
					<option value="00">00</option>
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>
					<option value="04">04</option>
					<option value="05">05</option>
					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>
					<option value="16">16</option>
					<option value="17">17</option>
					<option value="18">18</option>
				</select> &mdash;
				<select name="resultat2" tabindex="10">
					<option value="{$_SESSION['ma_resultat2']}" selected="selected">{$_SESSION['ma_resultat2']}</option>
					<option value="00">00</option>
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>
					<option value="04">04</option>
					<option value="05">05</option>
					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>
					<option value="16">16</option>
					<option value="17">17</option>
					<option value="18">18</option>
				</select>
			<label for="kommentar" onclick="kommentar.focus()">Kommentar</label>
				<textarea name="kommentar" class="note" tabindex="11">{$_SESSION['ma_kommentar']}</textarea>
			<input type="submit" name="skicka" value="Posta match &raquo;" tabindex="12" />
		</fieldset>
	</form>
		<p class="right"><a href="?s=matcher">Tillbaka</a></p>
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
	<form action="?p=match" method="post">
		<fieldset>
			<legend>L&auml;gg till en match</legend>
			<label for="motstand" onclick="motstand.focus()">Motst&aring;nd</label>
				<input type="text" name="motstand" tabindex="1" />
			<label for="karta" onclick="karta.focus()">Karta</label>
				<input type="text" name="karta" tabindex="2" />
			<label for="typ" onclick="typ.focus()">Typ av match</label>
				<input type="text" name="typ" tabindex="3" />
			<label for="date" onclick="date.focus()">Datum</label>
				<input type="text" name="date" id="date" />
			<label for="tid" onclick="tid.focus()">Tid</label>
				<input type="text" name="tid" id="tid" />
			<label for="resultat1" onclick="resultat1.focus()">Resultat</label>
				<select name="resultat1" tabindex="9">
					<option value="00">00</option>
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>
					<option value="04">04</option>
					<option value="05">05</option>
					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>
					<option value="16">16</option>
					<option value="17">17</option>
					<option value="18">18</option>
				</select> &mdash;
				<select name="resultat2" tabindex="10">
					<option value="00">00</option>
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>
					<option value="04">04</option>
					<option value="05">05</option>
					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>
					<option value="16">16</option>
					<option value="17">17</option>
					<option value="18">18</option>
				</select>
			<label for="kommentar" onclick="kommentar.focus()">Kommentar</label>
				<textarea name="kommentar" class="note" tabindex="11"></textarea>
			<input type="submit" name="skicka" value="Posta match &raquo;" tabindex="12" />
		</fieldset>
	</form>
		<p class="right"><a href="?s=matcher">Tillbaka</a></p>
FORM;
	} # inget-fel-else-slut
} # add-check-if-slut

elseif(isset($_GET['v']) && $_GET['v'] = intval($_GET['v'])) {
/*
 * Visar specifierad match
*/
	$sql = mysql_query("SELECT motstand, karta, typ, datum, tid, resultat, kommentar FROM ".XA_PREFIX."matcher WHERE id = '".$_GET['v']."'") or exit(mysql_error());
	$data = mysql_fetch_assoc($sql);
	#$data['kommentar'] = nl2br($data['kommentar']);
	$namn = getClanName();
	$r = explode("-", $data['resultat']);
	$res = getMatchWinner($data['resultat']);
	if($res == 1) {
		$text = "<span class=\"win\">{$r[0]}</span>-<span class=\"loose\">{$r[1]}</span>";
	}
	elseif($res == 0) {
		$text = "<span class=\"loose\">{$r[0]}</span>-<span class=\"win\">{$r[1]}</span>";
	}
	else {
		$text = "<span class=\"draw\">{$r[0]}</span>-<span class=\"draw\">{$r[1]}</span>";
	}
	
echo <<<TEXT
	<h2 class="match">{$namn} vs. {$data['motstand']}</h2>
	<ul>
		<li>Resultat: {$text}</li>
		<li>Matchtyp: {$data['typ']}</li>
		<li>Kommentar: {$data['kommentar']}</li>
	</ul>
	<p class="right">Spelad den {$data['datum']}, klockan {$data['tid']}.</p>
	<p class="right"><a href="?s=matcher">Tillbaka</a></p>

TEXT;
}

elseif(isset($_GET['e']) && $_GET['e'] = intval($_GET['e'])) {
/*
 * En match skall ändras.
*/
	if(!isset($_GET['err'])) {
		$sql = mysql_query("SELECT motstand, karta, typ, datum, tid, resultat, kommentar FROM ".XA_PREFIX."matcher WHERE id = '".$_GET['e']."'") or exit(mysql_error());
		$data = mysql_fetch_assoc($sql);
		$res = explode("-", $data['resultat']);
echo <<<FORM
	<form action="?u=match&id={$_GET['e']}" method="post">
		<fieldset>
			<legend>Uppdatera match</legend>
			<label for="motstand" onclick="motstand.focus()">Motst&aring;nd</label>
				<input type="text" name="motstand" tabindex="1" value="{$data['motstand']}" />
			<label for="karta" onclick="karta.focus()">Karta</label>
				<input type="text" name="karta" tabindex="2" value="{$data['karta']}" />
			<label for="typ" onclick="typ.focus()">Typ av match</label>
				<input type="text" name="typ" tabindex="3" value="{$data['typ']}" />
			<label for="date" onclick="date.focus()">Datum</label>
				<input type="text" name="date" id="date" value="{$data['datum']}" />
			<label for="tid" onclick="tid.focus()">Tid</label>
				<input type="text" name="tid" id="tid" value="{$data['tid']}" />
			<label for="resultat1" onclick="resultat1.focus()">Resultat</label>
				<select name="resultat1" tabindex="9">
					<option value="{$res[0]}" selected="selected">{$res[0]}</option>
					<option value="00">00</option>
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>
					<option value="04">04</option>
					<option value="05">05</option>
					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>
					<option value="16">16</option>
					<option value="17">17</option>
					<option value="18">18</option>
				</select> &mdash;
				<select name="resultat2" tabindex="10">
					<option value="{$res[1]}" selected="selected">{$res[1]}</option>
					<option value="00">00</option>
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>
					<option value="04">04</option>
					<option value="05">05</option>
					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>
					<option value="16">16</option>
					<option value="17">17</option>
					<option value="18">18</option>
				</select>
			<label for="kommentar" onclick="kommentar.focus()">Kommentar</label>
				<textarea name="kommentar" class="note" tabindex="11">{$data['kommentar']}</textarea>
			<input type="submit" name="skicka" value="Uppdatera &raquo;" tabindex="12" />
		</fieldset>
	</form>
		<p class="right"><a href="?s=matcher">Tillbaka</a></p>
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
	<form action="?u=match&id={$_GET['e']}" method="post">
		<fieldset>
			<legend>L&auml;gg till en match</legend>
			<label for="motstand" onclick="motstand.focus()">Motst&aring;nd</label>
				<input type="text" name="motstand" tabindex="1" value="{$_SESSION['ma_motstand']}" />
			<label for="karta" onclick="karta.focus()">Karta</label>
				<input type="text" name="karta" tabindex="2" value="{$_SESSION['ma_karta']}" />
			<label for="typ" onclick="typ.focus()">Typ av match</label>
				<input type="text" name="typ" tabindex="3" value="{$_SESSION['ma_typ']}" />
			<label for="date" onclick="date.focus()">Datum</label>
				<input type="text" name="date" id="date" value="{$_SESSION['ma_date']}" />
			<label for="tid" onclick="tid.focus()">Tid</label>
				<input type="text" name="tid" id="tid" value="{$_SESSION['ma_tid']}" />
			<label for="resultat1" onclick="resultat1.focus()">Resultat</label>
				<select name="resultat1" tabindex="9">
					<option value="{$_SESSION['ma_resultat1']}" selected="selected">{$_SESSION['ma_resultat1']}</option>
					<option value="00">00</option>
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>
					<option value="04">04</option>
					<option value="05">05</option>
					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>
					<option value="16">16</option>
					<option value="17">17</option>
					<option value="18">18</option>
				</select> &mdash;
				<select name="resultat2" tabindex="10">
					<option value="{$_SESSION['ma_resultat2']}" selected="selected">{$_SESSION['ma_resultat2']}</option>
					<option value="00">00</option>
					<option value="01">01</option>
					<option value="02">02</option>
					<option value="03">03</option>
					<option value="04">04</option>
					<option value="05">05</option>
					<option value="06">06</option>
					<option value="07">07</option>
					<option value="08">08</option>
					<option value="09">09</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>
					<option value="16">16</option>
					<option value="17">17</option>
					<option value="18">18</option>
				</select>
			<label for="kommentar" onclick="kommentar.focus()">Kommentar</label>
				<textarea name="kommentar" class="note" tabindex="11">{$_SESSION['ma_kommentar']}</textarea>
			<input type="submit" name="skicka" value="Uppdatera &raquo;" tabindex="12" />
		</fieldset>
	</form>
		<p class="right"><a href="?s=matcher">Tillbaka</a></p>
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
 * Skall en match tas bort? Bäst att vi frågar.
*/
echo <<<NOTICE
	<p class="question">Vill du verkligen ta bort den h&auml;r matchen?</p>
	<p><a href="?d=match&amp;id={$_GET['d']}" class="good">Ja</a> &mdash; <a href="?s=matcher" class="bad">Nej</a></p>
NOTICE;
}

else {
	if(isset($_GET['klar'])) {
	# Har en match precis postats kan vi meddela att allt gick bra
		echo xa_success("Matchen har lagts till.");
	}
	if(isset($_GET['borttagen'])) {
	# Har en match precis tagits bort kan vi meddela att allt gick bra, även där
		echo xa_success("Matchen har tagits bort.");
	}
	if(isset($_GET['uppdaterad'])) {
	# Har en match precis uppdaterats kan vi meddela att allt gick bra, även där
		echo xa_success("Matchen har uppdaterats.");
	}
	$sql = mysql_query("SELECT id, motstand FROM ".XA_PREFIX."matcher ORDER BY id DESC");
	if(mysql_numrows($sql)) {
		echo "<ul>\n";
		while(($data = mysql_fetch_assoc($sql)) !== false) {
			echo '<li><a href="?s=matcher&amp;v='.$data['id'].'">'.getClanName().' vs. '.$data['motstand'].'</a><a href="?s=matcher&amp;e='.$data['id'].'" class="edit"><img src="./img/edit.png" alt="&Auml;ndra" title="&Auml;ndra match" /></a><a href="?s=matcher&amp;d='.$data['id'].'" class="delete"><img src="./img/delete.png" alt="Ta bort" title="Ta bort match" /></a></li>';
		}
		echo "\n</ul>";
	}
	else {
		echo "<p>Inga matcher funna.</p>";
	}
	echo '<p class="right"><a href="?s=matcher&amp;add">L&auml;gg till match</a></p>';

}
