<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Inspiration;
use App\Http\Requests\Inspiration\CreateRequest;
use App\Http\Requests\Inspiration\OneInspirationRequest;
use App\Http\Requests\Inspiration\PageRequest;
use App\Http\Requests\Inspiration\UpdateRequest;
use App\Http\Requests\Inspiration\DeleteRequest;
use App\Http\Requests\Inspiration\CommentCreateRequest;
use App\Http\Requests\Inspiration\CommentDeleteRequest;
use App\Http\Requests\Inspiration\CommentUpdateRequest;
use App\Http\Requests\Inspiration\CommentsGetRequest;
use App\Http\Requests\Inspiration\LikeCreateRequest;
use App\Http\Requests\Inspiration\LikeDeleteRequest;


/**
 * @resource Inspiration
 *
 * This module handles the creation, updating and deletion of
 * inspiration media.
 *
 * Note: an inspiration is created either by the admin or a
 * user with a wedding or engagement.
 */
class InspirationController extends Controller
{
    /**
     * Create inspiration.
     *
     * Note: Auth Required.
     *
     * This will create an inspiration. The user must have a wedding or an
     * an engagement.
     */
    public function create(CreateRequest $request)
    {
        $user = Auth::user();
        if (!$user->has('weddingAsBride') && !$user->has('weddingAsGroom')) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_creation'),
                'error-description' => 'this user has to have a wedding or an engagement to create an inspiration',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $inspiration = new Inspiration;
        $inspiration->description = $request->get('description');
        $inspiration->user_id = $user->id;
        $inspiration->media_type = $request->get('media_type');

        if ($request->hasFile('file')) {

            $fileName = time().'_'.rand(1, 100).'.'.$request->file('file')->guessExtension();
            $request->file('file')->move('storage/uploads', $fileName);
            $inspiration->media_link = $fileName;
        }

        $inspiration->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'inspiration successfully created',
        ], Response::HTTP_OK);
    }

    /**
     * Update inspiration.
     *
     * Note: Auth Required.
     *
     * The user updating the inspiration must be the user that created it.
     */
    public function update(UpdateRequest $request)
    {
        $inspiration = NULL;
        if (! ($inspiration = Inspiration::find($request->get('inspiration_id')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'could not find inspiration for id given',
            ], Response::HTTP_NOT_FOUND);
        }

        $user = Auth::user();
        if ($inspiration->user_id != $user->id) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'this user cannot edit this inspiration',
            ], Response::HTTP_NOT_ACCEPTABLE);

        }

        $inspiration->description = $request->get('description');
        $inspiration->media_type = $request->get('media_type');

        if ($request->hasFile('file')) {

            if ($inspiration->media_link)
                Storage::delete('storage/uploads'.$inspiration->media_link);
            $fileName = time().'_'.rand(1, 100).'.'.$request->file('file')->guessExtension();
            $request->file('file')->move('storage/uploads', $fileName);
            $inspiration->media_link = $fileName;
        }

        $inspiration->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'inspiration successfully updated',
        ], Response::HTTP_OK);
    }

    /**
     * Get one inspiration by id.
     *
     * Returns an inspiration by given the id.
     */
    public function getOther(OneInspirationRequest $request)
    {
        $inspiration = NULL;
        if (! ($inspiration = Inspiration::find($request->get('inspiration_id')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'could not find inspiration for id given',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully retrieved inspiration',
            'inspiration'       => $inspiraton->toArray(),
        ], Response::HTTP_OK);

    }

    /**
     * Deletes inspiration.
     *
     * Note: Auth Required.
     *
     * Deletes an inspiration. The user deleting it must have created it.
     */
    public function delete(DeleteRequest $request)
    {
        $inspiration = NULL;
        if (! ($inspiration = Inspiration::find($request->get('inspiration_id')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'could not find inspiration for id given',
            ], Response::HTTP_NOT_FOUND);
        }

        $user = Auth::user();
        if ($user->id != $inspiration->user_id) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user cannot delete this inspiration',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $inspiration->trash();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully deleted inspiration',
        ], Response::HTTP_OK);
    }


    /**
     * Get inspirations paginated.
     *
     * Returns a list of inspirations paginated.
     */
    public function getPaginated(PageRequest $request)
    {
        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully got users',
            'users'             => Inspiration::paginate(config('const.pages.small')),
        ], Response::HTTP_OK);
    }


    /**
     * Create inspiration comment.
     *
     * Note: Auth Required.
     *
     * Creates a comment by a user on an inspiration.
     */
    public function createComment(CommentCreateRequest $request)
    {

        $inspiration = NULL;
        if (! ($inspiration = Inspiration::find($request->get('inspiration_id')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'could not find inspiration for id given',
            ], Response::HTTP_NOT_FOUND);
        }

        $user = Auth::user();
        $comment = new InspirationComment;
        $comment->comment = $request->get('comment');
        $comment->associate($user);
        $comment->associate($inspiration);

        $comment->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully created comment',
        ], Response::HTTP_OK);
    }

    /**
     * Get comment paginated.
     *
     * Returns a list of comments on the paginated.
     */
    public function getComments(CommentsGetRequest $request)
    {
        $inspiration = NULL;
        if (! ($inspiration = Inspiration::find($request->get('inspiration_id')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'could not find inspiration for id given',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully retrieved comments',
            'comments'          => $inspiration->comments()
                                               ->get()
                                               ->paginate(config('const.page.small'))
                                               ->toArray(),
        ], Response::HTTP_OK);
    }

    /**
     * Update inspiration comment.
     *
     * Note: Auth Required.
     *
     * Updates a comment made by user on inspiration. The user updating it
     * must be the one who made the comment.
     */
    public function updateComment(CommentUpdateRequest $request)
    {
        $comment = NULL;
        if (! ($comment = InspirationComment::find($request->get('comment_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'could not find comment for id given',
            ], Response::HTTP_NOT_FOUND);
        }

        $user = Auth::user();
        if ($user->id != $comment->user_id) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user cannot edit this inspiration comment',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $comment->comment = $request->get('comment');
        $comment->associate($user);
        $comment->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully updated comment',
        ], Response::HTTP_OK);
    }


    /**
     * Delete inspiration comment
     *
     * Note: Auth Required.
     *
     * Deletes a comment on an inspiration. The user deleting must be
     * the one who made the comment.
     */
    public function deleteComment(CommentDeleteRequest $request)
    {
        $comment = NULL;
        if (! ($comment = InspirationComment::find($request->get('comment_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'could not find comment for id given',
            ], Response::HTTP_NOT_FOUND);
        }

        $user = Auth::user();
        if ($user->id != $comment->user_id) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user cannot delete this inspiration comment',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $comment->delete();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully deleted the comment',
        ], Response::HTTP_OK);
    }

    /**
     * Creates a like on an inspiration.
     *
     * Note: Auth Required.
     *
     * Creates a like on an inspiration by a user.
     */
    public function createLike()
    {
        $inspiration = NULL;
        if (! ($inspiration = Inspiration::find($request->get('inspiration_id')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'could not find inspiration for id given',
            ], Response::HTTP_NOT_FOUND);
        }

        $authUser = Auth::user();
        $like = InspirationLike::where('inspiration_id', '=', $inspiration->id)
                                 ->where('user_id', '=', $authUser->id)->first();
        if ($like) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_creation'),
                'error-description' => 'this user already likes this inspiration',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $like = new InspirationLike;
        $like->associate(Auth::user());
        $like->associate($inspiration);
        $like->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully created like',
        ], Response::HTTP_OK);
    }

    /**
     * Get likes paginated.
     *
     * Returns a list of likes on an inspiration paginated.
     */
    public function getLikes(LikesGetRequest $request)
    {
        $inspiration = NULL;
        if (! ($inspiration = Inspiration::find($request->get('inspiration_id')))) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'could not find inspiration for id given',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully retrieved likes',
            'comments'          => $inspiration->likes()
                                               ->get()
                                               ->paginate(config('const.page.small'))
                                               ->toArray(),
        ], Response::HTTP_OK);
    }


    /**
     * Delete inspiration like
     *
     * Note: Auth Required.
     *
     * Removes a like on an inspiration by the authenticated user. The user removing
     * it must be the one who created it.
     */
    public function deleteLike(LikeDeleteRequest $request)
    {
        $like = NULL;
        if (! ($like = InspirationLike::find($request->get('like_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'could not find like for id given',
            ], Response::HTTP_NOT_FOUND);
        }

        $user = Auth::user();
        if ($user->id != $user->user_id) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user cannot delete this inspiration comment',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }


        $like->delete();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully deleted the like',
        ], Response::HTTP_OK);
    }
}
