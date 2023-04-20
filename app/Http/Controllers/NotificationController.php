<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private $limit = 20;
    private $limitUnreadNotifications = 8;
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
     * Lista las notificationes no leidas
     */
    public function listUnreadNotifications()
    {
        //
        $count_notifications = auth()->user()->noticationsReceiveUnread()->get()->count();
        $exceeds_max = $count_notifications > $this->limitUnreadNotifications ? true : false;
        $excess = $exceeds_max ? $count_notifications - $this->limitUnreadNotifications : 0;

        return response()->json([
            'state' => true,
            'notifications' =>  auth()->user()
                                      ->noticationsReceiveUnreadWithLimit(0,$this->limitUnreadNotifications)
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
            'count_notifications' => $count_notifications,
            'exceeds_max' => $exceeds_max,
            'excess' => $excess,
        ],200);
    }


    /**
     * Marca una notificacion como leida (cambia el estado)
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