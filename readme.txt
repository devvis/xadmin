Introduktion till XAdmin 2.0
****************************

Kul att du som användare har valt att använda XAdmin 2.0. I det här dokumentet
så kommer jag att förklara dels hur systemet är uppbyggt och hur det är tänkt
att användas. Du kommer även att få svar på en del frågor som kan uppstå vid
installationen av XAdmin.

För det första, vad ÄR XAdmin?
Jo, det är ett så kallat CMS (Content Mangament System, ungefär
Innehållshanterare på svenska) anpassat för Counter-Strike klaner.
Systemet är till för att underlätta hanteringen av innehållet på ens klansida,
så som uppdatering av nyheter, hantering av medlemmar och matcher. Det mesta som
hör till helt enkelt.

I version två så tillkom det en del nya funktioner till XAdmin och även en helt
ny layout. Vi har även skrivit om all kod i systemet, då den tidigare inte
direkt var så bra. (Innehöll dessvärre många fel och säkerhetshål). Inför
version 2 så har vi även valt att göra systemet så "brett" som möjligt, tidigare
så var man t.ex. tvungen att använda sig utav MySQL 4.1.X.X eller senare för att
det skulle gå att installera systemet som planerat, denna begränsning ska dock
nu vara borttagen.

Installationen har även förbättrats från tidigare versioner, då man iblandstötte
på fel som kan vara svåra att rätta till om man inte vet vad som har inträffat.
Numera så kommer installationen automatiskt att försöka rätta till de fel som
kan uppstå under installationens gång, och om det inte lyckas visas en
förklaring till hur man bör gå till väga för att åtgärda problemet själv. Lite
längre ner i dokumentet så kommer det dock att finnas förklaringar på fel som
installationen inte klarade av att åtgärda själv och hur du som användare kan
rätta till dom.

Vi rekomenderar att webbservern som du sätter upp XAdmin2 på har följande
versioner av Apache, PHP och MySQL installerat:
	- Apache 1.3 eller senare.
	- PHP 5.1.0 eller senare.
	- MySQL 5.0 eller senare.

Som tidigare nämnt så bör även XAdmin2 fungera på webbservrar som inte
uppfyller kraven ovan, men det där är vad vi rekomenderar som sagt.

Innan du installerar så se till att du har hämtat senaste versionen av XAdmin
från http://xcoders.info.


När du har laddat ner filen från hemsidan; packa upp den till en temporär mapp
på din dator och ladda därefter upp den till din webbserver/ditt webbhotell.
Därefter så går du till endera http://www.dindomän.se/ eller
http://www.dindomän.se/ins/ för att starta installationen.

Nu borde det mesta gå relativt smärtfritt. Vad du behöver ha tillgängligt innan
du startar installationen är användarnamnet, lösenordet, databasnamnet och
adressen till mysqlservern, då du kommer att bli ombedd att fylla i dessa
uppgifter under installationen.

Därefter så kopieras de filer som behövs kopieras och alla inställningar görs,
och när allt det är klart så är även systemet klart att användas.

När en uppdatering har släppts till XAdmin så kommer du automatiskt att bli
informerad om det när du loggar in. Detta för att du som användare på ett så
smidigt sätt som möjligt skall bli informerad om nya funktioner och
säkerhetsuppdateringar.


Det absolut vanligaste problemet som uppstår under en installation av XAdmin är
följande:

	"Kunde inte ändra skrivrättigheter på /adm/lib/config.php automatiskt."

Efter det felmeddelandet står även vad som skall göras. För att ytterligare
förtydliga detta tar vi det även här;
Får du det problemet; använd ditt FTP-program och gå till /adm/lib/ och använd
FTP-programmets chmod-funktion (99% av alla ftp-klienter har en sådan funktion,
saknas den föreslår vi ett byte av ftp-klient) på filen config.php. Läget som
skall sättas är 0777 (alt. 777). Kan man inte skriva i något nummer utan endast
har kryssrutor att välja mellan; testa att kryssa i alla rutor och se om det
fungerar.

Skulle fel nummer 7 uppstå (Ett fel inträffade under installationen. Felet kunde
inte identifieras och beror förmodligen på modifierad kod.), vänligen kontakta
oss i #xcoders @ quakenet och beskriv ditt problem till en av opsen i kanalen.

Vid övriga frågor och funderingar kring systemet går det naturligtvis också bra
att kontakta oss via irc eller mail.

Tack för att du har valt att använda XAdmin som CMS!

// Gustav E. / gustav@xcoders.info / R34p3r @ quakenet