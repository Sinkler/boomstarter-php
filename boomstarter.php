<?php
/**
 * Boomstarter
 * 
 * Набор методов для работы с API
 * 
 * @author Drax <dj@boomstarter.ru>
 * @version 1.0
 */
class Boomstarter
{
    /**
     * UUID магазина
     *
     * @var string UUID магазина
     */
    private $uuid;
    
    /**
     * Token для доступа к API
     *
     * @var string Token для доступа к API
     */
    private $token;
	
    /**
     * Конструктор класса, объявление переменных
     *
     * @param uuid $uuid UUID магазина
     * @param token $token Token магазина
     */
    public function __construct($uuid, $token)
    {
        $this->uuid = $uuid;
        $this->token = $token;
    }
	
    /**
     * Получить список подарков
     *
     * @param type string Статус подарка (pending, shipping, delivered)
     * @param param array Набор параметров для обращения к API
     * @return array Список подарков
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
     * @param type string Статус подарка (pending, shipping, delivered)
     * @param uuid string UUID подарка
     * @param param array Набор параметров для обращения к API
     * @return array Ответ API
     */
    public function giftStatus($type, $uuid, $param = array())
    {
        switch ($type) {
            case 'order':
                $url = '/gifts/'.$uuid.'/order';
                $put = false;
                break;
            case 'schedule':
                $url = '/gifts/'.$uuid.'/schedule';
                $put = false;
                break;
            case 'delivery_state':
                $url = '/gifts/'.$uuid.'/delivery_state';
                $put = true;
                break;
        }
        $param['shop_uuid'] = $this->uuid;
        $param['shop_token'] = $this->token;
        $data = self::getData($url, http_build_query($param), $put);
        return $data;
    }

    /**
     * Показать кнопку "Хочу в подарок"
     *
     * @param uuid integer UUID подарка
     * @return string HTML код кнопки
     */
    public function gift($uuid)
    {
        return '<a href="#" product-id="'.$uuid.'" boomstarter-button-style="glassy">Хочу в подарок</a><script type="text/javascript" src="//boomstarter.ru/assets/gifts/api/v1.js" async="async"></script>';
    }

    /**
     * Запрос на получение данных
     *
     * @param url string Ссылка на API
     * @param post string POST данные для отправки
     * @param put boolean Метод запроса
     * @return array
     */
    private function getData($url, $post = null, $put = false)
    {
        $ch = curl_init('https://boomstarter.ru/api/v1.1/partners'.$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($put) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        }
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data,1);
    }
}
