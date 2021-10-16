<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Website;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WebsiteController extends Controller
{
    public $statusCode;
    public $status;
    public $message;
    public function __construct()
    {
        $this->status = TRUE;
        $this->statusCode = Response::HTTP_OK;
        $this->message = '';
    }
    public function index()
    {
        $websites = Website::orderBy('created_at','desc')->get()->all();
        $data = array();
        if($websites){
            $this->status= true;
            $this->statusCode = 200;
            $data = array('websites'=>$websites);
        }else{
            $this->status= false;
            $this->statusCode= 400;
            $this->message=  'Record not found';
        }
        $responseData = array('status'=>$this->status,'message'=>$this->message,'statusCode'=>$this->statusCode,'data'=>$data );
        return response()->json($responseData ,$this->statusCode);
    }

    public function show($id)
    {
        $website = Website::find($id);
        $data = array();
        if($website){
            $this->statusCode = 200;
            $this->status = false;
            $data = array('website'=>$website);
        }else{
            $this->message = 'Record not found';
            $this->statusCode = 400;
            $this->status = false;
        }
        $responseData = array('status'=>$this->status,'message'=>$this->message,'statusCode'=>$this->statusCode,'data'=>$data );
        return response()->json($responseData ,$this->statusCode);
    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'website'=>'required|url',
        ]);
        $data = array();
        if ($validator->fails()) {
            $this->message = $validator->errors();
            $this->statusCode = 422;
            $this->status = false;
            $responseData = array('status'=>$this->status,'message'=>$this->message,'statusCode'=>$this->statusCode,'data'=>$data );
            return response()->json($responseData ,$this->statusCode);
        }
        $website = new Website();
        $name = trim($request->name);
        $website->name = $name;
        $website->website = $request->website;
        $is_save = $website->save();
        $data = array();
        if($is_save){
            $this->message = 'Website successfully created';
            $this->statusCode = 201;
            $this->status = false;
            $data = array('website'=>$website);
        }else{
            $this->message = 'Website not created';
            $this->statusCode = 400;
            $this->status = false;
        }
        $responseData = array('status'=>$this->status,'message'=>$this->message,'statusCode'=>$this->statusCode,'data'=>$data );
        return response()->json($responseData ,$this->statusCode);
    }
}
