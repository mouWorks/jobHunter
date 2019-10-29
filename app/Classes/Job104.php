<?php
namespace App\Classes;

use App\Classes\JobBase;
use App\Domains\AWS\Sdk;
use App\Library\Lib;
use Aws\Credentials\Credentials;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Aws\S3\S3Client;
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
        define('JSON_DIR', __DIR__ . '/../../resources/json/');

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

    public function get_jobs($conditions)
    {
        // 設定更新時的查詢條件
        if ($conditions)
        {
            $this->_set_update_condition($conditions);
        }

        // 取得 api 網址，查詢資料
        $url = $this->_get_api_url();

        // 取得job資料
        $job_data = Curl::get_json_data($url);

        // job c_code 取得公司資料
        $c_codes = array_column($job_data['data'], 'C');
        $exist_company = $this->_get_companies($c_codes);
//        dd($exist_company);
//        $exist_company = app()->make(Company::class)->whereIn('c_code', $c_codes)->get()->keyBy('c_code')->toArray();
        $exist_company = [];

        $not_exist_company = [];
        foreach ($job_data['data'] as $job)
        {
            if (empty($exist_company[$job['C']])) {
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
//                $this->sdk->dynamoPutItem('companies', $not_exist_company[$c_code]);
            }
        }

        $company = array_merge($exist_company, $not_exist_company);

//        dd($company);

        $this->testCloudSearch($company);

        dd($company);

        foreach ($job_data as $index => $job) {
            $tmpJob = $this->_convert_job_row_data($job);
            $job_data[$index] = $job;
        }

        return $job_data;
    }

    public function _get_companies($c_codes)
    {
        $dynamodb = $this->sdk->getDynamoDB();

        $marshaler = new Marshaler();

        $items = [];

        $query = [];

        foreach ($c_codes as $index => $c_code) {
            $binding_key = ':c_code_' . $index;
            $items[$binding_key] = $c_code;
            $query[] = 'c_code = ' . $binding_key;
        }

//        $items = [":c_code_0" => "453b436c35373f6831333b64393f371a72a2a2a6c426d3f2674j53"];

        $eav = $marshaler->marshalItem($items);
        dd([$items, $query]);

        $params = [
            'TableName' => 'companies',
//            'ProjectionExpression' => '#yr, title, info.genres, info.actors[0]',
            'KeyConditionExpression' => implode(' and ', $query),
//            'KeyConditionExpression' => 'c_code = :c_code_0',
            'ExpressionAttributeValues'=> $eav
        ];

        echo "Querying for movies from 1992 - titles A-L, with genres and lead actor\n";

        $result = $dynamodb->query($params);

        dd($result);

    }

    public function testCloudSearch($company)
    {
        $cloudSearch = $this->sdk->createCloudSearchDomain([
            'endpoint' => env('AWS_CLOUDSEARCH_END_POINT')
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

    public function testAWS()
    {
//        $credentials = new Credentials('AKIAWDFVARRBQLHPK6GP', 'urwMvY4dg4tx74cCwiZS5qmpye2F+QMx3hg917DT');
//
//        $sdk = new Sdk([
//            'region'   => 'us-west-2',
//            'version'  => 'latest',
//            'credentials' => $credentials
//        ]);

        /** @var Sdk $sdk */
        $sdk = app()->make(Sdk::class);

        $dynamodb = $sdk->getDynamoDB();
        $marshaler = new Marshaler();

        $tableName = 'Movies';

        // ##################### search data #####################
        $eav = $marshaler->marshalItem([
            ":yyyy" => 2018,
            ":title" => "就是"
        ]);

//        $params = array(
//            "TableName" => $tableName,
//            "KeyConditions" => array(
//                "ComparisonOperator" => 'CONTAINS',
//                'title' => array(
//                    'AttributeValueList' => array(
//                        array(Type::STRING_SET => array("Red"))
//                    ),
//                )
//            )
//        );

        $params = [
            'TableName' => $tableName,
            "KeyConditions" => [
                'title' => [
                    "ComparisonOperator" => 'CONTAINS',
                    'AttributeValueList' => [
                        ['S' => "zzzzzz"]
                    ],
                ],
            ]
        ];

        $search = array(
            'TableName' => 'companies',
//            'Select' => 'COUNT',
            'KeyConditions' => array(
                'c_code' => array(
                    'ComparisonOperator' => 'EQ',
                    'AttributeValueList' => array(
                        array('S' => '394b436c35373f6831333b64393f371a72a2a2a2a41373f2674j57', 'S' => '3f5149723b3d456e3739416a3f453d208303030754773517119j02')
                    )
                )
            )
        );
        $response = $dynamodb->query($search);

        dd($response);

        $eav = $marshaler->marshalJson('
            {
                ":start_yr": 1950,
                ":end_yr": 2018
            }
        ');

        $params = [
            'TableName' => 'Movies',
            'ProjectionExpression' => '#yr, title',
            'FilterExpression' => '#yr between :start_yr and :end_yr',
            'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
            'ExpressionAttributeValues'=> $eav,
        ];

        echo "Querying for movies from 1992 - titles A-L, with genres and lead actor\n";

        try {
            $result = $dynamodb->scan($params);

            dd($result);

            echo "Query succeeded.\n";

//            foreach ($result['Items'] as $i) {
//                $movie = $marshaler->unmarshalItem($i);
//                print $movie['year'] . ': ' . $movie['title'] . ' ... ';
//
//                foreach ($movie['info']['genres'] as $gen) {
//                    print $gen . ' ';
//                }
//
//                echo ' ... ' . $movie['info']['actors'][0] . "\n";
//            }

        } catch (DynamoDbException $e) {
            echo "Unable to query:\n";
            echo $e->getMessage() . "\n";
        }

        // ##################### get data #####################
//        $key = $marshaler->marshalItem([
//            'year' => 2018,
//            'title' => '就是title',
//        ]);
//
//        $params = [
//            'TableName' => $tableName,
//            'Key' => $key
//        ];
//
//        try {
//            $result = $dynamodb->getItem($params);
//            print_r($result["Item"]);
//
//        } catch (DynamoDbException $e) {
//            echo "Unable to get item:\n";
//            echo $e->getMessage() . "\n";
//        }

        // ##################### insert data #####################
//        $params = [
//            'TableName' => 'Movies',
//            'Item' => $marshaler->marshalItem([
//                'year' => 2017,
//                'title' => '就是title3',
//            ])
//        ];
//
//        try {
//            $result = $dynamodb->putItem($params);
//            echo "Added movie success";
//        } catch (DynamoDbException $e) {
//            echo "Unable to add movie:\n";
//            echo $e->getMessage() . "\n";
//            return;
//        }


        // ##################### create table #####################
//        $params = [
//            'TableName' => 'Movies',
//            'KeySchema' => [
//                [
//                    'AttributeName' => 'year',
//                    'KeyType' => 'HASH'  //Partition key
//                ],
//                [
//                    'AttributeName' => 'title',
//                    'KeyType' => 'RANGE'  //Sort key
//                ]
//            ],
//            'AttributeDefinitions' => [
//                [
//                    'AttributeName' => 'year',
//                    'AttributeType' => 'N'
//                ],
//                [
//                    'AttributeName' => 'title',
//                    'AttributeType' => 'S'
//                ],
//
//            ],
//            'ProvisionedThroughput' => [
//                'ReadCapacityUnits' => 10,
//                'WriteCapacityUnits' => 10
//            ]
//        ];
//
//        try {
//            $result = $dynamodb->createTable($params);
//            echo 'Created table.  Status: ' .
//                $result['TableDescription']['TableStatus'] ."\n";
//
//        } catch (DynamoDbException $e) {
//            echo "Unable to create table:\n";
//            echo $e->getMessage() . "\n";
//        }

        dd(' ============ ');

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
        }

        return $company_info;
    }

}
