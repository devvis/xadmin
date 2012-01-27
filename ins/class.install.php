<?php
if(!defined("XA_INCLUDED")) {
	die;
}
class install {
	private $xv;	# version
	private $xb;	# build
	private $iv;	# installversion
	private $ib;	# installbuild
	
	private $cfg = false;
	private $phpv;
	
	private $errnum;
	
	public function __construct($xadminv, $xadminb, $installv, $installb) {
		$this->xv = $xadminv;
		$this->xb = $xadminb;
		$this->iv = $installv;
		$this->ib = $installb;
		if(file_exists("../adm/lib/config.php")) {
			if(!is_writable("../adm/lib/config.php")) {
				if(!chmod("../adm/lib/config.php", 0777)) {
					self::insError("Filen /adm/lib/config.php är inte skrivbar. Testa att ändra CHMOD till 0777 på filen och försök därefter igen.", 100);
					die;	
				}
				else {
					$this->cfg = true;
				}
			}
			else {
				$this->cfg = true;
			}
		}
		else {
			if(touch("../adm/lib/config.php")) {
				if(!file_exists("../adm/lib/config.php")) {
					self::insError("Kunde inte skapa /adm/lib/config.php automatiskt. Vänligen ladda upp config.php manuellt från arkivet, alternativt skapa filen själv.", 101);
					die;
				}
				else {
					$this->cfg = true;
				}
			}
			else {
				self::insError("Kunde inte skapa /adm/lib/config.php automatiskt. Vänligen ladda upp config.php manuellt från arkivet, alternativt skapa filen själv.", 101);
				die;
			}
		}
		
		self::preInstall();
		
	}
	
	public function __destruct() {
		if($this->errnum > 0) {
			echo '<p>Totala antalet fel som uppstod i installationen: ', $this->errnum, '</p>';
		}
	}
	
	private function runDbQuery($sql) {
		mysql_query($sql);
		if(mysql_affected_rows() >= 0) {
			return true;
		}
		else {
			$this->errnum += $this->errnum;
			return false;
		}
	}
	
