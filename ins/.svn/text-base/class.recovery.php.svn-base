<?php
class recovery {
	
	private $prefix;
	private $my_name;
	private $my_pass;
	private $my_server;
	private $my_db;
	private $xv;
	private $xb;
	private $iv;
	private $ib;
	private $cfg;
	private $errnum;
	
	public function __construct($prefix, $uname, $pass, $server, $db, $xv, $xb, $iv, $ib) {
		$this->prefix = $prefix;
		$this->my_name = $uname;
		$this->my_pass = $pass;
		$this->my_server = $server;
		$this->my_db = $db;
		$this->xv = $xv;
		$this->xb = $xb;
		$this->iv = $iv;
		$this->ib = $ib;
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
		self::restoreConfig();
	}

	public function __destruct() {
		if($this->errnum > 0) {
			echo '<p>Totala antalet fel som uppstod i installationen: ', $this->errnum, '</p>';
		}
	}

	private function restoreConfig() {
		if($this->cfg == false) {
			self::insError("Det har uppstått ett fel med config.php. Vänligen se till att filen existerar samt har skrivrättigheter (chmod 0777). Försök därefter igen.", 110);
			die;
		}
		if($this->prefix == "") {
			$this->prefix = uniqid()."_";
			# Vi sätter ett prefix trots allt.
			# Användandet av uniqid() gör att man knappast riskerar att få två prefix som är lika.
		}

		if($this->my_name == "" || $this->my_server == "" || $this->my_db == "") {
			self::insError("Det saknas tillräckligt med information för att kunna upprätta en anslutning till MySQL-servern.", 111);
			return false;
		}
		if(!mysql_connect($this->my_server, $this->my_name, $this->my_pass)) {
			self::insError("Det gick inte att ansluta till MySQL-servern med den information som angavs.", 112);
			return false;
		}
		if(!mysql_select_db($this->my_db)) {
			self::insError("Det gick att upprätta en anslutning till MySQL-servern, men kunde inte välja databas.", 113);
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
define("XA_PREFIX", "{$this->prefix}");
define("XA_VERSION", "{$this->xv}");
define("XA_BUILD", "{$this->xb}");

mysql_connect("{$this->my_server}", "{$this->my_name}", "{$this->my_pass}") or exit("
	<h1>Sidan är nere!</h1>
		<p>Sidan är för tillfället nere. Vi jobbar på att få allt att fungera så snart som möjligt.</p><hr />
		<address>XAdmin v{$this->xv}</address>");

mysql_select_db("{$this->my_db}") or exit("
	<h1>Sidan är nere!</h1>
		<p>Sidan är för tillfället nere. Vi jobbar på att få allt att fungera så snart som möjligt.</p><hr />
		<address>XAdmin v{$this->xv}</address>");

CONFIG;
		if(!file_put_contents("../adm/lib/config.php", $cfg)) {
			self::insError("Det gick inte att skriva datan till /adm/lib/config.php, vänligen kontrollera att filen existerar samt har skrivrättigheter (chmod 0777).", 114);
			return false;
		}
		return true;
	}

	private function insError($str, $errno) {
		++$this->errnum;
		$str = htmlentities($str);
		$errno = intval($errno);
		echo '<p class="error">', $errno, ': ', $str,'</p>';
		return true;
	}
}
