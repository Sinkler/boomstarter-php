<?php
class boomstarter {

	private static $instance;
	private function __construct() {
		$this->uuid = '';
		$this->token = '';
	}
	private function __clone() {}
  	public static function getInstance() {
    	if (self::$instance === null) {
    	 	self::$instance = new self;
    	}
    	return self::$instance;
  	}
  	
  	public function setShop($uuid,$token) {
	  	$this->uuid = $uuid;
	  	$this->token = $token;
  	}
  	
  	public function gifts($type = null) {
	  	$data = json_decode(file_get_contents('https://boomstarter.ru/api/v1.1/partners/gifts'.($type ? '/'.$type : '').'?shop_uuid='.$this->uuid.'&shop_token='.$this->token),1);
		print_r($data);
  	}

    public function gift($id) {
        $text = '<a href="#" product-id="'.$id.'" boomstarter-button-style="glassy">Хочу в подарок</a>';
        $text .= '<script type="text/javascript" src="//boomstarter.ru/assets/gifts/api/v1.js" async="async"></script>';
        return $text;
	}

}
?>