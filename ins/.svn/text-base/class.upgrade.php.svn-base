<?php
class upgrade {
	public $version;
	public $build;
	public $upgrade;
	private $cver;
	private $cbld;
	private $done = false;
	private $prefix;
	private $errnum = 0;
	
	public function __construct($ver, $bld, $cver, $cbld, $prefix) {
		$this->cver = $cver;
		$this->cbld = $cbld;
		$this->version = $ver;
		$this->build = $bld;
		$this->prefix = $prefix;
		self::checkVersion();
	}
	
	private function checkVersion() {
		$ver = str_replace(".", "", $this->version);
		$ver = str_replace("b", "", $ver);
		$ver = str_replace("rc", "", $ver);
		$cver = str_replace(".", "", $this->cver);
		$cver = str_replace("b", "", $cver);
		$cver = str_replace("rc", "", $cver);
		
		if($ver == $cver) {
			if($this->cbld < $this->build) {
			# Om vi har samma version, men annan build; uppdatera!
				$this->upgrade = true;
				return true;
			}
			else {
			# Allt verkar vara samma :\
				$this->upgrade = false;
				return false;
			}
		}
		else {
		# Olika versioner; vi uppdaterar!
			$this->upgrade = true;
			return true;
		}
	}
	
	private function dbError($err, $errno) {
		$err = htmlspecialchars($err);
		return "<p class=\"error\">{$errno}: {$err}</p>";
	}
	
