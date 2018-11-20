<?PHP
class Singleton{
	private $name;
	static public $instance;

	private function __construct(){
		
	}
	static public function getInstance(){
	if(!self::$instance){
		self::$instance = new self();
	}
	return self::$instance;
	
	}

	public function setName($n){
		$this->name=$n;
	}

	public function getName(){
		return $this->name;

	}

}
$oa = Singleton::getInstance();
$ob = Singleton::getInstance();

$oa->setName("HELLO");
$ob->setName("World");
echo $oa->getName().$ob->getName();

//因为$oa 和$ob为同一个实例，所以会被覆盖
//因为__construct()方法被私有，所以不能使用new关键字来创建实例

echo "<Hr />";


class Normal{
	private $name;
	static public $instance;

	//private function __construct(){
		
	//}
/**
	static public function getInstance(){
	if(!self::$instance){
		self::$instance = new self();
	}
	return self::$instance;
	
	}
**/
	public function setName($n){
		$this->name=$n;
	}

	public function getName(){
		return $this->name;

	}

}


$oa = new Normal();
$ob = new Normal();
$oa->setName("HELLO");
$ob->setName("World");
echo $oa->getName().$ob->getName();