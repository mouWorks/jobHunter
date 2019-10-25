<?php
namespace App\Classes;

use App\Classes\JobBase;
use GuzzleHttp\Promise;
use App\Library\Curl;
use App\Library\Debug;
use App\Models\Company;
use App\Models\Job;
use GuzzleHttp\Client;


/**
* Job 104
*/
class Job104 extends JobBase
{
    /**
     * 是否為預灠模式(不做資料匯入，只顯示 104 條件及資訊)
     * @var boolean
     */
    private $_preview_mode = FALSE;

    public function __construct()
    {
        define('JSON_DIR', __DIR__ . '/../../resources/json/');
    }

    /**
     * 允許搜尋的欄位
     *
     * @var array
     */
    protected $_allow_search_field = [
        'keyword',
        'cat',
        'area',
        'ind',
        'major',
        'comp',
        'jskill',
        'cert',
    ];

    /**
     * 104 API 網址
     * @var string
     */
    private $_api_url = 'https://www.104.com.tw/i/apis/jobsearch.cfm';

    /**
     * 呼叫 104 API 的查詢條件
     * @var array
     */
    private $_update_conditions = [
        'cat'  => '2007001006',
        'role' => [1, 4],
        'pgsz' => 50,
        'fmt'  => 8,
    ];

    /**
     * 取得 104 API 呼叫網址
     * @return string url
     */
    private function _get_api_url()
    {
        return $this->_api_url . '?' . $this->_get_api_params() . '&fmt=8';
    }

    /**
     * 組合 api 參數
     * @return string api 參數
     */
    private function _get_api_params()
    {
        $get_param_array = [];
        foreach ($this->_update_conditions as $key => $value)
        {
            $value_str = '';
            switch (gettype($value))
            {
                case 'array':
                    $value_str = implode(',', $value);
                    break;

                case 'string':
                    $value_str = urlencode($value);
                    break;

                case 'integer':
                default:
                    $value_str = $value;
                    break;
            }
            $get_param_array[] = "{$key}={$value_str}";
        }
        return implode('&', $get_param_array);
    }

    /**
     * 設定更新過瀘條件
     * @param array $conditions 更新時的查詢條件
     */
    public function _set_update_condition($conditions = NULL)
    {
        $this->_update_conditions = $conditions;
    }

