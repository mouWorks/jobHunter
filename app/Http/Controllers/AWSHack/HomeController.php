<?php

namespace App\Http\Controllers\AWSHack;

use App\Domains\Service\JobService;
use App\Domains\ViewModule\ViewModule;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /** @var JobService */
    private $jobService;

    /** @var ViewModule */
    private $viewModule;

    public function __construct(JobService $jobService, ViewModule $viewModule)
    {
        $this->jobService = $jobService;
        $this->viewModule = $viewModule;
    }

    public function index()
    {
        $conditions = ['page' => 1, 'kws' => '工程師', 'area' => '6001001000', 'pgsz' => 6];

        [$paginatin, $jobs['104']] = $this->jobService->get104Job($conditions);

        [$paginatin, $jobs['ptt']] = $this->jobService->getPttJob($conditions);

        [$paginatin, $jobs['part_time']] = $this->jobService->getPartTimeJob(['page' => 1, 'pgsz' => 6]);

        return view('AwsHack/home', [
            'jobs' => $jobs,
            'location_select_box' => $this->viewModule->getLocationSelectBox()
        ]);
    }

    public function pttSync(){

        echo 'PttSync';

        return $this->jobService->pttSync();
    }

}