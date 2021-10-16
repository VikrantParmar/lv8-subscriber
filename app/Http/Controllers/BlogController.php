<?php
namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Blog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
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
        $blogs = Blog::orderBy('id','desc')->get()->all();
        $data = array();
        if($blogs){
            $this->status= true;
            $this->statusCode = 200;
            $data = array('blogs'=>$blogs);
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
        $blog = Blog::find($id);
        $data = array();
        if($blog){
            $this->statusCode = 200;
            $this->status = false;
            $data = array('blog'=>$blog);
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
            'title' => 'required',
            'website_id'=>'required',
            'description'=>'required'
        ]);
        $data = array();
        if ($validator->fails()) {
            $this->message = $validator->errors();
            $this->statusCode = 422;
            $this->status = false;
            $responseData = array('status'=>$this->status,'message'=>$this->message,'statusCode'=>$this->statusCode,'data'=>$data );
            return response()->json($responseData ,$this->statusCode);
        }
        $blog = new Blog;
        $title = trim($request->title);
        $blog->title = $title;
        $blog->website_id = $request->website_id;
        $blog->description = $request->description;
        $blog->slug = Str::slug($title);
        $is_save = $blog->save();
        if($is_save){
            $this->message = 'Blog successfully created';
            $this->statusCode = 201;
            $this->status = false;
            $data = array('blog'=>$blog);
            dispatch(new SendEmailJob($blog));
        }else{
            $this->message = 'Blog not created';
            $this->statusCode = 400;
            $this->status = false;
        }
        $responseData = array('status'=>$this->status,'message'=>$this->message,'statusCode'=>$this->statusCode,'data'=>$data );
        return response()->json($responseData ,$this->statusCode);
    }
}
