<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use PHPMailer;
use Illuminate\Support\Facades\Crypt;

class SendReminderEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user =$user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mailer = new PHPMailer(true);

//        $mailer->SMTPDebug = 2; //Alternative to above constant

        $mailer->isSMTP();

        $mailer->CharSet = 'utf-8';

        $mailer->SMTPAuth = true;

        $mailer->SMTPSecure= "ssl";

        $mailer->Host = "smtp.qq.com";

        $mailer->Port = 465;

        $mailer->Username = 'zhangshuaibindiy@qq.com';

        $mailer->Password = 'gxvjoroivmlobcedw';

        $mailer->setFrom('zhangshuaibindiy@qq.com', 'TEST');

        $mailer->Subject = 'Mail test';

        $mailer->MsgHTML('<h1>Mail test ssl</h1>');

        $mailer->addAddress('zhangshuaibin2015@163.com');

        $stat = $mailer->send();

        echo $stat ? 'ok' : 'failed!';
    }

    public function failed()
    {
        echo 'failed';
    }
}

























