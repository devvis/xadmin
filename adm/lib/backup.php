<?php
# Databasbackup
# Copyright (c) 2009 - Gustav Eklundh / XCoders
# Baserat på backup_tables() av David Walsh
# v1.0

function dbBackup($tables) {
	# TODO: Fixa databasbackup

	if(!mysql_ping()) {
	# Lever vi?
		return false;
	}
	if($tables[0] == "" || !isset($tables)) {
		return false;
	}
	foreach($tables as $table) {
		$sql = mysql_query("SELECT * FROM {$table}");
		$num = mysql_num_fields($sql);
		$ret = "DROP TABLE IF EXISTS {$table}";
		$data = mysql_fetch_row(mysql_query("SHOW CREATE TABLE {$table}"));
		$ret .= "\n{$data};\n";
		$i = 0;
		while($i < $num) {
			while(($data = mysql_fetch_row($sql)) !== false) {
				$ret .= "INSERT INTO {$table} VALUES (";
				$j = 0;
				while($j < $num) {
					if(isset($data[$j])) {
						$data[$j] = addslashes($data[$j]);
						$data[$j] = ereg_replace("\n", "\\n", $data[$j]); // TODO: str_replace?
						$ret .= '"'.$data[$j].'"';
					}
					else {
						$ret .= '""';
					}
					if($j < ($num-1)) {
						$ret .= ",";
					}
					++$j;
				}
			}
			++$i;
		}
		$ret .= "\n\n";
	}
	if(file_put_contents("db_backup-".str_replace(":", "-", date("c")), $ret)) {
		return true;
	}
	else {
		return false;
	}
}
