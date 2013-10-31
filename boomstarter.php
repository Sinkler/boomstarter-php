<?php
/**
 * Boomstarter
 * 
 * Набор методов для работы с API
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
     * @param param array
     * @return array
     */
    public function giftList($type = null, $param = array())
    {
        $param['shop_uuid'] = $this->uuid;
        $param['shop_token'] = $this->token;
        $url = '/gifts'.($type ? '/'.$type : '').'?'.http_build_query($param);
        $data = self::getData($url);
        return $data;
    }
    
    /**
     * Изменить статус подарка
     *
     * @param type string
     * @param uuid string
     * @param param array
     * @return array
     */
    public function giftStatus($type, $uuid, $param = array())
    {
        switch ($type) {
            case 'order':
                $url = '/gifts/'.$uuid.'/order';
                break;
            case 'schedule':
                $url = '/gifts/'.$uuid.'/schedule';
                break;
            case 'delivery_state':
                $url = '/gifts/'.$uuid.'/delivery_state';
                break;
        }
        $param['shop_uuid'] = $this->uuid;
        $param['shop_token'] = $this->token;
        $data = self::getData($url, http_build_query($param));
        return $data;
    }

    /**
     * Показать кнопку "Хочу в подарок"
     *
     * @param id integer
     * @return string
     */
    public function gift($id)
    {
        return '<a href="#" product-id="'.$id.'" boomstarter-button-style="glassy">Хочу в подарок</a><script type="text/javascript" src="//boomstarter.ru/assets/gifts/api/v1.js" async="async"></script>';
    }

    /**
     * Запрос на получение данных
     *
     * @param url string
     * @param post string
     * @return array
     */
    private function getData($url, $post = null)
    {
        $ch = curl_init('https://boomstarter.ru/api/v1.1/partners'.$url);
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
