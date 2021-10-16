<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\SendEmailDemo;
use Mail;
class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    /**
     * Create a new job instance.
     *giftVoucherEmailSend.php
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new SendEmailDemo($this->details);
        @Mail::to($this->details['email'])->send($email);
        /*
        if($this->details){

            $id = $this->details->id;

            $email = new SendEmailDemo($this->details);

            Mail::to($this->details['email'])->send($email);

        }else{

        }
        $email = new SendEmailDemo($this->details);
        Mail::to($this->details['email'])->send($email);*/
    }
}