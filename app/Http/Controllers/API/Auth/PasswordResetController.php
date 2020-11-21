<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreatePassUserAPIRequest;
use App\Http\Requests\API\ResetPassUserAPIRequest;
use App\Http\Resources\UserResource;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends AppBaseController
{
    use SendsPasswordResetEmails;


    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    /**
     * @param Request $request
     * @return Response
     *
     * @param int $id
     * @return Response
     *
     * @SWG\Post(
     *      path="/password/resetlink",
     *      summary="Request a password reset link to change on web.",
     *      tags={"User"},
     *      description="Send email to user to reset the password from the web, if you use this the user will reset from web and you will just ask for login after the link is sent.",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in="body",
     *          name="body",
     *          description="The Username or Email and Password of the user",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="email",
     *                  type="string"
     *              ),
     *          ),
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="object"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        if ($response == Password::RESET_LINK_SENT) {
            return $this->sendResponse(
                $request->email,
                __('passwords.sent')
            );
        } else {
            return $this->sendError(
                __('passwords.user'),
                401
            );
        }
    }

    /**
     * Create token password reset
     *
     * @param  [string] login_field
     * @return [string] message
     *
     * @param Request $request
     * @return Response
     * @SWG\Post(
     *      path="/password/create",
     *      summary="Request a password reset.",
     *      tags={"User"},
     *      description="Get the token for password reset by mail.",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in="body",
     *          name="body",
     *          description="The Username or Email and Password of the user",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="login_field",
     *                  type="string"
     *              ),
     *          ),
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="object"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function create(CreatePassUserAPIRequest $request)
    {
        $fieldType = filter_var($request->login_field, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $user = User::where($fieldType, $request->login_field)->first();
        if (!$user)
            return $this->sendError(
                __('passwords.user'),
                401
            );
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $request->login_field],
            [
                'email' => $request->login_field,
                'token' => mt_rand(1000, 9999)
            ]
        );
        if ($user && $passwordReset)
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );

        return $this->sendResponse(
            $user->$fieldType,
            __('passwords.sent')
        );
    }
    /**
     * Create token password reset
     *
     * @param  [string] code
     * @return [string] message
     *
     * @param Request $request
     * @return Response
     * @SWG\Get(
     *      path="/password/find/{code}",
     *      summary="Check validity of reset code.",
     *      tags={"User"},
     *      description="Check if the code is not expired",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="code",
     *          description="code of User",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="object"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function find($code)
    {
        $passwordReset = PasswordReset::where('token', $code)
            ->first();
        if (!$passwordReset)
            return $this->sendError(
                __('passwords.token'),
                401
            );
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return $this->sendError(
                __('passwords.token'),
                401
            );
        }
        return $this->sendResponse(
            $passwordReset->only('token', 'updated_at'),
            __('passwords.tokenValid')
        );
    }
    /**
     * Reset password
     *
     * @param  [string] login_field
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] code
     * @return [string] message
     * @return [json] user object

     * @param Request $request
     * @return Response
     * @SWG\Post(
     *      path="/password/reset",
     *      summary="Reset the password of the Users.",
     *      tags={"User"},
     *      description="Reset the password of the Users.",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in="body",
     *          name="body",
     *          description="The Phone or Email and Password of the user",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="login_field",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="password",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="password_confirmation",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="code",
     *                  type="string"
     *              ),
     *          ),
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="object"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function reset(ResetPassUserAPIRequest $request)
    {
        $passwordReset = PasswordReset::where([
            ['token', $request->code],
            ['email', $request->login_field]
        ])->first();
        if (!$passwordReset)
            return $this->sendError(
                __('passwords.token'),
                401
            );
        $fieldType = filter_var($request->login_field, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $user = User::where($fieldType, $request->login_field)->first();
        if (!$user)
            return $this->sendError(
                __('passwords.user'),
                401
            );
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));
        return $this->sendResponse(
            $user->$fieldType,
            __('passwords.reset')
        );
    }
}
