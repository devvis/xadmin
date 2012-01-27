<?php
/**
*	config.php - En del av XAdmin
*	*****************************
*
*	XAdmin är en produkt skapad av XCoders.info.
*	XAdmin är licensierat under BSD-licensen, se bifogad licensfil (licens.txt) eller se länk vid installationen.
*	Som användare av XAdmin2 så binder du dig automatiskt till att följa de regler som licensen anger.
*
*	Copyrigt 2006-2011 XCoders.info - Alla rättigheter reserverade
*	Denna fil skapades i samband med installationen av XAdmin2 den 2011-07-23.
*
*	Versioner som används:
*		XAdmin version: 2.0b4-1005
*		XAdmin installationsversion: 2.0 build 2000
*
**/
define("XA_INSTALLDATE", "2011-07-23");
define("XA_PREFIX", "xa_");
define("XA_VERSION", "2.0b4");
define("XA_BUILD", "1005");

mysql_connect("localhost", "root", "") or exit("
	<h1>Sidan är nere!</h1>
		<p>Sidan är för tillfället nere. Vi jobbar på att få allt att fungera så snart som möjligt.</p><hr />
		<address>XAdmin v2.0b4</address>");

mysql_select_db("xadmin-dev") or exit("
	<h1>Sidan är nere!</h1>
		<p>Sidan är för tillfället nere. Vi jobbar på att få allt att fungera så snart som möjligt.</p><hr />
		<address>XAdmin v2.0b4</address>");
