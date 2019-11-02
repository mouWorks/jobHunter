<?php

namespace App\Domains\AWS;

use Aws\CloudSearchDomain\CloudSearchDomainClient;
use Aws\Credentials\Credentials;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

class Sdk extends \Aws\Sdk
{
    private $_credentials;

    private $_dynamoDb;

    private $_cloud_search;

    public function __construct(array $args = [])
    {
        $access_key = env('AWS_ACCESS_KEY');

        $secret = env('AWS_ACCESS_SECRET');

        $region = env('AWS_REGION');

        $this->_credentials = new Credentials($access_key, $secret);

        $args = array_merge($args, [
            'region'   => $region,
            'credentials' => $this->_credentials,
            'version'  => 'latest',
        ]);

        parent::__construct($args);
    }

    /**
     * dynamoDB put item
     * @param $tableName
     * @param $data
     */
    public function dynamoPutItem(string $tableName, array $data): void
    {
        $data = array_filter($data);

        $marshaler = new Marshaler();

        $item = $marshaler->marshalItem($data);
        $params = [
            'TableName' => $tableName,
            'Item' => $item
        ];

        $this->getDynamoDB()->putItem($params);
    }

    public function dynamoGetItem(string $tableName, array $keys): array
    {
        $keys = array_filter($keys);

        $marshaler = new Marshaler();

        $item = $marshaler->marshalItem($keys);
        $params = [
            'TableName' => $tableName,
            'Key' => $item
        ];

        $this->getDynamoDB()->getItem($params);
    }

    public function dynamoBatchGetItem(string $table, string $key, string $type, array $values): array
    {
        $keys = [];

        foreach ($values as $value) {
            $keys[] = [
                $key => [
                    $type => $value
                ]
            ];
        }

        $result = $this->getDynamoDB()->batchGetItem([
            'RequestItems' => [
                $table => [
                    'Keys' => $keys
                ]
            ]
        ]);

        $response = $result['Responses'][$table];

        $data = [];

        foreach ($response as $document) {
            $tmpDocument = [];
            foreach ($document as $key => $value) {
                $tmpDocument[$key] = end($value);
            }
            $data[] = $tmpDocument;
        }

        return $data;
    }

    public function getDynamoDB(): DynamoDbClient
    {
        if (is_null($this->_dynamoDb)) {
            $this->_dynamoDb = $this->createDynamoDb();
        }

        return $this->_dynamoDb;
    }

    public function getCloudSearch(): CloudSearchDomainClient
    {
        if (is_null($this->_cloud_search)) {
            $this->_cloud_search = $this->createCloudSearchDomain([
                'endpoint' => env('AWS_CLOUDSEARCH_ENDPOINT')
            ]);
        }

        return $this->_cloud_search;
    }

    private function _parse_ptt(array $job)
    {
        // 地區,薪資min,薪資max,工作職稱,公司名稱,公司圖片,工作描述,url,source,time
        $description = empty($job['description']) ? '' : strip_tags(mb_substr($job['description'], 0, 300));
        return [
            'id' => $job['id'],
            'region' => $job['region'] ?? '',
            'max_salary' => $job['max_salary'] ?? '',
            'min_salary' => $job['min_salary'] ?? '',
            'job_title' => $job['title'],
            'company_name' => $job['company_name'] ?? '',
            'company_img' => '',
            'description' => $description,
            'url' => $job['source_url'] ?? '',
            'source' => 'ptt',
            'time' => '',
        ];
    }

    private function _get_time()
    {
        list($usec, $sec) = explode(" ", microtime());
        return floor($sec . ($usec * 1000));
    }

    public function cloudSearchDoSearch(
        array $options,
        string $keyWord = '',
        int $perPage = 20,
        int $page = 1,
        string $sort = 'create_time desc',
        array $equalParams = []
    )
    {
        $offset = ($page-1) * $perPage;


        $params = [
            'size' => $perPage,
            'query' => $keyWord, // 關鍵字
            'queryParser' => 'dismax', // http://docs.aws.amazon.com/cloudsearch/latest/developerguide/searching.html
            'queryOptions' => json_encode(['fields' => $options]),
            'sort' => $sort, // 排序
            'start' => $offset // 分頁
        ];

        if (!empty($equalParams)) {
            $filterQuery = '(and ';

            foreach ($equalParams as $fieldName => $value) {
                $filterQuery .= "(term field=$fieldName $value) ";
            }

            $filterQuery .= ')';

            $params['filterQuery'] = $filterQuery;
        }

        $data = $this->getCloudSearch()->search($params);

        if ($data['hits']['found'] === 0) {
            return [];
        }

        // convert data format
        $response = [];
        foreach ($data['hits']['hit'] as $document) {
            $tmpData = [];
            foreach ($document['fields'] as $field => $value) {
                $tmpData[$field] = end($value);
            }
            $response[] = $tmpData;
        }

        return $response;
    }

    public function cloudSearchPutJob(array $job_data, string $source): void
    {
        $allow_resource = ['104', 'line', 'ptt'];

        if (!in_array($source, $allow_resource)) {
            return;
        }
        $time = $this->_get_time();

        foreach ($job_data as $index => $job) {
            $job = $this->{'_parse_' . $source}($job);
            $job['create_time'] = $time++;
            $documents = [
                'type' => 'add',
                'id' => $job['id'],
                'fields' => $job,
            ];
            $jobs[] = $job;

            try {
                var_dump($index);
                $this->getCloudSearch()->uploadDocuments([
                    'contentType' => 'application/json',
                    'documents' => json_encode([$documents]),
                ]);
            } catch (\Exception $e) {
                var_dump($e->getMessage());
            }
        }
    }
}
