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

    public function searchPutDocument(array $documents): void
    {
        try {
            $this->getCloudSearch()->uploadDocuments([
                'contentType' => 'application/json',
                'documents' => json_encode($documents),
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
