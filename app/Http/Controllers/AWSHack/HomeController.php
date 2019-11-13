<?php

namespace App\Http\Controllers\AWSHack;

use App\Classes\Job104;
use App\Classes\JobBase;
use App\Classes\JobPtt;
use App\Domains\AWS\Sdk;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /** @var Job104 */
    private $job104;

    /** @var Sdk */
    private $sdk;

    /** @var JobPtt */
    private $jobPtt;

    public function __construct(Job104 $job104, JobPtt $jobPtt, Sdk $sdk)
    {
        $this->job104 = $job104;
        $this->jobPtt = $jobPtt;
        $this->sdk = $sdk;
    }

    public function index()
    {
        $conditions = ['page' => 1, 'kws' => '工程師'];

        $jobs['104'] = $this->job104->get_jobs($conditions);

//        $jobs['ptt'] = $this->jobPtt->get_jobs($conditions);

        $jobs['parttime'] = $this->sdk->cloudSearchDoSearch(
            ['job_title'],
            $conditions['kws'],
            20,
            $conditions['page'],
            ['source' => 'parttime']
        );

        return view('AwsHack/home', [
            'jobs' => $jobs
        ]);
    }
}
