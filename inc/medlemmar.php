<?php
# medlemmar.php
# Även medlemmar har samma uppbyggnad som nyheter och main, därför är det egentligen inga konstigheter här heller
# Vi kör på :)

if(isset($_GET['visa']) && $_GET['visa'] = intval($_GET['visa'])) {
# Ska vi visa en speciell medlem? :)
	echo "<h1>".getMemberItem("nick", $_GET['visa'])."</h1>"; # En rubrik med spelarens nick är alltid trevligt.
	# Samma som i matcher.php; vi förbereder all data för utskrift:
	$alder = getMemberItem("alder", $_GET['visa']);
	$position = getMemberItem("position", $_GET['visa']);
	$mus = getMemberItem("mus", $_GET['visa']);
	$musmatta = getMemberItem("musmatta", $_GET['visa']);
	$upplosning = getMemberItem("upplosning", $_GET['visa']);
	$karta = getMemberItem("karta", $_GET['visa']);
	$email = getMemberItem("email", $_GET['visa']);
	$citat = getMemberItem("citat", $_GET['visa']);
	$info = getMemberItem("info", $_GET['visa']);

	# All information är hämtad, nu är det bara att skriva ut den :)

echo <<<INFO
	<ul>
		<li>&Aring;lder: {$alder}</li>
		<li>Position i klanen: {$position}</li>
		<li>Mus: {$mus}</li>
		<li>Musmatta: {$musmatta}</li>
		<li>Uppl&ouml;sning: {$upplosning}</li>
		<li>Favoritkarta: {$karta}</li>
		<li>Email: {$email}</li>
		<li>Citat: {$citat}</li>
	</ul>
	<h2>&Ouml;vrig information</h2>
	{$info}
	<p class="link"><a href="?s=medlemmar">Tillbaka</a></p>
INFO;

	# All information är nu utskriven tillsammans med en länk tillbaka till listan :)

}
else {
# Tydligen inte, vi visar alla i stället.
	$sql = mysql_query("SELECT id FROM ".XA_PREFIX."medlemmar ORDER BY id DESC");
	echo "<h1>Medlemmar</h1>";
	echo "<ul>";
	while(($data = mysql_fetch_assoc($sql)) !== false) {
		echo "<li><a href=\"?s=medlemmar&visa=".$data['id']."\">".getMemberItem("nick", $data['id'])."</a></li>";
		# En länk till spelaren är utskriven :)
	}
	echo "</ul>";
}