    /**
     * 轉換 104 API 來的 JOB DATA
     * @param  object $row api 資料
     * @return array      job 資料表資料
     */
    private function _convert_job_row_data($row)
    {
        return [
            'title'                =>  isset($row['JOB']) ? $row['JOB'] : NULL,
            'j_code'               =>  isset($row['J']) ? $row['J'] : NULL,
            'job_addr_no_descript' =>  isset($row['JOB_ADDR_NO_DESCRIPT']) ? $row['JOB_ADDR_NO_DESCRIPT'] : NULL,
            'job_address'          =>  isset($row['JOB_ADDRESS']) ? $row['JOB_ADDRESS'] : NULL,
            'jobcat_descript'      =>  isset($row['JOBCAT_DESCRIPT']) ? $row['JOBCAT_DESCRIPT'] : NULL,
            'description'          =>  isset($row['DESCRIPTION']) ? $row['DESCRIPTION'] : NULL,
            'period'               =>  isset($row['PERIOD']) ? $row['PERIOD'] : NULL,
            'appear_date'          =>  isset($row['APPEAR_DATE']) ? $row['APPEAR_DATE'] : NULL,
            'dis_role'             =>  isset($row['DIS_ROLE']) ? $row['DIS_ROLE'] : NULL,
            'dis_level'            =>  isset($row['DIS_LEVEL']) ? $row['DIS_LEVEL'] : NULL,
            'dis_role2'            =>  isset($row['DIS_ROLE2']) ? $row['DIS_ROLE2'] : NULL,
            'dis_level2'           =>  isset($row['DIS_LEVEL2']) ? $row['DIS_LEVEL2'] : NULL,
            'dis_role3'            =>  isset($row['DIS_ROLE3']) ? $row['DIS_ROLE3'] : NULL,
            'dis_level3'           =>  isset($row['DIS_LEVEL3']) ? $row['DIS_LEVEL3'] : NULL,
            'driver'               =>  isset($row['DRIVER']) ? $row['DRIVER'] : NULL,
            'handicompendium'      =>  isset($row['HANDICOMPENDIUM']) ? $row['HANDICOMPENDIUM'] : NULL,
            'role'                 =>  isset($row['ROLE']) ? $row['ROLE'] : NULL,
            'role_status'          =>  isset($row['ROLE_STATUS']) ? $row['ROLE_STATUS'] : NULL,
            's2'                   =>  isset($row['S2']) ? $row['S2'] : NULL,
            's3'                   =>  isset($row['S3']) ? $row['S3'] : NULL,
            'sal_month_low'        =>  isset($row['SAL_MONTH_LOW']) ? $row['SAL_MONTH_LOW'] : NULL,
            'sal_month_high'       =>  isset($row['SAL_MONTH_HIGH']) ? $row['SAL_MONTH_HIGH'] : NULL,
            'worktime'             =>  isset($row['WORKTIME']) ? $row['WORKTIME'] : NULL,
            'startby'              =>  isset($row['STARTBY']) ? $row['STARTBY'] : NULL,
            'cert_all_descript'    =>  isset($row['CERT_ALL_DESCRIPT']) ? $row['CERT_ALL_DESCRIPT'] : NULL,
            'jobskill_all_desc'    =>  isset($row['JOBSKILL_ALL_DESC']) ? $row['JOBSKILL_ALL_DESC'] : NULL,
            'pcskill_all_desc'     =>  isset($row['PCSKILL_ALL_DESC']) ? $row['PCSKILL_ALL_DESC'] : NULL,
            'language1'            =>  isset($row['LANGUAGE1']) ? $row['LANGUAGE1'] : NULL,
            'language2'            =>  isset($row['LANGUAGE2']) ? $row['LANGUAGE2'] : NULL,
            'language3'            =>  isset($row['LANGUAGE3']) ? $row['LANGUAGE3'] : NULL,
            'lat'                  =>  isset($row['LAT']) ? $row['LAT'] : NULL,
            'lon'                  =>  isset($row['LON']) ? $row['LON'] : NULL,
            'major_cat_descript'   =>  isset($row['MAJOR_CAT_DESCRIPT']) ? $row['MAJOR_CAT_DESCRIPT'] : NULL,
            'minbinary_edu'        =>  isset($row['MINBINARY_EDU']) ? $row['MINBINARY_EDU'] : NULL,
            'need_emp'             =>  isset($row['NEED_EMP']) ? $row['NEED_EMP'] : NULL,
            'need_emp1'            =>  isset($row['NEED_EMP1']) ? $row['NEED_EMP1'] : NULL,
            'ondutytime'           =>  isset($row['ONDUTYTIME']) ? $row['ONDUTYTIME'] : NULL,
            'offduty_time'         =>  isset($row['OFFDUTY_TIME']) ? $row['OFFDUTY_TIME'] : NULL,
            'others'               =>  isset($row['OTHERS']) ? $row['OTHERS'] : NULL,
        	'source'  			   =>  '104'
        ];
    }

    /**
     * 轉換 104 API 來的 JOB DATA
     * @param  object $row api 資料
     * @return array      company 資料表資料
     */
    private function _convert_company_row_data($row)
    {
        return [
            'name'             => isset($row['NAME'])             ? $row['NAME'] : NULL,
            'c_code'           => isset($row['C'])                ? $row['C'] : NULL,
            'addr_no_descript' => isset($row['ADDR_NO_DESCRIPT']) ? $row['ADDR_NO_DESCRIPT'] : NULL,
            'address'          => isset($row['ADDRESS'])          ? $row['ADDRESS'] : NULL,
            'addr_indzone'     => isset($row['ADDR_INDZONE'])     ? $row['ADDR_INDZONE'] : NULL,
            'indcat'           => isset($row['INDCAT'])           ? $row['INDCAT'] : NULL,
            'link'             => isset($row['LINK'])             ? $row['LINK'] : NULL,
            'product'          => isset($row['PRODUCT'])          ? $row['PRODUCT'] : NULL,
            'profile'          => isset($row['PROFILE'])          ? $row['PROFILE'] : NULL,
            'welfare'          => isset($row['WELFARE'])          ? $row['WELFARE'] : NULL,
        ];
    }

