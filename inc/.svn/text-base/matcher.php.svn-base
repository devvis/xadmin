<?php
# matcher.php
# Principen här är densamma som för nyheter, därför kommer förklaringen inte vara lika utförlig här
# För mer information, se main.php och nyheter.php :)

if(isset($_GET['visa']) && $_GET['visa'] = intval($_GET['visa'])) {
	# Vi ska nu visa en match!
	echo "<h1>".getClanName()." vs. ".getMatchItem("motstand", $_GET['visa'])."</h1>";
	# getClanName() retunerar er klans namn, och getMatchInfo("motstand") retunerar motståndarnas klannamn
	# Vi gör klart all information för presentation;
	$karta = getMatchItem("karta", $_GET['visa']);
	$typ = getMatchItem("typ", $_GET['visa']);
	$datum = getMatchItem("datum", $_GET['visa']);
	$tid = getMatchItem("tid", $_GET['visa']);
	$resultat = getMatchItem("resultat", $_GET['visa']);
	$kommentar = getMatchItem("kommentar", $_GET['visa']);
	
	# Värt att notera är att resultatet lagras i följande format "ert resultat"-"motståndarnas resultat"
	# ex: Ni vinner med 10-3, resultatet som sparas i databasen blir då just "10-3"
	# För att ta reda på vem som vann matchen finns funktionen getMachWinner($resultat).
	# Genom att mata in variabeln som innehåller resultatet så retunerar funktionen olika siffror
	# Vid vinst retuneras 1, vid förlust 0 och vid eventuell oavgjord match retuneras -1
	# För att då kolla vem som vann kan vi använda oss av en kod i stil med detta:
	$r = explode("-", $resultat); # explode() delar upp värdet i $resultat till två delar på vardera sida om "-"-tecknet
	# $r[0] är nu er klans resultat, och $r[1] är motståndarnas resultat
	$res = getMatchWinner($resultat); # Vi anropar funktionen getMatchWinner() och lagrar svaret i variabeln $res
	if($res == 1) { # Är $res 1 har vi vunnit
		$text = "<span class=\"win\">".$r[0]."</span>-<span class=\"loose\">".$r[1]."</span>";
		# Vad vi gör här är att vi använder en span-tagg för att sätta färg på det lag som vann respektive det som förlorade
		# Nu när $res är 1 innebär det att vi vann, därför får $r[0] span-klassen "win", och $r[1] (som är motståndarna) span-klassen "loose"
	}
	elseif($res == 0) { # Är $res däremot 0 har vi förlorat
		$text = "<span class=\"loose\">".$r[0]."</span>-<span class=\"win\">".$r[1]."</span>";
		# Eftersom vi nu förlorade får vårt resultat klassen loose och motståndarnas resultat klassen win
	}
	else { # Är $res något annat (-1) blev det oavgjort
		$text = "<span class=\"draw\">".$r[0]."</span>-<span class=\"draw\">".$r[1]."</span>";
		# Vid oavgjort får båda resultaten samma klass; draw.
	} 
	
	# Heredock-syntaxen är väldigt smidig när man ska skriva ut mycket html/text.
	# Se mer information på http://se2.php.net/manual/en/language.types.string.php#language.types.string.syntax.heredoc
echo <<<INFO
	<ul>
		<li>Karta som spelades: {$karta}</li>
		<li>Typ av match: {$typ}</li>
		<li>Datum för matchen: {$datum}</li>
		<li>Tid för matchen: {$tid}</li>
		<li>Resultat: {$text}</li>
	</ul>
	<h2>&Ouml;vrig kommentar</h2>
	{$kommentar}
	<p class="link"><a href="?s=matcher">Tillbaka</a></p>
INFO;
# Vi har nu skrivit ut all information om matchen, tillsammans med en länk tillbaka till listan över matcherna.
# Notera också att $text användes som resultat, då det är i den variabeln vi sparade det css-stylade resultatet :)
	
}
else {
	$sql = mysql_query("SELECT id FROM ".XA_PREFIX."matcher ORDER BY id DESC");
	echo "<h1>Matcher</h1>";
	echo "<ul>";
	while(($data = mysql_fetch_assoc($sql)) !== false) {
		echo "<li><a href=\"?s=matcher&amp;visa=".$data['id']."\">".getClanName()." vs. ".getMatchItem("motstand", $data['id'])."</a></li>";
		# Detta skriver ut en länk med vilka som har mötts, klickar man på länken visas mer utförlig matchinformation :)
	}
	echo "</ul>";
}