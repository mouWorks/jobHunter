<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Classes\Job;
use App\Classes\Job104;
use App\Classes\JobPtt;
use App\Library\Debug;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;

class UpdateController extends Controller
{
	/**
	 * 建立 job 實體
	 * @param  string $source 來源
	 * @return object         job 實體
	 */
	private function _create_job($source = '104')
	{
		switch ($source)
		{
			case 'ptt':
				$job = new JobPtt();
				break;

			case '104':
			default:
				$job = new Job104();
				break;
		}
		return $job;
	}

	/**
	 * 取得 get 查詢欄位
	 *
	 * @param  array $search_field 可查詢欄位
	 * @return array               查詢參數
	 */
	private function _get_param(Request $request, $search_field = [])
	{
		$search_param = [];
		foreach ($search_field as $field)
		{
			$value = $request->input($field);
			if ($value)
			{
				$search_param[$field] = $value;
			}
		}
		return $search_param;
	}

	/**
	 * 顯示工作列表
	 *
	 * @return Response
	 */
	public function index(Request $request, $source = '104')
	{
		// 取得 job 實體
		$job = $this->_create_job($source);

		// 取得可查詢欄位
		$search_field = $job->get_allow_search_field();

		// 取得查詢參數
		$search_param = $this->_get_param($request, $search_field);

		// 取得查詢資料
		$data = $job->search($search_param);

		// 畫面輸出
		$view_data = [
			'url'          => '/list/' . $source,
			'data'         => $data,
			'search_field' => $search_field,
			'search_param' => $search_param
		];
		return view('joblist', $view_data);
	}

	/**
	 * 從來源更新工作資料庫
	 * @param  string $source 來源類別
	 * @return Response
	 */
	public function update(Request $request, $source = '104')
	{
		// 取得 job 實體
		$job = $this->_create_job($source);

        // 查詢條件預設值
        $conditions = [
            'cat'  => ['2007001006', '2007001004', '2007001008', '2007001012'],
            'area' => ['6001001000', '6001002000'],
            'role' => [1, 4],
            'exp'  => 7,
            'kws'  => 'php python',
            'kwop' => 3,
        ];

        // 從 json 取得查詢條件
        $json_file = "../resources/json/condition.json";
        $condition_file = '';
        if (file_exists($json_file))
        {
        	$json = file_get_contents($json_file);
            $data = json_decode($json, TRUE);

            if (!$data){
                exit("JSON 格式壞了！請檢查一下");
            }

            $conditions = $data;
            $condition_file = $json_file;
        }

        // 取得分頁
        $conditions['page'] = $request->input('page', NULL);

		// 是否為預灠
		$conditions['preview'] = $request->input('preview', NULL);

		// 更新資料庫
		$job_data = $job->update($conditions);
		Debug::fblog('$job_data', $job_data);

        // 將查詢條件塞到 view 顯示
        if ( ! isset($job_data['condition']) || ! $job_data['condition'])
        {
            $job_data['condition'] = json_encode($conditions);
            $job_data['condition_file'] = $condition_file;
        }

        // 判斷更新是否要自動跳轉下一頁
        $job_data['go_next_page_js'] = '';
        if ( ! $conditions['preview'] && $job_data['page'] != $job_data['total_page'])
        {
            $next_url = "/update/{$source}?page=" . ($job_data['page'] + 1);
            $job_data['go_next_page_js'] = "<script>window.location.href = '{$next_url}';</script>";
        }

        // 清除過期工作
        if (! $conditions['preview'] && $job_data['page'] == $job_data['total_page']) {
            $this->clear_expired_job();
        }

		return view('update_report', $job_data);
	}

    public function clear_expired_job()
    {
        $today = date("Y-m-d 00:00:00");
        DB::delete("delete from job WHERE updated_at < '$today'");
        echo "刪除過期工作記錄!!!";
	}
}
