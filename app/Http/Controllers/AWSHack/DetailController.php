<?php

namespace App\Http\Controllers\AWSHack;

use App\Classes\JobBase;
use App\Domains\AWS\Sdk;
use App\Domains\Service\JobService;
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

    public function __construct(Sdk $sdk)
    {
        $this->sdk = $sdk;
    }

    public function index()
    {
        return view('AwsHack/Detail/job104');
    }

    public function ptt()
    {
        $data = $this->sdk->dynamoGetItem('PttJobs', ['id' => 'M.1522660357.A.BAA']);

        dd($data);

        return view('AwsHack/Detail/jobptt');
    }

    public function pt()
    {
        return view('AwsHack/Detail/jobpt');
    }
}