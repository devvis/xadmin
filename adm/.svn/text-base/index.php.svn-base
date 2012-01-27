<?php
# XAdmin2 by XCoders
# Taking administrative tasks onto a new level.
# Easy as cake.
# Copyright (c) 2009 XCoders / Gustav Eklundh
# Licensed under the terms of the BSD-License, see licens.txt for more information
session_start();
require("lib/config.php");
require("lib/core.php");
require("lib/backup.php");
$backup = "./lib";			# <-- fixa sen :|


# TODO: Fixa modalboxar för Ja/Nej-frågor

if(!defined("XA_PREFIX")) {
	if(is_dir("../ins/")) {
		# XAdmin är inte installerat, vi går till installationen
		header("Location:../ins/");
		die;
	}
	else {
		xa_notice("XAdmin verkar inte vara installerat, v&auml;nligen ladda upp /ins/-mappen och installera.");
		die;
	}
}

/*
if(version_compare(PHP_VERSION, "5.1.0", ">=")) {
	session_regenerate_id(true);
}
else {
	session_regenerate_id();
	$tmp = SID;
	session_write_close();
	session_id($tmp);
	session_start();
}
*/


if(isset($_GET['login']) && isset($_POST['login'])) {
	$_POST = array_map("mysql_real_escape_string", $_POST);
	$_POST = array_map("htmlspecialchars", $_POST);
	$_POST = array_map("trim", $_POST);
	if($_POST['xa_name'] == "" || $_POST['xa_pass'] == "") {
		header("Location:./?err=1");
		die;
	}
	$sql = mysql_query("SELECT id FROM ".XA_PREFIX."konton WHERE uname = '".$_POST['xa_name']."' AND pass = '".genPass($_POST['xa_pass'], $_POST['xa_name'])."'") or exit(mysql_error());
	if(!$data = mysql_fetch_assoc($sql)) {
		header("Location:./?err=2");
		die;
	}
	
	session_regenerate_id(true);
	
	mysql_query("UPDATE ".XA_PREFIX."konton SET ltimes = ltimes+1 WHERE id = '".$data['id']."'") or exit(mysql_error());
	
	$_SESSION['xa_agent'] = sha1($_SERVER['HTTP_USER_AGENT'].md5("./lib/config.php"));
	$_SESSION['xa_ip'] = $_SERVER['REMOTE_ADDR'];
	$_SESSION['xa_user'] = $_POST['xa_name'];
	$_SESSION['xa_id'] = $data['id'];
	header("Location:./");
	die;
}

if(isset($_GET['logout']) && isset($_SESSION['xa_user'])) {
	if(isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-3600, '/');
	}
	session_regenerate_id(true);
	$_SESSION = array();
	session_destroy();
	header("Location:./");
	die;
}

if(!main::isLoggedIn()) {
	die(require("./inc/login.php"));
}

