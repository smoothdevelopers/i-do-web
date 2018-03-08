<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

use App\User;
use App\Engagement;
use App\EngagementPhoto;
use App\Http\Requests\Engagement\CreateRequest;
use App\Http\Requests\Engagement\OneEngagementRequest;
use App\Http\Requests\Engagement\PageRequest;
use App\Http\Requests\Engagement\UpdateRequest;
use App\Http\Requests\Engagement\CommentCreateRequest;
use App\Http\Requests\Engagement\CommentDeleteRequest;
use App\Http\Requests\Engagement\CommentUpdateRequest;
use App\Http\Requests\Engagement\CommentsGetRequest;
use App\Http\Requests\Engagement\LikeCreateRequest;
use App\Http\Requests\Engagement\LikeDeleteRequest;
use App\Http\Requests\Engagement\PhotoAuthorizeRequest;
use App\Http\Requests\Engagement\PhotoDeleteRequest;
use App\Http\Requests\Engagement\PhotoSaveRequest;
use App\Http\Requests\Engagement\PhotosGetRequest;



/**
 * @resource Engagement
 *
 * This module handles the creation, updating and deletion of
 * engagements.
 * The following represents the different options for an engagement:
 * 
 * ### Culture
 * | Culture     | Value|
 * |-------------|-------------:|
 * | christian   | 0|
 * | hindu       | 1|
 * | muslim      | 2|
 * | traditional | 3|
 *  
 * ### Privacy
 * | Privacy     | Value|
 * |-------------|-------------:|
 * | private     | 0|
 * | invited     | 1|
 * | public      | 2|
 *
 * ### Acceptance
 * | Privacy     | Value|
 * |-------------|-------------:|
 * | pending     | 0|
 * | rejected    | 1|
 * | accepted    | 2|
 * 
 * For privacy, the invited value indicates that only the invited guests have access
 * to seeing this engagement.
 *
 * The acceptance holds the state of the engagement after creation, this will allow
 * the user involved in the engagement to accept it or reject it.
 * 
 */
class EngagementController extends Controller
{

    /**
     * Create engagement.
     *
     * Note: Auth Required.
     *
     * This will create an engagement between two app users and return the engagement
     * id on success. The user creating the engagement must either be the bride or
     * the groom in the engagement.
     *
     *
     */
    public function create(CreateRequest $request)
    {
        $groom = null;
        $bride = null;
        $is_surprise = true;
        $userId = Auth::user()->id;
        if (($request->has('groom_id') && !$request->has('bride_id'))) {

            if (! $request->has('surprise_other')) {

                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.error.unauthorized_creation'),
                    'error-description' => 'there must be a couple to create an engagement, either the name for one or ids for both',
                ], Response::HTTP_NOT_ACCEPTABLE);
            }

