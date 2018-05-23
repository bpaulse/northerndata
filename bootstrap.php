<?php
// include('mydb.php');
define( "MACHINENAME", "localpc" );
// define( "MACHINENAME", "web" );

class mydbset {

	private $machine;

	private $dbsetting = array (
		'web' => array(
			'localhost', 'bevanp0', 'GzvwpJRC5GBgLTd', 'bevanp0_qio'
		),
		'localpc' => array(
			'localhost', 'root', '', 'northerndata'
		)
	);

	public function setMachineName ( $name ){
		$this->machine = $name;
	}

	public function __construct( $machine ){
		$this->machine = $machine;
	}

	public function getMachineName() {
		return $this->machine;
	}

	public function setDBSet ($pcname){

		return $this->dbsetting[$pcname];

	}

}

