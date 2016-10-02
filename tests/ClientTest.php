<?php

class ClientTest extends TestCase
{
    /**
     * test get client
     */
    public function testIndex()
    {
        $this->json('GET','/api/v1/client')
            ->seeStatusCode(200);
    }

    /**
     * create client test
     */
    public function testCreate(){
        $data=[
            'name'=>str_random(4),
            'gender'=>'male',
        ];
        $post=$this->post('/api/v1/client',$data)->seeStatusCode(200);
        return json_decode($post->response->getContent())->id;
    }

    /**
     * update client test
     * @depends testCreate
     * @param $id
     */
    public function testUpdate($id){
        $data=[
            'name'=>str_random(4),
            'gender'=>'female',
        ];
        $this->put('/api/v1/client/'.$id,$data)->seeStatusCode(200);
    }

    /**
     * histories client test
     * @depends testCreate
     * @param $id
     */
    public function testHistories($id){
        $this->json('GET','/api/v1/histories/client/'.$id)->seeStatusCode(200);
    }

    /**
     * delete client test
     * @depends testCreate
     * @param $id
     */
    public function testDelete($id){
        $this->delete('/api/v1/client/'.$id)->seeStatusCode(200);
    }
}
