<?php

namespace App\Addon\visit;

use App\Events\AddonEvent;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery\Exception;

class Controller {
    private $field = 'visit';

    public function __construct() {

    }

    public function register($param) {
        if (!Schema::hasColumn( $param->table, $this->field )) {
            Schema::table( $param->table, function(Blueprint $table) {
                $table->integer( $this->field )->default( 0 );
            } );
        }
        return (object)array('code' => 200);
    }

    public function info() {
        return array('name' => 'visit', 'description' => 'visit count');
    }

    public function create() {

    }

    public function read() {

    }

    public function update() {

    }

    public function view($table, $xid) {
        $query = DB::table( $table );
        $row = $query->select( 'visit' )->where( 'xid', $xid )->first();
        $cnt = $row->visit + 1;
        $query->update( ['visit' => $cnt] );
    }

    public function delete() {

    }
}
