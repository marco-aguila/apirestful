<?php

namespace App\Providers;

use App\User;

use App\Product;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        
        //Evento para mandar un email a usuarios creados y verificacion del mismo
        User::created( function($user) {
        retry(5, function() use ($user) {
             Mail::to($user)->send(new UserCreated($user));
             },100);
        });
         //Evento para mandar un email si cambio el correo
         User::updated( function($user) {

            if ($user->isDirty('email')) {
                retry(5, function() use ($user) {
                    Mail::to($user)->send(new UserMailChanged($user));
                    },100);
            }
          });

        //Evento para cambiar el estado de un producto cuando su existencia ya no sea mayor a uno y cambiarlo como PRODUCT_NO_DISPCNIBLE
        Product::updated( function($product) {
            if ($product->quantity == 0 && $product->estaDisponible()) {
                $product->status = Product::PRODUCTO_NO_DISPONIBLE;

                $product->save();
            }
        });

    }
}
