<?php

namespace App\Providers;

use App\Helper\Addon;
use Illuminate\Support\ServiceProvider;

class AddonProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        foreach (glob( app_path( 'Addon' ) . '/*' ) as $path) {
            $name = str_replace( app_path( 'Addon/' ), '', $path );
            $instance = "App\Addon\\${name}\Controller";
            $addon = new $instance();
            Addon::set( $name, array('instance' => $instance, 'info' => $addon->info()) );
            $addon->initialize();
        }
    }
}
