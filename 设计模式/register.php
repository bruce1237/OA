<?php
class Register{
	protected static $objects;
	function set($alias,$object){
		self::$objects[$alias] = $object;
	}

	static public function get($alias){
		return self::$objects[$alias];
	}

	private	function _unset($alias){
		unset(self::$objects[$alias]);
	}
}

class A{
	public $a;
	function __construct(){
		$this->a="aaa";
	}
}

$obj = new A();

$reg = new Register();
$reg->set('name',$obj);

$oo = Register::get('name');
var_dump($oo);




