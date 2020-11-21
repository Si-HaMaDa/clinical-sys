<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUserAPIRequest;
use App\Http\Requests\API\UpdateUserAPIRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\DeviceTokenAPIRequest;
use App\Http\Requests\API\LoginUserAPIRequest;
use App\Http\Requests\API\VerifyUserAPIRequest;
use App\Http\Resources\UserResource;
use App\Models\Devicetoken;
use Response;

/**
 * Class UserController
 * @package App\Http\Controllers\API
 */

class UserAPIController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * @param CreateUserAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/users",
     *      summary="Store a newly created User in storage",
     *      tags={"User"},
     *      description="Store User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User that should be stored",
     *          required=false,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="name",
     *                  description="name",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="email",
     *                  description="email",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="phone",
     *                  description="phone",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="password",
     *                  description="password",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="password_confirmation",
     *                  description="password_confirmation",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="type",
     *                  description="type",
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
     *                  ref="#/definitions/User"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateUserAPIRequest $request)
    {
        $input = $request->all();

        $user = $this->userRepository->create($input);
        $user->sendEmailVerificationNotification();

        return $this->sendResponse(
            $user->only(['id', 'name', 'email', 'phone']),
            __('auth.registration.success_need_verify')
        );
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/users/{id}",
     *      summary="Display the specified User",
     *      tags={"User"},
     *      description="Get User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of User",
     *          type="integer",
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
     *                  ref="#/definitions/User"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError(
                __('messages.not_found', ['model' => __('models/users.singular')])
            );
        }

        return $this->sendResponse(
            new UserResource($user),
            __('messages.retrieved', ['model' => __('models/users.singular')])
        );
    }

    /**
     * @param int $id
     * @param UpdateUserAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/users/{id}",
     *      summary="Update the specified User in storage",
     *      tags={"User"},
     *      description="Update User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of User",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="User that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/User")
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
     *                  ref="#/definitions/User"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateUserAPIRequest $request)
    {
        $input = $request->all();

        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError(
                __('messages.not_found', ['model' => __('models/users.singular')])
            );
        }

        $user = $this->userRepository->update($input, $id);

        return $this->sendResponse(
            new UserResource($user),
            __('messages.updated', ['model' => __('models/users.singular')])
        );
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/users/login",
     *      summary="Do a login of the Users.",
     *      tags={"User"},
     *      description="Login the User",
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
     *              @SWG\Property(
     *                  property="password",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="device_token",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="os_type",
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
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/User")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function login(LoginUserAPIRequest $request)
    {

        $fieldType = filter_var($request->login_field, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($fieldType, $request->login_field)->first();
        // $user = User::where('email', $request->login_field)->first();

        if ($user) {
            if (\Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant User')->accessToken;
                $user->token = $token;
                $user->token_type = 'Bearer';

                if ($request->device_token)
                    $user->devicetokens()->updateOrCreate(['device_token' => $request->device_token], $request->only('device_token', 'os_type'));

                return $this->sendResponse(
                    $user->only(['id', 'name', 'email', 'phone', 'gender', 'birthday', 'image', 'token', 'token_type']),
                    __('auth.success')
                );
            } else {
                return $this->sendError(
                    __('auth.failed'),
                    401
                );
            }
        } else {
            return $this->sendError(
                __('auth.failed'),
                401
            );
        }
    }


    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/users/verify_code",
     *      summary="Do a Verify of the Users.",
     *      tags={"User"},
     *      description="Verify the User",
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
     *              @SWG\Property(
     *                  property="password",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="verify_code",
     *                  example="1111",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="device_token",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="os_type",
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
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/User")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function verify_code(VerifyUserAPIRequest $request)
    {
        $fieldType = filter_var($request->login_field, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($fieldType, $request->login_field)->firstOrFail();

        if (!\Hash::check($request->password, $user->password))
            return $this->sendError(
                __('auth.failed'),
                401
            );

        if ($request->verify_code != 1111)
            return $this->sendError(
                __('auth.verify_code_false'),
                403
            );

        $user->phone_verified_at = now();
        $user->save();
        $token = $user->createToken('Laravel Password Grant User')->accessToken;
        $user->token = $token;
        $user->token_type = 'Bearer';

        if ($request->device_token)
            $user->devicetokens()->updateOrCreate(['device_token' => $request->device_token], $request->only('device_token', 'os_type'));

        return $this->sendResponse(
            // new UserResource($user),
            $user->only('id', 'name', 'email', 'phone', 'gender', 'birthday', 'image', 'token', 'token_type'),
            __('auth.verified'),
        );
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/users/device_token",
     *      summary="Save Device token of the Users.",
     *      tags={"User"},
     *      description="Device token the User",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in="body",
     *          name="body",
     *          description="The Username or Email and Password of the user",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="device_token",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="os_type",
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
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/User")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function device_token(DeviceTokenAPIRequest $request)
    {
        $input = $request->only('device_token', 'os_type');
        $input['user_id'] = auth('api')->id() ?? null;

        $device_token = Devicetoken::updateOrCreate(['device_token' => $input['device_token']], $input);

        if (!$device_token)
            return $this->sendError(
                __('auth.failed'),
                401
            );

        return $this->sendResponse(
            $device_token->device_token,
            __('auth.verified'),
        );
    }
}
