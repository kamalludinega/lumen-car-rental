<?php
/**
 * Created by PhpStorm.
 * User: vti-eg
 * Date: 30/09/16
 * Time: 19:54
 */

namespace App\Http\Controllers;

use App\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * get all car
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        $car  = Car::get(['brand','type','year','color','plate']);
        return response()->json($car);
    }

    /**
     * create car
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        //validation
        $this->validation($request);
        //insert car
        $car = Car::create(
            [
                'brand'=>$request->get('brand'),
                'type'=>$request->get('type'),
                'year'=>$request->get('year'),
                'color'=>$request->get('color'),
                'plate'=>$request->get('plate'),
            ]
        );
        return response()->json($car);
    }

    /**
     * update car
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id,Request $request){
        $car=Car::find($id);
        if($car==null){
            return response()->json('Car not found');
        }
        $this->validation($request,$id);
        $car->brand = $request->input('brand');
        $car->type = $request->input('type');
        $car->year = $request->input('year');
        $car->color = $request->input('color');
        $car->plate = $request->input('plate');
        $car->save();
        unset($car->id);
        return response()->json($car);
    }

    /**
     * delete car
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id){
        $car=Car::find($id);
        if($car==null){
            return response()->json("Car not found");
        }
        $car->delete();
        return response()->json("Car deleted");
    }

    /**
     * car field validation
     * @param Request $request
     */
    protected function validation(Request $request,$id=null){
        $id = $id!=null ? ','.$id : '';
        //validation
        $this->validate($request, [
            'brand' => 'required',
            'type' => 'required',
            'year' => 'required|digits:4|numeric|max:'.date('Y'),
            'color' => 'required',
            'plate' => 'required|unique:car,plate'.$id,
        ]);
    }
}