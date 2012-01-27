<?php
if(!defined("XA_PREFIX") || !main::isLoggedIn()) {
	die;
}
$sql = mysql_query("SELECT value FROM ".XA_PREFIX."settings WHERE setting = 'historia' LIMIT 1");
$data = mysql_fetch_assoc($sql);
?>
<h1>Historia</h1>
<?php
if(isset($_GET['e'])) {
	switch($_GET['e']) {
		case 1:
			echo xa_error("Du m&aring;ste posta lite data ocks&aring;.");
			break;
	}
}
if(isset($_GET['uppdaterad'])) {
	echo xa_success("Historian &auml;r uppdaterad.");
}
# Notera fulhacket på action nedan \o/
?>
<form action="?u=historia&amp;id=1" method="post">
	<fieldset>
		<legend>Klanens historia</legend>
		<textarea name="historia" tabindex="1"><?php echo $data['value']; ?></textarea>
		<input type="submit" name="uppdatera" value="Uppdatera &raquo;" />	
	</fieldset>
</form>
