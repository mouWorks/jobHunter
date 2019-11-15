<?php

namespace App\Domains\Service;

use App\Classes\Job104;
use App\Classes\JobPtt;
use App\Domains\AWS\Sdk;

class JobService
{
    /** @var Job104 */
    private $job104;

    /** @var Sdk */
    private $sdk;

    /** @var JobPtt */
    private $jobPtt;

    /** @var ParserService */
    private $parserService;

    /**
     * JobService constructor.
     * @param Job104 $job104
     * @param JobPtt $jobPtt
     * @param Sdk $sdk
     */
    public function __construct(Job104 $job104, JobPtt $jobPtt, Sdk $sdk, ParserService $parserService)
    {
        $this->job104 = $job104;
        $this->jobPtt = $jobPtt;
        $this->sdk = $sdk;
        $this->parserService = $parserService;
    }

    /**
     * @param array $conditions
     * @return array
     */
    public function get104Job(array $conditions): array
    {
        $jobData = array_slice($this->job104->get_jobs($conditions), 0, 8);

        $jobData = collect($jobData)->map(function ($job) {
            $tmpJob = [];
            $city = mb_substr($job['job_addr_no_descript'], 0, 3);
            $area = mb_substr($job['job_addr_no_descript'], 3, 3);
            $tmpJob['job_addr_no_descript'] = $city . '</br>' . $area;
            $tmpJob['description'] = nl2br(mb_substr($job['description'], 0, 200));
            $tmpJob['salary'] = $this->parserService->getSalaryDesc($job['sal_month_low'], $job['sal_month_high']);
            $tmpJob['img'] = $job['company']['img'] ?? null;
            $tmpJob['date'] = date( 'Y-m-d', strtotime($job['appear_date']));
            $tmpJob['source'] = '104';
            $tmpJob['title'] = $job['title'];
            $tmpJob['company'] = [
                'name' => $job['company']['name'],
                'indcat' => $job['company']['indcat'],
                'capital' => $job['company']['capital'] ?? ParserService::NO_DESC,
                'employees' => $job['company']['employees'] ?? ParserService::NO_DESC,
            ];
            $tmpJob['internal_url'] = './job/104/1';

            return $tmpJob;
        });

        return $jobData->toArray();
    }

    /**
     * @param array $conditions
     * @return array
     */
    public function getPartTimeJob(array $conditions): array
    {
        if (empty($conditions)) {
            $partTimeJob = $this->sdk->cloudSearchDoSearch(
                ['source'],
                'parttime',
                10,
                1,
                ['source' => 'parttime']
            );

            return $partTimeJob;
        }

        $partTimeJob = $this->sdk->cloudSearchDoSearch(
            ['job_title'],
            $conditions['kws'],
            20,
            $conditions['page'],
            ['source' => 'parttime']
        );

        return $partTimeJob;
    }
}
