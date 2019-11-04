<?php


use App\Classes\Job104;
use App\Classes\JobPtt;

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
//
//    public function testJob104()
//    {
//        // arrange
//        /** @var Job104 $job */
//        $job = $this->app->make(Job104::class);
//
//        // act
//        $conditions = [
//            'kws'  => 'php',
//        ];
//        $result = $job->get_jobs($conditions);
//
//        // assert
//        $this->assertEquals(20, count($result));
//    }
//
//    public function testJobPtt()
//    {
//        // arrange
//        /** @var Job104 $job */
//        $job = $this->app->make(JobPtt::class);
//
//        // act
//        $conditions = [
//            'page'  => 1,
//            'kws' => '工程師',
//        ];
//        $result = $job->get_jobs($conditions);
//
//        // assert
//        $this->assertEquals(20, count($result));
//    }

    public function testJobPttWrite()
    {
        // arrange
        /** @var JobPtt $job */
        $job = $this->app->make(JobPtt::class);

        // act
        $result = $job->update_aws();

        // assert
        dd($result);
    }
}
