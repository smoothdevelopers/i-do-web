<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

use App\WeddingPhoto;
use App\Http\Requests\WeddingPhoto\SaveRequest;
use App\Http\Requests\WeddingPhoto\AuthorizeRequest;

class PhotoController extends Controller
{
    public function save(SaveRequest $request)
    {
        $photo = new WeddingPhoto;

        $fileName = time() . "." . $request->file('image')->guessExtension();
        $request->file('image')->move('uploads', $fileName);
        $photo->image_url = $fileName;
        $photo->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully saved the photo',
        ], Response::HTTP_OK);
    }

    public function getAuthorized(Request $request)
    {
        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully authorized retrieved photos',
            'photos'            => WeddingPhoto::where('authorized', '=', '1')
                                                ->paginate(config('const.pages.small'))->toArray(),
        ], Response::HTTP_OK);
    }

    public function authorize(AuthorizeRequest $request)
    {

    }

    public function getUnauthorized(Request $request)
    {
        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully retrieved photos',
            'photos'            => WeddingPhoto::where('authorized', '=', '0')
                                                ->paginate(config('const.pages.small'))->toArray(),
        ], Response::HTTP_OK);
    }

    public function getForWedding()
    {
        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully retrieved photos for wedding',
            'photos'            => WeddingPhoto::paginate(config('const.pages.small'))->toArray(),
        ], Response::HTTP_OK);
    }

    public function delete()
    {

    }
}
