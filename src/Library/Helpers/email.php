<?php
use BlackCart\Core\Mail\SendMail;
use BlackCart\Core\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Mail;

/**
 * Function send mail
 * Mail queue to run need setting crontab for php artisan schedule:run
 *
 * @param   [string]  $view            Path to view
 * @param   array     $dataView        Content send to view
 * @param   array     $emailConfig     to, cc, bbc, subject..
 * @param   array     $attach      Attach file
 *
 * @return  mixed
 */
function bc_send_mail($view, array $dataView = [], array $emailConfig = [], array $attach = [])
{
    if (!empty(bc_config('email_action_mode'))) {
        if (!empty(bc_config('email_action_queue'))) {
            dispatch(new SendEmailJob($view, $dataView,  $emailConfig, $attach));
        } else {
            bc_process_send_mail($view, $dataView,  $emailConfig, $attach);
        }
    } else {
        return false;
    }
}

/**
 * Process send mail
 *
 * @param   [type]  $view         [$view description]
 * @param   array   $dataView     [$dataView description]
 * @param   array   $emailConfig  [$emailConfig description]
 * @param   array   $attach       [$attach description]
 *
 * @return  [][][]                [return description]
 */
function bc_process_send_mail($view, array $dataView = [], array $emailConfig = [], array $attach = []) {
    try {

        $status = Mail::send(new SendMail($view, $dataView, $emailConfig, $attach));
        if ($status) {
            dd($status);
        }
    } catch (\Throwable $e) {
        bc_report("Sendmail view:" . $view . PHP_EOL . $e->getMessage());
    }
}
