<?php
/**
 * Boomstarter
 * 
 * Набор методом для работы с API
 * 
 * @author <dj@boomstarter.ru>
 * @version 1.0
 */
class Boomstarter
{
    /**
     * UUID магазина
     *
     * @var string
     */
    private $uuid;
    
    /**
     * Token для доступа к API
     *
     * @var string
     */
    private $token;
	
    /**
     * Конструктор класса, объявление переменных
     *
     * @param uuid $uuid
     * @param token $token
     */
    public function __construct($uuid, $token)
    {
        $this->uuid = $uuid;
        $this->token = $token;
    }
	
    /**
     * Получить список подарков
     *
     * @param type string
     * @return array
     */
    public function gifts($type = null)
    {
        $data = self::getData($type);
        print_r($data);
        return $data;
    }

    /**
     * Показать кнопку "Хочу в подарок"
     *
     * @return string
     */
    public function gift($id)
    {
        return '<a href="#" product-id="'.$id.'" boomstarter-button-style="glassy">Хочу в подарок</a><script type="text/javascript" src="//boomstarter.ru/assets/gifts/api/v1.js" async="async"></script>';
    }

    /**
     * Получить список подарков
     *
     * @param type string
     * @param post string
     * @return array
     */
    private function getData($type = null, $post = null)
    {
        $url = 'https://boomstarter.ru/api/v1.1/partners/gifts'.($type ? '/'.$type : '').'?shop_uuid='.$this->uuid.'&shop_token='.$this->token;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data,1);
    }
}
