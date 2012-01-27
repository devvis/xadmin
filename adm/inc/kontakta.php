<?php
if(!defined("XA_PREFIX") || !main::isLoggedIn()) {
	die;
}
$sql = mysql_query("SELECT name, email FROM ".XA_PREFIX."konton WHERE id = '".$_SESSION['xa_id']."'");
$data = mysql_fetch_assoc($sql);
echo "<h1>Kontakta oss</h1>";
if(isset($_GET['e'])) {
	switch($_GET['e']) {
		case 0:
			echo xa_error("Kunde inte skicka meddelandet. Kontrollera att servern &auml;r konfigurerad f&ouml;r att skicka epost.");
		case 1:
			echo xa_error("Du m&aring;ste fylla i alla f&auml;lt.");
			break;
		case 2:
			echo xa_error("Du m&aring;ste fylla i en giltig epost-adress.");
			break;
	}
}
if(isset($_GET['skickat'])) {
	xa_success("Meddelandet har skickats! Tack f&ouml;r din feedback!");
}
?>
<form action="?kontakta" method="post">
	<fieldset>
		<legend>Kontakta XCoders</legend>
			<label for="xa_namn">Namn</label>
			<input type="text" name="xa_namn" id="xa_namn" value="<?php echo $data['name']; ?>" />
			<label for="xa_mail">Mail</label>
			<input type="text" name="xa_mail" id="xa_mail" value="<?php echo $data['email']; ?>" />
			<label for="xa_arende">&Auml;rende</label>
			<select name="xa_arende" id="xa_arende">
				<option value="bugg">Bugg</option>
				<option value="forslag">F&ouml;rslag</option>
				<option value="ovrigt">&Ouml;vrigt</option>
			</select>
			<label for="xa_msg">Meddelande</label>
			<textarea name="xa_msg" id="xa_msg"></textarea>
			<input type="submit" name="submit" value="Skicka &raquo;" />
	</fieldset>
	</form>
