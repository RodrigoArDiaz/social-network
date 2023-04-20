<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private $limit = 20;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('notifications' , ['notifications' => $user->noticationsReceiveWithLimit(0,20)
                                                               ->get()
                                                               ->each(function($notification){
                                                                    $now = \Carbon\Carbon::now();
                                                                    $notification['time_diference'] = str_replace('before', 'ago', Carbon::parse($notification['created_at'])->diffForHumans($now));
                                                                    switch ($notification['type']) {
                                                                        case 'UF':
                                                                            $notification['route_redirect'] = route('posts', $notification['user_id_send']);
                                                                            break;
                                                                        case 'UC':
                                                                            $notification['route_redirect'] = route('posts', $notification['user_id_send']);
                                                                            break;
                                                                        case 'PC':
                                                                            $notification['route_redirect'] = route('post.show', $notification['post_id']);
                                                                            break;
                                                                        case 'PL':
                                                                            $notification['route_redirect'] = route('post.show', $notification['post_id']);
                                                                            break;
                                                                        default:
                                                                            # code...
                                                                            break;
                                                                    }
                                                                })
                                                                ->load('userSend')
                                                                ->toJson()
                                                                ,]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function moreResults($page_number)
    {
        $user = auth()->user();

        $notifications = $user->noticationsReceiveWithLimit(($page_number - 1)*$this->limit, $this->limit)
                                ->get()
                                ->each(function($notification){
                                    $now = \Carbon\Carbon::now();
                                    $notification['time_diference'] = str_replace('before', 'ago', Carbon::parse($notification['created_at'])->diffForHumans($now));
                                    switch ($notification['type']) {
                                        case 'UF':
                                            $notification['route_redirect'] = route('posts', $notification['user_id_send']);
                                            break;
                                        case 'UC':
                                            $notification['route_redirect'] = route('posts', $notification['user_id_send']);
                                            break;
                                        case 'PC':
                                            $notification['route_redirect'] = route('post.show', $notification['post_id']);
                                            break;
                                        case 'PL':
                                            $notification['route_redirect'] = route('post.show', $notification['post_id']);
                                            break;
                                        default:
                                            # code...
                                            break;
                                    }
                                })
                                ->load('userSend');

        //Retorno de json
        return response()->json([
            'state' => true,
            'notifications' =>   $notifications,
        ],200);
    }

    /**
     * Lista las notificationes
     */
    public function listUnreadNotifications()
    {
        return response()->json([
            'state' => true,
            'notifications' =>  auth()->user()
                                      ->noticationsReceiveUnreadWithLimit(0,8)
                                      ->get()
                                      ->each(function($notification){
                                        $now = \Carbon\Carbon::now();
                                        $notification['time_diference'] = str_replace('before', 'ago', Carbon::parse($notification['created_at'])->diffForHumans($now));
                                        switch ($notification['type']) {
                                            case 'UF':
                                                $notification['route_redirect'] = route('posts', $notification['user_id_send']);
                                                break;
                                            case 'UC':
                                                $notification['route_redirect'] = route('posts', $notification['user_id_send']);
                                                break;
                                            case 'PC':
                                                $notification['route_redirect'] = route('post.show', $notification['post_id']);
                                                break;
                                            case 'PL':
                                                $notification['route_redirect'] = route('post.show', $notification['post_id']);
                                                break;
                                            default:
                                                # code...
                                                break;
                                        }
                                      })
                                      ->load('userSend'),
            'count_notifications' => auth()->user()->noticationsReceive()->get()->count(),
        ],200);
    }


    /**
     *
     */
    public function read($notification_id)
    {
        $notification = Notification::find($notification_id);
        $notification->state = 'R';
        $notification->save();
        $notification->touch();
        return response()->json([
                                'state' => true,
                                'message' => 'Notification read',
        ],200);
    }
}