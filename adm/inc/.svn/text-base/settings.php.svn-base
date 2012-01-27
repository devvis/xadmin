<?php
if(!defined("XA_PREFIX") || !main::isLoggedIn()) {
	die;
}


echo "<h1>Inst&auml;llningar</h1>";
if(isset($_GET['uppdaterad'])) {
# Har en nyhet precis uppdaterats kan vi meddela att allt gick bra, även där
	echo xa_success("Inst&auml;llningarna har uppdaterats.");
}
?>

	<form action="?su" method="post">
		<fieldset>
			<legend>Uppdatera inst&auml;llningar</legend>
			<label for="klannamn" onclick="klannamn.focus()">Klannamn</label>
				<input type="text" id="klannamn" name="klannamn" value="<?php echo getClanName(); ?>" />
			<label for="kontaktamail" onclick="kontaktamail.focus()">Kontakta oss-mail</label>
				<input type="text" id="kontaktamail" name="kontaktamail" value="<?php echo getContactMail(); ?>" />
			<label for="kommentarer" onclick="kommentarer.focus()">Aktivera kommentarer till nyheter?</label>
				<select name="kommentarer" id="kommentarer">
					<?php
					if(getCommentStatus() == 1) {
						echo '
					<option value="yes" selected="selected">Ja</option>
					<option value="no">Nej</option>
';
					}
					else {
						echo '
					<option value="yes">Ja</option>
					<option value="no" selected="selected">Nej</option>
';
					}					
					?>
				</select>
			<label for="debug">Aktivera ut&ouml;kad felrapportering?</label>
				<select name="debug" id="debug">
					<?php
					if(getDebugMode() == 1) {
						echo '
					<option value="yes" selected="selected">Ja</option>
					<option value="no">Nej</option>
';
					}
					else {
						echo '
					<option value="yes">Ja</option>
					<option value="no" selected="selected">Nej</option>
';
					}					
					?>
				</select>
			<input type="submit" name="skicka" value="Spara &raquo;" />
		</fieldset>
	</form>
<?php
	if(checkWritePerms($backup)) {
?>
	<form action="?backup" method="post">
		<fieldset>
			<legend>Databasbackup</legend>
				<label for="backup">Ta en backup av databasen</label>
				<input type="submit" name="backup" id="backup" value="Backup!" />
		</fieldset>
	</form>
<?php
	}
	else {
		echo "<p>Se till att {$backup} &auml;r skrivbar av webbservern f&ouml;r att kunna g&ouml;ra en backup.</p>";
	}
?>