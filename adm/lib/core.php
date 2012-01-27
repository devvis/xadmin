<?php
if(!defined("XA_PREFIX")) {
	die;
}
else {
	# Fix för notice vid E_ALL :)
	date_default_timezone_set("Europe/Stockholm");
}

if(getDebugMode() == 1) {
	error_reporting(E_ALL | E_STRICT);
}
else {
	error_reporting(E_ERROR | E_PARSE);
}

if(get_magic_quotes_gpc()) {
# Fix för magic_quotes_gpc
	$in = array(&$_GET, &$_POST, &$_COOKIE);
		while((list($k,$v) = each($in)) !== false) {
			foreach($v as $key => $val) {
				if(!is_array($val)) {
					$in[$k][$key] = stripslashes($val);
					continue;
				}
				$in[] =& $in[$k][$key];
			 }
		}
	unset($in);
}


function xa_info($str) {
# Skall användas vid generell information
	return "<p class=\"info\">".$str."</p>";
}

function xa_warning($str) {
# Skall användas vid mindre fel/varningar
	return "<p class=\"warning\">".$str."</p>";
}

function xa_error($str) {
# Skall användas vid faktiska fel
	return "<p class=\"error\">".$str."</p>";
}

function xa_success($str) {
# Skall användas vid lyckad operation
	return "<p class=\"success\">".$str."</p>";
}

function getClanName() {
	$data = mysql_fetch_assoc(mysql_query("SELECT value FROM ".XA_PREFIX."settings WHERE setting = 'klannamn'"));
	return $data['value'];
}

function getMatchWinner($res) {
	$r = explode("-",$res);
	if($r[0] > $r[1]) {
	# Vinst
		return 1;
	}
	elseif($r[0] < $r[1]) {
	# Förlust
		return 0;
	}
	else {
	# Oavgjort
		return -1;
	}
}

function isHeadAdmin($uid) {
	$uid = intval($uid);
	$sql = mysql_query("SELECT uclass FROM ".XA_PREFIX."konton WHERE id = '".$uid."'") or exit(mysql_error());
	$data = mysql_fetch_assoc($sql);
	if($data['uclass'] == "a") {
		return true;
	}
	else {
		return false;
	}
}

function genPass($p, $u) {
	return md5(sha1($p).substr(crc32($u),0,9).$p.$u);
}

class main {
	public static function pwGen($len = 8) {
	# Nom nom nom :)
		$v = array('a', 'e', 'i', 'o', 'u', 'y');
		$con = array('I', 'l', '1', 'O', '0');
		$rep = array('A', 'k', '3', 'U', '9');
		$cho = array(0 => mt_rand(0, 1), 1 => mt_rand(0, 1), 2 => mt_rand(0, 2));
		$p = array(0 => '', 1 => '', 2 => '');
	
		if($cho[0]) {
			$p[0] = mt_rand(1, mt_rand(9,99));
		}
		if($cho[1]) {
			$p[2] = mt_rand(1, mt_rand(9,99));
		}
	
		$len -= (strlen($p[0]) + strlen($p[2]));
	
		for($i = 0; $i < $len; $i++) {
			if($i % 2 == 0) {
				$p[1] .= chr(mt_rand(97, 122));
			}
			else {
				$p[1] .= $v[array_rand($v)];
			}
		}
	
		if($cho[2]) {
			$p[1] = ucfirst($p[1]);
		}
		if($cho[2] == 2) {
			$p[1] = strrev($p[1]);
		}
	
		$r = $p[0] . $p[1] . $p[2];
		$r = str_replace($con, $rep, $r);
		return $r;
	}
	
	public static function firstTime($id) {
		$id = intval($id);
		$sql = mysql_query("SELECT ft FROM ".XA_PREFIX."konton WHERE id = '{$id}'") or exit(mysql_error());
		$data = mysql_fetch_assoc($sql);
		if($data['ft'] == 1) {
			mysql_query("UPDATE ".XA_PREFIX."konton SET ft = '0' WHERE id = '{$id}'") or exit(mysql_error());
			return true;
		}
		else {
			return false;
		}
	}
	
