<?php

namespace App\Http\Middleware;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;

use Closure;

class CleanJsonResponse
{
    /**
     * Handle an outgoing response
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response->headers->get('Content-Type') == 'application/json') {

            $array = $response->original;

            if (isset($array['is_dirty']) && $array['is_dirty'] ) {

                if (isset($array['errors'])) {

                    $errorArray = $array['errors']->toArray();

                    $errorCodes = reset($errorArray);
                    $errorField = key($errorArray);

                    $newResponse = [
                        'error'             => true,
                        'error-code'        => (int)$errorCodes[0],
                        'error-field'       => $errorField,
                        'error-description' => $errorField . config('const.validation.messages.'.$errorCodes[0]),
                    ];

                    if (isset($array['debug'])) {
                        $newResponse['debug'] = $array['debug'];
                    }

                    return response()->json($newResponse, Response::HTTP_NOT_ACCEPTABLE);

                } else {

                    $newResponse = [
                        'error'             => true,
                        'error-code'        => 1000,
                        'error-field'       => '',
                        'error-description' => $array['message'] . ' Please check the docs.',
                    ];

                    if (isset($array['debug'])) {
                        $newResponse['debug'] = $array['debug'];
                    }

                    return response()->json($newResponse, Response::HTTP_NOT_ACCEPTABLE);
                }
            }
        }
        return $response;
    }
}
