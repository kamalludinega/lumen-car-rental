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
        return response()->json(['id'=>$car->id]);
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
        return response()->json('Car data updated');
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

    /**
     * car rental history
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function histories($id,Request $request){
        //valdate month
        $this->validate($request,[
            'month'=>'required|date_format:m-Y',
        ]);

        //get rental history
        $histories=Car::with(['histories'=>function($query) use($request){
            $monthyear=explode('-',$request->input('month'));
            $query->join('client','client.id','=','rental.client-id')
                ->whereRaw('(MONTH(`date-from`) = "'.$monthyear[0].'" AND YEAR(`date-from`) = "'.$monthyear[1].'") OR (MONTH(`date-to`) = "'.$monthyear[0].'" AND YEAR(`date-to`) = "'.$monthyear[1].'")')
                ->select('car-id','client.name AS rent-by','date-from','date-to');
        }])->find($id);

        if($histories==null){
            $histories=['error'=>'Car data not found'];
        }
        return response()->json($histories);
    }
}