<?php


use App\Classes\Job104;
use App\Classes\JobPtt;
use App\Domains\Product\Services\BackupEventService;

class JobControllerTest extends TestCase
{
//    public function testJobController104JobSuccess()
//    {
//        // arrange
//        $this->withoutMiddleware();
//
//        // act
//        $response = $this->get(route('awshack.job.show', ['source' => '104']));
//
//        // assert
//        $response->assertResponseOk();
//    }

//    public function testJob104()
//    {
//        // arrange
//        /** @var Job104 $job */
//        $job = $this->app->make(Job104::class);
//
//        // act
//        $conditions = [
//            'cat'  => ['2007001006', '2007001004', '2007001008', '2007001012'],
//            'area' => ['6001001000', '6001002000'],
//            'role' => [1, 4],
//            'exp'  => 7,
//            'kws'  => 'php python',
//            'kwop' => 3,
//        ];
//        $result = $job->get_jobs($conditions);
////        $result = $job->testAWS();
//
//        // assert
//        dd($result);
//    }

    public function testJobPtt()
    {
        // arrange
        /** @var JobPtt $job */
        $job = $this->app->make(JobPtt::class);

        // act
        $result = $job->update_aws();
//        $result = $job->testAWS();

        // assert
        dd($result);
    }
}
