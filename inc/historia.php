<h1><?php echo getClanName(); ?> - Historia</h1>
<!-- Vi använder getClanName() igen för att få en trevlig rubrik på sidan -->
<?php
# Funktionen för att hämta klanens historia är mycket enkel
# Anropa bara getHistory() så hämtas klanens historia, easy as cake :)
	echo getHistory();
?>