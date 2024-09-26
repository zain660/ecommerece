<?php

namespace App\Http\Controllers;

use App\Models\ChatInquiry;
use Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Modules\Product\Entities\Product;
class ChatController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        return view('frontend.amazy.pages.chat.index');
    }

    public function conversation($seller_id, $pro_id)
    {
        $get_seller_details = User::findorfail($seller_id);
        $product_details = Product::findorfail($pro_id);
        return view('frontend.amazy.pages.chat.conversation', get_defined_vars());
    }

    public function send_message(Request $request, $seller_id, $pro_id)
    {
        $get_seller_details = ChatInquiry::where('seller_id', operator: $seller_id)
            ->where('buyer_id', auth()->user()->id)
            ->where('product_id', $pro_id)->first();

        if ($get_seller_details == null) {
            $inquiry = new ChatInquiry();
            $inquiry->user_id = auth()->user()->id;
            $inquiry->seller_id = $seller_id;
            $inquiry->product_id = $pro_id;
            $inquiry->initial_msg = $request->message;
            $inquiry->save();
        } else {
            ChatInquiry::where('seller_id', $seller_id)
                ->where('buyer_id', auth()->user()->id)
                ->where('product_id', $pro_id)->update(array(
                        'initial_msg' => $request->message,
            ));
        }
        $convo_link = asset('chat/conversation/'.$seller_id.'/'.$pro_id.'');

        $this->send_message_to_user([ 
            "message" => $request->message,
            "user_id" => auth()->user()->id,
            "id" => $seller_id,
            "link" => $convo_link,
            "username" => Auth::user()->name,
            "files" => '',
            "file_type" => '',
        ]); 
        return response()->json(['status' => 'success']);
    }
}
