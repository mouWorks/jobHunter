<?php


class JobControllerTest extends TestCase
{
    public function testJobController104JobSuccess()
    {
        // arrange
        $this->withoutMiddleware();

        // act
        $response = $this->get(route('awshack.job.show', ['source' => '104']));

        // assert
        $response->assertResponseOk();
    }
}
