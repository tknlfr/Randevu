<?php

namespace App\Http\Controllers\api;

use App\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WorkingHours;

class indexController extends Controller
{
    public function getWorkingHours($date='')
    {
        $date=($date=='') ? date("Y-m-d"): $date;
        $day=date("l",strtotime($date));
        $returnArray=[];
        $hours= WorkingHours::where('day',$day)->get();
        foreach($hours as $k => $v)
        {
           $control=Appointment::where('date',$date)
           ->where('workingHour',$v['id'])
           ->where(function($control){
               $control->orwhere('isActive',APPOINTMENT_DEFAULT);
               $control->orwhere('isActive',APPOINTMENT_SUCCESS);
           })
           ->count();
           //11.00-12.00
           //  0  -  1
           $exp=explode('-',$v['hours']);
           $nowTime=date("H.i");
           $v['isActive']=($control==0 and $exp[0]>$nowTime) ? true : false;
            $returnArray[]=$v;
        }
        return response()->json($returnArray);
    }
    public function appointmentStore(Request $request)
    {
        $returnArray=[];
        $returnArray['status']=false;
        $all = $request->except('_token');
        $control=Appointment::where('date',$all['date'])->where('workingHour',$all['workingHour'])->count();
        if($control !=0) {
            $returnArray['message']="Bu randevu tarihi doludur.";
            return response()->json($returnArray);
        }
        $create = Appointment::create($all);
        if($create)
        {
            $returnArray['status']=true;
            $returnArray['message']="Randevunuz basari ile alinmistir";
        }
        else
        {
            $returnArray['message']="Veri eklenemedi Bizimle iletisime geciniz.";
        }
        return response()->json($returnArray);
    }
    public function getWorkingStore(Request $request)
    {
        $all=$request->except('_token');
        WorkingHours::query()->delete();
        foreach($all as $k=>$v)
        {

                foreach($v as $key=>$value)
                {
                    WorkingHours::create([
                        'day'=>$k,
                        'hours'=>$value

                    ]);
                }
        }


        return response()->json($all);
    }
    public function getWorkingList()
    {
        $returnArray=[];
        $data=WorkingHours::all();
        foreach($data as $k=> $v)
        {
            $returnArray[$v['day']][]=$v['hours'];
        }
        return response()->json($returnArray);
    }
}
