<?php

namespace App\Http\Controllers;

use App\Events\OrderShipped;
use App\Http\Controllers\Controller;

class OrderController extends Controller {

    /**
     * Ship the given order.
     *
     * @param  int  $orderId
     * @return Response
     */
    public function ship( $orderId = 1 ) {

        $ev = broadcast( new \App\Events\Event() );
//         dd($ev);
        $ev = broadcast( new \App\Events\EventName() );
//         dd($ev);
        $ev = broadcast( new OrderShipped( 1 ) );
        dd($ev);

        $roomId = 1;

        $data = [
            'event' => $roomId,
            'data' => [
                'power'     => 1,
                'message'   => date('r'),
            ]
        ];

        $ev = \Redis::publish( 'room', json_encode( $data ) );

        return "event fired";

    }

    public function saveMessage() {

        $roomId     = request()->post('room_id');
        $message    = request()->post('message');

        $saved = (object)[
            'message_id'    => rand(1,1000),
            'user_id'       => rand(1,1000),
            'message'       => $message,
            'room_id'       => $roomId,
            'posted'        => date('r'),
        ];

        $user = (object)[
            'user_id'       => $saved->user_id,
            'username'      => "someone-".rand(1,1000),
        ];

        $data = [
            'event' => $saved->room_id,
            'data' => [
                'saved' => $saved,
                'user'  => $user,
            ]
        ];

        $ev = \Redis::publish( 'room', json_encode( $data ) );

        return "good";

    }

}