	public static function isLockedDown() {
		$sql = mysql_query("SELECT value FROM ".XA_PREFIX."settings WHERE setting = 'lockdown'") or exit(mysql_error());
		$data = mysql_fetch_assoc($sql);
		if($data['value'] == "yes") {
			return true;
		}
		else {
			return false;
		}
	}

	public static function getNews($u, $f = 0) {
		$res = null;
		if(extension_loaded("curl")) {
			# Vi gillar curl :)
			$c = curl_init($u);
			
			curl_setopt($c, CURLOPT_HEADER, 0);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_TIMEOUT, 3);
			
			$res = curl_exec($c);
			
			curl_close($c);
		}
		else {
			if(ini_get("allow_url_fopen") == true) {
				$res = file_get_contents($u);
			}
			else {
				if(ini_set("allow_url_fopen", true)) {
					$res = file_get_contents($u);
				}
				elseif(($h = fsockopen($u, 80)) !== false) {
					while(!feof($h)) {
						$res .= fgets($h, 1024);
					}
					fclose($h);
				}
				else {
					$res = xa_error("Kan inte h&auml;mta nyheter, d&aring; varken cURL &auml;r aktiverat eller allow_url_fopen &auml;r satt till &quot;true&quot;.");
				}
			}
		}
		if($res == null) {
		# Bara för att vara säker.
			$res = xa_warning("P&aring; grund av anslutningsproblem kunde inte nyheterna h&auml;mtas.");
		}
		if($f == 1 && strpos($res ,"BRA_SEPARATOR_AER_BRA")) {
			$res = explode("BRA_SEPARATOR_AER_BRA", $res);
			return $res[0].$res[1];
		}
		else {
			$res = explode("BRA_SEPARATOR_AER_BRA", $res);
			return $res[0];
		}
	}
	
	public static function isUpToDate($rval = 0) {
		$res = null;
		if(extension_loaded("curl")) {
			$c = curl_init("http://xcoders.info/xv.php");
			curl_setopt($c, CURLOPT_HEADER, 0);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_TIMEOUT, 3);
			
			$res = curl_exec($c);
			
			curl_close($c);
		}
		else {
			if(ini_get("allow_url_fopen") == true) {
				$res = file_get_contents("http://xcoders.info/xv.php");
			}
			else {
				if(ini_set("allow_url_fopen", true)) {
					$res = file_get_contents("http://xcoders.info/xv.php");
				}
				else {
					$h = fsockopen("http://xcoders.info/xv.php", 80);
					$res = fgets($h);
					fclose($h);
				}
			}
		}
		if($rval == 0) {
			#if($res == null) {
			#	return true;
			#}
			#elseif(self::verFix(XA_VERSION.XA_BUILD) < self::verFix($res, true)) {
			if(self::verFix(XA_VERSION.XA_BUILD) < self::verFix($res, true)) {
				return false;
			}
			else {
				return true;
			}
		}
		else {
			return $res;
		}
	}
	
	private static function verFix($ver, $fixbld = false) {
		$ver = str_replace(".", "", $ver);
		$ver = str_replace("b", "", $ver);
		if($fixbld == true) {
			$v = explode("-", $ver);
			$ver = $v[0].$v[1];
		}
		return intval($ver);
	}
	
	public static function isLoggedIn() {
		if(isset($_SESSION['xa_id']) && isset($_SESSION['xa_user']) && isset($_SESSION['xa_ip']) && $_SESSION['xa_ip'] == $_SERVER['REMOTE_ADDR']) {
			if($_SESSION['xa_agent'] == sha1($_SERVER['HTTP_USER_AGENT'].md5("./lib/config.php"))) {
				return true;
			}
			else {
				if(version_compare(PHP_VERSION, "5.1.0", ">=")) {
					session_regenerate_id(true);
				}
				else {
					session_regenerate_id();
				}
				return false;
			}
		}
		else {
			return false;
		}
	}

	public static function debugInfo() {
		$var  = "PHP-Version:      ".phpversion()."\n";
		$var .= "MySQL-server:     ".mysql_get_server_info()."\n";
		$var .= "MySQL-client:     ".mysql_get_client_info()."\n";
		$var .= "PHP-interface:    ".php_sapi_name()."\n";
		$var .= "OS:               ".php_uname()." / ".PHP_OS."\n";
		$var .= "XAdmin-version:   ".XA_VERSION."\n";
		$var .= "XAdmin-build:     ".XA_BUILD."\n";
		$var .= "XAdmin-insdate:   ".XA_INSTALLDATE."\n";
		$var .= "Browser:          ".$_SERVER['HTTP_USER_AGENT']."\n";
		$var .= "Server info:      ".$_SERVER['SERVER_SOFTWARE']."\n";
		if(get_magic_quotes_gpc()) {
			$var .= "Magic-quotes-gpc: P&aring;\n";
		}
		else {
			$var .= "Magic-quotes-gpc: Av\n";
		}
		if(get_magic_quotes_runtime()) {
			$var .= "Magic-quotes-run: P&aring;\n";
		}
		else {
			$var .= "Magic-quotes-run: Av\n";
		}
		$var .= "Extensions:      ".print_r(get_loaded_extensions(), 1)."\n";
		
			
		return $var;
	}
}

