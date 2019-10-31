<?php
namespace App\Classes;

use App\Classes\JobBase;
use App\Domains\AWS\Sdk;
use App\Library\Curl;
use App\Models\Job;
use App\Models\Company;

/**
* Job PTT
*/
class JobPtt extends JobBase
{
    /**
     * 允許搜尋的欄位
     *
     * @var array
     */
    protected $_allow_search_field = [
        'keyword',
    ];

    /**
     * 104 API 網址
     * @var string
     */
    private $_ptt_url ='https://www.ptt.cc/bbs/Soft_Job/';
    private $_serch_url ='https://www.ptt.cc/bbs/Soft_Job/search?q=[徵才]';

    private $_ptt_article_ids = [];

    private $_content = "";

    public $_total_page = 0;

    private $_limit = 50;

    /** @var Sdk */
    private $sdk;

    public function __construct()
    {
        $this->sdk = app()->make(Sdk::class);
    }

    public function update_aws()
    {
        // 取得第一筆CloudSearch ptt id
        $first_job_id = $this->_get_first_job_id();

        $page = 1;

        $all_ids = [];

        $stop_scan = false;

        $max_limit = 1;

        while(!$stop_scan)
        {
            $tmp_ids = $this->get_ids_by_page($page);

            if (!empty($first_job_id)) {
                if (in_array($first_job_id, $tmp_ids)) {
                    $index = array_search($first_job_id, $tmp_ids);
                    $stop_scan = true;
                    if ($index === 0) {
                        return;
                    }
                    $tmp_ids = array_slice($tmp_ids, 0, $index);
                }
            }

            $all_ids = array_merge($all_ids, $tmp_ids);

            $page++;

            if ($page > $max_limit) {
                $stop_scan = true;
            }
        }

        // 取得ids job
        $job_data = $this->get_job_data($all_ids);

        // 寫進dynamodb & CloudSearch
        foreach ($job_data as $job) {
            $this->sdk->dynamoPutItem('PttJobs', $job);
        }

        $this->sdk->CloudSearchPutJob($job_data, 'ptt');
    }

    private function get_ids_by_page(int $page = 1)
    {
        $url = $this->_serch_url . '&page=' . $page;

        $result = Curl::get_response($url);

        if (!$result['status'])
        {
            exit("今天不順，抓不到資料");
        }

        $this->_content = $result['data'];

        $patten = '/.*\<a\ href="\/bbs\/Soft_Job\/(.*).html\"\>\[徵才\]\ (.*)\<\/a\>.*/';

        if (!preg_match_all($patten, $this->_content, $match))
        {
            return [];
        }

        return $match[1];
    }

    /**
     * 從來源更新資料庫
     */
    public function update($condition = [])
    {
    	//先抓第一頁
    	if ($this->crawler_first_page())
    	{
    		//有頁數後開始往後抓
    		$this->crawler_prev_job();

    		//開始抓單頁的資料
    		$this->crawler_job_page();
    	}

        //return view('update_report', ['source' => self::class]);
    }

    /**
     * 先抓第一頁中的所有有符合的 url, 及頁數
     *
     * @param unknown $param
     */
    protected function crawler_first_page()
    {
        $result = Curl::get_response($this->_serch_url);

    	if (!$result['status'])
    	{
    		exit("今天不順，抓不到資料");
    	}

    	$this->_content = $result['data'];

    	$this->_total_page = $this->_find_list_page_btn($this->_content);

    	$this->_find_list_title($this->_content);

    	return TRUE;
    }

    /**
     * 先抓頁面中的所有有符合的 url
     *
     * @param unknown $param
     */
    protected function crawler_prev_job()
    {
        $limit = $this->_limit;
        $limit = 2;
        // crawler_first_page已爬第一頁,因此從第二頁開始爬
    	for ($page = 2; $page <= $limit; $page++)
    	{
	    	$url_page = '&page=' . $page;
	    	$url      = $this->_serch_url . $url_page;

	    	$result = Curl::get_response($url);

	    	if (!$result['status'])
	    	{
	    		exit("今天不順，抓不到資料xxx");
	    	}

	    	$this->_content = $result['data'];

	    	$this->_find_list_title($this->_content);

	    	usleep(100);
    	}
    }

