<?php
# nyheter.php
# Okej, den h�r sidan �r t�nkt att dels lista alla nyheter som �r postade, och �ven visa en specifik nyhet
# om anv�ndaren t.ex. klickar p� en l�nk till en nyhet.
# Samma princip anv�nds h�r som i main.php, med ett till�gg; huvudloopen. Det �r inga konstigheter egentligen
# utan �r ganska "straight forward".

# Eftersom samma sida ocks� ska visa en specifik nyhet m�ste vi veta n�r vi ska lista alla nyheter och
# n�r vi ska visa en specifik. Detta g�r vi genom att kolla huruvida variabeln $_GET['visa'] �r satt eller ej.
if(isset($_GET['visa']) && $_GET['visa'] = intval($_GET['visa'])) {
# isset() kollar om variabeln �r satt, �r den det s� ska vi visa en specifik nyhet
# intval() g�r s� att vi kan anv�nda $_GET['visa'] utan att beh�va oroa oss f�r SQL-injections :)
# Nu blir koden n�stan identisk som den i main.php, vi ska h�mta ut en nyhet med ett id vi redan har.
	echo "<h1>"; # En rubrik vill alla ha
	echo getNewsItem("titel", $_GET['visa']); # Notera att $_GET['visa'] inneh�ller id't till v�r nyhet.
	echo "</h1>"; # Fast inte f�r stor rubrik, b�st att st�nga i tid ;)
	
	echo getNewsItem("nyhet", $_GET['visa']); # Vi h�mtar sj�lva nyheten och skriver ut under rubriken.
	# F�r att skriva ut kommentarerna till en nyhet g�r det att g�ra p� tv� s�tt.
	# Det enkla s�ttet �r att anv�nda funktionen getComments() som skriver ut alla kommentarer till r�tt nyhet.
	# Nackdelen med denna �r att kommentarerna skrivs ut i formatet:
	# <div class="kommentar">
	#     <p class="namn">Namnet p� den som skrev kommentaren</p>
	#     <p class="datum">0000-00-00 00:00</p>
	#     <p class="kommentar">Sj�lva kommentaren</p>
	# </div>
	# Detta g�r att det blir lite sv�rt att styla kommentarerna precis som man vill, �ven fast det naturligtvis
	# g�r att anv�nda CSS f�r att komma �t varje tagg, men ordningen och vilka taggar som anv�nds f�rblir densamma.
	# I exemplet h�r anv�nds dock denna funktion f�r att skriva ut kommentarerna, men nedan s� finns det �ven kod
	# f�r hur man annars skulle kunna g�ra.
	echo "<h2>Kommentarer</h2>";
	printComments($_GET['visa'], true, true, true);
	# Parametrarna indikerar f�ljande:
	# printComments(nyhetsid, [visa namn], [visa kommentar], [visa datum])
	# Det enda som beh�vs �r nyhetsid, standard �r visa kommentar och namn, men inte datum.
	
	# Vill man i st�llet ha lite mer kontroll �ver hur kommentarerna ska se ut och skrivas ut g�r det att g�ra t.ex. s� h�r:
	# $sql = mysql_query("SELECT namn, kommentar, datum FROM ".XA_PREFIX."kommentarer WHERE nid = '".$_GET['visa']."'");
	# while($data = mysql_fetch_assoc($sql)) {
	# 	echo "<p>Skriven av: ".$data['namn']."<br />\n";
	# 	echo "Den: ".$data['datum']."<br />\n";
	# 	echo "<div class=\"kommentar\">".$data['kommentar']."</div>";
	# }
	# Detta skall man dock f�rs�ka undvika om man k�nner sig lite os�ker p� PHP, d� resultatet kanske inte alltid blir som
	# man t�nkt sig.
	
	# N�r man v�l har skrivit ut alla kommentarer (om det finns n�gra) kan det vara bra med ett formul�r f�r att �ven skriva
	# nya kommentarer.

	printCommentForm($_GET['visa']); # Skriver ut kommentarsformul�ret f�r den specifika nyheten.
	
	echo "<p class=\"link\"><a href=\"?s=nyheter\">Tillbaka</a></p>";
	# Vi l�gger in en "Tillbaka"-l�nk f�r att g� till nyhetsindex igen.
}
else {
	# Ska vi i st�llet lista alla nyheter som finns? Svar ja.
	
	$sql = mysql_query("SELECT id FROM ".XA_PREFIX."nyheter ORDER BY id DESC");
	# Samma som innan, bara det att vi nu h�mtar alla nyheter (dvs. vi har tagit bort LIMIT 1 fr�n SQL-satsen)
	# Nu till det magiska; loopen.
	# Om vi kikar p� hur det s�g ut i main.php s� var det endast $data = mysql_fetch_assoc($sql);, detta d� vi
	# endast hade en post fr�n databasen att h�mta. Nu har vi fler, och f�r att vi ska kunna skriva ut alla
	# kr�vs det att vi "loopar" oss igenom hela databasen. Vilket i realiteten bara inneb�r att vi g�r igenom
	# nyhet f�r nyhet och skriver ut informationen.
	echo "<h1>Nyheter</h1>"; # S�tter en rubrik p� sidan s� blir allt lite tydligare
	echo "<ul>"; # T�nkte l�gga dom i en lista, s� vi b�rjar med en ul-tagg
	while(($data = mysql_fetch_assoc($sql)) !== false) {
		echo "<li><a href=\"?s=nyheter&amp;visa=".$data['id']."\">";	# B�rjan p� list-delen, notera �ven den n�got
																		# kladdiga l�nken som p�b�rjas d�r. Vi har en
																		# href som g�r till ?s=nyheter&amp;visa=*ID*
																		# d�r *ID* �r v�r nyhetsid.
		echo getNewsItem("titel", $data['id']); # Detta blir d� sj�lva l�nktexten
		# Vi visar titeln med en l�nk s� f�r folk klicka p� den rubrik som verkar intressant
		echo "</a></li>"; # Vi st�nger b�de a-taggen samt li-taggen
		
	} # Loopen �r nu klar och kommer, s� l�nge det finns nyheter kvar, att k�ra om igen.
}