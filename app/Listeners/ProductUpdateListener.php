<?php

namespace App\Listeners;

use App\Events\ProductUpdateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProductUpdateListener
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ProductUpdateEvent  $event
     * @return void
     */
    public function handle(ProductUpdateEvent $event)
    {
        $product = $event->product;
        if ($product->stock > 0) {
            $subscriptions = $product->userSubscription()->take($product->stock)->get();

            foreach ($subscriptions as $subscrip) {
                $user = $subscrip->user;
                
                /*
                * commented while there is no mail server configured 
                *//*
                \Mail::raw('Stock of Product ' . $event->product->name . ' aviable ', function ($message)  use ($user) {
                    $message
                        ->to($user->email)
                        ->subject('Notification from Api Rest');
                });*/
            }

            $product->userSubscription()->take($product->stock)->delete();
        }
    }
}
