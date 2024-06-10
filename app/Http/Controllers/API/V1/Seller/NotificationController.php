<?php

namespace App\Http\Controllers\API\V1\Seller;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SellerNewOrderNotification;

class NotificationController extends Controller
{
    use ApiResponser;
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        return view('product');
    }

    public function notifications()
    {
        $user = Auth::user();
        $toArray = [];

        foreach ($user->notifications as $key => $notification) {
            $toArray[$key] = $notification->data;
            $toArray[$key]['id'] = $notification->id;
            $toArray[$key]['type'] = $notification->type;
            $toArray[$key]['created_at'] = $notification->created_at;
            $toArray[$key]['read_at'] = $notification->read_at;
        }

        return $this->success(['notifications' => $toArray, 'unread' => $user->unreadNotifications->count()]);
    }

    public function masrkAsRead(Request $request)
    {
        $user = User::find(Auth::id());

        if ($request->has('status') && $request->status == 0) {
            $user->notifications()->delete();
        }

        if ($request->has('status') && $request->has('id')) {
            if ($request->status == 1)
                $user->notifications()->where('id', $request->id)->update(['read_at' => now()]);
            else if ($request->status == 2)
                $user->notifications()->where('id', $request->id)->update(['read_at' => null]);
            else if ($request->status == 3)
                $user->notifications()->where('id', $request->id)->delete();
        }
        $toArray = [];
        foreach ($user->notifications as $key => $notification) {
            $toArray[$key] = $notification->data;
            $toArray[$key]['id'] = $notification->id;
            $toArray[$key]['type'] = $notification->type;
            $toArray[$key]['created_at'] = $notification->created_at;
            $toArray[$key]['read_at'] = $notification->read_at;
        }

        return $this->success(['notifications' => $toArray, 'unread' => $user->unreadNotifications->count()]);
    }

    public function send_notifications(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid request.', 'errors' => $validator->errors()], 404);
        }

        $data = [
            'title' => $request->title,
            'body' => $request->message,
            'url' => $request->url ?? null,
            'order_id' => $request->order_id ?? null,
        ];

        $users = User::all();

        Notification::send($users, new SellerNewOrderNotification($data));
        $this->sendNotifications($data);

        return response()->json(['message' => 'Notfication send successfully.!'], 200);
    }

    public function sendNotifications($data)
    {

        //$user = User::find($request->id);
        $data = [
            //"to" => $user->device_token,
            "notification" =>
            [
                "title" => $data['title'],
                "body" => $data['body'],
                "url" => $data['url'],
                "icon" => url('/logo/logo.png')
            ],
            "condition" => "'all' in topics || 'android' in topics || 'ios' in topics",
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . env('FIREBASE_TOKEN'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        curl_exec($ch);
    }

    public function sendPush(Request $request)
    {
        $user = User::find($request->id);
        $data = [
            "to" => $user->device_token,
            "notification" =>
            [
                "title" => 'Web Push',
                "body" => "Sample Notification",
                "icon" => url('/logo.png')
            ],
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . env('FIRBASE_API_KEY'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        curl_exec($ch);

        return redirect('/home')->with('message', 'Notification sent!');
    }
}