if(isset($_GET['p'])) {
	switch($_GET['p']) {
		case "nyhet":
		##----------------
		# NYHETER
		##----------------
			if(isset($_POST['titel']) && isset($_POST['skribent']) && isset($_POST['nyhet'])) {
				if($_POST['titel'] != "" && $_POST['skribent'] != "" && $_POST['nyhet'] != "") {
					$_POST = array_map("mysql_real_escape_string", $_POST);
					$_POST = array_map("trim", $_POST);
					mysql_query("INSERT INTO ".XA_PREFIX."nyheter SET titel = '".$_POST['titel']."', nyhet = '".$_POST['nyhet']."', skribent = '".mysql_real_escape_string($_SESSION['xa_user'])."'") or exit(mysql_error());
					header("Location:./?s=nyheter&postad");
					die;
				}
				else {
				# användaren har inte fyllt i alla fält, vi sparar data och skickar tillbaka användaren
				# lite lame om man måste skriva om en pisslång nyhet bara för man missade rubriken ;)
					$_SESSION['n_titel'] = $_POST['titel'];
					$_SESSION['n_nyhet'] = $_POST['nyhet'];
					header("Location:./?s=nyheter&skriv&err=2");
					die;
				}
			}
			else {
			# användaren har inte postat någon data, bad stuff will happen.
				header("Location:./?s=nyheter&skriv&err=1");
				die;
			}
			break;
		
		case "match":
		##----------------
		# MATCHER
		##----------------
			if(isset($_POST['motstand']) && isset($_POST['karta']) && isset($_POST['typ']) && isset($_POST['date']) && isset($_POST['tid']) && isset($_POST['resultat1']) && isset($_POST['resultat2']) && isset($_POST['kommentar'])) {
				if($_POST['motstand'] != "" && $_POST['karta'] != "" && $_POST['typ'] != "" && $_POST['date'] != "" && $_POST['tid'] != "" && $_POST['resultat1'] != "" && $_POST['resultat2'] != "" && $_POST['kommentar']) {
					$_POST = array_map("mysql_real_escape_string", $_POST);
					$_POST = array_map("trim", $_POST);
					mysql_query("INSERT INTO ".XA_PREFIX."matcher SET motstand = '".$_POST['motstand']."', karta = '".$_POST['karta']."', typ = '".$_POST['typ']."', datum = '".$_POST['date']."', tid = '".$_POST['tid']."', resultat = '".$_POST['resultat1']."-".$_POST['resultat2']."', kommentar = '".$_POST['kommentar']."'") or exit(mysql_error());
					header("Location:./?s=matcher&postad");
					die;
				}
				else {
				# användaren har inte fyllt i alla fält, vi sparar data och skickar tillbaka användaren
				# lite lame om man måste skriva om en pisslång nyhet bara för man missade rubriken ;)
					$_SESSION['ma_motstand']	= $_POST['motstand'];
					$_SESSION['ma_karta']		= $_POST['karta'];
					$_SESSION['ma_typ']			= $_POST['typ'];
					$_SESSION['ma_date']		= $_POST['date'];
					$_SESSION['ma_tid']			= $_POST['tid'];
					$_SESSION['ma_resultat1']	= $_POST['resultat1'];
					$_SESSION['ma_resultat2']	= $_POST['resultat2'];
					$_SESSION['ma_kommentar']	= $_POST['kommentar'];
					header("Location:./?s=matcher&add&err=2");
					die;
				}
			}
			else {
			# användaren har inte postat någon data, bad stuff will happen.
				header("Location:./?s=matcher&add&err=1");
				die;
			}
			break;
		
		case "medlem":
		##----------------
		# MEDLEMMAR
		##----------------
			if(isset($_POST['nick']) && isset($_POST['alder']) && isset($_POST['position']) && isset($_POST['mus']) && isset($_POST['musmatta']) && isset($_POST['upplosning']) && isset($_POST['karta']) && isset($_POST['email']) && isset($_POST['citat']) && isset($_POST['info'])) {
				if($_POST['nick'] != "" && $_POST['alder'] != "" && $_POST['position'] != "" && $_POST['mus'] != "" && $_POST['musmatta'] != "" && $_POST['upplosning'] != "" && $_POST['karta'] != "" && $_POST['email'] != "" && $_POST['citat'] != "" && $_POST['info'] != "") {
					$_POST = array_map("mysql_real_escape_string", $_POST);
					$_POST = array_map("trim", $_POST);
					mysql_query("INSERT INTO ".XA_PREFIX."medlemmar SET nick = '".$_POST['nick']."', alder = '".$_POST['alder']."', position = '".$_POST['position']."', mus = '".$_POST['mus']."', musmatta = '".$_POST['musmatta']."', upplosning = '".$_POST['upplosning']."', karta = '".$_POST['karta']."', email = '".$_POST['email']."', citat = '".$_POST['citat']."', info = '".$_POST['info']."'") or exit(mysql_error());
					header("Location:./?s=medlemmar&postad");
					die;
				}
				else {
					$_SESSION['me_nick']		= $_POST['nick'];
					$_SESSION['me_alder']		= $_POST['alder'];
					$_SESSION['me_position']	= $_POST['position'];
					$_SESSION['me_mus']			= $_POST['mus'];
					$_SESSION['me_musmatta']	= $_POST['musmatta'];
					$_SESSION['me_upplosning']	= $_POST['upplosning'];
					$_SESSION['me_karta']		= $_POST['karta'];
					$_SESSION['me_email']		= $_POST['email'];
					$_SESSION['me_citat']		= $_POST['citat'];
					$_SESSION['me_info']		= $_POST['info'];
					header("Location:./?s=medlemmar&add&err=2");
					die;
				}
			}
			else {
				header("Location:./?s=medlemmar&add&err=1");
				die;
			}
			break;
			
		case "konto":
			if(!isHeadAdmin($_SESSION['xa_id'])) {
				header("Location:./");
				die;
			}
			if(isset($_POST['uname']) && isset($_POST['pass']) && isset($_POST['name']) && isset($_POST['uclass']) && isset($_POST['email'])) {
				if($_POST['uname'] != "" && $_POST['pass'] != "" && $_POST['name'] != "" && $_POST['uclass'] != "") {
					$_POST = array_map("mysql_real_escape_string", $_POST);
					$_POST = array_map("trim", $_POST);
					$_POST = array_map("htmlspecialchars", $_POST);
					mysql_query("INSERT INTO ".XA_PREFIX."konton SET uname = '".$_POST['uname']."', pass = '".genPass($_POST['pass'], $_POST['uname'])."', name = '".$_POST['name']."', uclass = '".$_POST['uclass']."', email = '".$_POST['email']."'") or exit(mysql_error());
					header("Location:./?s=konton&postad");
					die;
				}
				else {
					$_SESSION['ko_uname']	= $_POST['uname'];
					$_SESSION['ko_pass']	= $_POST['pass'];
					$_SESSION['ko_name']	= $_POST['name'];
					$_SESSION['ko_uclass']	= $_POST['uclass'];
					$_SESSION['ko_email']	= $_POST['email'];
					header("Location:./?s=konton&add&err=2");
					die;
				}
			}
			else {
				header("Location:./?s=konton&add&err=1");
				die;
			}
			break;
	}
}

