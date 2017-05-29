<?
class testClass {
	private $time_start;

	public function __construct() {
		echo 'load - ok!<br>';
		$this->time_start = microtime(true);
	}
	public function __destruct() {
		echo "work time: " . number_format( microtime(true) - $this->time_start, 10 ) . ' sec<br>';
		echo 'unload - ok!<br>';
	}
}
?>