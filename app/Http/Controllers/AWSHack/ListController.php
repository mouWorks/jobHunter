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
        $page = $request->get('page') ?? 1;

        $conditions = ['page' => $page, 'kws' => $q ?? '工程師', 'area' => $location];

        [$pagination, $jobs] = $this->jobService->get104Job($conditions);

        if (!empty($jobs)) {
            $paginatinView = $this->viewModule->pagination($pagination['total_page'], $page,'/awshack/list/104?', $_GET);
        } else {
            $paginatinView = '';
        }

        return view('AwsHack/List/list104', [
            'jobs' => $jobs,
            'location_select_box' => $this->viewModule->getLocationSelectBox($location),
            'pagination_view' => $paginatinView,
            'q' => $q
        ]);
    }

    public function ptt(Request $request)
    {
        $location = $request->get('selectLocation') ?? '';

        $q = $request->get('q');

        $page = $request->get('page') ?? 1;

        $conditions = [
            'kws' => $q,
            'page' => $page,
        ];

        if (!empty($location)) {
            $conditions['location'] = ViewModule::LOCATIONS[$location] ?? null;
        }

        [$pagination, $jobs] = $this->jobService->getPttJob($conditions);

        $paginatinView = $this->viewModule->pagination($pagination['total_page'], $page,'/awshack/list/ptt?', $_GET);

        return view('AwsHack/List/listptt', [
            'jobs' => $jobs,
            'location_select_box' => $this->viewModule->getLocationSelectBox($location),
            'pagination_view' => $paginatinView,
            'q' => $q,
        ]);
    }

    public function pt(Request $request)
    {
        $location = $request->get('selectLocation') ?? '';

        $q = $request->get('q');

        $page = $request->get('page') ?? 1;

        $conditions = [
            'kws' => $q,
            'page' => $page,
        ];

        if (!empty($location)) {
            $conditions['location'] = ViewModule::LOCATIONS[$location] ?? null;
        }

        [$pagination, $jobs] = $this->jobService->getPartTimeJob($conditions);

        $paginatinView = $this->viewModule->pagination($pagination['total_page'], $page,'/awshack/list/pt?', $_GET);

        return view('AwsHack/List/listpt', [
            'jobs' => $jobs,
            'location_select_box' => $this->viewModule->getLocationSelectBox($location),
            'pagination_view' => $paginatinView,
            'q' => $q
        ]);
    }
}