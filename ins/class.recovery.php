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
					self::insError("Filen /adm/lib/config.php �r inte skrivbar. Testa att �ndra CHMOD till 0777 p� filen och f�rs�k d�refter igen.", 100);
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
					self::insError("Kunde inte skapa /adm/lib/config.php automatiskt. V�nligen ladda upp config.php manuellt fr�n arkivet, alternativt skapa filen sj�lv.", 101);
					die;
				}
				else {
					$this->cfg = true;
				}
			}
			else {
				self::insError("Kunde inte skapa /adm/lib/config.php automatiskt. V�nligen ladda upp config.php manuellt fr�n arkivet, alternativt skapa filen sj�lv.", 101);
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
			self::insError("Det har uppst�tt ett fel med config.php. V�nligen se till att filen existerar samt har skrivr�ttigheter (chmod 0777). F�rs�k d�refter igen.", 110);
			die;
		}
		if($this->prefix == "") {
			$this->prefix = uniqid()."_";
			# Vi s�tter ett prefix trots allt.
			# Anv�ndandet av uniqid() g�r att man knappast riskerar att f� tv� prefix som �r lika.
		}

		if($this->my_name == "" || $this->my_server == "" || $this->my_db == "") {
			self::insError("Det saknas tillr�ckligt med information f�r att kunna uppr�tta en anslutning till MySQL-servern.", 111);
			return false;
		}
		if(!mysql_connect($this->my_server, $this->my_name, $this->my_pass)) {
			self::insError("Det gick inte att ansluta till MySQL-servern med den information som angavs.", 112);
			return false;
		}
		if(!mysql_select_db($this->my_db)) {
			self::insError("Det gick att uppr�tta en anslutning till MySQL-servern, men kunde inte v�lja databas.", 113);
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
*	XAdmin �r en produkt skapad av XCoders.info.
*	XAdmin �r licensierat under BSD-licensen, se bifogad licensfil (licens.txt) eller se l�nk vid installationen.
*	Som anv�ndare av XAdmin2 s� binder du dig automatiskt till att f�lja de regler som licensen anger.
*
*	Copyrigt 2006-{$year} XCoders.info - Alla r�ttigheter reserverade
*	Denna fil skapades i samband med installationen av XAdmin2 den {$date}.
*
*	Versioner som anv�nds:
*		XAdmin version: {$this->xv}-{$this->xb}
*		XAdmin installationsversion: {$this->iv} build {$this->ib}
*
**/
define("XA_INSTALLDATE", "{$date}");
define("XA_PREFIX", "{$this->prefix}");
define("XA_VERSION", "{$this->xv}");
define("XA_BUILD", "{$this->xb}");

mysql_connect("{$this->my_server}", "{$this->my_name}", "{$this->my_pass}") or exit("
	<h1>Sidan �r nere!</h1>
		<p>Sidan �r f�r tillf�llet nere. Vi jobbar p� att f� allt att fungera s� snart som m�jligt.</p><hr />
		<address>XAdmin v{$this->xv}</address>");

mysql_select_db("{$this->my_db}") or exit("
	<h1>Sidan �r nere!</h1>
		<p>Sidan �r f�r tillf�llet nere. Vi jobbar p� att f� allt att fungera s� snart som m�jligt.</p><hr />
		<address>XAdmin v{$this->xv}</address>");

CONFIG;
		if(!file_put_contents("../adm/lib/config.php", $cfg)) {
			self::insError("Det gick inte att skriva datan till /adm/lib/config.php, v�nligen kontrollera att filen existerar samt har skrivr�ttigheter (chmod 0777).", 114);
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
