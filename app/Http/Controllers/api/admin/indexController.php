<?php

namespace App\Http\Controllers\api\admin;

use App\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WorkingHours;

class indexController extends Controller
{
    public function process(Request $request)
    {
        $all=$request->except('_token');
        Appointment::where('id',$all['id'])->update(['isActive'=>$all['type']]);
        return response()->json(['status'=>true]);
    }
    public function all()
    {
        $returnArray=[];
        //waiting
        $returnArray['waiting']=Appointment::where('isActive',0)->orderBy('workingHour','asc')->paginate(9);
        $returnArray['waiting']->getCollection()->transform(function($value){
            $value['working']= WorkingHours::getString($value['workingHour']);
            return $value;
        });
        //cancel
        $returnArray['cancel']=Appointment::where('isActive',2)->orderBy('workingHour','asc')->paginate(9);
        $returnArray['cancel']->getCollection()->transform(function($value){
            $value['working']= WorkingHours::getString($value['workingHour']);
            return $value;
        });
        //list
        $returnArray['list']=Appointment::where('isActive',1)->where('date','>',date("Y-m-d"))->orderBy('workingHour','asc')->paginate(9);
        $returnArray['list']->getCollection()->transform(function($value){
            $value['working']= WorkingHours::getString($value['workingHour']);
            return $value;
        });
        //Last List
        $returnArray['last_list']=Appointment::where('date','<',date("Y-m-d"))->orderBy('workingHour','asc')->paginate(9);
        $returnArray['last_list']->getCollection()->transform(function($value){
            $value['working']= WorkingHours::getString($value['workingHour']);
            return $value;
        });
        //Today List
        $returnArray['today_list']=Appointment::where('isActive',1)->where('date',date("Y-m-d"))->orderBy('workingHour','asc')->paginate(9);
        $returnArray['today_list']->getCollection()->transform(function($value){
            $value['working']= WorkingHours::getString($value['workingHour']);
            return $value;
        });
        return response()->json($returnArray);
    }
    public function getWaitingList()
    {
        $data=Appointment::where('isActive',0)->orderBy('workingHour','asc')->paginate(9);
        $data->getCollection()->transform(function($value){
            $value['working']= WorkingHours::getString($value['workingHour']);
            return $value;
        });
        return response()->json($data);
    }
    public function getCancelList()
    {
        $data=Appointment::where('isActive',2)->orderBy('workingHour','asc')->paginate(9);
        $data->getCollection()->transform(function($value){
            $value['working']= WorkingHours::getString($value['workingHour']);
            return $value;
        });
        return response()->json($data);
    }
    public function getList()
    {
        $data=Appointment::where('isActive',1)->where('date','>',date("Y-m-d"))->orderBy('workingHour','asc')->paginate(9);
        $data->getCollection()->transform(function($value){
            $value['working']= WorkingHours::getString($value['workingHour']);
            return $value;
        });
        return response()->json($data);
    }
    public function getLastList()
    {
        $data=Appointment::where('date','<',date("Y-m-d"))->orderBy('workingHour','asc')->paginate(9);
        $data->getCollection()->transform(function($value){
            $value['working']= WorkingHours::getString($value['workingHour']);
            return $value;
        });
        return response()->json($data);
    }
    public function getTodayList()
    {
        $data=Appointment::where('isActive',1)->where('date',date("Y-m-d"))->orderBy('workingHour','asc')->paginate(9);
        $data->getCollection()->transform(function($value){
            $value['working']= WorkingHours::getString($value['workingHour']);
            return $value;
        });
        return response()->json($data);
    }
}