if(isset($_GET['u']) && isset($_GET['id']) && $_GET['id'] = intval($_GET['id'])) {
	switch($_GET['u']) {
		case "nyhet":
		##----------------
		# NYHETER
		##----------------
			if(isset($_POST['titel']) && isset($_POST['skribent']) && isset($_POST['nyhet'])) {
				if($_POST['titel'] != "" && $_POST['skribent'] != "" && $_POST['nyhet'] != "") {
					# do stuff
					$_POST = array_map("mysql_real_escape_string", $_POST);
					$_POST = array_map("trim", $_POST);
					mysql_query("UPDATE ".XA_PREFIX."nyheter SET titel = '".$_POST['titel']."', nyhet = '".$_POST['nyhet']."', skribent = '".mysql_real_escape_string($_SESSION['xa_user'])."' WHERE id = '".$_GET['id']."'") or exit(mysql_error());
					header("Location:./?s=nyheter&uppdaterad");
					die;
				}
				else {
				# användaren har inte fyllt i alla fält, vi sparar data och skickar tillbaka användaren
				# lite lame om man måste skriva om en pisslång nyhet bara för man missade rubriken ;)
					$_SESSION['n_titel'] = $_POST['titel'];
					$_SESSION['n_nyhet'] = $_POST['nyhet'];
					header("Location:./?s=nyheter&e={$_GET['id']}&err=2");
					die;
				}
			}
			else {
			# användaren har inte postat någon data, bad stuff will happen.
				header("Location:./?s=nyheter&e={$_GET['id']}&err=1");
				die;
			}
			break;
		
		case "match":
		##----------------
		# MATCHER
		##----------------
			if(isset($_POST['motstand']) && isset($_POST['karta']) && isset($_POST['typ']) && isset($_POST['date']) && isset($_POST['tid']) && isset($_POST['resultat1']) && isset($_POST['resultat2']) && isset($_POST['kommentar'])) {
				if($_POST['motstand'] != "" && $_POST['karta'] != "" && $_POST['typ'] != "" && $_POST['date'] != "" && $_POST['tid'] != "" && $_POST['resultat1'] != "" && $_POST['resultat2'] != "" && $_POST['kommentar']) {
					$_POST = array_map("mysql_real_escape_string", $_POST);
					$_POST = array_map("trim", $_POST);
					mysql_query("UPDATE ".XA_PREFIX."matcher SET motstand = '".$_POST['motstand']."', karta = '".$_POST['karta']."', typ = '".$_POST['typ']."', datum = '".$_POST['date']."', tid = '".$_POST['tid']."', resultat = '".$_POST['resultat1']."-".$_POST['resultat2']."', kommentar = '".$_POST['kommentar']."' WHERE id = '".$_GET['id']."'") or exit(mysql_error());
					header("Location:./?s=matcher&uppdaterad");
					die;
				}
				else {
				# användaren har inte fyllt i alla fält, vi sparar data och skickar tillbaka användaren
				# lite lame om man måste skriva om en pisslång nyhet bara för man missade rubriken ;)
					$_SESSION['ma_motstand']	= $_POST['motstand'];
					$_SESSION['ma_karta']		= $_POST['karta'];
					$_SESSION['ma_typ']			= $_POST['typ'];
					$_SESSION['ma_date']		= $_POST['date'];
					$_SESSION['ma_tid']			= $_POST['tid'];
					$_SESSION['ma_resultat1']	= $_POST['resultat1'];
					$_SESSION['ma_resultat2']	= $_POST['resultat2'];
					$_SESSION['ma_kommentar']	= $_POST['kommentar'];
					header("Location:./?s=matcher&e={$_GET['id']}&err=2");
					die;
			}
		}
		else {
		# användaren har inte postat någon data, bad stuff will happen.
			header("Location:./?s=matcher&e={$_GET['id']}&err=1");
			die;
		}
		break;
		
		case "medlem":
		##----------------
		# MEDLEMMAR
		##----------------
			if(isset($_POST['nick']) && isset($_POST['alder']) && isset($_POST['position']) && isset($_POST['mus']) && isset($_POST['musmatta']) && isset($_POST['upplosning']) && isset($_POST['karta']) && isset($_POST['email']) && isset($_POST['citat']) && isset($_POST['info'])) {
				if($_POST['nick'] != "" && $_POST['alder'] != "" && $_POST['position'] != "" && $_POST['mus'] != "" && $_POST['musmatta'] != "" && $_POST['upplosning'] != "" && $_POST['karta'] != "" && $_POST['email'] != "" && $_POST['citat'] != "" && $_POST['info'] != "") {
					$_POST = array_map("mysql_real_escape_string", $_POST);
					$_POST = array_map("trim", $_POST);
					mysql_query("UPDATE ".XA_PREFIX."medlemmar SET nick = '".$_POST['nick']."', alder = '".$_POST['alder']."', position = '".$_POST['position']."', mus = '".$_POST['mus']."', musmatta = '".$_POST['musmatta']."', upplosning = '".$_POST['upplosning']."', karta = '".$_POST['karta']."', email = '".$_POST['email']."', citat = '".$_POST['citat']."', info = '".$_POST['info']."' WHERE id = '".$_GET['id']."'") or exit(mysql_error());
					header("Location:./?s=medlemmar&uppdaterad");
					die;
				}
				else {
					$_SESSION['me_nick']		= $_POST['nick'];
					$_SESSION['me_alder']		= $_POST['alder'];
					$_SESSION['me_position']	= $_POST['position'];
					$_SESSION['me_mus']			= $_POST['mus'];
					$_SESSION['me_musmatta']	= $_POST['musmatta'];
					$_SESSION['me_upplosning']	= $_POST['upplosning'];
					$_SESSION['me_karta']		= $_POST['karta'];
					$_SESSION['me_email']		= $_POST['email'];
					$_SESSION['me_citat']		= $_POST['citat'];
					$_SESSION['me_info']		= $_POST['info'];
					header("Location:./?s=medlemmar&e={$_GET['id']}&err=2");
					die;
				}
			}
			else {
				header("Location:./?s=medlemmar&e={$_GET['id']}&add&err=1");
				die;
			}
			break;

		case "konto":
			if(!isHeadAdmin($_SESSION['xa_id'])) {
				header("Location:./");
				die;
			}
			if(isset($_POST['name']) && isset($_POST['uclass']) && isset($_POST['email'])) {
				if($_POST['name'] != "" && $_POST['uclass'] != "") {
					if($_GET['id'] == $_SESSION['xa_id'] && $_POST['uclass'] == "n") {
						header("Location:./?s=konton&fel");
						die;
					}
					$_POST = array_map("mysql_real_escape_string", $_POST);
					$_POST = array_map("trim", $_POST);
					$_POST = array_map("htmlspecialchars", $_POST);

					mysql_query("UPDATE ".XA_PREFIX."konton SET name = '".$_POST['name']."', uclass = '".$_POST['uclass']."', email = '".$_POST['email']."' WHERE id = '".$_GET['id']."'") or exit(mysql_error());
					header("Location:./?s=konton&uppdaterad");
					die;
				}
				else {
					$_SESSION['ko_name']	= $_POST['name'];
					$_SESSION['ko_uclass']	= $_POST['uclass'];
					$_SESSION['ko_email']	= $_POST['email'];
					header("Location:./?s=konton&e={$_GET['id']}&err=2");
					die;
				}
			}
			else {
				header("Location:./?s=konton&e={$_GET['id']}&err=1");
				die;
			}
			break;
		
		case "historia":
			if(isset($_POST['historia'])) {
				$_POST = array_map("mysql_real_escape_string", $_POST);
				$_POST = array_map("trim", $_POST);
				
				mysql_query("UPDATE ".XA_PREFIX."settings SET value = '".$_POST['historia']."' WHERE setting = 'historia'") or exit(mysql_error());

				header("Location:./?s=historia&uppdaterad");
				die;
			}
			else {
				header("Location:./?s=historia&e=1");
				die;
			}
	}
}

		
if(isset($_GET['d']) && isset($_GET['id']) && $_GET['id'] = intval($_GET['id'])) {
	switch($_GET['d']) {
		case "nyhet":
			mysql_query("DELETE FROM ".XA_PREFIX."nyheter WHERE id = '".$_GET['id']."'") or exit(mysql_error());
			header("Location:./?s=nyheter&borttagen");
			die;
			break;
		case "kommentar":
			mysql_query("DELETE FROM ".XA_PREFIX."kommentarer WHERE id = '".$_GET['id']."'") or exit(mysql_error());
			header("Location:./?s=nyheter&cborttagen");
			die;
			break;
		case "match":
			mysql_query("DELETE FROM ".XA_PREFIX."matcher WHERE id = '".$_GET['id']."'") or exit(mysql_error());
			header("Location:./?s=matcher&borttagen");
			die;
			break;
		case "medlem":
			mysql_query("DELETE FROM ".XA_PREFIX."medlemmar WHERE id = '".$_GET['id']."'") or exit(mysql_error());
			header("Location:./?s=medlemmar&borttagen");
			die;
			break;
		case "konto":
			if(!isHeadAdmin($_SESSION['xa_id']) || $_GET['id'] == $_SESSION['xa_id']) {
			# Inge bra om folk får plocka konton som dom vill :(
				header("Location:./?err=100");
				die;
				break;
			}
			else {
				mysql_query("DELETE FROM ".XA_PREFIX."konton WHERE id = '".$_GET['id']."'") or exit(mysql_error());
				header("Location:./?s=konton&borttagen");
				die;
				break;
			}
	}
}


