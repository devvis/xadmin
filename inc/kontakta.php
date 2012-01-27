<?php
echo "<h1>Kontakta oss</h1>"; # Här skriver vi endast ut rubriken på sidan för att berätta vad man faktiskt gör här :)
if(isset($_GET['kontaktfel'])) {
	echo "<p>N&aring;got gick fel. Testa att fylla i alla f&auml;lt och f&ouml;rs&ouml;k igen!</p>";
}
if(isset($_GET['kontaktklart'])) {
	echo "<p>Ditt mail har skickats till oss!</p>";
}

printContactForm(); # För att skriva ut kontaktformuläret är det bara att anropa funktionen printContactForm() precis som här.
# Vill man går det naturligtvis bra att även göra ett eget kontaktformulär.
# Se bara till att action pekar till /index.php (eller den filen där sendContactMessage() anropas i).
# Formuläret som använts i printContactForm() ser ut så här:
/*
	<form action="{$_SERVER['PHP_SELF']}?kontakta" method="post" class="kontakta">
		<label for="namn">Namn</label>
		<input type="text" name="namn" id="namn" />
		<label for="mail">Mail</label>
		<input type="text" name="mail" id="mail" />
		<label for="msg">Meddelande</label>
		<textarea name="msg" id="msg"></textarea>
		<input type="submit" name="kontakta" value="Skicka &raquo;" />
	</form>
*/
# Noter att namnen på fälten också behöver vara desamma för att sendContactMessage() ska fungera.
# Det som absolut måste finnas med är ?kontakta på slutet i action, annars kommer inte sendContactMessage() att kunna identifiera formuläret.
# Vill du endast ändra utseendet på formuläret finns det hjälp i /inc/stilmall.css, längst ner finns
# kommentarer som förklarar hur man ändrar kontaktformuläret.
?>