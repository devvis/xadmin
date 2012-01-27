<?php
# main.php
# För mer information om funktionen getNewsItem() och dess parametrar, vänligen se nyheter.php
?>
<p class="note">Mkay, detta &auml;r f&ouml;rstasidan till XAdmin's demosida. Demolayouten &auml;r skapad av v&aring;r s&ouml;ta kamrat Robin, tack! :)</p>
<h1><?php echo getNewsItem("titel"); ?></h1>
	<!-- Ovan ser vi då koden för att hämta rubriken till nyheten med id't som vi plockade fram tidigare -->
	
<?php
# Nu ska vi få fram själva nyhetstexten också, därför anropar vi samma funktion som tidigare, fast med ett annat argument;
echo getNewsItem("nyhet");
# Och vipps, så har vi senaste nyheten utskriven på sidan, fancy va? :)
