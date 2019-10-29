<?php

namespace App\Http\Controllers\AWSHack;

use App\Classes\JobBase;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JobController extends Controller
{
    use DispatchesJobs, ValidatesRequests;

	private function _create_job(string $source = '104'): JobBase
	{
	    $class_name = 'App\\Classes\\Job' . ucfirst($source);
	    return new $class_name;
	}

	public function get_job(Request $request, string $source = '104'): JsonResponse
    {
        $job = $this->_create_job($source);

        $conditions = [
            'kws'  => $request->get('q'),
            'page'  => $request->get('page') ?? 1,
        ];

        $response = $job->get_jobs($conditions);

        return new JsonResponse($response);
    }
}
