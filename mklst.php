<?php
# Skapar files.lst :)
require("class.md5.php");
$skip = array("./files.list", "./adm/lib.config.php");
$d = new md5create("./*", $skip);
