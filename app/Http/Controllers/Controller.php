<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function ok($items = null)
    {
        return response()->json($items)->setEncodingOptions(JSON_NUMERIC_CHECK);
    }


    public function success($items = null, $status = 200)
    {
        $data = ['status' => 'success'];

        if ($items instanceof Arrayable) {
            $items = $items->toArray();
        }

        if ($items) {
            foreach ($items as $key => $item) {
                $data[$key] = $item;
            }
        }
        return response()->json($data, $status)->setEncodingOptions(JSON_NUMERIC_CHECK);
    }

    public function error($items = null, $status = 500)
    {
        $data = array();

        if ($items) {
            foreach ($items as $key => $item) {
                $data['errors'][$key][] = $item;
            }
        }

        return response()->json($data, $status)->setEncodingOptions(JSON_NUMERIC_CHECK);
    }


    public function send_message_to_user($data = [])
    {
        //    dd($data);
        date_default_timezone_set("asia/karachi");
        $return_array = [];

        $Notification_types = array($data['id'] => Auth::id(), Auth::id() => $data['id']);
        foreach ($Notification_types as $firstkey => $secondKey) {
            $user = User::find($firstkey);
            $user_sec = User::find($secondKey);
            //  dd($user,$user_sec);

            $ch = curl_init();
            $carbon = Carbon::now();
            // echo $data['user_id'] . "\n" .Auth::id() . "    ";
            $data_json = '{
                "text": "' . $data['message'] . '",
                "user_id":"' . Auth::user()->id . '" ,
                "link": "' . $data['link'] . '",
                "username": "' . Auth::user()->name . '",
                "files": "' . $data['files'] . '",
                "file_type":"' . $data['file_type'] . '",
                "date": "' . $carbon->format('d-m-Y h:i A') . '"
            }';
            // dd($data);
            array_push($return_array, [$secondKey => $data_json]);

            curl_setopt($ch, CURLOPT_URL, "https://joshua-5cd76-default-rtdb.firebaseio.com/user_id_" . $secondKey . "/messages/user_id_" . $firstkey . "/" . $carbon->format('YmdGis') . ".json");
            // dd($data,$text,$link,$User_id,$notification_id,$lead_type);
            $server_key = 'AIzaSyDmwVnCg4QfnIY8QiHN7aXE10HMAynLAJg';
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key=' . $server_key
            );
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            $res = curl_exec($ch);
            $httpCode = curl_getinfo($ch);
            // dd($res);
            curl_close($ch);
        }

        //dd($return_array);
        $user = User::find($data['id']);
        $this->send_notification_to_user(['user_id' => $data['id'], 'message' => Auth::user()->name . " Send You a message", 'url' => '/Conversation/' . $user_sec->id . '/' . $user_sec->name]);


        return $res;
    }

    public function send_notification_to_user($data = [])
    {
        // dd($data);

        date_default_timezone_set("asia/karachi");

        $Notification_types = array('web_notification', 'app_notification');
        foreach ($Notification_types as $types) {

            $ch = curl_init();
            $carbon = Carbon::now();
            // echo $data['user_id'] . "\n" .Auth::id() . "    ";
            $data_json = '{"text": "' . $data['message'] . '","user_id": "' . $data['user_id'] . '" ,"url": "' . $data['url'] . '", "date": "' . $carbon->format('d-m-Y h:i A') . '"}';
            // dd($data);

            curl_setopt($ch, CURLOPT_URL, "https://joshua-5cd76-default-rtdb.firebaseio.com/user_id_" . $data['user_id'] . "/" . $types . "/" . $carbon->format('YmdGis') . ".json");
            // dd($data,$text,$link,$User_id,$notification_id,$lead_type);
            $server_key = 'AIzaSyDmwVnCg4QfnIY8QiHN7aXE10HMAynLAJg';
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key=' . $server_key
            );
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
            $res = curl_exec($ch);
            $httpCode = curl_getinfo($ch);
            // dd($res);
            curl_close($ch);
        }

        //dd($res);

        return $res;
    }
}
