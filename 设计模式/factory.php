<?php
abstract class Operation{
	abstract public function setValue($v1,$v2);
}

class add extends Operation{
	public function setValue($v1,$v2){
		return $v1+$v2;
	}
}
class sub extends Operation{
	public function setValue($v1,$v2){
		return $v1-$v2;
	}
}


class Factory {
	public static function createObject($operate){
		switch($operate){
			case "+":
				return new add();
			break;
			case "-":
				return new sub();
			break;
			default:
				return "AAA";
			break;
		}
	}
}

$a = Factory::createObject("-");
$result = $a->setValue(30,20);
echo $result;


