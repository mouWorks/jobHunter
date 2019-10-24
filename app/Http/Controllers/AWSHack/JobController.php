<?php

namespace App\Http\Controllers\AWSHack;

use App\Classes\JobBase;
use App\Models\Company;
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
            'cat'  => ['2007001006', '2007001004', '2007001008', '2007001012'],
            'area' => ['6001001000', '6001002000'],
            'role' => [1, 4],
            'exp'  => 7,
            'kws'  => 'php python',
            'kwop' => 3,
        ];

        $response = $job->get_jobs($conditions);

//        $company_data = $this->_convert_company_row_data($row);
//
//        $companyID = Company::insert($company_data);

        // todo combine company data



        return new JsonResponse($response);
    }
}
