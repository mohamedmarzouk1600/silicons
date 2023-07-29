<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 05 ON 09 Oct 2018
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Modules\Administrators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MaxDev\Enums\Status;
use MaxDev\Enums\UserStatus;
use MaxDev\Models\AdminGroup;
use MaxDev\Services\UserablesService;
use MaxDev\Models\Admin;
use MaxDev\Modules\Administrators\Requests\AdminFormRequest;
use Yajra\DataTables\DataTables;

class AdminsController extends AdministratorsController
{
    public $viewDir = 'admin.admin.';
    public $routePrefix = 'admin.admins.';
    public $resourceName = 'admin';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->viewData['title'] = __(ucfirst($this->resourceName).'s');
        $this->viewData['tableColumns'] = [__('ID'),__('Name'), __('Group'),__('Email'), __('Status'),__('Action')];
        $this->viewData['Cols'] = ['id','name','group','email','status','action'];
        $this->viewData['buttons'] = $this->template->generateButtons($this->routePrefix, null, 'index');

        if ($request->isDataTable) {
            $eloquentData = Admin::select(['id','fullname','email','status','user_group'])->with(['adminGroup:id,name,user_group']);
            // Filtering
            if ($request->input('order.0.column') !== false && isset($this->viewData['Cols'][$request->input('order.0.column')])) {
                $column_name=$this->viewData['Cols'][$request->input('order.0.column')];
                switch ($column_name) {
                    case 'name':
                        $by='fullname';
                        break;
                    case 'id':
                    case 'status':
                        $by=$column_name;
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
                $eloquentData->where('id', 'like', '%'.$request->id.'%');
            }

            if ($request->name) {
                $name=$request->name;
                $eloquentData->where('fullname', 'LIKE', '%'.$name.'%');
            }

            if ($request->input('search.value')) {
                $name=$request->input('search.value');
                $eloquentData->where('fullname', 'LIKE', '%'.$name.'%');
            }


            // DataTables
            return DataTables::of($eloquentData)
                ->rawColumns(['status', 'action'])
                ->addColumn('id', function ($data) {
                    return $data->shortId();
                })
                ->addColumn('name', function ($data) {
                    return $data->fullname;
                })
                ->addColumn('group', function ($data) {
                    return link_to_route('admin.admin-groups.show', $data->adminGroup->name, $data->adminGroup->id);
                })
                ->addColumn('email', function ($data) {
                    return $data->email;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == Status::INACTIVE) {
                        return '<span class="badge badge-danger">'.__('In-Active').'</span>';
                    } else {
                        return '<span class="badge badge-success">'.__('Active').'</span>';
                    }
                })
                ->addColumn('action', function ($data) {
                    return $this->template->generateRowDropDown([
                        [
                            'route' =>  $this->routePrefix.'show',
                            'url'   =>  route($this->routePrefix.'show', $data->id),
                            'class' =>  'fa fa-eye',
                            'text'  =>  __('View'),
                        ],
                        [
                            'route' =>  $this->routePrefix.'edit',
                            'url'   =>  route($this->routePrefix.'edit', $data->id),
                            'class' =>  'fa fa-edit',
                            'text'  =>  __('Edit'),
                        ],
                        [
                            'route' =>  $this->routePrefix.'destroy',
                            'onclick'   =>  'deleteRecord(\''.route($this->routePrefix.'destroy', $data->id).'\',this)',
                            'class' =>  'fa fa-trash',
                            'text'  =>  __('Delete'),
                        ],
                    ]);
                })
                ->make(true);
        }
        return view($this->viewDir.'index', $this->viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->viewData['title'] = __('New '.ucfirst($this->resourceName));
        $this->viewData['submit'] = __('Add '.ucfirst($this->resourceName));
        $this->viewData['adminGroups'] = AdminGroup::all()->pluck('name', 'id');

        return view($this->viewDir.'form', $this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminFormRequest $request)
    {
        $RequestedData = $request->only(['fullname','email','password','status','user_group','language']);
        $RequestedData['password'] = bcrypt($RequestedData['password']);
        $RequestedData['creatable_id'] = Auth::id();
        $RequestedData['creatable_type'] = get_class(Auth::user());
        $admin = new Admin($RequestedData);
        if ($admin->save($RequestedData)) {
            return $this->WithSuccess($this->routePrefix.'index', __('Successfully added '.$this->resourceName));
        } else {
            return $this->WithError($this->routePrefix.'create', __('Couldn\'t add '.$this->resourceName));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \MaxDev\Models\Admin  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $user)
    {
        $user->load(['adminGroup'=>function ($query) {
            $query->select(['id','name','user_group']);
        }]);
        $this->viewData['title'] = __('View '.$this->resourceName);
        $this->viewData['row'] = $user;
        $this->viewData['buttons'] = $this->template->generateButtons($this->routePrefix, $user, 'show');

        $this->viewData['tableColumns'] = [
            __('Time From'),
            __('Time To'),
            __('Office'),
            __('Action')
        ];
        $this->viewData['Cols'] = [
            'from',
            'to',
            'office',
            'action'
        ];
        return view($this->viewDir.'show', $this->viewData);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MaxDev\Models\Admin  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $user)
    {
        $this->viewData['title'] = __('Edit '.ucfirst($this->resourceName)).' '.$user->fullname;
        $this->viewData['submit'] = __('Save '.ucfirst($this->resourceName));
        $this->viewData['adminGroups'] = AdminGroup::all()->pluck('name', 'id')->prepend(__('Select'), 0);
        $this->viewData['row'] = $user;

        return view($this->viewDir.'form', $this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MaxDev\Models\Admin  $user
     * @return \Illuminate\Http\Response
     */
    public function update(AdminFormRequest $request, Admin $user)
    {
        $RequestedData = $request->only(['fullname','email','password','status','user_group','language']);
        if (isset($RequestedData['password'])) {
            $RequestedData['password'] = bcrypt($RequestedData['password']);
        } else {
            unset($RequestedData['password']);
        }

        if ($user->update($RequestedData)) {
            
            return $this->WithSuccess($this->routePrefix.'index', __('Successfully Updated '.$this->resourceName));
        } else {
            return $this->WithError([$this->routePrefix.'edit', $user->id], __('Couldn\'t update '.$this->resourceName));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MaxDev\Models\Admin  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $user)
    {
        $status = $user->delete();

        if (request()->ajax()) {
            return response()->json([
                'type'      =>      (($status) ? 'success' : 'error'),
                'msg'       =>      (($status) ? __($this->resourceName.' successfully deleted') : __('Can not delete '.$this->resourceName)),
            ]);
        } else {
            if ($status) {
                return $this->WithSuccess($this->routePrefix.'index', __('Successfully deleted '.$this->resourceName));
            } else {
                return $this->WithError($this->routePrefix.'index', __('Couldn\'t delete '.$this->resourceName));
            }
        }
    }

    public function setUserStatus(Request $request, GPService $GPService)
    {
        return (isset($request->user_status) && in_array($request->user_status, UserStatus::getValues())) ?
            response()->json(['status'=>$GPService->changeGPStatus(auth('admin')->user(), $request->user_status)])
            : response()->json(['status'=>UserStatus::getName(auth()->user()->user_status)])
            ;
    }
}
