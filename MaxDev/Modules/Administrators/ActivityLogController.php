<?php

namespace MaxDev\Modules\Administrators;

use MaxDev\Enums\ActivitylogPatient;
use MaxDev\Models\Patient;
use Yajra\DataTables\DataTables;
use ReflectionException;

class ActivityLogController extends AdministratorsController
{
    public string $resourceName = 'Activity Log';

    /**
     * Display A Listing Of The Resource.
     * @param $patient_id
     * @return mixed
     * @throws ReflectionException
     */
    public function index($patient_id)
    {
        $eloquentData = \DB::table('activity_log')
            ->where(['causer_type'=>Patient::class,'causer_id'=>$patient_id])
            ->whereIn('log_name', ActivitylogPatient::getValues())
            ->orderByDesc('id');

        if (request()->input('search.value')) {
            $name = request()->input('search.value');
            $eloquentData->where('log_name', 'LIKE', '%' . $name . '%');
            $eloquentData->orWhere('description', 'LIKE', '%' . $name . '%');
        }

        return DataTables::of($eloquentData)
            ->addColumn('id', function ($data) {
                return $data->id;
            })
            ->addColumn('log_name', function ($data) {
                return $data->log_name;
            })
            ->addColumn('description', function ($data) {
                return $data->description;
            })
            ->addColumn('subject_id', function ($data) {
                return $data->subject_id;
            })
            ->addColumn('subject_type', function ($data) {
                return $data->subject_type;
            })
            ->addColumn('event', function ($data) {
                return $data->event;
            })
            ->addColumn('properties', function ($data) {
                return $data->properties;
            })
            ->addColumn('ip', function ($data) {
                return $data->ip;
            })
            ->addColumn('user_agent', function ($data) {
                return $data->user_agent;
            })
            ->addColumn('url', function ($data) {
                return $data->url;
            })
            ->addColumn('method', function ($data) {
                return $data->method;
            })
            ->addColumn('created_at', function ($data) {
                return $data->created_at;
            })
            ->make(true);
    }

}
