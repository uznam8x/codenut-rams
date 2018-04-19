<?php

namespace App\Addon\visit;

use App\Events\AddonEvent;
use App\Helper\Announcement;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Event;
use Mockery\Exception;

class Controller {
    private $field = 'visit';

    public function __construct() {

    }

    public function initialize() {
        Event::listen( 'addon.activate', array($this, 'activate') );
        Event::listen( 'addon.deactivate', array($this, 'deactivate') );
        Event::listen( 'store.view', array($this, 'view') );
    }

    public function activate($key) {
        $data = Announcement::get($key);
        if( $data->addon === 'visit' ){
            if (!Schema::hasColumn( 'cr_store_'.$data->store, $this->field )) {
                Schema::table( 'cr_store_'.$data->store, function(Blueprint $table) {
                    $table->integer( $this->field )->default( 0 );
                } );
            }
        }
    }

    public function deactivate($key){
        $data = Announcement::get($key);
        if( $data->addon === 'visit' ){
            if (Schema::hasColumn( 'cr_store_'.$data->store, $this->field )) {
                Schema::table( 'cr_store_'.$data->store, function(Blueprint $table) {
                    $table->dropColumn($this->field);
                } );
            }
        }
    }

    public function info() {
        return array('name' => 'visit', 'description' => 'visit count');
    }

    public function view($key) {
        $data = Announcement::get( $key );
        $table = 'cr_store_'.$data['store'];
        $query = DB::table( $table );
        $row = $query->select( 'visit' )->where( 'xid', $data['xid'] )->first();
        $cnt = $row->visit + 1;
        $query->update( ['visit' => $cnt] );
    }

    public function delete() {

    }
}
