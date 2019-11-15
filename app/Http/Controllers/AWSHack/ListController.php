<?php

namespace App\Http\Controllers\AWSHack;

use App\Classes\JobBase;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function index()
    {
        return view('AwsHack/List/list104');
    }

    public function ptt()
    {
        return view('AwsHack/List/listptt');
    }

    public function pt()
    {
        return view('AwsHack/List/listpt');
    }
}