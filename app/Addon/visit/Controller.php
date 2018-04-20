<?php

namespace App\Addon\visit;

use App\Events\AddonEvent;
use App\Helper\Action;
use App\Helper\Announcement;
use App\Helper\Data;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Event;
use Mockery\Exception;

class Controller {
    private $field = 'visit';

    public function __construct() {
        $this->initialize();
    }
    public function info() {
        return array('name' => 'visit', 'description' => 'visit count');
    }

    public function initialize() {
        Action::listen( 'addon.activate', array($this, 'activate') );
        Action::listen( 'addon.deactivate', array($this, 'deactivate') );
        Action::listen( 'store.view', array($this, 'view') );
    }

    public function activate($key) {
        $data = Data::get( $key );
        if (!Schema::hasColumn( 'cr_store_' . $data->store, $this->field )) {
            Schema::table( 'cr_store_' . $data->store, function(Blueprint $table) {
                $table->integer( $this->field )->default( 0 );
            } );
        }
    }

    public function deactivate($key) {
        $data = Data::get( $key );
        if (Schema::hasColumn( 'cr_store_' . $data->store, $this->field )) {
            Schema::table( 'cr_store_' . $data->store, function(Blueprint $table) {
                $table->dropColumn( $this->field );
            } );
        }
    }



    public function view($key) {
        $data = Data::get( $key );
        $table = 'cr_store_' . $data->store;
        $query = DB::table( $table );
        $row = $query->select( 'visit' )->where( 'xid', $data->xid )->first();
        $cnt = $row->visit + 1;
        $query->update( ['visit' => $cnt] );
    }

    public function delete() {

    }
}