    protected function crawler_job_page()
    {
    	if (count($this->_ptt_article_ids) == 0 )
    	{
    		return FALSE;
    	}

    	foreach ($this->_ptt_article_ids as $article_id)
    	{
            $url = $this->_ptt_url . $article_id . '.html';
    		$result = Curl::get_response($url);

    		if (!$result['status'])
    		{
    			echo "取不到該筆資料 ". $url;
    			continue;
    		}

    		$content = $result['data'];

    		//整理格式後寫到 DB
            $job_data['id'] = $article_id;
    		$job_data['title'] = $this->_find_job_title($content);

    		if (NULL == $job_data['title'])
    		{
    			echo "該筆抓不到 title " . $url;
    			continue;
    		}
//    		$company_data['name']   = $this->_find_company_name($content);
//    		$company_data['c_code'] = $this->_gen_hash_code($company_data['name']);

//    		$companyID = Company::insert($company_data);

            $job_data['description']  = $this->_find_job_description($content);
            $job_data['source_url']   = $url;
            $job_data['source']       = 'ptt';
            $job_data['j_code']       = $this->_gen_hash_code($job_data['title']);
//            $job_data['companyID']    = $companyID;
            $job_data['appear_date']    = $this->_find_postdate($content);

            $salary = $this->_find_salary($job_data['description']);

            if (!empty($salary)) {
                $job_data['sal_month_low'] = $salary[0];
                $job_data['sal_month_high'] = $salary[1];
            }
            dd($job_data);
            // todo 寫進dynamoDB
            // todo 寫進CloudSearch
            $jobID = Job::insert($job_data);

    	}

    	exit("為了避免跑到 view 發生錯誤訊息，先在這裡中斷了");
    }

    private function _gen_hash_code ($str)
    {
    	$num = 0;
    	return $hash_str = md5($str);
    }

    private function _find_company_name($content = "")
    {
    	$content = $this->_exchage_content_format($content);

    	$patten[] = '/(.*有限公司).*/';
    	$patten[] = '/(.*顧問公司).*/';
    	$patten[] = '/.*公司名稱：(.*)/';
    	$patten[] = '/.*公司名稱，統編\(中華民國以外註冊可免填\)：(.*)/';
    	$patten[] = '/.*[公司名稱]\：(.*)/';
        $content = str_replace(' ', '', $content);

    	$content = strip_tags($content);

    	foreach ($patten as $ptn)
    	{
	    	if (preg_match($ptn, $content, $match))
	    	{
	    		break;
	    	}
    	}

    	if (!isset($match[1]))
    	{
    		return "來源 PTT";
    	}

    	$rep_patten[] = "/【公司名稱：/";
    	$rep_patten[] = "/公司名稱：/";
    	$rep_patten[] = "/(【.*：)/";

    	$match[1] = trim($match[1]);
    	$match[1] = preg_replace($rep_patten, '', $match[1]);

    	if ($match[1] == "")
    	{
    		return "來源 PTT";
    	}
    	$title = $match[1];

    	return $title;
    }

    private function _find_list_title($content = "")
    {
    	$patten = '/.*\<a\ href="\/bbs\/Soft_Job\/(.*).html\"\>\[徵才\]\ (.*)\<\/a\>.*/';

    	if (!preg_match_all($patten, $content, $match))
    	{
    		return [];
    	}

    	return $match[1];
    }

    private function _find_list_page_btn($content = "")
    {

    	$patten = '/.*href=\"\/bbs\/Soft_Job\/search\?page\=(\d+)&amp;q=%5B%E5%BE%B5%E6%89%8D%5D\"\>最舊<\/a>.*/';

    	if (!preg_match($patten, $content, $match))
    	{
    		return FALSE;
    	}

    	return (int) $match[1];

    }

