<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FCMNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userIds;
    public $title;
    public $body;
    public $data;
    public $click_action;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userIds, $title, $body, $data, $click_action = null)
    {
        $this->userIds = $userIds;
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
        $this->click_action = $click_action;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{

            $registration_ids = User::whereIn('id', $this->userIds)->pluck('fcm_key')->toArray();

            if(empty($registration_ids)) return false;

            $fields = [
                'registration_ids' => $registration_ids,
                'notification' => array_merge(
                    ['title' => $this->title, 'body' => $this->body],
                    $this->click_action ? ['click_action' => $this-> click_action] : [],
                ),
                'data' => $this->data
            ];

            $headers = array(
                'Content-Type:application/json',
                'Authorization:key='.config('services.firebase.server_key')
            );

            $ch = curl_init('https://fcm.googleapis.com/fcm/send');

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            $result = curl_exec($ch);

            if ($result === FALSE) {
                log::info('FCM Send Error: ', [curl_error($ch)]);
                return false;
            }

            curl_close($ch);

             log::info('FCM response: ', [$result]);

            return true;

        } catch (\Exception $ex) {
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return response()->json([
                'status_code' => 500,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 500);
        }
    }
}
