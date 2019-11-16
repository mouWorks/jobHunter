<?php
namespace App\Classes;

use App\Domains\AWS\Sdk;
use App\Library\Lib;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
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

    /** @var Sdk */
    private $sdk;

    public function __construct()
    {
//        define('JSON_DIR', __DIR__ . '/../../resources/json/');

        $this->sdk = app()->make(Sdk::class);
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

    /**
     * 取得工作 & 公司資訊
     * @param $conditions
     * @return array
     */
    public function get_jobs($conditions)
    {
        // 設定查詢條件
        $this->_set_update_condition($conditions);

        // 取得 api 網址，查詢資料
        $url = $this->_get_api_url();

        // 取得job資料
        $job_data = Curl::get_json_data($url);

        if (is_null($job_data)) {
            return [];
        }

        // c_code 從dynamoDB取得公司資料
        $c_codes = array_column($job_data['data'], 'C');
        $exist_company = $this->_get_companies($c_codes);
        $not_exist_company = [];

        foreach ($job_data['data'] as $job)
        {
            // 取得不存在dynamodb的公司
            if (!empty($job['C']) && empty($exist_company[$job['C']])) {
                $not_exist_company[$job['C']] = $this->_convert_company_row_data($job);
            }
        }

        if (!empty($not_exist_company)) {
            $c_codes = array_column($not_exist_company, 'c_code');
            // 非同步取得company url id
            $url_ids = $this->_get_url_ids($c_codes);

            $company_info = $this->_get_company_info($url_ids);

            // combine公司資訊並寫入dynamoDB
            foreach ($not_exist_company as $c_code => $company) {
                if (empty($company_info[$c_code])) {
                    continue;
                }
                $not_exist_company[$c_code]['employees'] = intval($company_info[$c_code]['empNo']);
                $not_exist_company[$c_code]['capital'] = Lib::capital2number($company_info[$c_code]['capital']);
                $not_exist_company[$c_code]['url'] = $url_ids[$c_code];
                $not_exist_company[$c_code]['img'] = $company_info[$c_code]['img'];
                $this->sdk->dynamoPutItem('companies', $not_exist_company[$c_code]);
            }
        }

        $companies = array_merge($exist_company, $not_exist_company);

        $response_job = [];

        // combine jobs & companies
        foreach ($job_data['data'] as $index => $job) {
            $tmpJob = $this->_convert_job_row_data($job);
            $response_job[$index] = $tmpJob;
            if (!empty($job['C'])) {
                $response_job[$index]['company'] = $companies[$job['C']] ?? null;
            } else {
                $response_job[$index]['company'] = null;
            }
        }

        $pagination = [
            'record_count' => $job_data['RECORDCOUNT'],
            'page_count' => $job_data['PAGECOUNT'],
            'page' => $job_data['PAGE'],
            'total_page' => $job_data['TOTALPAGE'],
        ];

        return [$pagination, $response_job];
    }

    public function _get_companies(array $c_codes): array
    {
        if (empty($c_codes)) {
            return [];
        }
        $c_codes = array_unique($c_codes);
        $companies = $this->sdk->dynamoBatchGetItem('companies', 'c_code', 'S', $c_codes);
        $companies = collect($companies)->keyBy('c_code');
        return $companies->toArray();
    }

    public function testCloudSearch($company)
    {
        $cloudSearch = $this->sdk->createCloudSearchDomain([
            'endpoint' => env('AWS_CLOUDSEARCH_ENDPOINT')
        ]);

        $documents = [];

        $index = 10;

        foreach ($company as $com) {
            $documents[] = [
                'type' => 'add',
                'id' => $com['c_code'],
                'fields' => [
                    'c_code' => $com['c_code'],
                    'capital' => $com['capital'],
                    'employees' => $com['employees'],
                    'name' => $com['name'],
                    'url' => $com['url'],
                    'indcat' => $com['indcat'],
                ],
            ];
        }

        try {
            $cloudSearch->uploadDocuments([
                'contentType' => 'application/json',
                'documents' => json_encode($documents),
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        dd('success');
    }

    /**
     * 使用c_code取得url_id
     * @param array $c_codes
     * @return array
     */
    private function _get_url_ids(array $c_codes): array
    {
        $url = "https://www.104.com.tw/";

        $url_id_client = new Client(['base_uri' => $url]);

        foreach ($c_codes as $c_code)
        {
            $promises[$c_code] = $url_id_client->getAsync("/jobbank/custjob/index.php?r=cust&j={$c_code}");
        }

        $results = Promise\settle($promises)->wait();

        $url_ids = [];

        foreach ($results as $c_code => $result) {
            $response = $result['value']->getBody()->getContents();

            preg_match_all('/<meta property="og:url" content="https:\/\/www\.104\.com\.tw\/company\/(.*)">/', $response, $matches);

            if ( ! isset($matches[1][0])) {
                continue;
            }

            $url_ids[$c_code] = $matches[1][0];
        }

        return $url_ids;
    }

    private function _get_company_info(array $url_ids)
    {
        $url = "https://www.104.com.tw/";

        $client = new Client(['base_uri' => $url]);

        foreach ($url_ids as $c_code => $url_id)
        {
            $promises[$c_code] = $client->getAsync("/company/ajax/content/{$url_id}");
        }

        $results = Promise\settle($promises)->wait();

        $company_info = [];
        foreach ($results as $code => $result) {
            if (empty($result['value'])) {
                continue;
            }
            $tmp_company = json_decode($result['value']->getBody()->getContents(), true);
            $company_info[$code]['empNo'] = $tmp_company['data']['empNo'];
            $company_info[$code]['capital'] = $tmp_company['data']['capital'];
            $company_info[$code]['img'] = $tmp_company['data']['corpImage1'];
            if (!empty($tmp_company['data']['corpImage1'])) {
                $company_info[$code]['img'] = $tmp_company['data']['corpImage1'];
            } elseif (!empty($tmp_company['data']['corpImage2'])) {
                $company_info[$code]['img'] = $tmp_company['data']['corpImage2'];
            } elseif (!empty($tmp_company['data']['corpImage3'])) {
                $company_info[$code]['img'] = $tmp_company['data']['corpImage3'];
            }
        }

        return $company_info;
    }

}
