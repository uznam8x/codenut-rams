<?php

namespace App\Http\Middleware;

use Closure;

class Mime {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$args) {

        $type = array('json' => 'application/json', 'xml' => 'application/xml', 'text' => 'text/plain',);
        $contentType = $type['json'];

        foreach ($args as $param) {
            $contentType = $type[$param];
        }

        $response = $next( $request );
        $response->headers->set( 'Content-Type', $contentType );
        return $response;
    }
}
