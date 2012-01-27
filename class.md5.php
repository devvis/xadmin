<?php
class md5create {
	private $list;
	private $skip;

	public function __construct($dir, $skip = array()) {
		$this->skip = $skip;
		self::genList($dir);
		self::writeFile("files.lst");
	}
	
	private function genList($dir) {
		$g = glob($dir);
		if(count($g) > 0) {
			foreach($g as $t) {
				if(!is_dir($t)) {
					#if($t != "./files.lst" || $t != "./adm/lib/config.php" || $t != "./index.php") {
					if(!in_array($t, $this->skip)) {
						$this->list .= $t.":".md5_file($t)."\n";
					}
				}
				else {
					if(!eregi("svn", $t) || $t != "./inc") {
						$t = $t."/*";
						self::genList($t);
					}
				}
			}
		}
	}
	
	private function writeFile($file) {
		file_put_contents($file, $this->list);
	}
}

class md5verify {

	public function __construct($fil) {
		if(file_exists($fil)) {
			self::checkMD5($fil);
		}
	}
	
	private function checkMD5($fil) {
		$f = fopen($fil, "r");
		echo "<pre>";
		while(!feof($f)) {
			$res = fgets($f);
			$res = explode(":", $res);
			if(file_exists($res[0])) {
				if(trim(md5_file($res[0])) == trim($res[1])) {
					echo "<span style=\"color:#00CC33\">[ OK ]</span> ", $res[0], "\n";
				}
				else {
					echo "<span style=\"color:#FF0033\">[ FEL ]</span> ", $res[0], "\n";
				}
			}
			else {
				if($res[0] != "\n" || $res[0] != "") {
					echo "<span style=\"color:#FF0033\">[ FEL ]</span> ", $res[0], " - Finns inte.\n";
				}
			}
		}
		echo "</pre>";
		fclose($f);
		return true;
	}
	
}
