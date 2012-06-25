<?php
class db {
 
	private $result;
 
 
	public function __construct($server, $user, $password, $db) {
		mysql_connect($server, $user, $password) or $this->debug('Error.');
		mysql_select_db($db) or $this->debug('Error.');
		mysql_query("SET NAMES 'utf8'");
    }
 
 
	public function query($query) {
		$this->result = mysql_query($query) or $this->debug();
		return $this->result;
	}
 
 
	public function execute($query) {
		mysql_query($query) or $this->debug();
	}
 
 
	public function fetchObject($query = '') {
		if (empty($query)) {
			return mysql_fetch_object($this->result);
		} else {
			return mysql_fetch_object($query);
		}
    }
 
 
	public function fetchArray($query = '') {
		if (empty($query)) {
			return mysql_fetch_array($this->result);
		} else {
			return mysql_fetch_array($query);
		}
	}
 
 
    public function numRows($query = '') {
		if (empty($query)) {
			return mysql_num_rows($this->result);
		} else {
			return mysql_num_rows($query);
		}
	}
 
 
	public function debug($error = '') {
		if (defined('DEBUG')) {
			if (empty($error)) { $error = mysql_error(); }
			die('<div><p><strong>Error</strong><span>' . $error . '</span></p></div>');
		}
	}
 
 
	public function free_result($query = '') {
		if (!empty($query)) {
			mysql_free_result($query);
			$this->result = '';
		}
	}
}
 
?>
