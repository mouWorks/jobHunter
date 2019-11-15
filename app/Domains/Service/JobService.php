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
        $jobData = $this->job104->get_jobs($conditions);

        $jobData = collect($jobData)->map(function ($job) {
            $tmpJob = [];
            $tmpJob['location'] = $this->parserService->getLocationInfo($job['job_addr_no_descript']);
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

    public function getPttJob(array $condition): array
    {
        if (empty($condition['kws'])) {
            $field = 'source';
            $condition['kws'] = 'ptt';
        } else {
            $field = 'job_title';
        }


        $pttJob = $this->sdk->cloudSearchDoSearch(
            [$field],
            $condition['kws'],
            20,
            $condition['page'],
            ['source' => 'ptt']
        );

        $pttJob = collect($pttJob)->map(function ($job) {
            $tmpJob = [];
            $tmpJob['title'] = $job['job_title'];
            $tmpJob['description'] = $job['description'];
            $tmpJob['location'] = $this->parserService->getLocationInfo($job['region'] ?? '');
            $tmpJob['date'] = date('Y-m-d', substr($job['create_time'], 0, 10));
            $tmpJob['company_name'] = $job['company_name'];
            $tmpJob['id'] = $job['id'];
            $tmpJob['salary'] = $this->parserService->getSalaryDesc($job['min_salary'], $job['max_salary']);
            $tmpJob['url'] = $job['url'];

            return $tmpJob;
        });

        return $pttJob->toArray();
    }

    /**
     * @param array $conditions
     * @return array
     */
    public function getPartTimeJob(array $conditions): array
    {
        if (empty($condition['kws'])) {
            $field = 'source';
            $conditions['kws'] = 'parttime';
        } else {
            $field = 'job_title';
        }

        $partTimeJob = $this->sdk->cloudSearchDoSearch(
            [$field],
            $conditions['kws'],
            20,
            $conditions['page'],
            ['source' => 'parttime']
        );

        $partTimeJob = collect($partTimeJob)->map(function($job){
            $tmpJob = [];
            $tmpJob['title'] = $job['job_title'];
            $tmpJob['description'] = $job['description'];
            $tmpJob['location'] = $this->parserService->getLocationInfo($job['region'] ?? '');
            $tmpJob['time'] = $job['time'];
            $tmpJob['id'] = $job['id'];
            $tmpJob['salary'] = $this->parserService->getSalaryDesc($job['min_salary'], $job['max_salary']);
            return $tmpJob;
        });

        return $partTimeJob->toArray();
    }
}
