<?php

namespace App\Addon\vote;

use App\Events\AddonEvent;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Event;
use Mockery\Exception;

class Controller {
    private $field = 'vote';

    public function __construct() {

    }

    public function initialize() {
        Event::listen( 'store.read', function($key) {
            $this->read( $key );
            //echo 'asdf';
        } );
        Event::listen( 'store.view', function($key) {
            $this->view( $key );
            //echo 'asdf';
        } );
        Event::listen( 'store.update', function($event) {
            //echo 'asdf';
        } );
    }

    public function insert($param) {
        if (!Schema::hasColumn( $param->table, $this->field )) {
            Schema::table( $param->table, function(Blueprint $table) {
                $table->integer( $this->field )->default( 0 );
            } );
        }
        return $param;
    }

    /*
    private function insert($param) {
        if(!Schema::hasColumn($param->table, $this->field)){
            Schema::table($param->table, function(Blueprint $table) {
                $table->integer($this->field)->default(0);
            });
        }
        $param->prop[$this->field] = 0;
        return $param->prop;
    }


    public function handle(AddonEvent $event) {
        try{
            return call_user_func(array($this, $event->type), $event->param);
        } catch(Exception $e){
            return $event->param->prop;
        }
    }*/

    public function read($key) {
        echo 'vote.read';
    }

    public function view($key) {
        echo 'vote.view';
    }


    public function info() {
        return array('name' => 'vote', 'description' => 'vote count');
    }
    /*
    public function read() {
        return array('name' => 'vote', 'description' => 'store add vote');
    }*/
}