    private function _find_postdate($content = "")
    {
    	$content = $this->_exchage_content_format($content);

    	$patten = '/.*\<span\ class=\"article\-meta\-tag\">時間<\/span><span\ class=\"article-meta-value\">(.*)<\/span><\/div>.*/';

    	if (!preg_match($patten, $content, $match))
    	{
    		return NULL;
    	}
    	$match[1] = str_replace('：', ':', $match[1]);

    	$post_date = date("Y-m-d", strtotime($match[1]));

    	return  $post_date;
    }

    private function _exchage_content_format($content)
    {
    	$patten[] = "/\:/";
    	$patten[] = "/\】/";
    	$patten[] = "/》/";
    	return preg_replace($patten,"：", $content);
    }

    private function _find_job_title($content)
    {
    	$content = $this->_exchage_content_format($content);

    	$patten = '/\[徵才\]\ (.*)\ \-\ 看板\ Soft_Job/';

    	if (!preg_match($patten, strip_tags($content), $match))
    	{
    		return NULL;
    	}
    	$title = $match[1];

    	return $title;
    }


    private function _find_job_description($content)
    {
    	$content = preg_replace('/\s(?=\s)/', '', $content);
    	$content = preg_replace('/[\n\r\t]/', '</br>', $content);

    	$patten = '/<span class=\"article\-meta\-value\">.*<\/span><\/div>(.*)\<span\ class\=\"f2\"\>.*發信站.*/';


    	if (!preg_match($patten, $content, $match))
    	{
    		return NULL;
    	}

    	$descript = strip_tags($match[1], '<br>');

    	return $descript;
    }

    /**
     * 搜尋
     */
    public function search($param = [])
    {
        return Job::search($param);
    }

    private function _find_salary(?string $descript)
    {
        if (empty($descript)) {
            return[];
        }
        $descript = str_replace(' ', '', $descript);
        $descript = preg_replace('/\s(?=\s)/', '', $descript);
        $descript = str_replace(',', '', $descript);
        $descript = str_replace('K', '000', $descript);
        $descript = str_replace('k', '000', $descript);
        $descript = str_replace('$', '', $descript);
        $descript = str_replace('up', '', $descript);
        $descript = str_replace('UP', '', $descript);

        $min_salary_pattern = '/薪資\D*(\d*\.\d*|\d*).*<\/br>/';
        $max_salary_pattern = '/薪資\D*\d*\D*(\d*\.\d*|\d*).*<\/br>/';

        $min_match = [];
        $max_match = [];

        preg_match($min_salary_pattern, $descript, $min_match);
        preg_match($max_salary_pattern, $descript, $max_match);

        $min_salary = $min_match[1] ?? 0;
        $max_salary = $max_match[1] ?? 0;

        return [$min_salary, $max_salary];
    }

    private function _get_first_job_id()
    {
        return null;
    }

    private function get_job_data(array $ids)
    {
        $job_data = [];
        $salary_data = [];

        foreach ($ids as $article_id) {
            $url = $this->_ptt_url . $article_id . '.html';
            $result = Curl::get_response($url);

            if (!$result['status']) {
                echo "取不到該筆資料 " . $url;
                continue;
            }

            $content = $result['data'];

            //整理格式後寫到 DB
            $job['id'] = $article_id;
            $job['title']        = $this->_find_job_title($content);

            if (NULL == $job['title'])
            {
                echo "該筆抓不到 title " . $url;
                continue;
            }

            $region = $this->get_region($content);

            $job['company_name']   = $this->_find_company_name($content);
            $job['region']   = $region['city'] . $region['area'];
            $job['description']  = $this->_find_job_description($content);
            $job['source_url']   = $url;
            $job['source']       = 'ptt';
            $job['j_code']       = $this->_gen_hash_code($job['title']);
            $job['appear_date']    = $this->_find_postdate($content);

            $salary = $this->_find_salary($job['description']);
            $salary_data[] = $salary;

            if (!empty($salary)) {
                $job['min_salary'] = (int) $salary[0];
                $job['max_salary'] = (int) $salary[1];
            } else {
                $job['min_salary'] = 0;
                $job['max_salary'] = 0;
            }

            $job_data[] = $job;
        }
        dd($job_data);

        return $job_data;
    }
}
