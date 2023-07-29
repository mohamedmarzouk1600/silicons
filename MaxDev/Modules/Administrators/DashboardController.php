<?php

namespace MaxDev\Modules\Administrators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use MaxDev\Enums\CallStatus;
use MaxDev\Enums\RecommendationTypes;
use MaxDev\Enums\UserGroupType;
use MaxDev\Models\User;
use MaxDev\Services\StatisticsService;

class DashboardController extends AdministratorsController
{
    /**
     * @throws \ReflectionException
     */
    public function index(StatisticsService $statisticsService)
    {
        $mainDashboardPath = 'admin.dashboard.home-';
        $this->viewData['title'] = __('Admin dashboard');
        $this->viewData['stats'] = [
            'patients_count' => User::count(),
        ];
        $this->viewData['tableColumns'] = [__('Name'), __('Mobile'), __('Missed Call At')];
        $this->viewData['cols'] = ['name', 'mobile', 'missed_call_at'];

        switch (auth('admin')->user()->user_group) {
            case UserGroupType::Admin :
                return $this->view($mainDashboardPath . 'admin');
            

            default:
                return $this->view($mainDashboardPath . 'default');
        }
    }

    public function NoAccess()
    {
        $this->viewData['title'] = __('No permissions');

        return $this->view('admin.no-access');
    }


    public function setting(Request $request)
    {
        if ($request->isMethod('PATCH')) {
            $RequestData = $request->only(['fullname', 'email', 'password', 'password_confirmation']);
            Validator::make($RequestData, [
                'fullname' => 'required',
                'email' => 'required|email|unique:users,email,' . Auth::id(),
                'password' => 'nullable|confirmed',
            ])->validate();

            if (!$RequestData['password']) {
                unset($RequestData['password'], $RequestData['password_confirmation']);
            } else {
                $RequestData['password'] = bcrypt($RequestData['password']);
            }
            Auth::user()->disableLogging();
            if (Auth::user()->update($RequestData)) {
                return $this->WithSuccess('admin.profile', __('information successfully updated'));
            } else {
                return $this->WithSuccess('admin.profile', __('information successfully updated'));
            }
        } else {
            $data['title'] = __('User setting');
            $data['row'] = Auth::user();

            return view('admin.user.setting', $data);
        }
    }
}
