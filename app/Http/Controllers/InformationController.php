<?php

namespace App\Http\Controllers;

use App\Information;
use Illuminate\Http\Request;

/**
 * @resource Information
 *
 * This module handles requests to I Do App information requests
 *
 */
class InformationController extends Controller
{
    /**
     * About Information
     *
     * Retrive about us information HTML
     */
    public function about()
    {
        $info = Information::where('key' , '=', config('const.info_keys.about'))->first();
        if (! $info) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'could not retrieve information',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully retrieved information',
            'data' => $info,
        ]);
    }

    /**
     * Terms and Conditions
     *
     * Retrive terms and conditions information HTML
     */
    public function termsAndConditions()
    {
        $info = Information::where('key' , '=', config('const.info_keys.terms'))->first();
        if (! $info) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'could not retrieve information',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully retrieved information',
            'data' => $info,
        ]);
    }

    /**
     * Privacy Policy
     *
     * Retrive privacy policy information HTML
     */
    public function privacyPolicy()
    {
        $info = Information::where('key' , '=', config('const.info_keys.policy'))->first();
        if (! $info) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'could not retrieve information',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully retrieved information',
            'data' => $info,
        ]);
    }
}
