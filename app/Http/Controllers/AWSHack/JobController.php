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

    /**
     * LineBot Result for 104
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function get_104_for_line_bot(Request $request)
    {
        if (empty($request->get('q'))) {
            throw new \Exception('搜尋條件不得為空');
        }

        $job = $this->_create_job(104);

        $conditions = [
            'keyword'  => $request->get('q'),
            'pgsz' => 10,
        ];

        $response = $job->get_jobs($conditions);

        $response = collect($response[1])->map(function($job){

            return [
                'jobTitle' => $job['title'],
                'region' => $job['region'],
                'company_name' => $job['company_name'],
                'min_salary' => (int) ($job['sal_month_low'] ?? 0),
                'max_salary' => (int) ($job['sal_month_high'] ?? 0),
                'url' => 'https://jobhuntr.work/awshack/job/104/' . $job['j_code'],
            ];
        });

        return new JsonResponse($response);
    }
}
