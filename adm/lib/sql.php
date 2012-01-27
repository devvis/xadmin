<?php
# 

class database {
	private $dbtype;
	private $conn;
	private $debug;
	private $connerr;

	public $res;
	
	# För MySQLi-ssl session
	#private $myssl;
	
	public function __construct($db = "mysql", $debug = false) {
		$this->debug = (bool)$debug;
		switch($db) {
			case "postgre":
			case "postgresql":
			case "psql":
					$this->dbtype = "postgresql";
					break;
				
			case "sqlite":
				$this->dbtype = "sqlite";
				break;
			case "mysqli":
			case "mysql":
			default:
				if(extension_loaded("mysqli")) {
					$this->dbtype = "mysqli";
					#$this->dbtype = "mysql";
				}
				else {
					$this->dbtype = "mysql";
				}
				break;
		}
	}
	
	public function __destruct() {
		if($this->dbtype == "mysqli" && $this->conn->ping()) {
		# ping ensures that the connection is active and alive
		# just to avoid trying to close an already closed connection
			$this->conn->close();
		}
		elseif($this->dbtype == "mysql" && mysql_ping($this->conn)) {
		# mysql_ping ensures that the connection is active and alive
		# just to avoid trying to close an already closed connection
			mysql_close($this->conn);
		}
		elseif($this->dbtype == "postgresql") {
			# do stuff
		}
		else {
		# Assuming SQLite
			sqlite_close($this->conn);
		}
	}
	
	public function __toString() {
		# hmmm?
		return $this->res;
	}
	
	public function connect($uname, $passw, $db, $server, $mode = 0666) {
		if($this->dbtype != "sqlite") {
			if(empty($uname) || empty($server)) {
				throw new Exception("Can't initialize connection-phase without sufficient amount of parameters. Go fix.");
			}
		}
		if($db == "") {
			throw new Exception("Can you provide me a database to connect to. Pretty please?");
		}
		switch($this->dbtype) {		
			case "sqlite":
				$this->conn = new SQLiteDatabase($db, $mode, $this->connerr);
				if(!empty($this->connerr)) {
					throw new Exception("Error while initializing the connection to the database. Error returned was: ".$this->connerr);
				}
				break;

			case "postgresql":
				$this->conn = "omg";
				
				
				break;

			case "mysqli":
				$this->conn = new mysqli($server, $uname, $passw, $db);
				if(isset($this->conn->connect_error)) {
					throw new Exception("Error while initializing the connection to the database. Error returned was: ".mysqli_connect_error());
				}
				if($this->debug == true) {
					echo $this->conn->host_info;
				}
				break;

			case "mysql":
			default:
				$this->conn = mysql_connect($server, $uname, $passw);
				if(!$this->conn) {
					throw new Exception("Error while initializing the connection to the database. Error returned was: ".mysql_error());
				}
				if(!mysql_select_db($db, $this->conn)) {
					throw new Exception("Wrong database selected. Please correct.");
				}
				break;
		}
	}
	
	
	public function query($sql, $result = false) {
		if(empty($sql)) {
			throw new Exception("Cannot perform query without a query to execute. Doh.");
		}
		
		if(!$this->conn) {
			throw new Exception("Please initialize a connection before trying to execute stuff.");
		}
		
		if($result == false) {
		# Just perform the query and return the handle
			switch($this->dbtype) {
				case "sqlite":
					$this->res = ":D";
					break;
					
				case "mysqli":
					$this->res = $this->conn->query($sql);
					break;
				
				case "mysql":
				default:
					$this->res = mysql_query($sql, $this->conn);
					break;
			}
		}
		else {
		# Perform the query, then fetch the result and return it as an associative array
			switch($this->dbtype) {
				case "sqlite":
					
					break;
					
				case "postgresql":

					break;
					
				case "mysqli":
					$res = $this->conn->query($sql);
#					if($res != $this->conn->query($sql)) {
#						if($this->debug == true) {
#							self::notice($this->conn->error, $this->conn->errno);
#						}
#						else {
#							$res = null;
#						}
#					}
#					else {
						$this->res = $res->fetch_assoc();
						$res->close();
#					}
					break;
				
				case "mysql":
				default:
					$this->res = mysql_fetch_assoc(mysql_query($sql, $this->conn));
					break;
			}
		}
		$r = $this->res;
		return $r;
	}
	
	private function notice($str, $num = 0) {
		if(!empty($num)) {
			echo "<pre>Error: ",$str,"\nErrno: ",$num,"</pre>";
		}
		else {
			echo "<pre>Error: ",$str,"</pre>";
		}
	}

}
