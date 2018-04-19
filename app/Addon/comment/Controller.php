<?php

namespace App\Addon\comment;

use App\Events\AddonEvent;
use App\Helper\Announcement;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery\Exception;
use Illuminate\Support\Facades\Event;

class Controller {
    private $field = 'comment';

    public function __construct() {

    }

    public function initialize() {
        Event::listen( 'store.update', array($this, 'update') );
    }

    public function register($param) {
        if (!Schema::hasColumn( $param->table, $this->field . '_cnt' )) {
            Schema::table( $param->table, function(Blueprint $table) {
                $table->integer( 'comment_cnt' )->default( 0 );
            } );

            Schema::create( $param->table . '_' . $this->field, function(Blueprint $table) {
                $table->increments( 'xid' );
                $table->integer( 'pid' );
                $table->string( 'email' );
                $table->mediumText( 'content' );
                $table->timestamps();
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

    protected function update($key) {
        $data = Announcement::get( $key );
        var_dump( $data );
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