if(isset($_GET['su'])) {
	if(isset($_POST['klannamn']) && isset($_POST['kontaktamail'])) {
		$_POST = array_map("trim", $_POST);
		if($_POST['klannamn'] != "" && $_POST['kommentarer'] != "") {
			if($_POST['kontaktamail'] == "") {
				$_POST['kontaktamail'] = " ";
			}
			$_POST = array_map("mysql_real_escape_string", $_POST);
			mysql_query("UPDATE ".XA_PREFIX."settings SET value = '".$_POST['klannamn']."' WHERE setting = 'klannamn'") or exit(mysql_error());
			mysql_query("UPDATE ".XA_PREFIX."settings SET value = '".$_POST['kontaktamail']."' WHERE setting = 'kontaktamail'") or exit(mysql_error());
			mysql_query("UPDATE ".XA_PREFIX."settings SET value = '".$_POST['kommentarer']."' WHERE setting = 'kommentarer'") or exit(mysql_error());
			mysql_query("UPDATE ".XA_PREFIX."settings SET value = '".$_POST['debug']."' WHERE setting = 'debug'") or exit(mysql_error());
			header("Location:./?s=settings&uppdaterad");
			die;
		}
		else {
			header("Location:./?s=settings&err=2");
			die;
		}
	}
	else {
		header("Location:./?s=settings&err=1");
		die;
	}
}

