<?php
# nyheter.php
# Okej, den här sidan är tänkt att dels lista alla nyheter som är postade, och även visa en specifik nyhet
# om användaren t.ex. klickar på en länk till en nyhet.
# Samma princip används här som i main.php, med ett tillägg; huvudloopen. Det är inga konstigheter egentligen
# utan är ganska "straight forward".

# Eftersom samma sida också ska visa en specifik nyhet måste vi veta när vi ska lista alla nyheter och
# när vi ska visa en specifik. Detta gör vi genom att kolla huruvida variabeln $_GET['visa'] är satt eller ej.
if(isset($_GET['visa']) && $_GET['visa'] = intval($_GET['visa'])) {
# isset() kollar om variabeln är satt, är den det så ska vi visa en specifik nyhet
# intval() gör så att vi kan använda $_GET['visa'] utan att behöva oroa oss för SQL-injections :)
# Nu blir koden nästan identisk som den i main.php, vi ska hämta ut en nyhet med ett id vi redan har.
	echo "<h1>"; # En rubrik vill alla ha
	echo getNewsItem("titel", $_GET['visa']); # Notera att $_GET['visa'] innehåller id't till vår nyhet.
	echo "</h1>"; # Fast inte för stor rubrik, bäst att stänga i tid ;)
	
	echo getNewsItem("nyhet", $_GET['visa']); # Vi hämtar själva nyheten och skriver ut under rubriken.
	# För att skriva ut kommentarerna till en nyhet går det att göra på två sätt.
	# Det enkla sättet är att använda funktionen getComments() som skriver ut alla kommentarer till rätt nyhet.
	# Nackdelen med denna är att kommentarerna skrivs ut i formatet:
	# <div class="kommentar">
	#     <p class="namn">Namnet på den som skrev kommentaren</p>
	#     <p class="datum">0000-00-00 00:00</p>
	#     <p class="kommentar">Själva kommentaren</p>
	# </div>
	# Detta gör att det blir lite svårt att styla kommentarerna precis som man vill, även fast det naturligtvis
	# går att använda CSS för att komma åt varje tagg, men ordningen och vilka taggar som används förblir densamma.
	# I exemplet här används dock denna funktion för att skriva ut kommentarerna, men nedan så finns det även kod
	# för hur man annars skulle kunna göra.
	echo "<h2>Kommentarer</h2>";
	printComments($_GET['visa'], true, true, true);
	# Parametrarna indikerar följande:
	# printComments(nyhetsid, [visa namn], [visa kommentar], [visa datum])
	# Det enda som behövs är nyhetsid, standard är visa kommentar och namn, men inte datum.
	
	# Vill man i stället ha lite mer kontroll över hur kommentarerna ska se ut och skrivas ut går det att göra t.ex. så här:
	# $sql = mysql_query("SELECT namn, kommentar, datum FROM ".XA_PREFIX."kommentarer WHERE nid = '".$_GET['visa']."'");
	# while($data = mysql_fetch_assoc($sql)) {
	# 	echo "<p>Skriven av: ".$data['namn']."<br />\n";
	# 	echo "Den: ".$data['datum']."<br />\n";
	# 	echo "<div class=\"kommentar\">".$data['kommentar']."</div>";
	# }
	# Detta skall man dock försöka undvika om man känner sig lite osäker på PHP, då resultatet kanske inte alltid blir som
	# man tänkt sig.
	
	# När man väl har skrivit ut alla kommentarer (om det finns några) kan det vara bra med ett formulär för att även skriva
	# nya kommentarer.

	printCommentForm($_GET['visa']); # Skriver ut kommentarsformuläret för den specifika nyheten.
	
	echo "<p class=\"link\"><a href=\"?s=nyheter\">Tillbaka</a></p>";
	# Vi lägger in en "Tillbaka"-länk för att gå till nyhetsindex igen.
}
else {
	# Ska vi i stället lista alla nyheter som finns? Svar ja.
	
	$sql = mysql_query("SELECT id FROM ".XA_PREFIX."nyheter ORDER BY id DESC");
	# Samma som innan, bara det att vi nu hämtar alla nyheter (dvs. vi har tagit bort LIMIT 1 från SQL-satsen)
	# Nu till det magiska; loopen.
	# Om vi kikar på hur det såg ut i main.php så var det endast $data = mysql_fetch_assoc($sql);, detta då vi
	# endast hade en post från databasen att hämta. Nu har vi fler, och för att vi ska kunna skriva ut alla
	# krävs det att vi "loopar" oss igenom hela databasen. Vilket i realiteten bara innebär att vi går igenom
	# nyhet för nyhet och skriver ut informationen.
	echo "<h1>Nyheter</h1>"; # Sätter en rubrik på sidan så blir allt lite tydligare
	echo "<ul>"; # Tänkte lägga dom i en lista, så vi börjar med en ul-tagg
	while(($data = mysql_fetch_assoc($sql)) !== false) {
		echo "<li><a href=\"?s=nyheter&amp;visa=".$data['id']."\">";	# Början på list-delen, notera även den något
																		# kladdiga länken som påbörjas där. Vi har en
																		# href som går till ?s=nyheter&amp;visa=*ID*
																		# där *ID* är vår nyhetsid.
		echo getNewsItem("titel", $data['id']); # Detta blir då själva länktexten
		# Vi visar titeln med en länk så får folk klicka på den rubrik som verkar intressant
		echo "</a></li>"; # Vi stänger både a-taggen samt li-taggen
		
	} # Loopen är nu klar och kommer, så länge det finns nyheter kvar, att köra om igen.
}