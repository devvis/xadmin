<?php
/**
*	config.php - En del av XAdmin
*	*****************************
*
*	XAdmin �r en produkt skapad av XCoders.info.
*	XAdmin �r licensierat under BSD-licensen, se bifogad licensfil (licens.txt) eller se l�nk vid installationen.
*	Som anv�ndare av XAdmin2 s� binder du dig automatiskt till att f�lja de regler som licensen anger.
*
*	Copyrigt 2006-2011 XCoders.info - Alla r�ttigheter reserverade
*	Denna fil skapades i samband med installationen av XAdmin2 den 2011-07-23.
*
*	Versioner som anv�nds:
*		XAdmin version: 2.0b4-1005
*		XAdmin installationsversion: 2.0 build 2000
*
**/
define("XA_INSTALLDATE", "2011-07-23");
define("XA_PREFIX", "xa_");
define("XA_VERSION", "2.0b4");
define("XA_BUILD", "1005");

mysql_connect("localhost", "root", "") or exit("
	<h1>Sidan �r nere!</h1>
		<p>Sidan �r f�r tillf�llet nere. Vi jobbar p� att f� allt att fungera s� snart som m�jligt.</p><hr />
		<address>XAdmin v2.0b4</address>");

mysql_select_db("xadmin-dev") or exit("
	<h1>Sidan �r nere!</h1>
		<p>Sidan �r f�r tillf�llet nere. Vi jobbar p� att f� allt att fungera s� snart som m�jligt.</p><hr />
		<address>XAdmin v2.0b4</address>");
