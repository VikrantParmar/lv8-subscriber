<?php
namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Models\Blog;
use App\Models\Subscriber;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubscriberController extends Controller
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

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'website_id'=>'required|numeric',
        ]);
        $data = array();
        if ($validator->fails()) {
            $this->message = $validator->errors();
            $this->statusCode = 422;
            $this->status = false;
            $responseData = array('status'=>$this->status,'message'=>$this->message,'statusCode'=>$this->statusCode,'data'=>$data );
            return response()->json($responseData ,$this->statusCode);
        }
        $name = trim($request->name);
        $email = trim($request->email);
        $websiteId = $request->website_id;
        $alreadySubscribed = Subscriber::where(['email'=>$email,'website_id'=>$websiteId])->get()->first();
        if(!$alreadySubscribed){
            $subscriber = new Subscriber();

            $subscriber->name = $name;
            $subscriber->website_id = $websiteId;
            $subscriber->email = $email;
            $is_save = $subscriber->save();
            $data = array();
            if($is_save){
                $this->message = 'You are successfully subscribed';
                $this->statusCode = 201;
                $this->status = false;
                $data = array('subscriber'=>$subscriber);
            }else{
                $this->message = 'Failed to subscribe';
                $this->statusCode = 400;
                $this->status = false;
            }
        }else{
            $this->message = 'you are already subscribed';
            $this->statusCode = 200;
            $this->status = true;
            $data = array('subscriber'=>$alreadySubscribed);
        }

        $responseData = array('status'=>$this->status,'message'=>$this->message,'statusCode'=>$this->statusCode,'data'=>$data );
        return response()->json($responseData ,$this->statusCode);
    }

    public function sendEmail(Request $request)
    {
        /*$details = array(
            'email' =>'parmarvikrantr@gmail.com',
            'title' =>'Test Title ',
            'body' =>' body test herer',
        );*/

        $blogId= @$request->blog_id;
        $websiteId= @$request->website_id;

        $data = array();
        if($blogId){
            $blog = Blog::find($blogId);
            if($blog ){
                $websiteId =$blog->website_id;
                $subscribers= Subscriber::where(['website_id'=>$websiteId ])->get()->all();
                if($subscribers){
                    foreach($subscribers as $key=>$row){
                        $details = array(
                            'email' => $row->email,
                            'title' => $blog->title,
                            'body' => $blog->description,
                        );
                        @SendEmailJob::dispatch($details);
                    }
                    $this->message = 'Successfully sent';
                    $this->statusCode = 200;
                    $this->status = false;
                }else{
                    $this->message = 'No any subscriber available';
                    $this->statusCode = 200;
                    $this->status = false;
                }
                #$this->statusCode = 200;
                #$this->status = false;
                #$data = array('blog'=>$blog);
            }else{
                $this->message = 'Record not found';
                $this->statusCode = 400;
                $this->status = false;
            }
        }else if($websiteId){
            $blogs = Blog::where(['website_id'=>$websiteId ])->get()->all();
            if($blogs){
                foreach($blogs as $keyBlog => $rowBlog) {
                    $subscribers = Subscriber::where(['website_id' => $websiteId])->get()->all();
                    if ($subscribers) {
                        foreach ($subscribers as $key => $row) {
                            $details = array(
                                'email' => $row->email,
                                'title' => $rowBlog->title,
                                'body' => $rowBlog->description,
                            );
                            @SendEmailJob::dispatch($details);
                        }
                        $this->message = 'Successfully sent';
                        $this->statusCode = 200;
                        $this->status = false;
                    } else {
                        $this->message = 'No any subscriber available';
                        $this->statusCode = 200;

                    }
                }
            }
        }
        else{
            //All Email send to website wise
            $this->message = 'Missing blog_id or website_id';
            $this->statusCode = 400;
            $this->status = false;
        }

        $responseData = array('status'=>$this->status,'message'=>$this->message,'statusCode'=>$this->statusCode,'data'=>$data );
        return response()->json($responseData ,$this->statusCode);

        #$websites = Subscriber::orderBy('created_at','desc')->get()->all();

    }
}
