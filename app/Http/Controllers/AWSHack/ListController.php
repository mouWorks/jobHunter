<?php

namespace App\Http\Controllers\AWSHack;

use App\Classes\JobBase;
use App\Domains\Service\JobService;
use App\Domains\ViewModule\ViewModule;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ListController extends Controller
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

    public function index(Request $request)
    {
        $location = $request->get('selectLocation') ?? '';
        $q = $request->get('q');

        $conditions = ['page' => 1, 'kws' => $q ?? '工程師', 'area' => $location];

        return view('AwsHack/List/list104', [
            'jobs' => $this->jobService->get104Job($conditions),
            'location_select_box' => $this->viewModule->getLocationSelectBox($location),
            'q' => $q
        ]);
    }

    public function ptt(Request $request)
    {
        $location = $request->get('selectLocation') ?? '';

        $q = $request->get('q');

        $conditions = [
            'kws' => $q,
            'page' => 1,
        ];

        if (!empty($location)) {
            $conditions['location'] = ViewModule::LOCATIONS[$location] ?? null;
        }

        return view('AwsHack/List/listptt', [
            'jobs' => $this->jobService->getPttJob($conditions),
            'location_select_box' => $this->viewModule->getLocationSelectBox($location),
            'q' => $q,
        ]);
    }

    public function pt(Request $request)
    {
        $location = $request->get('selectLocation') ?? '';
        $q = $request->get('q') ?? '';

        return view('AwsHack/List/listpt', [
            'location_select_box' => $this->viewModule->getLocationSelectBox($location),
            'q' => $q
        ]);
    }
}