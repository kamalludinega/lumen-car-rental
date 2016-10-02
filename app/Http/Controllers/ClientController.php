<?php
/**
 * Created by PhpStorm.
 * User: vti-eg
 * Date: 30/09/16
 * Time: 19:54
 */

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * get all client
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        $client  = Client::all();
        return response()->json($client);
    }

    /**
     * create client
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        //validation
        $this->validation($request);
        //insert client
        $client = Client::create(
            ['name'=>$request->get('name'),'gender'=>$request->get('gender'),]
        );
        return response()->json(['id'=>$client->id]);
    }

    /**
     * update client
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id,Request $request){
        $client=Client::find($id);
        if($client==null){
            return response()->json('Client not found');
        }
        $this->validation($request);
        $client->name = $request->input('name');
        $client->gender = $request->input('gender');
        $client->save();
        return response()->json('Client data updated');
    }

    /**
     * delete client
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id){
        $client=Client::find($id);
        if($client==null){
            return response()->json("Client not found");
        }
        $client->delete();
        return response()->json("Client deleted");
    }

    /**
     * client field validation
     * @param Request $request
     */
    protected function validation(Request $request){
        //validation
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required|in:male,female',
        ]);
    }

    /**
     * client rental histories
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function histories($id){
        $histories=Client::with(['histories' => function($query){
            $query->join('car','car.id','=','rental.car-id')
                ->select('client-id','brand','type','plate','date-from','date-to');
        }])->find($id);
        return response()->json($histories);
    }
}