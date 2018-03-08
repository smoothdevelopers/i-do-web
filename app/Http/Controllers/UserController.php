<?php 
namespace App\Http\Controllers;

use JWTAuth;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

use App\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\LoginFBRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\RegisterFBRequest;
use App\Http\Requests\User\RefreshTokenRequest;
use App\Http\Requests\User\CreatePasswordRequest;
use App\Http\Requests\User\AddPhoneNumberRequest;
use App\Http\Requests\User\LogoutRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\GetUserByContactRequest;
use App\Http\Requests\User\GetUserByIDRequest;
use App\Http\Requests\User\SearchRequest;

/**
 * @resource User
 *
 * This module will handle all the user manipulation including:
 * 1. Registration  - with Facebook or email and password.
 * 2. Authentication - with Facebook ID or email and password.
 * 3. User details - access to logged in user's details.
 * 4. Assigning tokens - will assign new API access tokens for
 *     an already logged in user.
 *
 * Note: for all the those routes marked with Auth Required,
 *        the following headers must be passed:
 *  + 'Accept' => 'application/json'
 *  + 'Authorization' => 'Bearer ' + { logged in user's token }
 *
 * Please ensure you have the space after the 'Bearer ' value of the 'Authorization'
 * header e.g. 'Bearer eyJ0eXAiOiJKV1QiL...'
 *
 *
 * If you try to access an endpoint without the correct Bearer token you will get
 *: The token has been blacklisted
 *
 * ### Possible Errors
 * This shows all the error codes generated from this model
 *
 * | Code | Description|
 * |-------|-------:|
 * | 100 | Invalid login |
 * | 101 | Required user not found |
 * | 102 | User account created using Facebook |
 * | 103 | Email not verified |
 * | 104 | Facebook ID not found |
 *
 */