            $groom = User::find($request->get('groom_id'));
            if (!$groom) {
                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.error.user_not_found'),
                    'error-description' => 'could not find the user creating the engagement',
                ], Response::HTTP_NOT_FOUND);   
            }
            
            if ($groom->id != $userId) {

                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.error.unauthorized_creation'),
                    'error-description' => 'the authenticated user must be one of the engaged couple'
                ], Response::HTTP_NOT_ACCEPTABLE);
            }

            if ($this->userEngagement() != NULL) {

                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.error.already_created'),
                    'error-description' => 'this user already has an engagement'
                ], Response::HTTP_NOT_ACCEPTABLE);
            }

        } elseif (($request->has('bride_id') && !$request->has('groom_id'))) {

            if (! $request->has('surprise_other')) {

                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.error.unauthorized_creation'),
                    'error-description' => 'there must be a couple to create an engagement, either the name for one or ids for both',
                ], Response::HTTP_NOT_ACCEPTABLE);
            }

            $bride = User::find($request->get('bride_id'));
            if (! $bride) {
                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.error.user_not_found'),
                    'error-description' => 'could not find the user creating the engagement',
                ], Response::HTTP_NOT_FOUND);   
            }

            if ($bride->id != $userId) {

                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.error.unauthorized_creation'),
                    'error-description' => 'the authenticated user must be one of the engaged couple'
                ], Response::HTTP_NOT_ACCEPTABLE);
            }
        } else {
            
            $groom = User::find($request->get('groom_id'));
            $bride = User::find($request->get('bride_id'));

            if (!$groom || !$bride) {

                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.error.user_not_found'),
                    'error-description' => 'either the groom or bride is not a registered user'
                ], Response::HTTP_NOT_FOUND);
            }

            if ($this->userEngagement($bride) || $this->userEngagement($groom)) {

                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.error.already_created'),
                    'error-description' => 'one of the couple already has an engagement',
                ], Response::HTTP_NOT_ACCEPTABLE);
            }

            $is_surprise = false;
        }

        $engagement = new Engagement($request->except(['image']));
        $engagement->is_surprise = $is_surprise;
        $engagement->creator_id = $userId;

        if ($request->hasFile('image')) {
            $engagement->addMedia($request->file('image')->path())->toMediaCollection('image');
        }

        if ($bride) $engagement->bride()->associate($bride);
        if ($groom) $engagement->groom()->associate($groom);
        $engagement->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully saved engagement',
            'id'                => $engagement->id,
        ], Response::HTTP_OK);
    }

    /**
     * Update engagement.
     *
     * Note: Auth Required.
     *
     * Updates this users engagement.
     */
    public function update(UpdateRequest $request)
    {
        $engagement = null;
        if (! ($engagement = $this->userEngagement())) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'this user does not have an engagement'
            ], Response::HTTP_NOT_FOUND);
        }

        $is_surprise = true;        
        $userId = Auth::user()->id;        
        if (($request->has('groom_id') && !$request->has('bride_id'))) {

            if (! $request->has('surprise_other')) {

                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.error.unauthorized_creation'),
                    'error-description' => 'there must be a couple to update an engagement, either the name for one or ids for both',
                ], Response::HTTP_NOT_ACCEPTABLE);
            }

            $groom = User::find($request->get('groom_id'));
            if (!$groom) {
                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.error.user_not_found'),
                    'error-description' => 'could not find the user updating the engagement',
                ], Response::HTTP_NOT_FOUND);   
            }
            
            if ($groom->id != $userId) {

                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.error.unauthorized_creation'),
                    'error-description' => 'the authenticated user must be one of the engaged couple'
                ], Response::HTTP_NOT_ACCEPTABLE);
            }

        } elseif (($request->has('bride_id') && !$request->has('groom_id'))) {

            if (! $request->has('surprise_other')) {

                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.error.unauthorized_creation'),
                    'error-description' => 'there must be a couple to create an engagement, either the name for one or ids for both',
                ], Response::HTTP_NOT_ACCEPTABLE);
            }

            $bride = User::find($request->get('bride_id'));
            if (! $bride) {
                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.error.user_not_found'),
                    'error-description' => 'could not find the user updating the engagement',
                ], Response::HTTP_NOT_FOUND);   
            }

            if ($bride->id != $userId) {

                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.error.unauthorized_creation'),
                    'error-description' => 'the authenticated user must be one of the engaged couple'
                ], Response::HTTP_NOT_ACCEPTABLE);
            }


        } else {
            
            $groom = User::find($request->get('groom_id'));
            $bride = User::find($request->get('bride_id'));

            if (!$groom || !$bride) {

                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.error.user_not_found'),
                    'error-description' => 'either the groom or bride is not a registered user'
                ], Response::HTTP_NOT_FOUND);
            }

            if ($this->userEngagement($bride) || $this->userEngagement($groom)) {

                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.error.already_created'),
                    'error-description' => 'one of the couple already has an engagement',
                ], Response::HTTP_NOT_ACCEPTABLE);
            }

            $is_surprise = false;
        }

        $engagement->update($request->except(['image', 'creator_id']));
        $engagement->is_surprise = $is_surprise;
    
        if ($request->hasFile('image')) {
            $engagement->clearMediaCollection('image');
            $engagement->addMedia($request->file('image')->path())->toMediaCollection('image');
        }

        if ($bride) $engagement->bride()->associate($bride);
        if ($groom) $engagement->groom()->associate($groom);
        $engagement->update();


        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully updated engagement',
            'id'                => $engagement->id,
        ], Response::HTTP_OK);

    }

    /**
     * Get public engagements by page.
     *
     * Return all public accepted engagements in pages with every page having 15 engagements.
     */
    public function getPaginated(PageRequest $request)
    {
        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully retrieved engagements',
            'engagements'       => Engagement::where('privacy', '=', config('const.privacy.public'))
                                             ->where('status', '=', config('const.status.accepted'))
                                             ->paginate(config('const.pages.small'))->toArray(),
        ], Response::HTTP_OK);
    }

    /**
     * Get one public engagement by id.
     *
     * Returns a public accepted engagement given its id as a parameter.
     */
    public function getOther(OneEngagementRequest $request)
    {
        $engagement = Engagement::find($request->get('engagement_id'));

        if (! $engagement) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no engagement could be found for this id'
            ], Response::HTTP_NOT_FOUND);
        }

        if ( $engagement->privacy != config('const.privacy.public')) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user has no right to access this engagement'
            ], Response::HTTP_NOT_FOUND);
        }

        $engData = $engagement->toArray();
        $engData['image'] = $engagement->getFirsMediaUrl('image');
        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'engagement found',
            'engagement'        => $engData,
        ], Response::HTTP_OK);

    }

    /**
     * Close user's engagement.
     *
     * Note: Auth Required.
     *
     * Closes the authenticated user's engagement.
     */
    public function close()
    {
        $engagement = NULL;

        if (! ($engagement = $this->userEngagement())) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user has no right to change this engagement'
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
        $engagement->trash();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'engagement successfully closed',
        ], Response::HTTP_OK);
    }


    /**
     * Get this user's engagement.
     *
     * Note: Auth Required.
     *
     * Returns the authenticated user's engagement if found.
     */
    public function get()
    {
        $engagement = NULL;
        if (! ($engagement = $this->userEngagement())) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'this user does not have an engagement'
            ], Response::HTTP_NOT_FOUND);
        }

        $engData = $engagement->toArray();
        $engData['image'] = $engagement->getFirstMediaUrl('image');
        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => "successfully retrieved this user's engagement",
            'engagement'        => $engData,
        ], Response::HTTP_OK);

    }

    /**
     * Create a comment
     *
     * Note: Auth Required.
     *
     * Creates a comment on an engagement. The engagement has to be a public
     * engagement or the user has to be an invited user for them to be
     * able to comment on the engagement.
     */
    public function createComment(CommentCreateRequest $request)
    {
        $engagement = NULL;
        if (! ($engagement = Engagement::find($request->get('engagement_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no engagement was found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $authUser = Auth::user();
        $invitedUser = $engagement->invitedUsers()->get()->search(function ($user, $key) {
            return $user->id == $authUser->id;
        });

        if (! ($invitedUser || ($engagement->privacy == config('const.privacy.public')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user has no authority to comment on this engagement',
            ], Response::HTTP_NOT_FOUND);
        }

        $comment = new EngagementComment;
        $comment->comment = $request->get('comment');
        $comment->engagement()->associate($engagement);
        $comment->user()->associate($authUser);
        $comment->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully updated comment',
            'id'                => $comment->id,
        ], Response::HTTP_OK);
    }

    /**
     * Get comments.
     *
     * Note: Auth Required.
     *
     * Returns comments for an engagement if the user is authorized to
     * view them or the engagement is a public one.
     */
    public function getCommentsPaginated(CommentsGetRequest $request)
    {
        $engagement = NULL;
        if (! ($engagement = Engagement::find($request->get('engagement_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no engagement was found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $authUser = Auth::guard('web')->user()->id;
        $invitedUser = $engagement->invitedUsers()->get()->search(function ($user, $key) {
            return $user->id == $authUser->id;
        });

        if (! ($invitedUser || ($engagement->privacy == config('const.privacy.public')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user has no authority to comment on this engagement',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully retrieved engagement comments',
            'comments'          => $engagement->comments()->paginate(config('const.pages.small'))->toArray(),
        ]);
    }

    /**
     * Update a comment
     *
     * Note: Auth Required.
     *
     * Updates a comment that was previously made by the authenticated user
     */
    public function updateComment(CommentUpdateRequest $request)
    {
        $comment = NULL;
        if (! ($comment = EngagementComment::find($request->get('comment_id')))) {

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
                'error-description' => 'this user has no authority to comment on this engagement',
            ], Response::HTTP_NOT_FOUND);
        }

        // $invitedUser = $engagement->invitedUsers()->get()->search(function ($user, $key) {
        //     return $user->id == $authUser->id;
        // });
        // if (! ($invitedUser || ($engagement->privacy == config('const.privacy.public')))) {
        //
        // }
        $comment->comment = $request->get('comment');
        $comment->update();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'comment successfully updated',
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
        if (! ($comment = EngagementComment::find($request->get('comment_id')))) {

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
                'error-description' => 'this user has no authority to comment on this engagement',
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
     * Creates a like for this engagement by the authenticated user
     */
    public function createLike(LikeCreateRequest $request)
    {
        $engagement = NULL;
        if (! ($engagement = Engagement::find($request->get('engagement_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no engagement was found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $authUser = Auth::user();
        $like = EngagementLike::where('engagement_id', '=', $engagement->id)
                              ->where('user_id', '=', $authUser->id)->first();
        if ($like) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_creation'),
                'error-description' => 'this user already likes this engagement',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $invitedUser = $engagement->invitedUsers()->get()->search(function ($user, $key) {
            return $user->id == $authUser->id;
        });

        if (! ($invitedUser || ($engagement->privacy == config('const.privacy.public')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user has no authority to like this engagement',
            ], Response::HTTP_NOT_FOUND);
        }

        $like = new EngagementLike;
        $like->user()->associate($authUser);
        $like->engagement()->associate($engagement);
        $like->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'like successfully created',
            'id'                => $like->id,
        ], Response::HTTP_OK);

    }

    /**
     * Remove a like
     *
     * Note: Auth Required.
     *
     * Removes a like on an engagement by the authenticated user.
     */
    public function deleteLike()
    {
        $engagement = NULL;
        if (! ($engagement = Engagement::find($request->get('engagement_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no engagement was found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $authUser = Auth::user();
        $like = EngagementLike::where('engagement_id', '=', $engagement->id)
                              ->where('user_id', '=', $authUser->id)->first();

        if (!$like) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_creation'),
                'error-description' => 'this user has not liked this engagement',
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
     * Saves engagement photo for the given engagement by the given user. The
     * user saving the photo must either be the owner of engagement (in which
     * case the photo is saved authorized) or another user with the permission
     * to access this engagement's data (when it is public or when the user
     * is an invited user)
     *
     */
    public function savePhoto(PhotoSaveRequest $request)
    {
        $engagement = NULL;
        if (! ($engagement = Engagement::find($request->get('engagement_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no engagement was found for the given id'
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

        $userOwnsThisEngagement = false;
        if ($user->has('engagementAsBride') || $user->has('engagementAsGroom')) {

            $userEngagement = $user->engagementAsGroom()->get();
            if (! $userEngagement) {
                $userEngagement = $user->engagementAsBride()->get();
            }
            $userOwnsThisEngagement = ($userEngagement->id == $engagement->id);
        }

        $invitedUser = $engagement->invitedUsers()->get()->search(function ($user, $key) {
            return $user->id == $authUser->id;
        });

        if (! ($userOwnsThisEngagement
                || $invitedUser
                || $engagement->privacy == config('const.privacy.public'))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_creation'),
                'error-description' => 'this user has no authority to save a photo for this engagement',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $photo = new EngagementPhoto;
        $fileName = time() . "." . $request->file('image')->guessExtension();
        $request->file('image')->move('uploads', $fileName);
        $photo->image_url = $fileName;
        $photo->authorized = $userOwnsThisEngagement;
        $photo->engagement()->associate($engagement);
        $photo->user()->associate($user);
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
     * Returns a list of authorized images of this engagement if the engagement
     * is public or if the user has permission (user_id holds the user of the user
     * accessing the images)
     *
     */
    public function getAuthorizedPhotos(PhotosGetRequest $request)
    {
        $engagement = NULL;
        if (! ($engagement = Engagement::find($request->get('engagement_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no engagement was found for the given id'
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

        $userOwnsThisEngagement = false;
        if ($user->has('engagementAsBride') || $user->has('engagementAsGroom')) {

            $userEngagement = $user->engagementAsGroom()->get();
            if (! $userEngagement) {
                $userEngagement = $user->engagementAsBride()->get();
            }

            $userOwnsThisEngagement = ($userEngagement->id == $engagement->id);
        }

        $invitedUser = $engagement->invitedUsers()->get()->search(function ($user, $key) {
            return $user->id == $authUser->id;
        });

        if (! ($userOwnsThisEngagement
                || $invitedUser
                || $engagement->privacy == config('const.privacy.public'))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user has no authority to access this engagements photos',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully authorized retrieved photos',
            'photos'            => $engagement->photos()
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
     * must be one of the couples in the engagement.
     */
    public function authorizePhoto(PhotoAuthorizeRequest $request)
    {
        $engagement = NULL;
        if (! ($engagement = $this->userEngagement())) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'this user does not have a engagement'
            ], Response::HTTP_NOT_FOUND);
        }

        $photo = NULL;
        if (! ($photo = EngagementPhoto::find($request->get('photo_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no photo was found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($engagement->id != $photo->engagement()->first()->id) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_creation'),
                'error-description' => 'this photo does not belong to this users engagement'
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
        $engagement = NULL;
        if (! ($engagement = $this->userEngagement())) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'this user does not have a engagement'
            ], Response::HTTP_NOT_FOUND);
        }


        $userOwnsThisEngagement = false;
        if ($user->has('engagementAsBride') || $user->has('engagementAsGroom')) {

            $userEngagement = $user->engagementAsGroom()->get();
            if (! $userEngagement) {
                $userEngagement = $user->engagementAsBride()->get();
            }

            $userOwnsThisEngagement = ($userEngagement->id == $engagement->id);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully retrieved photos',
            'photos'            => $engagement->photos()
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
    //         'error-description' => 'successfully retrieved photos for engagement',
    //         'photos'            => EngagementPhoto::paginate(config('const.pages.small'))->toArray(),
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
        if (! ($photo = EngagementPhoto::find($request->get('photo_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no photo was found for the given id'
            ], Response::HTTP_NOT_FOUND);
        }

        $id = $request->get('user_id');
        $photoEngagement = $photo->engagement()->get();

        if ($photo->user_id != $id && $photoEngagement->groom_id != $id
            && $photoEngagement->bride_id != $id) {
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
 	 * Returns an engagement for the user if the user has one, null otherwise
 	 *
 	 * @param type
 	 * @return NULL or the user engagement if he/she has one.
	 */
    private function userEngagement($user = null, $status = '')
    {
        if ($user === null)
            $user = Auth::user();

        if ($user->engagementAsBride()->count() || $user->engagementAsGroom()->count()) {

            $engagement = $user->engagementAsGroom()->get();
            if (! $engagement) {
                $engagement = $user->engagementAsBride()->get();
            }

            switch ($status) {
            case config('const.engagement.status.accepted') :
                if ($engagement->status === config('const.engagement.status.accepted'))
                    return $engagement;
                break;
            case config('const.engagement.status.pending') :
                if ($engagement->status === config('const.engagement.status.pending'))
                    return $engagement;
                break;
            case config('const.engagement.status.rejected') :
                if ($engagement->accepted === config('const.engagement.status.rejected'))
                    return $engagement;
                break;
            }
        }

        return NULL;
    }
}
