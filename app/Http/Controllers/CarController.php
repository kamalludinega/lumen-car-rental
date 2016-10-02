<?php
/**
 * Created by PhpStorm.
 * User: vti-eg
 * Date: 30/09/16
 * Time: 19:54
 */

namespace App\Http\Controllers;

use App\Car;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    /**
     * get rented car in specific date
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rented(Request $request){
        //valdate month
        $this->validate($request,[
            'date'=>'required|date_format:d-m-Y',
        ]);

        //change date format
        $dbdate=Carbon::createFromFormat('d-m-Y',$request->input('date'))->format('Y-m-d');

        //get rented car
        $rented=DB::table('car')
            ->join('rental','rental.car-id','=','car.id')
            ->whereRaw('"'.$dbdate.'" BETWEEN `date-from` AND `date-to`')
            ->select('brand','type','plate')->distinct()->get();

        return response()->json(['date'=>$request->input('date'),'rented_cars'=>$rented]);
    }

    /**
     * get free car at specific time
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function free(Request $request){
        //valdate month
        $this->validate($request,[
            'date'=>'required|date_format:d-m-Y',
        ]);
        //change date format
        $dbdate=Carbon::createFromFormat('d-m-Y',$request->input('date'))->format('Y-m-d');

        //get free car
        $free=DB::table('car')
            ->leftjoin('rental','rental.car-id','=','car.id')
            ->whereRaw('car.id NOT IN (SELECT `car-id` FROM rental WHERE "'.$dbdate.'" BETWEEN `date-from` AND `date-to`) OR rental.id IS NULL')
            ->select('brand','type','plate')->distinct()->get();

        return response()->json(['date'=>$request->input('date'),'free_cars'=>$free]);
    }
}