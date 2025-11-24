<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function subscriber()
    {
        $subscribers = Subscriber::orderBy('created_at')->get();
        return view('admin.Subscribers.subscriber', compact('subscribers'));
    }
}