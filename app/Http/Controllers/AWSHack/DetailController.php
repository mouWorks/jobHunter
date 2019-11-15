<?php

namespace App\Http\Controllers\AWSHack;

use App\Classes\JobBase;
use App\Domains\AWS\Sdk;
use App\Domains\Service\JobService;
use App\Domains\Service\ParserService;
use App\Domains\ViewModule\ViewModule;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    /** @var Sdk*/
    private $sdk;

    /** @var ParserService */
    private $parserService;

    public function __construct(Sdk $sdk, ParserService $parserService)
    {
        $this->sdk = $sdk;
        $this->parserService = $parserService;
    }

    public function index()
    {
        return view('AwsHack/Detail/job104');
    }

    public function ptt(string $id)
    {
        $params = ['id' => $id];
        $job = $this->sdk->dynamoGetItem('PttJobs', $params);
        $job['salary'] = $this->parserService->getSalaryDesc($job['min_salary'] ?? 0, $job['max_salary'] ?? 0);

        return view('AwsHack/Detail/jobptt', [
            'job' => $job,
        ]);
    }

    public function pt($id)
    {
        $params = ['pt_code' => $id];
        $job = $this->sdk->dynamoGetItem('PartTimeJobs', $params);

        return view('AwsHack/Detail/jobpt', [
            'job' => $job,
        ]);
    }
}