<?php
# main.php
# F�r mer information om funktionen getNewsItem() och dess parametrar, v�nligen se nyheter.php
?>
<p class="note">Mkay, detta &auml;r f&ouml;rstasidan till XAdmin's demosida. Demolayouten &auml;r skapad av v&aring;r s&ouml;ta kamrat Robin, tack! :)</p>
<h1><?php echo getNewsItem("titel"); ?></h1>
	<!-- Ovan ser vi d� koden f�r att h�mta rubriken till nyheten med id't som vi plockade fram tidigare -->
	
<?php
# Nu ska vi f� fram sj�lva nyhetstexten ocks�, d�rf�r anropar vi samma funktion som tidigare, fast med ett annat argument;
echo getNewsItem("nyhet");
# Och vipps, s� har vi senaste nyheten utskriven p� sidan, fancy va? :)