    /**
     * 從來源更新資料庫
     */
    public function update($conditions = NULL)
    {
        // 設定更新時的查詢條件
        if ($conditions)
        {
            $this->_set_update_condition($conditions);
        }

        // 判斷是否為預灠模式
        $this->_preview_mode = $conditions['preview'];

        // 取得 api 網址，查詢資料
        $url = $this->_get_api_url();
        $json_data = Curl::get_json_data($url);

        Debug::fblog($json_data);

        // 取得額外資訊
        $api_condition_response = '';
        if ($this->_preview_mode)
        {
            $api_condition_response = Curl::get_response(str_replace('&fmt=8', '&fmt=9', $url))['data'];
        }

        // 取不到資料時
        if ( ! $json_data)
        {
            return ['資料取得錯誤'];
        }

        // 寫入資料
        if ( ! $this->_preview_mode)
        {
            foreach ($json_data['data'] as $row)
            {
                // 寫入 company 資料表
                if (!isset($row['C']))
                {
                	print_r($row);
                	echo "該筆垃圾 ";
                	continue;
                }

            	$company_data = $this->_convert_company_row_data($row);

                $companyID = Company::insert($company_data);

                // 寫入 job 資料表
                $job_data = $this->_convert_job_row_data($row);

                $job_data['companyID'] = $companyID;

                //var_dump($job_data);
                $jobID = Job::insert($job_data);
            }
        }

        // 回傳資料
        $record_count        = $json_data['RECORDCOUNT'];
        $page_size           = (isset($conditions['pgsz'])) ? $conditions['pgsz'] : 20;
        $page                = $json_data['PAGE'];
        $total_page          = $json_data['TOTALPAGE'];
        $finish_record_count = $page_size * $page;

        // 計算完成度
        if ($page == $total_page)
        {
            $finish_percent = '100.00';
            $finish_record_count = $record_count;
        }
        else
        {
            $finish_percent = number_format(($finish_record_count / $record_count) * 100, 2);
        }

        return [
            'source'              => self::class,
            'preview_mode'        => $this->_preview_mode,
            'api_url'             => $url,
            'condition'           => $api_condition_response,
            'record_count'        => $record_count,
            'finish_record_count' => $finish_record_count,
            'page_size'           => $page_size,
            'page'                => $page,
            'total_page'          => $total_page,
            'finish_percent'      => $finish_percent
        ];
    }

    public function info()
    {

    }

    /**
     * 搜尋
     */
    public function search($param = [])
    {
        return Job::search($param);
    }

    /**
     * 104 json 檔案測試
     * @return Response
     */
    public function show_category()
    {
        $file = JSON_DIR . '104/category.json';
        $content = file_get_contents($file);
        return "<pre>" .  print_r(json_decode($content), TRUE). "</pre>";
    }

    public function get_jobs($conditions)
    {
        // 設定更新時的查詢條件
        if ($conditions)
        {
            $this->_set_update_condition($conditions);
        }

        // 取得 api 網址，查詢資料
        $url = $this->_get_api_url();
        $json_data = Curl::get_json_data($url);

        $c_codes = array_column($json_data['data'], 'C');

        $companies = app()->make(Company::class)->whereIn('c_code', $c_codes)->get()->keyBy('c_code');
        $not_exist_companies = [];

        foreach ($json_data['data'] as $job) {
            if (empty($companies[$job['C']])) {
                $company_data = $this->_convert_company_row_data($job);
                Company::insert($company_data);
                $companies[$job['C']] = $company_data;
                $not_exist_companies[] = $job['C'];
            }
        }

        if (!empty($not_exist_companies)) {

            // 非同步取得company url fake id
            $url = "https://www.104.com.tw/";
//
            $url_id_client = new Client(['base_uri' => $url]);
            $company_client = new Client(['base_uri' => $url]);

            foreach ($not_exist_companies as $c_code) {
                $fake_id_promises[$c_code] = $url_id_client->getAsync("/jobbank/custjob/index.php?r=cust&j={$c_code}");
            }

            $url_id_results = Promise\settle($fake_id_promises)->wait();

            foreach ($url_id_results as $c_code => $result) {
                $response = $result['value']->getBody()->getContents();
                preg_match_all('/<meta property="og:url" content="https:\/\/www\.104\.com\.tw\/company\/(.*)">/', $response, $matches);

                if ( ! isset($matches[1][0])) {
                    // throw new Exception("找不到公司的網址。");
                    throw new \Exception();
                }

                $url_id = $matches[1][0];

                $promises_company[$c_code] = $company_client->getAsync("/company/ajax/content/{$url_id}");
            }

            $company_results = Promise\settle($promises_company)->wait();

            foreach ($company_results as $code => $result) {
                dd($result['value']->getBody()->getContents());
            }
        }

        return $json_data;
    }

}