if(isset($_GET['kontakta']) && isset($_POST['submit'])) {
	$_POST = array_map("trim", $_POST);
	if(empty($_POST['xa_namn']) || empty($_POST['xa_arende']) || empty($_POST['xa_mail']) || empty($_POST['xa_msg'])) {
		header("Location:?s=kontakta&e=1");
		die;
	}
	if(function_exists("filter_var")) {
		if(!filter_var($_POST['xa_mail'], FILTER_VALIDATE_EMAIL)) {
			header("Location:?s=kontakta&e=2");
			die;
		}
	}
	$_POST = array_map("htmlspecialchars", $_POST);
	$head = "From: {$_POST['xa_mail']}\r\nReply-To: {$_POST['xa_mail']}\r\nX-Mailer: PHP/".phpversion()."\r\nMIME-Version: 1.0\r\nContent-type: text/plain";
	switch($_POST['xa_arende']) {
		case "bugg":
			$title = "[ XAdmin ] Bugg i XAdmin v".XA_VERSION;
			$msg = $_POST['xa_msg']."\n\nDebuginfo:\n".main::debugInfo();
			break;
		case "forslag":
			$title = "[ XAdmin ] F&ouml;rslag till XAdmin";
			$msg = $_POST['xa_msg'];
			break;
		case "ovrigt":
		default:
			$title = "[ XAdmin ] &Ouml;vrigt";
			$msg = $_POST['xa_msg'];
			break;
	}
	if(mail("gustav@xcoders.info", $title, $msg, $head)) {
		header("Location:?s=kontakta&skickat");
		die;
	}
	else {
		header("Location:?s=kontakta&fel");
		die;
	}
}
if(isset($_GET['backup'], $_POST['backup'])) {
	$tables = array(XA_PREFIX."kommentarer", XA_PREFIX."konton", XA_PREFIX."matcher", XA_PREFIX."medlemmar", XA_PREFIX."nyheter", XA_PREFIX."settings");
	dbBackup($tables);
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>XAdmin v<?php echo XA_VERSION; ?> - <?php echo getClanName(); ?></title>
<link type="text/css" rel="stylesheet" href="reset.css" />
<link type="text/css" rel="stylesheet" href="style.css" />
<link type="text/css" rel="stylesheet" href="./lib/ui.core.css" />
<link type="text/css" rel="stylesheet" href="./lib/ui.theme.css" />
<link type="text/css" rel="stylesheet" href="./lib/clockpick.css" />
<link type="text/css" rel="stylesheet" href="./lib/ui.datepicker.css" />
<script type="text/javascript" src="./lib/jquery.js"></script>
<script type="text/javascript" src="./lib/jquery.ui.js"></script>
<script type="text/javascript" src="./lib/clockpick.js"></script>
<script type="text/javascript" src="./lib/ui.datepicker-sv.js"></script>
<script type="text/javascript" src="./lib/main.js"></script>
<?php
if(isset($_GET['s'])) {
	if($_GET['s'] == "nyheter" || $_GET['s'] == "matcher" || $_GET['s'] == "medlemmar" || $_GET['s'] == "historia") {
		$tema = "advanced";
echo <<<JS
	<script type="text/javascript" src="./lib/tiny_mce/tiny_mce_gzip.js"></script>
	<script type="text/javascript" src="./lib/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript">
		tinyMCE_GZ.init({
			mode : "textareas",
			theme : "{$tema}",
			language : "sv",
			disk_cache : true,
			plugins : "tinyautosave,flags",
			theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect",
			theme_advanced_buttons2 : "bullist,numlist,|,outdent,indent,|,link,unlink,image,code,flags,|,tinyautosave",
			theme_advanced_button2_add : "flags",
			theme_advanced_buttons3 : "",
			theme_advanced_disable : "help,newdocument,strikethrough,sub,sup,undo,redo,styleselect,anchor,cleanup",
			theme_advanced_blockformats : "p,div,h1,h2,h3,h4,blockquote",
			content_css : "content.css"
		});
		tinyMCE.init({
			mode : "textareas",
			theme : "{$tema}",
			language : "sv",
			plugins : "tinyautosave,flags",
			theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect",
			theme_advanced_buttons2 : "bullist,numlist,|,outdent,indent,|,link,unlink,image,code,flags,|,tinyautosave",
			theme_advanced_buttons3 : "",
			theme_advanced_button2_add : "flags",
			theme_advanced_disable : "help,newdocument,strikethrough,sub,sup,undo,redo,styleselect,anchor,cleanup",
			theme_advanced_blockformats : "p,div,h1,h2,h3,h4,blockquote",
			content_css : "content.css"
		});
	</script>
JS;
	}
}
?>
</head>

<body>
	<div id="base">
		<div id="header">
			<a class="logo" href="./">xadmin</a>
			<ul class="topnav">
				<li><a href="<?php echo str_replace("/adm/index.php", "", $_SERVER['PHP_SELF']); ?>">G&aring; till sidan</a></li>
				<li>|</li>
<?php
	if(isHeadAdmin($_SESSION['xa_id'])) {
?>
				<li>Inloggad som <a href="?s=konton&e=<?php echo $_SESSION['xa_id']; ?>"><?php echo $_SESSION['xa_user']; ?></a></li>
<?php
	}
	else {
?>
				<li>Inloggad som <?php echo $_SESSION['xa_user']; ?></li>
<?php
	}
?>
				<li>|</li>
				<li class="logout"><a href="?logout">Logga ut</a></li>
			</ul>
		</div>

		<ul id="meny">
			<li><a href="./">F&ouml;rstasidan</a></li>
			<li>
				<a href="?s=nyheter">Nyheter</a>
				<ul>
					<li><a href="?s=nyheter&amp;skriv">Posta nyhet</a></li>
				</ul>
			</li>
			<li>
				<a href="?s=medlemmar">Medlemmar</a>
				<ul>
					<li><a href="?s=medlemmar&amp;add">L&auml;gg till medlem</a></li>
				</ul>
			</li>
			<li>
				<a href="?s=matcher">Matcher</a>
				<ul>
					<li><a href="?s=matcher&amp;add">L&auml;gg till match</a></li>
				</ul>
			</li>
			<li><a href="?s=historia">Historia</a></li>
<?php
	if(isHeadAdmin($_SESSION['xa_id'])) {
?>
			<li><a href="?s=konton">Konton</a></li>
<?php
	}
?>
			<li><a href="?s=settings">Inst&auml;llningar</a></li>
		</ul>

		<div id="main">
			<ul id="snabbmeny">
				<li><a href="?s=nyheter&amp;skriv">Skriv en nyhet</a></li>
				<li><a href="?s=matcher&amp;add">L&auml;gg till en match</a></li>
				<li><a href="?s=medlemmar&amp;add">L&auml;gg till en medlem</a></li>
			</ul>
			<div id="cont">
<?php
# here goes the include-kåd
	$inc = array("nyheter", "matcher", "medlemmar", "gastbok", "historia", "konton", "settings", "kontakta", "xadmin");
	if(isset($_GET['s']) && in_array($_GET['s'], $inc) && is_file("./inc/".$_GET['s'].".php")) {
		require("./inc/".$_GET['s'].".php");
	}
	else {
		require("./inc/start.php");
	}

?>
			</div>

			<div id="footer">
				Copyright &copy; 2006 - <?php echo date("Y"); ?> <a href="http://xcoders.info/" title="http://xcoders.info/">XCoders.info</a> &mdash; <a href="?s=kontakta" title="Kontakta oss">Kontakta XCoders</a>
			</div>
		</div>
	</div>

</body>
</html>
