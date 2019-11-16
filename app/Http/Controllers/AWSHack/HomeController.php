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
        $conditions = ['page' => 1, 'kws' => '工程師', 'area' => '6001001000'];

        [$paginatin, $jobs['104']] = array_slice($this->jobService->get104Job($conditions), 0, 8);

        [$paginatin, $jobs['ptt']] = array_slice($this->jobService->getPttJob($conditions), 0, 8);

        [$paginatin, $jobs['part_time']] = array_slice($this->jobService->getPartTimeJob(['page' => 1]), 0, 8);

        return view('AwsHack/home', [
            'jobs' => $jobs,
            'location_select_box' => $this->viewModule->getLocationSelectBox()
        ]);
    }
}