function fixImgUrl($url) {
	# Fixar inte escapade \", blir galet fail på någe vänster :(
	return str_replace("lib/tiny_mce/plugins/flags/img/", "./adm/lib/tiny_mce/plugins/flags/img/", $url);
}

function fixChars($str) {
	$str = htmlspecialchars($str);
	$str = str_replace("&amp;", "&", $str);
	$str = str_replace("&lt;", "<", $str);
	$str = str_replace("&gt;", ">", $str);
	$str = str_replace("&quot;", "\"", $str);
	return $str;
}

function getNewsItem($item, $id = false) {
	# Scheisse!
	if($id == false) {
		$sql = mysql_query("SELECT id FROM ".XA_PREFIX."nyheter ORDER BY id DESC");
		$data = mysql_fetch_assoc($sql);
		$id = $data['id'];
	}
	$id = intval($id);
	$valid = array("titel", "nyhet", "skribent", "datum");
	if(in_array($item, $valid)) {
			$sql = mysql_query("SELECT ".$item." FROM ".XA_PREFIX."nyheter WHERE id = '".$id."'");
			$data = mysql_fetch_assoc($sql);
			if($item == "datum") {
				$data[$item] = formatTimestamp($data[$item]);
			}
			return fixChars(fixImgUrl($data[$item]));
	}
	else {
		return 0;
	}
}
		
function getMemberItem($item, $id = 0) {
	# Vi turistar i tillvaron,
	$id = intval($id);
	$valid = array("nick", "alder", "position", "mus", "musmatta", "upplosning", "karta", "email", "citat", "info");
	if(in_array($item, $valid)) {
			$sql = mysql_query("SELECT ".$item." FROM ".XA_PREFIX."medlemmar WHERE id = '".$id."'");
			$data = mysql_fetch_assoc($sql);
			return fixChars($data[$item]);
	}
	else {
		return 0;
	}
}

function getMatchItem($item, $id = 0) {
	# och samtidigt som det är massor av mord i våra tankar;
	$id = intval($id);
	$valid = array("motstand", "karta", "typ", "datum", "tid", "resultat", "kommentar");
	if(in_array($item, $valid)) {
			$sql = mysql_query("SELECT ".$item." FROM ".XA_PREFIX."matcher WHERE id = '".$id."'");
			$data = mysql_fetch_assoc($sql);
			return fixChars($data[$item]);
	}
	else {
		return 0;
	}
}

function getHistory() {
	# beger vi oss ut till havs!
	$sql = mysql_query("SELECT value FROM ".XA_PREFIX."settings WHERE setting = 'historia' LIMIT 1");
	$data = mysql_fetch_assoc($sql);
	return fixChars($data['value']);
}

