<?php

namespace MaxDev\Modules\Administrators\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Agent;
use MaxDev\Enums\UserGroupType;
use MaxDev\Enums\UserStatus;
use MaxDev\Events\AdminShouldLogout;
use MaxDev\Events\LogoutAdmin;
use MaxDev\Events\PatientCallingDoctor;
use MaxDev\Modules\Administrators\AdministratorsController;
use MaxDev\Modules\WebBaseController;
use MaxDev\Services\NurseService;
use MaxDev\Models\Admin;
use MaxDev\Services\QueuesCalls\GPQueue;

class AdminLoginController extends AdministratorsController
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/administrators';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest:admin')->except('logout');
    }


    public function redirectTo()
    {
        $this->redirectTo = AdminHomeUrl('admin');
        if (!$this->redirectTo) {
            if (AdminCan('admin.dashboard')) {
                $this->redirectTo = route('admin.dashboard', null, false);
            } else {
                $this->redirectTo = route('admin.no-access', null, false);
            }
        }
        return route($this->redirectTo[0], isset($this->redirectTo[1]) ? $this->redirectTo[1] : null, false);
    }

    public function ShowLoginForm()
    {
        $this->viewData['title'] = __('Login');
        return $this->view('admin.login');
    }

    public function credentials(Request $request)
    {
        return array_merge($request->only('email', 'password'), ['status'=>'1']);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function guard()
    {
        return Auth::guard('admin');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $agent = new Agent();
            $user = Admin::find(auth('admin')->id());
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function authenticated(Request $request, $user)
    {
        auth('admin')->logoutOtherDevices(request('password'));
        event(new AdminShouldLogout(auth('admin')->id()));
    }
}
