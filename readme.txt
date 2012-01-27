Introduktion till XAdmin 2.0
****************************

Kul att du som anv�ndare har valt att anv�nda XAdmin 2.0. I det h�r dokumentet
s� kommer jag att f�rklara dels hur systemet �r uppbyggt och hur det �r t�nkt
att anv�ndas. Du kommer �ven att f� svar p� en del fr�gor som kan uppst� vid
installationen av XAdmin.

F�r det f�rsta, vad �R XAdmin?
Jo, det �r ett s� kallat CMS (Content Mangament System, ungef�r
Inneh�llshanterare p� svenska) anpassat f�r Counter-Strike klaner.
Systemet �r till f�r att underl�tta hanteringen av inneh�llet p� ens klansida,
s� som uppdatering av nyheter, hantering av medlemmar och matcher. Det mesta som
h�r till helt enkelt.

I version tv� s� tillkom det en del nya funktioner till XAdmin och �ven en helt
ny layout. Vi har �ven skrivit om all kod i systemet, d� den tidigare inte
direkt var s� bra. (Inneh�ll dessv�rre m�nga fel och s�kerhetsh�l). Inf�r
version 2 s� har vi �ven valt att g�ra systemet s� "brett" som m�jligt, tidigare
s� var man t.ex. tvungen att anv�nda sig utav MySQL 4.1.X.X eller senare f�r att
det skulle g� att installera systemet som planerat, denna begr�nsning ska dock
nu vara borttagen.

Installationen har �ven f�rb�ttrats fr�n tidigare versioner, d� man iblandst�tte
p� fel som kan vara sv�ra att r�tta till om man inte vet vad som har intr�ffat.
Numera s� kommer installationen automatiskt att f�rs�ka r�tta till de fel som
kan uppst� under installationens g�ng, och om det inte lyckas visas en
f�rklaring till hur man b�r g� till v�ga f�r att �tg�rda problemet sj�lv. Lite
l�ngre ner i dokumentet s� kommer det dock att finnas f�rklaringar p� fel som
installationen inte klarade av att �tg�rda sj�lv och hur du som anv�ndare kan
r�tta till dom.

Vi rekomenderar att webbservern som du s�tter upp XAdmin2 p� har f�ljande
versioner av Apache, PHP och MySQL installerat:
	- Apache 1.3 eller senare.
	- PHP 5.1.0 eller senare.
	- MySQL 5.0 eller senare.

Som tidigare n�mnt s� b�r �ven XAdmin2 fungera p� webbservrar som inte
uppfyller kraven ovan, men det d�r �r vad vi rekomenderar som sagt.

Innan du installerar s� se till att du har h�mtat senaste versionen av XAdmin
fr�n http://xcoders.info.


N�r du har laddat ner filen fr�n hemsidan; packa upp den till en tempor�r mapp
p� din dator och ladda d�refter upp den till din webbserver/ditt webbhotell.
D�refter s� g�r du till endera http://www.dindom�n.se/ eller
http://www.dindom�n.se/ins/ f�r att starta installationen.

Nu borde det mesta g� relativt sm�rtfritt. Vad du beh�ver ha tillg�ngligt innan
du startar installationen �r anv�ndarnamnet, l�senordet, databasnamnet och
adressen till mysqlservern, d� du kommer att bli ombedd att fylla i dessa
uppgifter under installationen.

D�refter s� kopieras de filer som beh�vs kopieras och alla inst�llningar g�rs,
och n�r allt det �r klart s� �r �ven systemet klart att anv�ndas.

N�r en uppdatering har sl�ppts till XAdmin s� kommer du automatiskt att bli
informerad om det n�r du loggar in. Detta f�r att du som anv�ndare p� ett s�
smidigt s�tt som m�jligt skall bli informerad om nya funktioner och
s�kerhetsuppdateringar.


Det absolut vanligaste problemet som uppst�r under en installation av XAdmin �r
f�ljande:

	"Kunde inte �ndra skrivr�ttigheter p� /adm/lib/config.php automatiskt."

Efter det felmeddelandet st�r �ven vad som skall g�ras. F�r att ytterligare
f�rtydliga detta tar vi det �ven h�r;
F�r du det problemet; anv�nd ditt FTP-program och g� till /adm/lib/ och anv�nd
FTP-programmets chmod-funktion (99% av alla ftp-klienter har en s�dan funktion,
saknas den f�resl�r vi ett byte av ftp-klient) p� filen config.php. L�get som
skall s�ttas �r 0777 (alt. 777). Kan man inte skriva i n�got nummer utan endast
har kryssrutor att v�lja mellan; testa att kryssa i alla rutor och se om det
fungerar.

Skulle fel nummer 7 uppst� (Ett fel intr�ffade under installationen. Felet kunde
inte identifieras och beror f�rmodligen p� modifierad kod.), v�nligen kontakta
oss i #xcoders @ quakenet och beskriv ditt problem till en av opsen i kanalen.

Vid �vriga fr�gor och funderingar kring systemet g�r det naturligtvis ocks� bra
att kontakta oss via irc eller mail.

Tack f�r att du har valt att anv�nda XAdmin som CMS!

// Gustav E. / gustav@xcoders.info / R34p3r @ quakenet