<?php
use Carbon\Carbon;
class RentalTest extends TestCase
{
    /**
     * test get rental
     */
    public function testIndex()
    {
        $this->json('GET','/api/v1/rental')
            ->seeStatusCode(200);
    }

    /**
     * create rental test
     */
    public function testCreate(){
        $data=[
            'client-id'=>1,
            'car-id'=>1,
            'date-from'=>Carbon::now()->addDay(1)->toDateString(),
            'date-to'=>Carbon::now()->addDay(1)->toDateString(),
        ];
        $post=$this->post('/api/v1/rental',$data)->seeStatusCode(200);
        return json_decode($post->response->getContent())->id;
    }

    /**
     * update rental test
     * @depends testCreate
     * @param $id
     */
    public function testUpdate($id){
        $data=[
            'client-id'=>1,
            'car-id'=>1,
            'date-from'=>Carbon::now()->addDay(1)->toDateString(),
            'date-to'=>Carbon::now()->addDay(3)->toDateString(),
        ];
        $this->put('/api/v1/rental/'.$id,$data)->seeStatusCode(200);
    }

    /**
     * delete rental test
     * @depends testCreate
     * @param $id
     */
    public function testDelete($id){
        $this->delete('/api/v1/rental/'.$id)->seeStatusCode(200);
    }
}