	private function errorMsg($err, $errno) {
		$err = htmlspecialchars($err);
		return "<p class\"error\">{$errno}: {$err}</p>";
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
	
	private function addDbField($field, $table, $desc = "") {
		$table = $this->prefix.$table;
		mysql_query("DESCRIBE {$table} '{$field}'");
		if(mysql_affected_rows() == 0) {
		# Fältet finns inte, lägg till
			if(mysql_query("ALTER TABLE {$table} ADD {$field} {$desc}")) {
				if(mysql_error()) {
					$this->errnum += $this->errnum;
					echo mysql_error()."<br />";
				}
				mysql_query("DESCRIBE {$table} '{$field}'");
				if(mysql_affected_rows() > 0) {
				# Nu finns fältet, vi vann!
					return true;
				}
				else {
				# Fältet verkar inte finnas, förmodligen finns det ett mysql_error()
					$this->errnum += $this->errnum;
					return false;
				}
			}
			else {
			# Gick inge vida att köra querty heller, vi gillar det inte och retunerar false
				$this->errnum += $this->errnum;
				return false;
			}
		}
		else {
		# Fältet verkar redan finnas, skönt för oss. Kanske..?
			return true;
		}
	}
	
	private function addSettingValue($value, $setting) {
		$table = $this->prefix."settings";
		$data = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS kaka FROM {$table} WHERE setting = '{$setting}'"));
		if($data['kaka']  >= 1) {
		# Värdet vi försökte lägga till fanns redan, moving on.
			return true;
		}
		else {
		# Värdet fanns inte, lets get to the action!
			mysql_query("INSERT INTO {$table} SET setting = '{$setting}', value = '{$value}'");
			if(mysql_error()) {
				$this->errnum += $this->errnum;
				echo mysql_error()."<br />";
			}
			$data = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS kaka FROM {$table} WHERE setting = '{$setting}'"));
			if($data['kaka']  >= 1) {
			# Vi har nu uppdaterat settings med vad det nu var vi uppdaterade, epic!
				return true;
			}
			else {
			# Det fuckade ur helt, allt dog och vi förlorade. GG.
				$this->errnum += $this->errnum;
				return false;
			}
		}
	}

	private function updateConfig() {
		if(!$tmp = file_get_contents("../adm/lib/config.php")) {
			$this->errnum += $this->errnum;
			return false;
		}
		$tmp = str_replace($this->cver, $this->version, $tmp);
		$tmp = str_replace($this->cbld, $this->build, $tmp);
		if(file_put_contents("../adm/lib/config.php", $tmp)) {
			return true;
		}
		else {
			$this->errnum += $this->errnum;
			return false;
		}
	}
	
	public function doUpgrade() {
		if($this->upgrade == true) {
			if(($this->cver == "2.0b1" && $this->cbld == "1001") || ($this->cver == "2.0b1" && $this->cbld == "1000")) {
				self::upgradeFromB1();
			}
			elseif(($this->cver == "2.0b2" && $this->cbld == "1003") || ($this->cver == "2.0b2" && $this->cbld == "1000")) {
				self::upgradeFromB2();
			}
			elseif(($this->cver == "2.0b3" && $this->cbld == "1004") || ($this->cver == "2.0b3" && $this->cbld =="1000")) {
				self::upgradeFromB3();
			}
			elseif(($this->cver == "2.0b4" && $this->cbld == "1005") || ($this->cver == "2.0b4" && $this->cbld =="1000")) {
				self::upgradeFromB4();
			}
			else {
				throw new Exception("Uppdateringsfel. Hittar ingen lämplig uppdatering från v{$this->cver}-{$this->cbld}.");
			}
			if($this->done == true) {
				if(self::updateConfig()) {
					return true;
				}
				else {
					self::errorMsg("Kunde inte uppdatera config.php, se till att filen är skrivbar (chmod 0777 för att vara säker).", 201);
					return false;
				}
			}
			else {
				self::errorMsg("Det blev fel vid uppdateringen. Totala antalet fel som upptäcktes: {$this->errnum}");
				return false;
			}
		}
		else {
		# Vi hade tydligen redan uppdaterat..?
			return true;
		}
	}
	
	private function upgradeFromB1() {
		if(!self::addSettingValue(" ", "historia")) {
			self::dbError("Kunde inte uppdatera {$this->prefix}settings med nya värden.", 301);
		}
		self::upgradeFromB2();
	}

	private function upgradeFromB2() {
		if(!self::runDbQuery("ALTER TABLE {$this->prefix}konton ADD `ltimes` INT( 9 ) UNSIGNED NOT NULL")) {
			self::dbError("Kunde inte ändra i tabellen {$this->prefix}konton.", 302);
		}
		if(!self::runDbQuery("ALTER TABLE {$this->prefix}matcher CHANGE `id` `id` INT( 9 ) UNSIGNED NOT NULL AUTO_INCREMENT ")) {
			self::dbError("Kunde inte ändra i tabellen {$this->prefix}matcher.", 303);
		}
		if(!self::runDbQuery("ALTER TABLE {$this->prefix}medlemmar CHANGE `id` `id` INT( 9 ) UNSIGNED NOT NULL AUTO_INCREMENT ")) {
			self::dbError("Kunde inte ändra i tabellen {$this->prefix}medlemmar.", 304);
		}
		self::upgradeFromB3();		
	}

	private function upgradeFromB3() {
		if(!self::addSettingValue(" ", "kontaktamail")) {
			self::dbError("Kunde inte uppdatera {$this->prefix}settings med nya värden.", 305);
		}
		if(!self::addSettingValue("yes", "kommentarer")) {
			self::dbError("Kunde inte uppdatera {$this->prefix}settings med nya värden.", 306);
		}
		if(!self::addSettingValue("xc", "captcha")) {
			self::dbError("Kunde inte uppdatera {$this->prefix}settings med nya värden.", 307);
		}
		if(!self::addSettingValue("no", "debug")) {
			self::dbError("Kunde inte uppdatera {$this->prefix}settings med nya värden.", 308);
		}
		if(!self::runDbQuery("CREATE TABLE IF NOT EXISTS `{$this->prefix}kommentarer` (
		`id` INT( 9 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`nid` INT( 9 ) UNSIGNED NOT NULL ,
		`datum` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL ,
		`namn` VARCHAR( 255 ) NOT NULL ,
		`kommentar` TEXT NOT NULL
		) ENGINE = MYISAM ;")) {
			self::dbError("Kunde inte lägga till tabellen {$this->prefix}kommentarer.", 309);
		}
		if(!self::runDbQuery("ALTER TABLE `{$this->prefix}konton` CHANGE `pass` `pass` CHAR( 32 ) NOT NULL")) {
			self::dbError("Kunde inte modifiera tabellen {$this->prefix}konton.", 310);
		}
		if(!self::runDbQuery("ALTER TABLE `{$this->prefix}konton` CHANGE `ft` `ft` ENUM( '1', '0' )  NOT NULL DEFAULT '1'")) {
			self::dbError("Kunde inte modifiera tabellen {$this->prefix}konton.", 311);
		}
		if(!self::runDbQuery("ALTER TABLE `{$this->prefix}konton` CHANGE `uclass` `uclass` ENUM( 'n', 'a' ) NOT NULL DEFAULT 'n'")) {
			self::dbError("Kunde inte modifiera tabellen {$this->prefix}konton.", 312);
		}
		$this->done = true;
	}
	
	private function upgradeFromB4() {
		if(!self::addSettingValue("yes", "gastbok")) {
			self::dbError("Kunde inte uppdatera {$this->prefix}settings med nya värden.", 313);
		}
	}
}
