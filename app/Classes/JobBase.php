<?php
namespace App\Classes;

/**
* Job 基礎類別
*/
abstract class JobBase
{
    /**
     * 資料來源的過瀘條件
     * @var array
     */
    protected $_condition;

    /**
     * 允許搜尋的欄位
     * (由各子類別自行定義，將影響搜尋時的行為)
     *
     * @var array
     */
    protected $_allow_search_field = [];

    /**
     * 更新(從來源更新資料庫)
     */
    abstract public function update();

    /**
     * 查詢(從本地資料庫查詢)
     */
    abstract public function search();

    /**
     * 取得允許查詢欄位
     * @return array 允許查詢欄位
     */
    public function get_allow_search_field()
    {
        return $this->_allow_search_field;
    }

    public function get_region($address)
    {
        preg_match('/(.*?(市|自治州|地區|區劃|縣))/', $address, $matches);
        if (count($matches) > 1) {
            $city = $matches[count($matches) - 2];
            $address = str_replace($city, '', $address);
        } else {
            $city = '';
        }
        preg_match('/(.*?(區|縣|鎮|鄉|街道))/', $address, $matches);
        if (count($matches) > 1) {
            $area = $matches[count($matches) - 2];
            $address = str_replace($area, '', $address);
        } else {
            $area = '';
        }

        $city = str_replace(':', '', $city);
        $city = str_replace('公司地址', '', $city);
        $city = str_replace('(填寫詳細至號)', '', $city);
        $city = trim($city);

        return [
            'city' => $city,
            'area' => $area,
        ];
    }

    public function get_time()
    {
        list($usec, $sec) = explode(" ", microtime());
        return floor($sec . ($usec * 1000));
    }
}