function getContactMail() {
	$sql = mysql_query("SELECT value FROM ".XA_PREFIX."settings WHERE setting = 'kontaktamail' LIMIT 1");
	$data = mysql_fetch_assoc($sql);
	return fixChars($data['value']);
}

function formatTimestamp($timestamp) {
# v2.0b4, Gustav E.
# Returnerar ett timestamp utan sekunder.
	return substr($timestamp, 0, -3);
}

function printCommentForm($id) {
# v2.0b4, Gustav E.
# Skriver ut ett kommentarsformulär för den angivna nyheten ($id)
	$id = intval($id);
	if(getCommentStatus() == true) {
echo <<<HTML
	<form action="./?kommentera={$id}" method="post" class="kommentar">
		<label for="namn">Namn</label>
			<input type="text" name="namn" id="namn" />
		<label for="kommentar">Kommentar</label>
			<textarea rows="10" cols="30" id="kommentar" name="kommentar"></textarea>
		<input type="submit" name="submit" value="Posta kommentar &raquo;" />
	</form>
HTML;
	}
}

function addComment() {
# v2.0b4, Gustav E.
# Funktionen lägger till en kommentar till en nyhet när den postas.
	if(getCommentStatus() == true) {
		if(isset($_POST['submit'], $_POST['namn'], $_POST['kommentar'], $_GET['kommentera']) && intval($_GET['kommentera'])) {
			$_POST = array_map("trim", $_POST);
			if($_POST['namn'] != "" && $_POST['kommentar'] != "") {
				$_POST = array_map("htmlspecialchars", $_POST);
				$_POST = array_map("mysql_real_escape_string", $_POST);
				mysql_query("INSERT INTO ".XA_PREFIX."kommentarer SET namn = '".$_POST['namn']."', kommentar = '".$_POST['kommentar']."', nid = '".$_GET['kommentera']."'");
				header("Location:./?kommenterat");
				die;
			}
		}
	}
}

function showCommentText($text = "Din kommentar har postats.", $class = "postad") {
# v2.0b4, Gustav E.
# Visar en paragraf med klassen $class, innehållandes texten $text
	if(getCommentStatus() == true) {
		if(isset($_GET['kommenterat'])) {
			echo "<p class=\"".$class."\">".$text."</p>";
		}
	}
}

function printComments($nid, $namn = true, $kommentar = true, $datum = false) {
# v2.0b4, Gustav E.
# Skriver ut alla kommentarer tillhörandes nyheten $nid
# v2.0b5: Namnbyte från getComments till printComments för att tydliggöra vad funktionen gör.
	if(getCommentStatus() == true) {
		$nid = intval($nid);
		$sql = mysql_query("SELECT namn, kommentar, datum FROM ".XA_PREFIX."kommentarer WHERE nid = '".$nid."'");
		while(($data = mysql_fetch_assoc($sql)) !== false) {
			echo "\n";
			echo '<div class="kommentar">';
			echo "\n";
			if($namn == true) {
				echo "\t";
				echo '<p class="namn">Skriven av: '.$data['namn'].'</p>';
				echo "\n";
			}
			if($datum == true) {
				echo "\t";
				echo '<p class="datum">Den '.$data['datum'].'</p>';
				echo "\n";
			}
			if($kommentar == true) {
				echo "\t";
				echo '<p class="kommentar">'.nl2br($data['kommentar']).'</p>';
				echo "\n";
			}
			echo "</div>\n";
			echo "\n";
		}
	}
}

function getComments($nid, $namn = true, $kommentar = true, $datum = false) {
# v2.0b5 getComments byter namn till printComments för att tydligare klargöra vad
# funktionen gör. getComments finns dock kvar för bakåtkompabilitet.
	printComments($nid, $namn, $kommentar, $datum);
}

