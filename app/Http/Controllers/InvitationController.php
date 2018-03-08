<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Invitation;
use App\Http\Requests\Invitation\CreateRequest;
use App\Http\Requests\Invitation\UpdateRequest;
use App\Http\Requests\Invitation\DeleteRequest;
use App\Http\Requests\Invitation\SecureRequest;
use App\Http\Requests\Invitation\UserInvitationsGetRequest;
use App\Http\Requests\Invitation\WeddingInvitationsGetRequest;

/**
 * @resource Invitation
 *
 * This module will handle creation, deletion and securing of invitations
 * to users by the wedding couple.
 *
 * Note: for an inviation to be created, the user has to have a wedding.
 */
class InvitationController extends Controller
{
    /**
     * Create invitation.
     *
     * Note: Auth Required.
     *
     * This will create an invitation to another user for a given wedding.
     * The user creating the invitation must own the wedding for which the invitation
     * is created.
     */
    public function create(CreateRequest $request)
    {
        $invitation = new Invitation;

        $user = Auth::user();
        if ($this->userWedding() != $request->get('wedding_id')) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_creation'),
                'error-description' => 'this user cannot create an invitation to this wedding',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $invitation->slots      = $request->get('slots');
        $invitation->message    = $request->get('message');
        $invitation->wedding_id = $request->get('wedding_id');
        $invitation->inviter_id = $user->id;
        $invitation->type       = $request->get('type');
        $token = bcrypt($request->get('wedding_id').rand(1, 1237).rand(1, 119));
        $invitation->token = $token;

        if ($invitation->type == config('const.invitation.app')) {

            $invitation->invitee_id = $request->get('invitee_id');
        }

        $invitation->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully created an invitation',
            'id'                => $invitation->id,
            'link'              => $request->get('type') == config('const.invitation.link')
                                   ? route('/invitation/secure', ['token' => $token]) : '',
        ], Response::HTTP_OK);
    }


    /**
     * Secure invitation.
     *
     * Note: Auth Required.
     *
     * This will secure an invitation for a wedding for an invited user. This marks
     * the invitation as accepted.
     */
    public function secureInvitation(SecureRequest $request)
    {
        $user = Auth::user();
        if (! $user->has('invitationsAsInvitee')) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'this user is not invited to any wedding',
            ], Response::HTTP_NOT_FOUND);
        }

        $invitation = NULL;
        if (! ($invitation = Invitation::where('token', '=', $request->get('token')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no invitation was found for the id given',
            ], Response::HTTP_NOT_FOUND);
        }

        $userIsInvited = $user->invitationsAsInvitee()->get()->search(function ($invitation, $key) {
            return $user->id == $invitation->invitee_id;
        });

        if (! $userIsInvited) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'this user is not invited by this invitation',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($invitation->slots != 1 && $invitation->slots < $request->get('secured_slots')) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_creation'),
                'error-description' => 'user cannot secure more slots than allocated',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $invitation->accepted = true;
        $invitation->secured_slots = $request->get('secured_slots');
        $invitation->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully secured the invitation',
        ], Response::HTTP_OK);

    }


    /**
     * Cancel invitation.
     *
     * Note: Auth Required.
     *
     * This will cancel an invitation that has already been created by the
     * wedding couple for a particular user. The user cancelling must own the
     * wedding.
     *
     */
    public function delete(DeleteRequest $request)
    {
        $invitation = NULL;
        if (! ($invitation = Invitation::find($request->get('invitation_id')))) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.not_found'),
                'error-description' => 'no invitation was found for the id given',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($invitation->wedding()->first()->id !=  $this->userWedding()->id) {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.error.unauthorized_access'),
                'error-description' => 'this user has no right to cancel this invitation',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $invitation->trash();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully deleted invitation',
        ]);
    }


    /**
     * Get user invitations.
     *
     * Note: Auth Required.
     *
     * Retrieves all the invitations for a particular user.
     */
    public function userInvitations(UserInvitationsGetRequest $request)
    {
        $user = Auth::user();
        return response()->json([
            'error'             => false,
            'error-code'        => config('const.error.success'),
            'error-description' => 'successfully retrieved user invitations',
            'invitations'       => $user->has('invitationsAsInvitee')
                                   ? $user->invitationsAsInvitee()
                                          ->get()->toArray()
                                   : []
        ], Response::HTTP_OK);
    }

    /**
     * Get wedding invitations.
     *
     * Note: Auth Required.
     *
     * Retrieves all the invitations for a particular wedding. The user accesses
     * invitations of his/her wedding.
     */
    public function weddingInvitations(WeddingInvitationsGetRequest $request)
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
            'error-description' => 'successfully retrieved wedding invitations',
            'invitations'       => $wedding->has('invitations')
                                   ? $wedding->invitations()->get()->toArray()
                                   : []
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