class UserController extends Controller
{
    /**
     * Register with email and password.
     *
     * Will register the user with email and password.
     *
     * If the user email is already taken, then you will be notified
     * of this.
     * 
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->all());
        $user->password = bcrypt($request->password);

        if ($request->hasFile('profile_pic')) {
            $user->addMediaFromRequest('profile_pic')->toMediaCollection('avatar');
        }
        $user->save();
        $token = NULL;
        try {
            if (! $token = JWTAuth::attempt($request->only(['password', 'email']))) {

                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.errors.user.invalid_login'),
                    'error-description' => 'could not log in the user',
                    'token'             => $token,
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        } catch (JWTException $e) {

            return response()->json([
                'error'                 => true,
                'error-code'            => config('const.errors.internal_error'),
                'error-description'     => 'could not create token'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.errors.success'),
            'error-description' => 'user successfully created',
            'token'             => $token,
            'user'              => new UserResource($user),
        ], Response::HTTP_OK);
    }

    /**
     * Login with email and password.
     *
     * Will login a user with their email and password and return a token.
     *
     * #### Note
     * If the user attempting to login with email had registered
     * by Facebook ID, you will also get a custom error code
     * in which case you might want to notify the user that they
     * should login through facebook.
     *
     * Due to the above issue, a user will also be able to create
     * a password on an account they registered with Facebook after
     * which the user can either login using their email and password
     * or proceed with Facebook.
     *
     */
    public function login(LoginRequest $request)
    {
        $token = NULL;
        try {
            $token = JWTAuth::attempt($request->only('password', 'email'));
            if (! $token) {

                $user = User::where('email', $request->email)->first();
                if ($user && $user->fb_id) {
                    return response()->json([
                        'error'             => true,
                        'error-code'        => config('const.errors.user.created_with_fb'),
                        'error-description' => 'this account was created using facebook',
                    ], Response::HTTP_UNAUTHORIZED);
                }
                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.errors.user.invalid_login'),
                    'error-description' => 'invalid login credentials'
                ], Response::HTTP_UNAUTHORIZED);
            }

        } catch (JWTException $e) {

            return response()->json([
                'error'                 => true,
                'error-code'            => config('const.errors.internal_error'),
                'error-description'     => 'could not create token'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'error'                 => false,
            'error-code'            => config('const.errors.success'),
            'error-description'     => 'successfully logged in',
            'token'                 => $token
        ], Response::HTTP_OK);

    }

    /**
     * Login or register with Facebook ID.
     *
     * Will login a user with their Facebook ID and return a token. If the user
     * is not available on the database already, it will create him/her and log
     * him/her in.
     *
     * Note: If the user is being signed up, the name and gender values
     * will be expected.
     */
    public function loginFB(LoginFBRequest $request)
    {
        $user = User::where('fb_id', $request->fb_id)->first();

        if (! $user) {

            if ($request->email && $request->gender &&  $request->name) {
                $user = User::create($request->all());
                if ($request->hasFile('profile_pic')) {
                    $user->addMediaFromRequest('profile_pic')->toMediaCollection('avatar'); 
                    $user->save();
                }

            } else {

                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.errors.user.fb_id_not_found'),
                    'error-description' => 'this facebook account has not been registered',
                ], Response::HTTP_UNAUTHORIZED);
            }
        }

       $token = NULL;
        try {
            if (! ($token = JWTAuth::fromUser($user))) {

                return response()->json([
                    'error'             => true,
                    'error-code'        => config('const.errors.invalid_login'),
                    'error-description' => 'invalid login credentials'
                ], Response::HTTP_UNAUTHORIZED);
            }

        } catch (JWTException $e) {

            return response()->json([
                'error'                 => true,
                'error-code'            => config('const.errors.internal_error'),
                'error-description'     => 'could not create token',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        }

        return response()->json([
            'error'                 => false,
            'error-code'            => config('const.errors.success'),
            'error-description'     => 'successfully logged in',
            'token'                 => $token,
            'user'                  => new UserResource($user),
        ], Response::HTTP_OK);

    }

    /**
     * Refresh token
     *
     * Note: Auth Required
     *
     * This will refresh current logged in users token and return the new token
     *
     */
    public function refreshToken(RefreshTokenRequest $request)
    {
        try {
            $refreshToken = JWTAuth::refresh($request->token);

            return response()->json([
                'error'             => false,
                'error-code'        => config('const.errors.success'),
                'error-description' => 'token successfully refreshed',
                'new-token'         => $refreshToken
            ], Response::HTTP_OK);
        } catch (JWTException $e) {

            return response()->json([
                'error'                 => true,
                'error-code'            => config('const.errors.internal_error'),
                'error-description'     => 'could not refresh token'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Search users by name, email or phone.
     *
     * This is will be able to search for a user with phone number,
     * email address or their username. 
     *
     * The following tables describes the various options with 
     * which you can search for users.
     *
     * ### search_by
     * This is the field with which to search the users. If you 
     * leave this out, the search_term will be matched against
     * the name, the phone and the email.
     *
     * ### gender
     * It is optional to specify the gender of the users you want 
     * to get. 
     *
     * ### page
     * This is required for pagination of the results based on a 
     * count per page that you have to specify.      
     *
     * ### count
     * This is the count of users you would like to recieve on per page
     * basis. The default is 10. 
     *
     */
    public function searchUsers(SearchRequest $request)
    {
        $search = '%'.$request->search_term.'%';
        $query = User::query();

        if ($request->gender) {
            $query->where('gender', $request->gender);
        }

        if ($request->search_by) {

            $query->where($request->search_by, 'like', $search);
        } else {

            $query->where('phone', 'like', $search)
                  ->orWhere('name', 'like', $search)
                  ->orWhere('email', 'like', $search);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.errors.success'),
            'error-description' => 'successfully got users',
            'users'             => UserResource::collection($query->paginate($request->count ? $request->count : 10)),
        ], Response::HTTP_OK);

    }


    /**
     * User details.
     *
     * Get a user either by phone number or email
     *
     */
    public function getByContact(GetUserByContactRequest $request)
    {
        $query = User::query();

        if ($request->phone) {

            $query->where('phone', $request->phone);
        } else {
            
            $query->where('email', $request->email);
        }

        $user = $query->first();
        if ($user) {

            return response()->json([
                'error'             => false,
                'error-code'        => config('const.errors.success'),
                'error-description' => 'successfully got user',
                'user'             => new UserResource($user),
            ], Response::HTTP_OK);
        } else {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.errors.user_not_found'),
                'error-description' => 'could not get user',
            ], Response::HTTP_OK);
        }

    }

    /**
     * User details.
     *
     * Get user by their id.
     *
     */
    public function getByID(GetUserByIDRequest $request)
    {
        $user = User::find($request->id);

        if (! $user) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.errors.user_not_found'),
                'error-description' => 'could not find user for the id provided',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.errors.success'),
            'error-description' => 'successfully go the user',
            'user'              => new UserResource($user),
        ], Response::HTTP_OK);
    }

    /**
     * User details.
     *
     * Note: Auth Required
     *
     * Returns all the details of the currently logged in user.
     *
     */
    public function get()
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'error'             => true,
                'error-code'        => config('const.errors.user_not_found'),
                'error-description' => 'could not get user'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.errors.success'),
            'error-description' => 'here comes the user :)',
            'user'              => new UserResource($user),
        ], Response::HTTP_OK);
    }

    /**
     * Update user details.
     *
     * Note: Auth Required.
     *
     * This will update the details of an already logged in user.
     *
     */
    public function update(UpdateRequest $request)
    {
        $user = Auth::user();

        if ($user) {

            $user->update($request->all());

            if ($request->hasFile('profile_pic')) {

                Storage::delete('storage/uploads/' . $user->profile_pic);
                $fileName = time().'_'.rand(1, 100).'.'.$request->file('profile_pic')->guessExtension();
                $request->file('profile_pic')->move('uploads', $fileName);
                $user->profile_pic = $fileName;
            }

            $user->save();

            return response()->json([
                'error'             => false,
                'error-code'        => config('const.errors.success'),
                'error-description' => 'user detail successfully updated'
            ], Response::HTTP_OK);

        } else {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.errors.not_found'),
                'error-description' => 'no user could be found for this token'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Logout the user.
     *
     * Note: Auth Required.
     *
     * Will logout the logged in user and close the session.
     *
     */
    public function logout(LogoutRequest $request)
    {
        JWTAuth::invalidate($request->token);

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.errors.success'),
            'error-description' => 'user successfully logged out'
        ], Response::HTTP_OK);
    }


    /**
     * Close user account.
     *
     * Note: Auth Required.
     *
     * This will close the logged in user's account by soft deleting it.
     *
     */
    public function closeAccount(Request $request)
    {
        $user = Auth::user();

        if ($user) {

            $user->delete();

            return response()->json([
                'error'             => false,
                'error-code'        => config('const.errors.success'),
                'error-description' => 'user successfully deleted'
            ], Response::HTTP_OK);

        } else {

            return response()->json([
                'error'             => true,
                'error-code'        => config('const.errors.user_not_found'),
                'error-description' => 'no user could be found'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Create a password for a user.
     *
     * Note: Auth Required
     *
     * This will create a password for user that logged in using Facebook
     * so that they are allowed to login with an email and password.
     *
     */
    public function addPassword(CreatePasswordRequest $request)
    {
        $user = Auth::user();
        $user->password = bcrypt($user->password);
        $user->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.errors.success'),
            'error-description' => 'password succcesfully created'
        ], Response::HTTP_OK);
    }

    /**
     * Add phone number for a user
     * 
     * This will add the phone number to users account. This
     * will also include phone number verification later.
     */
    public function addPhone(AddPhoneNumberRequest $request)
    {
        $user = Auth::user();
        $user->phone = $request->phone;
        $user->save();

        return response()->json([
            'error'             => false,
            'error-code'        => config('const.errors.success'),
            'error-description' => 'phone succcesfully added'
        ], Response::HTTP_OK);
    }


}