	public function setupSQL($user, $pass, $name, $email, $klannamn, $kontaktamail) {
		if(file_exists("../adm/lib/config.php")) {
			require("../adm/lib/config.php");
		}
		else {
			self::insError("Filen /adm/lib/config.php saknas, och därför kan inte installationen fortsätta. Vänligen se till att filen finns och försök sedan igen.", 120);
			return false;
		}
		if(!defined("XA_PREFIX")) {
			self::insError("Det verkar ha uppstått ett fel vid skrivningen till config.php. Vänligen se till att filen existerar med skrivrättigheter (0777) och försök sendan igen.", 121);
			return false;
		}
		if($kontaktamail == "") {
			$kontaktamail = " ";
		}
		
		# Konton
		if(!self::runDbQuery("CREATE TABLE IF NOT EXISTS `".XA_PREFIX."konton` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(255) NOT NULL,
  `pass` char(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `uclass` enum('n','a') NOT NULL DEFAULT 'n',
  `email` varchar(255) NOT NULL,
  `ft` enum('1','0') NOT NULL DEFAULT '1',
  `ltimes` int(9) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uname` (`uname`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;")) {
			self::insError("Det gick inte att lägga till kontotabellen. Kontrollera att MySQL-användaren har skrivrättigheter till databasen.", 122);
			return false;
		}
		
		# Matcher
		if(!self::runDbQuery("CREATE TABLE IF NOT EXISTS `".XA_PREFIX."matcher` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `motstand` varchar(255) NOT NULL,
  `karta` varchar(255) NOT NULL,
  `typ` varchar(255) NOT NULL,
  `datum` date NOT NULL DEFAULT '0000-00-00',
  `tid` time NOT NULL DEFAULT '00:00:00',
  `resultat` varchar(9) NOT NULL,
  `kommentar` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;")) {
		self::insError("Det gick inte att lägga till matchtabellen. Kontrollera att MySQL-användaren har skrivrättigheter till databasen.", 123);
			return false;
		}
		
		# Medlemmar
		if(!self::runDbQuery("CREATE TABLE IF NOT EXISTS `".XA_PREFIX."medlemmar` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `nick` varchar(255) NOT NULL,
  `alder` int(2) unsigned NOT NULL,
  `position` varchar(255) NOT NULL,
  `mus` varchar(255) NOT NULL,
  `musmatta` varchar(255) NOT NULL,
  `upplosning` varchar(255) NOT NULL,
  `karta` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `info` text NOT NULL,
  `citat` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;")) {
			self::insError("Det gick inte att lägga till medlemstabellen. Kontrollera att MySQL-användaren har skrivrättigheter till databasen.", 124);
			return false;
		}
	
		# Nyheter
		if(!self::runDbQuery("CREATE TABLE IF NOT EXISTS `".XA_PREFIX."nyheter` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `titel` varchar(255) NOT NULL,
  `nyhet` longtext NOT NULL,
  `skribent` varchar(255) NOT NULL,
  `datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;")) {
			self::insError("Det gick inte att lägga till nyhetstabellen. Kontrollera att MySQL-användaren har skrivrättigheter till databasen.", 125);
			return false;
		}
		
		# Kommentarer
		if(!self::runDbQuery("CREATE TABLE IF NOT EXISTS `".XA_PREFIX."kommentarer` (
  `id` INT( 9 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `nid` INT( 9 ) UNSIGNED NOT NULL ,
  `datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `namn` VARCHAR( 255 ) NOT NULL ,
  `kommentar` TEXT NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;")) {
			self::insError("Det gick inte att lägga till kommentarstabellen. Kontrollera att MySQL-användaren har skrivrättigheter till databasen.", 126);
			return false;
		}
		
		# Inställningar
		if(!self::runDbQuery("CREATE TABLE IF NOT EXISTS `".XA_PREFIX."settings` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `setting` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting` (`setting`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;")) {
			self::insError("Det gick inte att lägga till inställningstabellen. Kontrollera att MySQL-användaren har skrivrättigheter till databasen.", 127);
			return false;
		}
		
		# Användare
		if(!self::runDbQuery("INSERT INTO `".XA_PREFIX."konton` SET uname = '".$user."', pass = '".self::genPass($pass, $user)."', name = '".$name."', uclass = 'a', email = '".$email."'")) {
			self::insError("Det gick inte att lägga till det administrativa kontot. Kontrollera att MySQL-användaren har skrivrättigheter till databasen.", 128);
			return false;
		}
		
		# Vi sätter faktiskt inställningarna också
		if(!self::runDbQuery("INSERT INTO `".XA_PREFIX."settings` (`setting`, `value`) VALUES
  ('klannamn', '".$klannamn."'),
  ('lockdown', 'no'),
  ('historia', ' '),
  ('kommentarer', 'yes'),
  ('debug', 'no'),
  ('gastbok', 'yes'),
  ('kontaktamail', '".$kontaktamail."');")) {
			self::insError("Det gick inte att lägga till inställningarna i inställningstabellen. Kontrollera att MySQL-användaren har skrivrättigheter till databasen.", 129);
			return false;
  		}
		
  		return true;
	}
	
	public function makeConfig($prefix, $uname, $pass, $server, $db) {
		if($prefix == "") {
			$prefix = uniqid()."_";
			# Vi sätter ett prefix trots allt.
			# Användandet av uniqid() gör att man knappast riskerar att få två prefix som är lika.
		}

		if($uname == "" || $server == "" || $db == "") {
			self::insError("Det saknas tillräckligt med information för att kunna upprätta en anslutning till MySQL-servern.", 110);
			return false;
		}
		if(!mysql_connect($server, $uname, $pass)) {
			self::insError("Det gick inte att ansluta till MySQL-servern med den information som angavs.", 111);
			return false;
		}
		if(!mysql_select_db($db)) {
			self::insError("Det gick att upprätta en anslutning till MySQL-servern, men kunde inte välja databas.", 112);
			return false;
		}
		$year = date("Y");
		$date = date("Y-m-d");
		$cfg = <<<CONFIG
<?php
/**
*	config.php - En del av XAdmin
*	*****************************
*
*	XAdmin är en produkt skapad av XCoders.info.
*	XAdmin är licensierat under BSD-licensen, se bifogad licensfil (licens.txt) eller se länk vid installationen.
*	Som användare av XAdmin2 så binder du dig automatiskt till att följa de regler som licensen anger.
*
*	Copyrigt 2006-{$year} XCoders.info - Alla rättigheter reserverade
*	Denna fil skapades i samband med installationen av XAdmin2 den {$date}.
*
*	Versioner som används:
*		XAdmin version: {$this->xv}-{$this->xb}
*		XAdmin installationsversion: {$this->iv} build {$this->ib}
*
**/
define("XA_INSTALLDATE", "{$date}");
define("XA_PREFIX", "{$prefix}");
define("XA_VERSION", "{$this->xv}");
define("XA_BUILD", "{$this->xb}");

mysql_connect("{$server}", "{$uname}", "{$pass}") or exit("
	<h1>Sidan är nere!</h1>
		<p>Sidan är för tillfället nere. Vi jobbar på att få allt att fungera så snart som möjligt.</p><hr />
		<address>XAdmin v{$this->xv}</address>");

mysql_select_db("{$db}") or exit("
	<h1>Sidan är nere!</h1>
		<p>Sidan är för tillfället nere. Vi jobbar på att få allt att fungera så snart som möjligt.</p><hr />
		<address>XAdmin v{$this->xv}</address>");

CONFIG;
		if(!file_put_contents("../adm/lib/config.php", $cfg)) {
			self::insError("Det gick inte att skriva datan till /adm/lib/config.php, vänligen kontrollera att filen existerar samt har skrivrättigheter (chmod 0777).", 113);
			return false;
		}
		return true;
	}
	
	private function preInstall() {
		if(version_compare(PHP_VERSION, "5.1.0", ">")) {
			$this->phpv = true;
		}
		else {
			$this->phpv = false;
			self::insError("Din version av PHP (".PHP_VERSION.") är lägre än rekommenderade 5.1.0. Detta gör att vi inte kan garantera att alla XAdmins funktioner fungerar som det är tänkt.", 102);
		}
		if($this->cfg != true) {
			self::insError("Det har uppstått ett problem gällande filen /adm/lib/config.php. Kontrollera att filen existerar och går att skriva till. Testa om möjligt att sätta CHMOD 0777 på filen.", 103);		
		}
	}

	private function insError($str, $errno) {
		++$this->errnum;
		$str = htmlentities($str);
		$errno = intval($errno);
		echo '<p class="error">', $errno, ': ', $str,'</p>';
		return true;
	}
	
	public static function error($str) {
		$str = htmlentities($str);
		echo '<p class="info"><span class="fel">[ FEL ]</span> ', $str, '</p>';
		return true;
	}
	public static function warning($str) {
		$str = htmlentities($str);
		echo '<p class="info"><span class="varning">[ VARNING ]</span> ', $str, '</p>';
		return true;
		
	}
	public static function ok($str) {
		$str = htmlentities($str);
		echo '<p class="info"><span class="ok">[ OK ]</span> ', $str, '</p>';
		return true;
		
	}
	
	private function genPass($p, $u) {
		return md5(sha1($p).substr(crc32($u),0,9).$p.$u);
	}

}
