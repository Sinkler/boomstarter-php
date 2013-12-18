<?php
/**
 * BoomStarter
 *
 * Набор методов для работы с API
 *
 * @author Drax <dj@boomstarter.ru>
 * @version 1.0
 */
class BoomStarter
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
     * @param string $uuid UUID магазина
     * @param string $token Token магазина
     */
    public function __construct($uuid, $token)
    {
        $this->uuid = $uuid;
        $this->token = $token;
    }

    public function giftListAll($limit = 250, $offset = 0)
    {
        return $this->giftList(null, $limit, $offset);
    }

    public function giftListPending($limit = 250, $offset = 0)
    {
        return $this->giftList('pending', $limit, $offset);
    }

    public function giftListShipping($limit = 250, $offset = 0)
    {
        return $this->giftList('shipping', $limit, $offset);
    }

    public function giftListDelivered($limit = 250, $offset = 0)
    {
        return $this->giftList('delivered', $limit, $offset);
    }

    public function giftStatusOrder($uuid, $order_id)
    {
        return $this->giftStatus('order', $uuid, array('order_id' => $order_id));
    }

    public function giftStatusSchedule($uuid, $timestamp)
    {
        return $this->giftStatus('schedule', $uuid, array('delivery_date' => date('Y-m-d', $timestamp)));
    }

    public function giftStatusDeliveryState($uuid)
    {
        return $this->giftStatus('delivery_state', $uuid, array('delivery_state' => 'delivery'));
    }

    /**
     * Получить список подарков
     *
     * @param string $type Статус подарка (pending, shipping, delivered)
     * @param int $limit
     * @param int $offset
     * @return array Список подарков
     */
    protected function giftList($type = null, $limit = 250, $offset = 0)
    {
        $param = array(
            'limit' => $limit,
            'offset' => $offset,
            'shop_uuid' => $this->uuid,
            'shop_token' => $this->token,
        );
        $url = '/gifts' . ($type ? '/' . $type : '') . '?' . http_build_query($param);
        $data = self::getData($url);
        return $data;
    }

    /**
     * Изменить статус подарка
     *
     * @param string $type Статус подарка (order, schedule, delivery_state)
     * @param string $uuid UUID подарка
     * @param array $param Набор параметров для обращения к API
     * @return array Ответ API
     */
    protected function giftStatus($type, $uuid, $param = array())
    {
        $url = '';
        $put = false;
        switch ($type) {
            case 'order':
                $url = '/gifts/' . $uuid . '/order';
                break;
            case 'schedule':
                $url = '/gifts/' . $uuid . '/schedule';
                break;
            case 'delivery_state':
                $url = '/gifts/' . $uuid . '/delivery_state';
                $put = true;
                break;
        }
        $param['shop_uuid'] = $this->uuid;
        $param['shop_token'] = $this->token;
        $data = self::getData($url, http_build_query($param), $put);
        return $data;
    }

    /**
     * Запрос на получение данных
     *
     * @param string $url Ссылка на API
     * @param string $post POST данные для отправки
     * @param boolean $put Метод запроса
     * @return array
     */
    private function getData($url, $post = null, $put = false)
    {
        $ch = curl_init('https://boomstarter.ru/api/v1.1/partners' . $url);
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
        return json_decode($data, true);
    }

    /**
     * Показать кнопку "Хочу в подарок"
     *
     * @param integer $uuid UUID подарка
     * @return string HTML код кнопки
     */
    public function gift($uuid)
    {
        return '<a href="#" product-id="' . $uuid . '" boomstarter-button-style="glassy">Хочу в подарок</a><script type="text/javascript" src="//boomstarter.ru/assets/gifts/api/v1.js" async="async"></script>';
    }
}
