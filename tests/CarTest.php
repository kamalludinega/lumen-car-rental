<?php

class CarTest extends TestCase
{
    /**
     * test get car
     */
    public function testIndex()
    {
        $this->json('GET','/api/v1/car')
            ->seeStatusCode(200);
    }

    /**
     * create car test
     */
    public function testCreate(){
        $data=[
            'brand'=>str_random(4),
            'type'=>str_random(4),
            'year'=>'2016',
            'color'=>str_random(4),
            'plate'=>str_random(4)
        ];
        $post=$this->post('/api/v1/car',$data)->seeStatusCode(200);
        return json_decode($post->response->getContent())->id;
    }

    /**
     * update car test
     * @depends testCreate
     * @param $id
     */
    public function testUpdate($id){
        $data=[
            'brand'=>str_random(4),
            'type'=>str_random(4),
            'year'=>'2016',
            'color'=>str_random(4),
            'plate'=>str_random(4)
        ];
        $this->put('/api/v1/car/'.$id,$data)->seeStatusCode(200);
    }

    /**
     * @depends testCreate
     * @param $id
     */
    public function testHistories($id){
        $data=['month'=>'10-2016'];
        $this->json('GET','/api/v1/histories/car/'.$id,$data)->seeStatusCode(200);
    }

    /**
     * @depends testCreate
     */
    public function testRented(){
        $data=['date'=>'05-10-2016'];
        $this->json('GET','/api/v1/car/rented',$data)->seeStatusCode(200);
    }

    /**
     * @depends testCreate
     */
    public function testFree(){
        $data=['date'=>'05-10-2016'];
        $this->json('GET','/api/v1/car/free',$data)->seeStatusCode(200);
    }

    /**
     * delete car test
     * @depends testCreate
     * @param $id
     */
    public function testDelete($id){
        $this->delete('/api/v1/car/'.$id)->seeStatusCode(200);
    }
}