function getNewsComments($item, $id = 0, $lim = 0) {
# v2.0b4, Gustav E.
# Returnerar alla kommentarer för nyheten $id
	$lim = intval($lim);
	$id = intval($id);
	$valid = array("datum", "namn", "kommentar");
	if(in_array($item, $valid)) {
		if($lim > 0) {
			$sql = mysql_query("SELECT ".$item." FROM ".XA_PREFIX."kommentarer WHERE nid = '".$id."' LIMIT '".$lim."' ORDER BY id DESC");
		}
		else {
			$sql = mysql_query("SELECT ".$item." FROM ".XA_PREFIX."kommentarer WHERE nid = '".$id."' ORDER BY id DESC");
		}
		$data = mysql_fetch_assoc($sql);
		if($item == "datum") {
			$data[$item] = formatTimestamp($data[$item]);
		}
		return fixChars($data[$item]);
	}
	else {
		return 0;
	}
}

function getNumberOfComments($id) {
# v2.0b4, Gustav E.
# Returnerar antalet kommentarer för nyheten $id
	$id = intval($id);
	$sql = mysql_query("SELECT kommentar FROM ".XA_PREFIX."kommentarer WHERE nid = '".$id."'");
	$num = mysql_num_rows($sql);
	return $num;
}

function getCommentStatus() {
# v2.0b4, Gustav E.
# Returnerar 1 om kommentarer är på, annars 0
	$sql = mysql_query("SELECT value FROM ".XA_PREFIX."settings WHERE setting = 'kommentarer'");
	$data = mysql_fetch_assoc($sql);
	if($data['value'] == "yes") {
		return true;
	}
	else {
		return false;
	}
}

function getCaptchaStatus() {
# v2.0b4, Gustav E.
# Returnerar 1 om recaptcha ska användas, 2 för inbygd captcha, 0 för ingen
# Notera att captcha inte ännu är implementerat och därför gör funktionen i nuläget inget.
	$data = mysql_fetch_assoc(mysql_query("SELECT value FROM ".XA_PREFIX."settings WHERE setting = 'captcha'"));
	switch($data['value']) {
		case "re":
			#return 1;
			return 0;
			break;
		case "xc":
			#return 2;
			return 0; # Finns ingen egenutvecklad för stunden.
			break;
		case "no":
		default:
			return 0;
			break;
	}
	return 0;
}

function printContactForm() {
# v2.0b4, Gustav E.
# Funktion som skriver ut ett fördefinerat kontakta oss-formulär
	echo <<<HTML

	<form action="{$_SERVER['PHP_SELF']}?kontakta" method="post" class="kontakta">
		<label for="namn">Namn</label>
		<input type="text" name="namn" id="namn" />
		<label for="mail">Mail</label>
		<input type="text" name="mail" id="mail" />
		<label for="msg">Meddelande</label>
		<textarea name="msg" id="msg"></textarea>
		<input type="hidden" name="referer" value="{$_SERVER['PHP_SELF']}" />
		<input type="submit" name="kontakta" value="Skicka &raquo;" />
	</form>
HTML;
}

function sendContactMessage() {
# v2.0b4, Gustav E.
# Funktion som ansvarar för att ett kontakta oss-meddelande skickas iväg.
	if(isset($_GET['kontakta']) && isset($_POST['kontakta'])) {
		$_POST = array_map("trim", $_POST);
		if($_POST['namn'] == "" || $_POST['mail'] == "" || $_POST['msg'] == "") {
			if(isset($_POST['referer'])) {
				header("Location:{$_POST['referer']}?kontaktfel");
				die;
			}
			else {
				header("Location:{$_SERVER['HTTP_REFERER']}?kontaktfel");
				die;
			}
		}
		else {
			$_POST = array_map("htmlspecialchars", $_POST);
			$head = "From: {$_POST['mail']}\r\nReply-To: {$_POST['mail']}\r\nX-Mailer: PHP/".phpversion()."\r\nMIME-Version: 1.0\r\nContent-type: text/plain";
			$title = "[ XAdmin ] Meddelande skickat via kontaktformul&auml;ret";
			$to = getContactMail();
			if(mail($to, $title, $_POST['msg'], $head)) {
				if(isset($_POST['referer'])) {
					header("Location:{$_POST['referer']}?kontaktklart");
					die;
				}
				else {
					header("Location:{$_SERVER['HTTP_REFERER']}?kontaktklart");
					die;
				}
			}
			else {
				if(isset($_POST['referer'])) {
					header("Location:{$_POST['referer']}?kontaktfel");
					die;
				}
				else {
					header("Location:{$_SERVER['HTTP_REFERER']}?kontaktfel");
					die;
				}
			}
		}
	}
}

