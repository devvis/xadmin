<?php
# matcher.php
# Principen h�r �r densamma som f�r nyheter, d�rf�r kommer f�rklaringen inte vara lika utf�rlig h�r
# F�r mer information, se main.php och nyheter.php :)

if(isset($_GET['visa']) && $_GET['visa'] = intval($_GET['visa'])) {
	# Vi ska nu visa en match!
	echo "<h1>".getClanName()." vs. ".getMatchItem("motstand", $_GET['visa'])."</h1>";
	# getClanName() retunerar er klans namn, och getMatchInfo("motstand") retunerar motst�ndarnas klannamn
	# Vi g�r klart all information f�r presentation;
	$karta = getMatchItem("karta", $_GET['visa']);
	$typ = getMatchItem("typ", $_GET['visa']);
	$datum = getMatchItem("datum", $_GET['visa']);
	$tid = getMatchItem("tid", $_GET['visa']);
	$resultat = getMatchItem("resultat", $_GET['visa']);
	$kommentar = getMatchItem("kommentar", $_GET['visa']);
	
	# V�rt att notera �r att resultatet lagras i f�ljande format "ert resultat"-"motst�ndarnas resultat"
	# ex: Ni vinner med 10-3, resultatet som sparas i databasen blir d� just "10-3"
	# F�r att ta reda p� vem som vann matchen finns funktionen getMachWinner($resultat).
	# Genom att mata in variabeln som inneh�ller resultatet s� retunerar funktionen olika siffror
	# Vid vinst retuneras 1, vid f�rlust 0 och vid eventuell oavgjord match retuneras -1
	# F�r att d� kolla vem som vann kan vi anv�nda oss av en kod i stil med detta:
	$r = explode("-", $resultat); # explode() delar upp v�rdet i $resultat till tv� delar p� vardera sida om "-"-tecknet
	# $r[0] �r nu er klans resultat, och $r[1] �r motst�ndarnas resultat
	$res = getMatchWinner($resultat); # Vi anropar funktionen getMatchWinner() och lagrar svaret i variabeln $res
	if($res == 1) { # �r $res 1 har vi vunnit
		$text = "<span class=\"win\">".$r[0]."</span>-<span class=\"loose\">".$r[1]."</span>";
		# Vad vi g�r h�r �r att vi anv�nder en span-tagg f�r att s�tta f�rg p� det lag som vann respektive det som f�rlorade
		# Nu n�r $res �r 1 inneb�r det att vi vann, d�rf�r f�r $r[0] span-klassen "win", och $r[1] (som �r motst�ndarna) span-klassen "loose"
	}
	elseif($res == 0) { # �r $res d�remot 0 har vi f�rlorat
		$text = "<span class=\"loose\">".$r[0]."</span>-<span class=\"win\">".$r[1]."</span>";
		# Eftersom vi nu f�rlorade f�r v�rt resultat klassen loose och motst�ndarnas resultat klassen win
	}
	else { # �r $res n�got annat (-1) blev det oavgjort
		$text = "<span class=\"draw\">".$r[0]."</span>-<span class=\"draw\">".$r[1]."</span>";
		# Vid oavgjort f�r b�da resultaten samma klass; draw.
	} 
	
	# Heredock-syntaxen �r v�ldigt smidig n�r man ska skriva ut mycket html/text.
	# Se mer information p� http://se2.php.net/manual/en/language.types.string.php#language.types.string.syntax.heredoc
echo <<<INFO
	<ul>
		<li>Karta som spelades: {$karta}</li>
		<li>Typ av match: {$typ}</li>
		<li>Datum f�r matchen: {$datum}</li>
		<li>Tid f�r matchen: {$tid}</li>
		<li>Resultat: {$text}</li>
	</ul>
	<h2>&Ouml;vrig kommentar</h2>
	{$kommentar}
	<p class="link"><a href="?s=matcher">Tillbaka</a></p>
INFO;
# Vi har nu skrivit ut all information om matchen, tillsammans med en l�nk tillbaka till listan �ver matcherna.
# Notera ocks� att $text anv�ndes som resultat, d� det �r i den variabeln vi sparade det css-stylade resultatet :)
	
}
else {
	$sql = mysql_query("SELECT id FROM ".XA_PREFIX."matcher ORDER BY id DESC");
	echo "<h1>Matcher</h1>";
	echo "<ul>";
	while(($data = mysql_fetch_assoc($sql)) !== false) {
		echo "<li><a href=\"?s=matcher&amp;visa=".$data['id']."\">".getClanName()." vs. ".getMatchItem("motstand", $data['id'])."</a></li>";
		# Detta skriver ut en l�nk med vilka som har m�tts, klickar man p� l�nken visas mer utf�rlig matchinformation :)
	}
	echo "</ul>";
}