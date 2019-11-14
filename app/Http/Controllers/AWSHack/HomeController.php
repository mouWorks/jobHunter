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

        $tmpJobs = array_slice($this->job104->get_jobs($conditions), 0, 8);

        $tmpJobs = collect($tmpJobs)->map(function ($job) {
            $city = mb_substr($job['job_addr_no_descript'], 0, 3);
            $area = mb_substr($job['job_addr_no_descript'], 3, 3);
            $job['job_addr_no_descript'] = $city . '</br>' . $area;
            $job['description'] = nl2br(mb_substr($job['description'], 0, 200));
            if (!empty($job['sal_month_low']) && !empty($job['sal_month_high'])) {
                $job['sal_month_low'] = str_replace('000', '', (int) $job['sal_month_low']) . 'K';
                $job['sal_month_high'] = str_replace('000', '', (int) $job['sal_month_high']) . 'K';
                $job['salary'] = $job['sal_month_low'] . '~' . $job['sal_month_high'];
            } else {
                $job['salary'] = '暫不提供';
            }

            return $job;
        });

        $jobs['104'] = $tmpJobs;

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
