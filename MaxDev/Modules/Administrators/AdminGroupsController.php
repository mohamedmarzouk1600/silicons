<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 05 ON 09 Oct 2018
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Modules\Administrators;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use MaxDev\Enums\Status;
use MaxDev\Models\AdminGroup;
use MaxDev\Models\AdminPermission;
use MaxDev\Modules\Administrators\Requests\AdminGroupFormRequest;
use Yajra\DataTables\DataTables;

class AdminGroupsController extends AdministratorsController
{
    public $viewDir = 'admin.admin-groups.';
    public $routePrefix = 'admin.admin-groups.';
    public $resourceName = 'admin groups';

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $this->viewData['title'] = __('Admins');
        $this->viewData['tableColumns'] = [__('ID'), __('Name'), __('Status'), __('Action')];
        $this->viewData['Cols'] = ['id', 'name', 'status', 'action'];
        $this->viewData['buttons'] = $this->template->generateButtons($this->routePrefix, null, 'index');

        if ($request->isDataTable) {
            $eloquentData = AdminGroup::select(['id', 'name', 'status', 'user_group']);

            // Filtering
            if ($request->input('order.0.column') !== false && isset($this->viewData['Cols'][$request->input('order.0.column')])) {
                $column_name = $this->viewData['Cols'][$request->input('order.0.column')];
                switch ($column_name) {
                    case 'name':
                        $by = 'name';
                        break;
                    case 'id':
                    case 'status':
                        $by = $column_name;
                        break;
                }
                if (isset($by)) {
                    $eloquentData->orderBy($by, $request->input('order.0.dir'));
                }
            }

            if ($request->created_at1 || $request->created_at2) {
                WhereBetween($eloquentData, $request->created_at1, $request->created_at2);
            }

            // Filtering
            if ($request->id) {
                $eloquentData->where('id', 'like', '%' . $request->id . '%');
            }

            if ($request->name) {
                $name = $request->name;
                $eloquentData->where('name', 'LIKE', '%' . $name . '%');
            }

            if ($request->input('search.value')) {
                $name = $request->input('search.value');
                $eloquentData->where('name', 'LIKE', '%' . $name . '%');
            }


            // DataTables
            return DataTables::of($eloquentData)
                ->rawColumns(['status', 'action'])
                ->addColumn('id', function ($data) {
                    return $data->shortId();
                })
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == Status::INACTIVE) {
                        return '<button type="button" class="btn btn-danger btn-icon">' . __('In-Active') . '</button>';
                    } else {
                        return '<button type="button" class="btn btn-success btn-icon">' . __('Active') . '</button>';
                    }
                })
                ->addColumn('action', function ($data) {
                    return $this->template->generateRowDropDown([
                        [
                            'route' => $this->routePrefix . 'show',
                            'url' => route($this->routePrefix . 'show', $data->id),
                            'class' => 'fa fa-eye',
                            'text' => __('View'),
                        ],
                        [
                            'route' => $this->routePrefix . 'edit',
                            'url' => route($this->routePrefix . 'edit', $data->id),
                            'class' => 'fa fa-edit',
                            'text' => __('Edit'),
                        ],
                        [
                            'route' => $this->routePrefix . 'destroy',
                            'onclick' => 'deleteRecord(\'' . route(
                                    $this->routePrefix . 'destroy',
                                    $data->id
                                ) . '\',this)',
                            'class' => 'fa fa-trash',
                            'text' => __('Delete'),
                        ],
                    ]);
                })
                ->make(true);
        }
        return view($this->viewDir . 'index', $this->viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->viewData['title'] = __('New ' . ucfirst($this->resourceName));
        $this->viewData['submit'] = __('Add ' . ucfirst($this->resourceName));
        $this->viewData['adminGroups'] = AdminGroup::all()->pluck('name', 'id')->prepend(__('Select'), 0);
        $this->viewData['permissions'] = config('permissions');
        return view($this->viewDir . 'form', $this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(AdminGroupFormRequest $request)
    {
        $RequestedData = $request->only([
            'name',
            'permissions',
            'home_url',
            'url_index',
            'status',
            'user_group'
        ]);
        $permissions = array();
        $perms = recursiveFindArray(config('permissions'), 'permissions');
        foreach ($perms as $val) {
            foreach ($val as $key => $oneperm) {
                $permissions[$key] = $oneperm;
            }
        }
        $coll = new Collection();

        if ($row = AdminGroup::create($RequestedData)) {
            if (Arr::get($RequestedData, 'permissions')) {
                array_map(function ($oneperm) use ($permissions, $row, &$coll) {
                    foreach ($permissions[$oneperm] as $oneroute) {
                        $coll->push(new AdminPermission(['route_name' => $oneroute, 'admin_group_id' => $row->id]));
                    }
                }, $RequestedData['permissions']);
                $row->permissions()->insert($coll->toArray());
                $row->refresh();
                Cache::rememberForever('adminGroup_' . $row->id, function () use ($row) {
                    return $row->permissions;
                });
            }
            return $this->WithSuccess($this->routePrefix . 'index', __('Successfully added' . $this->resourceName));
        } else {
            return $this->WithSuccess($this->routePrefix . 'create', __('Couldn\'t add' . $this->resourceName));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param AdminGroup $adminGroup
     * @return Response
     */
    public function show(AdminGroup $adminGroup)
    {
        $this->viewData['title'] = __('View ' . ucfirst($this->resourceName));
        $adminGroup->admins;
        $adminGroup->permissions;
        $this->viewData['row'] = $adminGroup;
        $this->viewData['buttons'] = $this->template->generateButtons($this->routePrefix, $adminGroup, 'show');
        return view($this->viewDir . 'show', $this->viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AdminGroup $adminGroup
     * @return Response
     */
    public function edit(AdminGroup $adminGroup)
    {
        $this->viewData['title'] = __('Edit' . ucfirst($this->resourceName)) . ' ' . $adminGroup->name;
        $this->viewData['submit'] = __('Save changes');
        $this->viewData['permissions'] = config('permissions');
        $this->viewData['currentpermissions'] = $adminGroup->permissions()->get()->pluck('route_name')->toArray();
        $this->viewData['row'] = $adminGroup;
        $this->viewData['buttons'] = $this->template->generateButtons($this->routePrefix, $adminGroup, 'edit');
        return view($this->viewDir . 'form', $this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminGroupFormRequest $request
     * @param AdminGroup $adminGroup
     * @return Response
     */
    public function update(AdminGroupFormRequest $request, AdminGroup $adminGroup)
    {
        $RequestedData = $request->only([
            'name',
            'permissions',
            'home_url',
            'url_index',
            'status',
            'user_group'
        ]);
        $permissions = array();
        $perms = recursiveFindArray(config('permissions'), 'permissions');
        foreach ($perms as $val) {
            foreach ($val as $key => $oneperm) {
                $permissions[$key] = $oneperm;
            }
        }
        if (Arr::get($RequestedData, 'permissions')) {
            $coll = new Collection();
            array_map(function ($oneperm) use ($permissions, &$coll, $adminGroup) {
                foreach ($permissions[$oneperm] as $oneroute) {
                    $coll->push(new AdminPermission(['route_name' => $oneroute, 'admin_group_id' => $adminGroup->id]));
                }
            }, $RequestedData['permissions']);
        }

        if ($adminGroup->update($RequestedData)) {
            if (Arr::get($RequestedData, 'permissions')) {
                $adminGroup->permissions()->delete();
                if (isset($coll) && $coll->count()) {
                    $adminGroup->permissions()->insert($coll->toArray());
                }
            }
            $adminGroup->refresh();

            Cache::forget('adminGroup_' . $adminGroup->id);
            Cache::rememberForever('adminGroup_' . $adminGroup->id, function () use ($adminGroup) {
                return $adminGroup->permissions;
            });
            return $this->WithSuccess($this->routePrefix . 'index', __('Successfully updated ' . $this->resourceName));
        } else {
            return $this->WithError(
                [$this->routePrefix . 'edit', $adminGroup->id],
                __('Couldn\'t update ' . $this->resourceName)
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AdminGroup $adminGroup
     * @return Response
     * @throws \Throwable
     */
    public function destroy(AdminGroup $adminGroup)
    {
        $GLOBALS['status'] = false;
        DB::transaction(function () use ($adminGroup) {
            Cache::forget('adminGroup_' . $adminGroup->id);
            $adminGroup->permissions()->delete();
            $adminGroup->delete();
            $GLOBALS['status'] = true;
        });

        if (request()->ajax()) {
            return response()->json([
                'type' => (($GLOBALS['status']) ? 'success' : 'error'),
                'msg' => (($GLOBALS['status']) ? __($this->resourceName . ' successfully deleted') : __('Can not delete ' . $this->resourceName)),
            ]);
        } else {
            if ($GLOBALS['status']) {
                return $this->WithSuccess(
                    $this->routePrefix . 'index',
                    __('Successfully deleted ' . $this->resourceName)
                );
            } else {
                return $this->WithError($this->routePrefix . 'index', __('Couldn\'t delete ' . $this->resourceName));
            }
        }
    }
}
