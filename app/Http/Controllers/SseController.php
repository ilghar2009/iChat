<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SseController extends Controller
{
    public function stream(){
        header('Content-Type:text/event-stream');
        header('Cache-Control:no-cache');
        header('Connection:keep-alive');

        while(true){
            if(connection_aborted()){
                break;
            }
        }
    }
}
