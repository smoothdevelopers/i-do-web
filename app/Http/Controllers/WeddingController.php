<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\Wedding;
use App\Http\Requests\Wedding\CreateRequest;
use App\Http\Requests\Wedding\UpdateRequest;
use App\Http\Requests\Wedding\PageRequest;
use App\Http\Requests\Wedding\OneWeddingRequest;
use App\Http\Requests\Wedding\CommentCreateRequest;
use App\Http\Requests\Wedding\CommentDeleteRequest;
use App\Http\Requests\Wedding\CommentUpdateRequest;
use App\Http\Requests\Wedding\CommentsGetRequest;
use App\Http\Requests\Wedding\LikeCreateRequest;
use App\Http\Requests\Wedding\LikeDeleteRequest;
use App\Http\Requests\Wedding\PhotoAuthorizeRequest;
use App\Http\Requests\Wedding\PhotoDeleteRequest;
use App\Http\Requests\Wedding\PhotoSaveRequest;
use App\Http\Requests\Wedding\PhotosGetRequest;

/**
 * @resource Wedding
 *
 * This module will handle creation, access, update and desctruction of
 * weddings :)
 *
 * Note: for a wedding to be created, we only require to have a couple and
 *       these two have to have created an account with us.
 */
class WeddingController extends Controller
{
    /**
     * Create wedding.
     *
     * Note: Auth Required.
     *
     * This will create a wedding between two app users and return the wedding
     * id on success. The user creating the wedding must either be the bride or
     * the groom in the wedding.
     */
    public function create(CreateRequest $request)
    {
        $groom = User::find($request->get('groom_id'));
        $bride = User::find($request->get('bride_id'));

        if (!$groom || !$bride) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'either the groom or bride is not a registered user'
            ], Response::HTTP_NOT_FOUND);
        }

        $userId = Auth::user()->id;
        if ($groom->id != $userId && $bride->id != $userId) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_creation'),
                'error-description' => 'the authenticated user must either be the bride or the groom'
            ], Response::HTTP_NOT_ACCEPTABLE);
        }


        $wedding = new Wedding;
        $wedding->description   = $request->get('description');
        $wedding->venue         = $request->get('venue');
        $wedding->reception     = $request->get('reception');
        $wedding->when          = $request->get('when');
        $wedding->venue_lat     = $request->get('venue_lat');
        $wedding->venue_lng     = $request->get('venue_lng');
        $wedding->reception_lat = $request->get('reception_lat');
        $wedding->reception_lng = $request->get('reception_lng');
        $wedding->privacy       = $request->get('privacy');

        if ($request->hasFile('image')) {

            $fileName = time().'_'.rand(1, 100).'.'. $request->file('image')->guessExtension();
            $request->file('image')->move('uploads', $fileName);
            $wedding->profile_pic = $fileName;
        }

        $wedding->bride()->associate($bride);
        $wedding->groom()->associate($groom);
        $wedding->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'wedding successfully created',
            'id'                => $wedding->id
        ], Response::HTTP_OK);
    }

    /**
     * Update wedding.
     *
     * Note: Auth Required.
     *
     * Updates this users wedding.
     */
    public function update(UpdateRequest $request)
    {
        $wedding = NULL;
        if (! ($wedding = $this->userWedding())) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'this user does not have a wedding'
            ], Response::HTTP_NOT_FOUND);
        }

        $groom = User::find($request->get('groom_id'));
        $bride = User::find($request->get('bride_id'));

        if (!$groom || !$bride) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'either the groom or bride is not a registered user'
            ], Response::HTTP_NOT_FOUND);
        }

        $userId = Auth::user()->id;
        if ($groom->id != $userId && $bride->id != $userId) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'the authenticated user must be one of the wedding couple'
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $wedding->description   = $request->get('description');
        $wedding->venue         = $request->get('venue');
        $wedding->reception     = $request->get('reception');
        $wedding->when          = $request->get('when');
        $wedding->venue_lat     = $request->get('venue_lat');
        $wedding->venue_lng     = $request->get('venue_lng');
        $wedding->reception_lat = $request->get('reception_lat');
        $wedding->reception_lng = $request->get('reception_lng');
        $wedding->privacy       = $request->get('privacy');

        if ($request->hasFile('image')) {

            Storage::delete('storage/uploads/' . $wedding->img_url);
            $fileName = time().'_'.rand(1, 100).'.'. $request->file('image')->guessExtension();
            $request->file('image')->move('uploads', $fileName);
            $wedding->img_url = $fileName;
        }

        $wedding->bride()->associate($bride);
        $wedding->groom()->associate($groom);
        $wedding->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'wedding successfully updated',
            'id'                => $wedding->id,
        ], Response::HTTP_OK);
    }

    /**
     * Close user wedding.
     *
     * Note: Auth Required.
     *
     * Closes the authenticated user's wedding.
     */
    public function close()
    {
        $wedding = NULL;
        if (! ($wedding = $this->userWedding())) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user has no right to change this wedding'
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $wedding->trash();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'wedding successfully closed',
        ], Response::HTTP_OK);
    }

    /**
     * Get this user's wedding.
     *
     * Note: Auth Required.
     *
     * Returns the authenticated user's wedding if found.
     */
    public function get()
    {
        $wedding = NULL;
        if (! ($wedding = $this->userWedding())) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'this user does not have a wedding'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => "successfully retrieved this user's wedding",
            'wedding'           => $wedding->toArray(),
        ], Response::HTTP_OK);
    }

    /**
     * Get public weddings by page.
     *
     * Return all public weddings in pages with every page having 15 weddings.
     */
    public function getPaginated(PageRequest $request)
    {
        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully retrieved weddings',
            'weddings'          => Wedding::where('privacy', '=', config('const.privacy.public'))
                                            ->paginate(config('const.pages.small'))->toArray(),
        ], Response::HTTP_OK);
    }

    /**
     * Get one public wedding by id.
     *
     * Returns a public wedding given its id as a parameter.
     */
    public function getOther(OneWeddingRequest $request)
    {
        $wedding = Wedding::find($request->get('wedding_id'));

        if (! $wedding) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no wedding could be found for this id'
            ], Response::HTTP_NOT_FOUND);
        }

        if ( $wedding->privacy != config('const.privacy.public')) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user has no right to access this wedding'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'wedding found',
            'wedding'           => $wedding->toArray(),
        ], Response::HTTP_OK);
    }


    /**
     * Create a comment
     *
     * Note: Auth Required.
     *
     * Creates a comment on a wedding. The wedding has to be a public
     * wedding or the user has to be an invited user for them to be
     * able to comment on the wedding.
     */
    public function createComment(CommentCreateRequest $request)
    {
        $wedding = NULL;
        if (! ($wedding = Wedding::find($request->get('wedding_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no wedding was found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $authUser = Auth::user();
        $invitedUser = $wedding->invitedUsers()->get()->search(function ($user, $key) {
            return $user->id == $authUser->id;
        });

        if (! ($invitedUser || ($wedding->privacy == config('const.privacy.public')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user has no authority to comment on this wedding',
            ], Response::HTTP_NOT_FOUND);
        }

        $comment = new WeddingComment;
        $comment->comment = $request->get('comment');
        $comment->associate($wedding);
        $comment->associate($authUser);
        $comment->save();
    }

    /**
     * Get comments.
     *
     * Note: Auth Required.
     *
     * Returns comments for a wedding if the user is authorized to
     * view them or the wedding is a public one.
     */
    public function getCommentsPaginated(CommentsGetRequest $request)
    {
        $wedding = NULL;
        if (! ($wedding = Wedding::find($request->get('wedding_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no wedding was found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $authUser = Auth::guard('web')->user()->id;
        $invitedUser = $wedding->invitedUsers()->get()->search(function ($user, $key) {
            return $user->id == $authUser->id;
        });

        if (! ($invitedUser || ($wedding->privacy == config('const.privacy.public')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user has no authority to comment on this wedding',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully retrieved wedding comments',
            'comments'          => $wedding->comments()->paginate(config('const.pages.small'))->toArray(),
        ]);
    }


    /**
     * Update a comment
     *
     * Note: Auth Required.
     *
     * Updates a comment that was previously made by the user
     */
    public function updateComment(CommentUpdateRequest $request)
    {
        $comment = NULL;
        if (! ($comment = Wedding::find($request->get('comment_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.comment_not_found'),
                'error-description' => 'no comment could be found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $authUser = Auth::user();
        if ($comment->user_id != $authUser->id) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user has no authority to comment on this wedding',
            ], Response::HTTP_NOT_FOUND);
        }

        // $invitedUser = $wedding->invitedUsers()->get()->search(function ($user, $key) {
        //     return $user->id == $authUser->id;
        // });
        // if (! ($invitedUser || ($wedding->privacy == config('const.privacy.public')))) {
        //
        // }

        $comment = new WeddingComment;
        $comment->comment = $request->get('comment');
        $comment->associate($wedding);
        $comment->associate($authUser);
        $comment->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'comment successfully created',
        ], Response::HTTP_OK);
    }

    /**
     * Delete a comment
     *
     * Note: Auth Required.
     *
     * Deletes a comment created by the user.
     */
    public function deleteComment(CommentDeleteRequest $request)
    {
        $comment = NULL;
        if (! ($comment = WeddingComment::find($request->get('comment_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no comment could be found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $authUser = Auth::user();
        if ($comment->user_id != $authUser->id) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user has no authority to comment on this wedding',
            ], Response::HTTP_NOT_FOUND);
        }

        $comment->delete();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'comment successfully deleted',
        ], Response::HTTP_OK);
    }

    /**
     * Create a like
     *
     * Note: Auth Required.
     *
     * Creates a like for this wedding by the authenticated user
     */
    public function createLike(LikeCreateRequest $request)
    {
        $wedding = NULL;
        if (! ($wedding = Wedding::find($request->get('wedding_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no wedding was found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $authUser = Auth::user();
        $like = WeddingLike::where('wedding_id', '=', $wedding->id)
                              ->where('user_id', '=', $authUser->id)->first();
        if ($like) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_creation'),
                'error-description' => 'this user already likes this wedding',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $invitedUser = $wedding->invitedUsers()->get()->search(function ($user, $key) {
            return $user->id == $authUser->id;
        });

        if (! ($invitedUser || ($wedding->privacy == config('const.privacy.public')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user has no authority to like this wedding',
            ], Response::HTTP_NOT_FOUND);
        }

        $like = new WeddingLike;
        $like->associate($authUser);
        $like->associate($wedding);
        $like->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'like successfully created',
        ], Response::HTTP_OK);

    }

    /**
     * Remove a like
     *
     * Note: Auth Required.
     *
     * Removes a like on an wedding by the authentcated user.
     */
    public function deleteLike()
    {
        $wedding = NULL;
        if (! ($wedding = Wedding::find($request->get('wedding_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no wedding was found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $authUser = Auth::user();
        $like = WeddingLike::where('wedding_id', '=', $wedding->id)
                              ->where('user_id', '=', $authUser->id)->first();

        if (!$like) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_creation'),
                'error-description' => 'this user has not liked this wedding',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $like->delete();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'like successfully removed',
        ], Response::HTTP_OK);
    }

    /**
     * Save Photo
     *
     * Note: Auth Required.
     *
     * Saves wedding photo for the given wedding by the given user. The
     * user saving the photo must either be the owner of wedding (in which
     * case the photo is saved authorized) or another user with the permission
     * to access this wedding's data (when it is public or when the user
     * is an invited user)
     *
     */
    public function savePhoto(PhotoSaveRequest $request)
    {
        $wedding = NULL;
        if (! ($wedding = Wedding::find($request->get('wedding_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no wedding was found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $user = NULL;
        if (! ($user = User::find($request->get('user_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no user was found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $userOwnsThisWedding = false;
        if ($user->has('weddingAsBride') || $user->has('weddingAsGroom')) {

            $userWedding = $user->weddingAsGroom()->get();
            if (! $userWedding) {
                $userWedding = $user->weddingAsBride()->get();
            }

            $userOwnsThisWedding = ($userWedding->id == $wedding->id);
        }

        $invitedUser = $wedding->invitedUsers()->get()->search(function ($user, $key) {
            return $user->id == $authUser->id;
        });

        if (! ($userOwnsThisWedding
                || $invitedUser
                || $wedding->privacy == config('const.privacy.public'))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_creation'),
                'error-description' => 'this user has no authority to save a photo for this wedding',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $photo = new WeddingPhoto;
        $fileName = time() . "." . $request->file('image')->guessExtension();
        $request->file('image')->move('uploads', $fileName);
        $photo->image_url = $fileName;
        $photo->authorized = $userOwnsThisWedding;
        $photo->associate($wedding);
        $photo->associate($user);
        $photo->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully saved the photo',
        ], Response::HTTP_OK);
    }

    /**
     * Get authorized images paged.
     *
     * Note: Auth Required.
     *
     * Returns a list of authorized images of this wedding if the wedding
     * is public or if the user has permission.
     */
    public function getAuthorizedPhotos(PhotosGetRequest $request)
    {
        $wedding = NULL;
        if (! ($wedding = Wedding::find($request->get('wedding_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no wedding was found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $user = NULL;
        if (! ($user = User::find($request->get('user_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no user was found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $userOwnsThisWedding = false;
        if ($user->has('weddingAsBride') || $user->has('weddingAsGroom')) {

            $userWedding = $user->weddingAsGroom()->get();
            if (! $userWedding) {
                $userWedding = $user->weddingAsBride()->get();
            }

            $userOwnsThisWedding = ($userWedding->id == $wedding->id);
        }

        $invitedUser = $wedding->invitedUsers()->get()->search(function ($user, $key) {
            return $user->id == $authUser->id;
        });

        if (! ($userOwnsThisWedding
                || $invitedUser
                || $wedding->privacy == config('const.privacy.public'))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user has no authority to access this weddings photos',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully authorized retrieved photos',
            'photos'            => $wedding->photos()
                                              ->where('authorized', '=', 1)
                                              ->paginate(config('const.pages.small'))
                                              ->toArray(),
        ], Response::HTTP_OK);
    }


    /**
     * Authorize a photo.
     *
     * Note: Auth Required.
     *
     * Authorize a specific photo that has been posted. The authenticated user
     * must be one of the couples in the wedding.
     */
    public function authorizePhoto(PhotoAuthorizeRequest $request)
    {
        $wedding = NULL;
        if (! ($wedding = $this->userWedding())) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'this user does not have a wedding'
            ], Response::HTTP_NOT_FOUND);
        }

        $photo = NULL;
        if (! ($photo = WeddingPhoto::find($request->get('photo_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no photo was found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($wedding->id != $photo->wedding()->first()->id) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_creation'),
                'error-description' => 'this photo does not belong to this users wedding'
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $photo->authorized = true;
        $photo->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'photo successfully authorized',
        ], Response::HTTP_OK);
    }


    /**
     * Get unauthorized photos paged.
     *
     * Note: Auth Required.
     *
     * Returns a list in pages of images that have not yet been
     * authorized by the couple, but have been posted already. The user
     * accessing them must be one of the couples
     */
    public function getUnauthorizedPhotos(PhotosGetRequest $request)
    {
        $wedding = NULL;
        if (! ($wedding = $this->userWedding())) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'this user does not have a wedding'
            ], Response::HTTP_NOT_FOUND);
        }


        $userOwnsThisWedding = false;
        if ($user->has('weddingAsBride') || $user->has('weddingAsGroom')) {

            $userWedding = $user->weddingAsGroom()->get();
            if (! $userWedding) {
                $userWedding = $user->weddingAsBride()->get();
            }

            $userOwnsThisWedding = ($userWedding->id == $wedding->id);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully retrieved photos',
            'photos'            => $wedding->photos()
                                              ->where('authorized', '=', 0)
                                              ->paginate(config('const.pages.small'))
                                              ->toArray(),
        ], Response::HTTP_OK);
    }

    // /**
    //  * Get unauthorized photos paged.
    //  *
    //  * Note: Auth Required.
    //  *
    //  * Returns a list in pages of images that have not yet been
    //  * authorized by the couple, but have been posted already.
    //  */
    // public function getPhotos()
    // {
    //     return response()->json([
    //         'error'             => false,
    //         'error-code'        => config('const.error.success'),
    //         'error-description' => 'successfully retrieved photos for wedding',
    //         'photos'            => WeddingPhoto::paginate(config('const.pages.small'))->toArray(),
    //     ], Response::HTTP_OK);
    // }

    /**
     * Delete a photo
     *
     * Note: Auth Required.
     *
     * Deletes an photo that has already been posted. The user deleting it
     * is either the person who posted it or the couples.
     */
    public function deletePhoto(PhotoDeleteRequest $request)
    {
        $photo = NULL;
        if (! ($photo = WeddingPhoto::find($request->get('photo_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no photo was found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $id = $request->get('user_id');
        $photoWedding = $photo->wedding()->get();

        if ($photo->user_id != $id && $photoWedding->groom_id != $id
            && $photoWedding->bride_id != $id) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user cannot delete this photo'
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $photo->delete();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'photo successfully deleted',
        ], Response::HTTP_OK);
    }


    /**
 	 * User Wedding
     *
 	 * @return returns this authentcated user's wedding or NULL
	 */
    private function userWedding()
    {
        $user = Auth::user();

        if ($user->has('weddingAsBride') || $user->has('weddingAsGroom')) {

            $wedding = $user->weddingAsBride()->get();
            if ( !$wedding) {
                $wedding = $user->weddingAsGroom()->get();
            }

            return $wedding;
        }
        return NULL;
    }
}
