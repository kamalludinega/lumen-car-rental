<?php
/**
 * Created by PhpStorm.
 * User: vti-eg
 * Date: 30/09/16
 * Time: 19:54
 */

namespace App\Http\Controllers;

use App\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;

class RentalController extends Controller
{
    /**
     * get all rental
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        $rental  = DB::table('rental')
            ->join('client','client.id','=','rental.client-id')
            ->join('car','car.id','=','rental.car-id')
            ->get([
                'client.name',
                'car.brand',
                'car.type',
                'car.plate',
                'rental.date-from',
                'rental.date-to'
            ]);
        return response()->json($rental);
    }

    /**
     * create rental
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        //validation
        $this->customValidation();
        $this->validation($request);
        //insert rental
        $rental = Rental::create(
            [
                'car-id'=>$request->get('car-id'),
                'client-id'=>$request->get('client-id'),
                'date-from'=>$request->get('date-from'),
                'date-to'=>$request->get('date-to'),
            ]
        );
        return response()->json(['id'=>$rental->id]);
    }

    /**
     * update rental
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id,Request $request){
        $rental=Rental::find($id);
        if($rental==null){
            return response()->json('Rental data not found');
        }

        //validation
        $this->customValidation();
        $this->validation($request,$id);

        $rental->{'car-id'} = $request->input('car-id');
        $rental->{'client-id'} = $request->input('client-id');
        $rental->{'date-from'} = $request->input('date-from');
        $rental->{'date-to'} = $request->input('date-to');
        $rental->save();

        //don't show rental id
        return response()->json('Rental data updated');
    }

    /**
     * delete rental
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id){
        $rental=Rental::find($id);
        if($rental==null){
            return response()->json("Rental data not found");
        }
        $rental->delete();
        return response()->json("Rental data deleted");
    }

    /**
     * rental field validation
     * @param Request $request
     */
    protected function validation(Request $request,$id=null){
        //get validation for date BETWEEN today +1 and today +7 ( day >= today and day <= today + 7)
        $after=Carbon::now()->toDateString();
        $before=Carbon::now()->addDay(8)->toDateString();

        //validation
        $this->validate($request, [
            'car-id' => 'required|exists:car,id|checkRentedCar:'.$id,
            'client-id' => 'required|exists:client,id|checkClientRent:'.$id,
            'date-from' => 'required|date_format:"Y-m-d"|after:'. $after.'|before:'. $before,
            'date-to' => 'required|date_format:"Y-m-d"|before:'.$before.'|checkMaxRentDay',
        ]);
    }

    public function customValidation(){
        //max rent day must be 3 days
        Validator::extend('checkMaxRentDay', function($attribute, $value, $parameters, $validator) {
            //check if duration is > 3 days
            if(Input::get('date-from')!=null && Input::get('date-to')!=null) {
                $datefrom = Carbon::createFromFormat('Y-m-d', Input::get('date-from'));
                $dateto = Carbon::createFromFormat('Y-m-d', Input::get('date-to'));
                if ($datefrom->diffInDays($dateto) > 3) {
                    return false;
                }
            }
            return true;
        });
        //validation message replacer
        Validator::replacer('checkMaxRentDay', function($attribute,$value,$parameters,$validator) {
            return 'Maximal rent day is 3 days';
        });

        //check car is already rented
        Validator::extend('checkRentedCar',function($attribute,$value,$parameters,$validator){
            if(Input::get('date-from')!=null && Input::get('date-to')!=null) {
                $rented = Rental::where('car-id', $value)
                    ->where('id', '<>', $parameters[0])
                    ->where('date-from', '<=', Input::get('date-to'))
                    ->where('date-to', '>=', Input::get('date-from'))->get();
                return $rented->isEmpty();
            }
            return true;
        });
        Validator::replacer('checkRentedCar',function($attribute,$value,$parameters,$validator){
            return 'Car still rented';
        });

        //check if client is renting a car in selected date
        Validator::extend('checkClientRent',function($attribute,$value,$parameters,$validator){
            if(Input::get('date-from')!=null && Input::get('date-to')!=null) {
                $clientrent = Rental::where('client-id', $value)
                    ->where('id', '<>', $parameters[0])
                    ->where('date-from', '<=', Input::get('date-to'))
                    ->where('date-to', '>=', Input::get('date-from'))->get();
                return $clientrent->isEmpty();
            }
            return true;
        });

        Validator::replacer('checkClientRent',function($attribute,$value,$parameters,$validator){
            return 'Client is still renting a car';
        });
    }
}