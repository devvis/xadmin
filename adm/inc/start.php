<?php
if(!defined("XA_PREFIX") || !main::isLoggedIn()) {
	die;
}
?>
				<h1>Startsida</h1>
				<div class="row">
					<div class="left">
<?php
	if(!main::isUpToDate()) {
		echo xa_info('Det finns en uppdatering till XAdmin - <a href="http://xcoders.info/">Klicka h&auml;r f&ouml;r att ladda ner</a>.');
	}
if(isset($_GET['err'])) {
	switch($_GET['err']) {
		case 100:
			echo xa_warning("Den beg&auml;rda handlingen kunde inte utf&ouml;ras d&aring; ditt konto saknar r&auml;ttigheter.");
			break;
	}
}
if(main::firstTime($_SESSION['xa_id'])) {
#echo xa_info("V&auml;lkommen till XAdmin! Eftersom detta &auml;r f&ouml;rsta g&aring;ngen du loggar in kan du nu se en <a href=\"./inc/tut/tutorial1.html\" title=\"Introduktion till XAdmin\" onclick=\"Modalbox.show(this.href, {title: this.title, width: 600}); return false;\">introduktion</a> till XAdmin.");
	echo xa_info("V&auml;lkommen till XAdmin! Hoppas att du kommer ha nytta av systemet!");
}
?>
						<h2>Information</h2>
						<dl>
							<dt>Antal inloggningar:</dt>
								<dd><?php $data = mysql_fetch_assoc(mysql_query("SELECT ltimes FROM ".XA_PREFIX."konton WHERE id = '".$_SESSION['xa_id']."'")); echo $data['ltimes']; ?></dd>
							<dt>Installerad version:</dt>
								<dd><?php echo XA_VERSION."-".XA_BUILD; ?></dd>
							<dt>Senaste versionen:</dt>
								<dd><?php echo main::isUpToDate(1); ?></dd>
						</dl>
					</div>

					<div class="right">
<?php
echo main::getNews("http://xcoders.info/xn.php", 0);
?>
						<p><a href=\"?s=xadmin&amp;nyheter\">L&auml;s mer...</a></p>
					</div>
				</div>