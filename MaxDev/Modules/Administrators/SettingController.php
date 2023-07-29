<?php

namespace MaxDev\Modules\Administrators;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use MaxDev\Models\Pharmacy;
use MaxDev\Models\Setting;
use MaxDev\Modules\Administrators\Requests\SettingsFormRequest;

class SettingController extends AdministratorsController
{
    public $viewDir = 'admin.settings.';
    public $routePrefix = 'admin.settings.';
    public $resourceName = 'setting';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = request()->groups ?? 'none';
        $this->viewData['title'] = __(ucfirst($this->resourceName).'s');
        $this->viewData['rows'] = Setting::where('groups', $groups)->orderBy('name')->paginate(15);
        $this->viewData['buttons'] = $this->template->generateButtons($this->routePrefix, null, 'index');
        return $this->view($this->viewDir.'index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->viewData['title'] = __('New '.ucfirst($this->resourceName));
        $this->viewData['buttons'] = $this->template->generateButtons($this->routePrefix, null, 'create');
        $this->viewData['groups'] = Setting::select('groups')->distinct()->pluck('groups', 'groups')->prepend(__('None'));
        return view($this->viewDir.'create', $this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SettingsFormRequest $request)
    {
        if ($setting = Setting::create($request->validated())) {
            Cache::forget('settings.'.$setting->name);
            Cache::rememberForever('settings.'.$setting->name, function () use ($setting) {
                if ($setting->has_translations) {
                    return [
                        'ar'    =>  $setting->getTranslation('value', 'ar'),
                        'en'    =>  $setting->getTranslation('value', 'en'),
                    ];
                } else {
                    return $setting->value;
                }
            });
            return $this->WithSuccess($this->routePrefix . 'index', __('Successfully added ' . $this->resourceName));
        } else {
            return $this->WithError($this->routePrefix . 'index', __('Could not add ' . $this->resourceName));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        $this->viewData['title'] = __('View '.ucfirst($this->resourceName));
        $this->viewData['row'] = $setting;
        $this->viewData['buttons'] = $this->template->generateButtons($this->routePrefix, $setting, 'show');
        return view($this->viewDir.'show', $this->viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        $this->viewData['title'] = __('Edit '.ucfirst($this->resourceName));
        $this->viewData['row'] = $setting;
        $this->viewData['buttons'] = $this->template->generateButtons($this->routePrefix, null, 'edit');
        $this->viewData['groups'] = Setting::select('groups')->distinct()->pluck('groups', 'groups')->prepend(__('None'));
        return view($this->viewDir.'create', $this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SettingsFormRequest $request, Setting $setting)
    {
        if ($setting->update($request->validated())) {
            Cache::forget('settings.'.$setting->name);
            Cache::rememberForever('settings.'.$setting->name, function () use ($setting) {
                if ($setting->has_translations) {
                    return [
                        'ar'    =>  $setting->getTranslation('value', 'ar'),
                        'en'    =>  $setting->getTranslation('value', 'en'),
                    ];
                } else {
                    return $setting->value;
                }
            });
            return $this->WithSuccess($this->routePrefix . 'index', __('Successfully edited ' . $this->resourceName));
        } else {
            return $this->WithError($this->routePrefix . 'index', __('Could not edited ' . $this->resourceName));
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        if ($status = $setting->delete()) {
            Cache::forget('settings.'.$setting->name);
        }
        if (request()->ajax()) {
            return response()->json([
                'type'      =>      (($status) ? 'success' : 'error'),
                'msg'       =>      (($status) ? __(ucfirst($this->resourceName).' successfully deleted') : __('Can not delete this '.$this->resourceName)),
            ]);
        }
    }


    public function validateSetting()
    {
        return Validator::make(\request()->only(['has_translations','name','value']), [
            'name'          =>  'required|unique:settings',
            'value'         =>  'required|array',
        ])->validate();
    }
}
