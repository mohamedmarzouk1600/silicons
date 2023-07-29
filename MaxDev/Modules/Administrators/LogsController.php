<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 05 ON 09 Oct 2018
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Modules\Administrators;

use App\Exceptions\TikshifException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;
use Jenssegers\Agent\Agent;

class LogsController extends AdministratorsController
{
    public $viewDir = 'admin.logs.';
    public $routePrefix = 'admin.logs.';
    public $resourceName = 'log';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->viewData['title'] = __($this->resourceName.'s');
        $this->viewData['tableColumns'] = [__('ID'),__('Description'), __('Subject'),__('User'),__('On'),__('Action')];
        $this->viewData['Cols'] = ['id','description','subject','causer','created_at','action'];

        if ($request->isDataTable) {
            $eloquentData = Activity::with(['causer'])
                ->select([
                    'id',
                    'log_name',
                    'description',
                    'subject_id',
                    'subject_type',
                    'causer_id',
                    'causer_type',
                    'created_at',
                    'updated_at'
                ])->OrderByDesc('id');

            if ($request->created_at1 || $request->created_at2) {
                WhereBetween($eloquentData, 'created_at', $request->created_at1, $request->created_at2);
            }

            if ($request->id) {
                $eloquentData->where('id', '=', $request->id);
            }

            if ($request->description) {
                $eloquentData->where('description', '=', $request->description);
            }

            if ($request->subject_type) {
                $eloquentData->where('subject_type', '=', $request->subject_type);
            }

            if ($request->subject_id) {
                $eloquentData->where('subject_id', '=', $request->subject_id);
            }

            if ($request->causer_type) {
                $eloquentData->where('causer_type', '=', $request->causer_type);
            }

            if ($request->causer_id) {
                $eloquentData->where('causer_id', '=', $request->causer_id);
            }

            return DataTables::of($eloquentData)
                ->addColumn('id', function ($data) {
                    return $data->id;
                })
                ->addColumn('description', function ($data) {
                    return $data->description;
                })
                ->addColumn('subject', function ($data) {
                    return $data->subject_type.' ('.$data->subject_id.')';
                })
                ->addColumn('causer', function ($data) {
                    return $data->causer_type.' ('.$data->causer_id.')';
                })
                ->addColumn('created_at', function ($data) {
                    return $data->created_at;
                })

                ->addColumn('action', function ($data) {
                    return $this->template->generateRowDropDown([
                        [
                            'route' =>  $this->routePrefix.'show',
                            'url'   =>  route($this->routePrefix.'show', $data->id),
                            'class' =>  'fa fa-eye',
                            'text'  =>  __('View'),
                        ],
                    ]);
                })
                ->make(true);
        }
        return view($this->viewDir.'index', $this->viewData);
    }

    public function show($ID)
    {
        $this->viewData['title'] = __('View '.$this->resourceName);
        $row = Activity::findOrFail($ID);

        $agent = new Agent();
        $agent->setUserAgent($row->user_agent);
        $row->agent = $agent;

        $location = @json_decode(file_get_contents('http://ip-api.com/json/'.$row->ip));
        if ($location->status!='fail') {
            $row->location = $location;
        }

        $this->viewData['row'] = $row;
        return view($this->viewDir.'show', $this->viewData);
    }

    /**
     * @throws TikshifException
     */
    public function restore_deleted_model($model, $id): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $model::withTrashed()->find($id)->restore();
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            return $this->WithError($this->routePrefix . 'index', __('Could Not Restore Deleted Model'));
        }

        return $this->WithSuccess($this->routePrefix . 'index', __('Deleted Model Restored Successfully'));
    }
}