function getDebugMode() {
# v2.0b4, Gustav E.
# Funktion som returnerar 1 vid debugmode, 0 om inte.
	$data = mysql_fetch_assoc(mysql_query("SELECT value FROM ".XA_PREFIX."settings WHERE setting = 'debug'"));
	if($data['value'] == "yes") {
		return 1;
	}
	else {
		return 0;
	}
}

function checkWritePerms($path) {
# v2.0b5, Gustav E.
# Funktion som ser om $path har skrivrättigheter
# Returnerar 0 om sådana saknas, annars 1.
	if(is_dir($path)) {
		if(is_writeable($path)) {
			return 1;
		}
		else {
			return 0;
		}
	}
	else if(file_exists($path)) {
		if(is_writeable($path)) {
			return 1;
		}
		else {
			return 0;
		}
	}
	else {
		return 0;
	}
}

function guestbookActivated() {
	$data = mysql_fetch_assoc(mysql_query("SELECT value FROM ".XA_PREFIX."settings WHERE setting = 'gastbok'"));
	if($data['value'] == "yes") {
		return true;
	}
	else {
		return false;
	}
}

function printGuestbookForm() {
# v2.0b5, Gustav E.
# Funktion som skriver ut gästboksformuläret
# Detta förutsatt att gästboken är aktiverad.
	if(guestbookActivated() == true) {
		echo <<<GUESTBOOK
		<form action="?postagb" method="post">
			<label for="gbnamn">Namn</label>
			<input type="text" name="gbnamn" id="gbnamn" />
			<label for="gbinlagg">Inl&auml;gg</label>
			<textarea rows="20" cols="200" name="gbinlagg" id="gbinlagg"></textarea>
			<input type="submit" name="skicka" value="Posta inl&auml;gg &raquo;" />
		</form>
GUESTBOOK;
	}
	else {
		echo "<p>G&auml;stboken &auml;r f&ouml;r n&auml;rvarande avst&auml;ngd.</p>\n";
	}
}

function addGuestbookMessage() {
# v2.0b5, Gustav E.
# Funktion som lägger till ett gästboksmeddelande
# om ett sådant har postats.
	if(isset($_GET['postagb'], $_POST['skicka'])) {
		$_POST = array_map("trim", $_POST);
		if($_POST['gbnamn'] == "" || $_POST['gbinlagg'] == "") {
			header("Location: ./?s=gastbok&fel");
			die;
		}
		else {
			$_POST = array_map("htmlentities", $_POST);
			$_POST = array_map("mysql_real_escape_string",$_POST);
			mysql_query("INSERT INTO ".XA_PREFIX."gastbok SET namn = '".$_POST['gbnamn']."', inlagg = '".$_POST['gbinlagg']."'");
			header("Location: ./?s=gastbok&postat");
			die;			
		}
	}
}


function getGuestbookComments($max = false) {
# v2.0b5, Gustav E.
# Funktion som skriver ut alla gästboksinlägg om
# inget annat är definerat ($max)
	if($max == false) {
		$sql = mysql_query("SELECT namn, inlagg, datum FROM ".XA_PREFIX."gastbok ORDER BY id DESC");
	}
	else {
		$max = intval($max);
		$sql = mysql_query("SELECT namn, inlagg, datum FROM ".XA_PREFIX."gastbok LIMIT ".$max." ORDER BY id DESC");
		
	}

}

function backupDatabase() {
# v2.0b5, Gustav E.
# Funktion som i
}