<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * company 資料表操作
 */
class Company extends Model
{
    /**
     * c_code 列表
     * @var array
     */
    private static $_c_code_list;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company';

    /**
     * 新增 company
     * @param  array $param 新增資料
     * @return boolean      新增是否成功
     */
    public static function insert($param = '')
    {
        // 無論是物件還是陣列，一律轉成陣列
        $param = (array) $param;

        // 取出 c_code
        $c_code = ($param['c_code']) ? $param['c_code'] : NULL;

        // 資料已存在則取消操作
        if ($c_code && isset(self::$_c_code_list[$c_code]))
        {
            return self::$_c_code_list[$c_code];
        }

        // 查詢 company 是否已存在
        $company_rows = DB::table('company')->where('c_code', $c_code)->get();
        $row = ($company_rows) ? $company_rows[0] : NULL;

        // 新增/更新
        if ($row)
        {
            $id = $row->companyID;
            DB::table('company')->where('companyID', $id)->update($param);
        }
        else
        {
            $id = DB::table('company')->insertGetId($param);
        }

        // 儲存 c_code
        self::$_c_code_list[$c_code] = $id;

        return $id;
    }

    /**
     * 搜尋
     * @param  array  $param 搜尋條件
     * @return array         company 資料
     */
    public static function search($param = [])
    {
        $page_size = (isset($param['page_size'])) ? $param['page_size'] : 50;
        $page      = (isset($param['page'])) ? $param['page'] : 1;

        // 分頁
        $page_start = ($page - 1) * $page_size;

        // 排序
        $orderby = [];
        if (isset($param['orderby']))
        {
            foreach ($param['orderby'] as $key => $asc)
            {
                $orderby[] = "{$key} {$asc}";
            }
        }
        $orderby = 'ORDER BY ' . implode(',', $orderby);

        // 查詢 company 資料
        $sql = "SELECT SQL_CALC_FOUND_ROWS `company`.*, count(*) AS `job_count`
                FROM `company`
                      INNER JOIN `job`
                      ON `job`.`companyID` = `company`.`companyID`
                GROUP BY `companyID`
                $orderby
                LIMIT $page_start, $page_size";
        // echo "<pre>sql = " . print_r($sql, TRUE). "</pre>";
        $rows  = DB::select($sql);
        $count = DB::select("SELECT FOUND_ROWS() as cnt")[0]->cnt;
        $total_page = ceil($count / $page_size);

        // 查詢 job 資料
        foreach ($rows as $key => $row)
        {
            $jobs = DB::table('job')
                         ->join('company', 'job.companyID', '=', 'company.companyID')
                         ->where('job.companyID', $row->companyID)
                         ->get();
            $row->jobs = $jobs;
        }

        return [
            'count'      => $count,
            'page_size'  => $page_size,
            'curr_page'  => $page,
            'total_page' => $total_page,
            'orderby'    => isset($param['orderby']) ? $param['orderby'] : NULL,
            'rows'       => $rows
        ];
    }

    /**
     * 取得 Company 資料
     * @param  Integer $companyID companyID
     * @return object             data
     */
    public static function get($companyID)
    {
        $rows = DB::table('company')->where('companyID', $companyID)->get();
        return ($rows) ? $rows[0] : NULL;
    }

    /**
     * 取得 1 筆員工人數為 NULL 的公司
     */
    public static function get_null_employees()
    {
        $count      = DB::table('company')->count();
        $null_count = DB::table('company')->whereNull('employees')->count();
        $rows       = DB::table('company')->whereNull('employees')->limit(1)->get();
        if ($count)
        {
            return [
                'count'      => $count,
                'null_count' => $null_count,
                'row'        => (count($rows)) ? $rows[0] : NULL
            ];
        }
        else
        {
            return NULL;
        }
    }

}
