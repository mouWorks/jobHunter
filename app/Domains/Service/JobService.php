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
        [$pagination , $jobData] = $this->job104->get_jobs($conditions);

        $jobData = collect($jobData)->map(function ($job) {
            $tmpJob = [];
            $tmpJob['welfare'] = nl2br($job['welfare'] ?? '');
            $tmpJob['others'] = nl2br($job['others'] ?? '');
            $tmpJob['location'] = $this->parserService->getLocationInfo($job['job_addr_no_descript']);
            $tmpJob['description'] = $this->parserService->getDescription($job['description']);
            $tmpJob['salary'] = $this->parserService->getSalaryDesc($job['sal_month_low'] ?? 0, $job['sal_month_high'] ?? 0);
            $tmpJob['img'] = $job['company']['img'] ?? null;
            $tmpJob['date'] = date( 'Y-m-d', strtotime($job['appear_date']));
            $tmpJob['source'] = '104';
            $tmpJob['title'] = $job['title'];
            $tmpJob['company'] = [
                'name' => $job['company']['name'],
                'indcat' => $job['company']['indcat'],
                'capital' => $this->parserService->num2str($job['company']['capital'] ?? null),
                'employees' => $job['company']['employees'] ?? ParserService::NO_DESC,
            ];
            $tmpJob['internal_url'] = '/awshack/job/104/1';
            $tmpJob['j_code'] = $job['j_code'];

            return $tmpJob;
        })->keyBy('j_code');

        return [$pagination, $jobData->toArray()];
    }

    public function getPttJob(array $conditions): array
    {
        if (empty($conditions['kws'])) {
            $field = 'source';
            $conditions['kws'] = 'ptt';
        } else {
            $field = 'job_title';
        }

        $options = [
            'source' => 'ptt'
        ];

        if (!empty($conditions['location'])) {
            $options['region'] = $conditions['location'];
        }


        $pttJob = $this->sdk->cloudSearchDoSearch(
            [$field],
            $conditions['kws'],
            20,
            $conditions['page'],
            $options
        );

        $pttJob = collect($pttJob)->map(function ($job) {
            $tmpJob = [];
            $tmpJob['title'] = $job['job_title'];
            $tmpJob['description'] = $this->parserService->getDescription($job['description']);
            $tmpJob['location'] = $this->parserService->getLocationInfo($job['region'] ?? '');
            $tmpJob['date'] = date('Y-m-d', substr($job['create_time'], 0, 10));
            $tmpJob['company_name'] = $job['company_name'];
            $tmpJob['id'] = $job['id'];
            $tmpJob['salary'] = $this->parserService->getSalaryDesc($job['min_salary'], $job['max_salary']);
            $tmpJob['internal_url'] = '/awshack/job/ptt/' . $job['id'];
            $tmpJob['external_url'] = $job['url'];

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
        if (empty($conditions['kws'])) {
            $field = 'source';
            $conditions['kws'] = 'parttime';
        } else {
            $field = 'job_title';
        }

        $options = [
            'source' => 'parttime'
        ];

        if (!empty($conditions['location'])) {
            $options['region'] = $conditions['location'];
        }

        $partTimeJob = $this->sdk->cloudSearchDoSearch(
            [$field],
            $conditions['kws'],
            20,
            $conditions['page'],
            $options
        );

        $partTimeJob = collect($partTimeJob)->map(function($job){
            $tmpJob = [];

            if (empty($job['create_time'])) {
                $date = '';
            } else {
                $date = date('Y-m-d', substr($job['create_time'] ?? 0, 0, 10));
            }

            $tmpJob['title'] = $job['job_title'];
            $tmpJob['description'] = $job['description'];
            $tmpJob['location'] = $this->parserService->getLocationInfo($job['region'] ?? '');
            $tmpJob['time'] = $job['time'];
            $tmpJob['date'] = $date;
            $tmpJob['id'] = $job['id'];
            $tmpJob['salary'] = $this->parserService->getSalaryDesc($job['min_salary'], $job['max_salary']);
            $tmpJob['internal_url'] = '/awshack/job/pt/' . $job['id'];
            return $tmpJob;
        });

        return $partTimeJob->toArray();
    }
}
