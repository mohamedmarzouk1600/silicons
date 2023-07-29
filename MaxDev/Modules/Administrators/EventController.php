<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated DummyDateTime
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Modules\Administrators;

use Illuminate\Http\Request;
use MaxDev\Models\Event;
use MaxDev\Modules\Administrators\Requests\EventFormRequest;
use Yajra\DataTables\DataTables;

use MaxDev\Services\EventService;
use PHPExcel;
use Mail;
use PHPExcel_IOFactory;
use MaxDev\Imports\ContactsImport;

class EventController extends AdministratorsController
{
    public $viewDir = 'admin.event.';
    public $routePrefix = 'admin.event.';
    public $resourceName = 'event';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, EventService $eventService)
    {
        $this->viewData['title'] = __($this->resourceName);
        $this->viewData['tableColumns'] = [__('ID'),__('Name'),__('Description'),__('From_date'),__('To_date'),__('Lat'),__('Lng'),__('Action')];
        $this->viewData['Cols'] = ['id','name','description','from_date','to_date','lat','lng','action'];
        $this->viewData['buttons'] = $this->template->generateButtons($this->routePrefix, null, 'index');

        if($request->isDataTable){
            $eloquentData = $eventService->adminIndex();
            // Filtering
            if($request->input('order.0.column') !== false && isset( $this->viewData['Cols'][$request->input('order.0.column')]) ){
                $column_name=$this->viewData['Cols'][$request->input('order.0.column')];
                switch ($column_name) {
                    case 'name':
                        $Filterby='name';
                    break;

                    case 'description':
                        $Filterby='description';
                    break;

                    case 'from_date':
                        $Filterby='from_date';
                    break;

                    case 'to_date':
                        $Filterby='to_date';
                    break;

                    case 'lat':
                        $Filterby='lat';
                    break;

                    case 'lng':
                        $Filterby='lng';
                    break;

                    default:
                        $Filterby='id';
                }
                if(isset($Filterby))
                    $eloquentData->orderBy( $Filterby, $request->input('order.0.dir'));
            }
            if( $request->created_at1 || $request->created_at2 ){
                WhereBetween($eloquentData, $request->created_at1, $request->created_at2);
            }

            // Filtering
            if($request->id){
                $eloquentData->where('id',$request->id);
            }

            if(isset($request->name) && $request->name){
                $attr=$request->name;
                $eloquentData->where('name', 'LIKE', '%'.$attr.'%');
            }

            if(isset($request->description) && $request->description){
                $attr=$request->description;
                $eloquentData->where('description', 'LIKE', '%'.$attr.'%');
            }

            if(isset($request->from_date) && $request->from_date){
                $attr=$request->from_date;
                $eloquentData->where('from_date', 'LIKE', '%'.$attr.'%');
            }

            if(isset($request->to_date) && $request->to_date){
                $attr=$request->to_date;
                $eloquentData->where('to_date', 'LIKE', '%'.$attr.'%');
            }

            if(isset($request->lat) && $request->lat){
                $attr=$request->lat;
                $eloquentData->where('lat', 'LIKE', '%'.$attr.'%');
            }

            if(isset($request->lng) && $request->lng){
                $attr=$request->lng;
                $eloquentData->where('lng', 'LIKE', '%'.$attr.'%');
            }


            if($request->input('search.value')){
                $name=$request->input('search.value');
                $eloquentData->where(function($query)use($name){
                    $query->where('id',$name)
                    ->orWhere('name','LIKE','%'.$name.'%')
                    ->orWhere('description','LIKE','%'.$name.'%')
                    ->orWhere('from_date','LIKE','%'.$name.'%')
                    ->orWhere('to_date','LIKE','%'.$name.'%')
                    ->orWhere('lat','LIKE','%'.$name.'%')
                    ->orWhere('lng','LIKE','%'.$name.'%')
                    ;
                });
            }


            // DataTables
            return DataTables::of($eloquentData)
                ->rawColumns(['action'])
                ->addColumn('id',function($data){
                    return $data->id;
                })
                ->addColumn('name',function($data){
                    return $data->name;
                })
                ->addColumn('description',function($data){
                    return $data->description;
                })
                ->addColumn('from_date',function($data){
                    return $data->from_date;
                })
                ->addColumn('to_date',function($data){
                    return $data->to_date;
                })
                ->addColumn('lat',function($data){
                    return $data->lat;
                })
                ->addColumn('lng',function($data){
                    return $data->lng;
                })
                ->addColumn('action',function($data){
                    return $this->template->generateRowDropDown([
                        [
                            'route' =>  $this->routePrefix.'show',
                            'url'   =>  route($this->routePrefix.'show',$data->id),
                            'class' =>  'fa fa-eye',
                            'text'  =>  __('View'),
                        ],
                        [
                            'route' =>  $this->routePrefix.'edit',
                            'url'   =>  route($this->routePrefix.'edit',$data->id),
                            'class' =>  'fa fa-edit',
                            'text'  =>  __('Edit'),
                        ],
                        [
                            'route' =>  $this->routePrefix.'upload',
                            'url'   =>  route($this->routePrefix.'upload',$data->id),
                            'class' =>  'fa fa-upload',
                            'text'  =>  __('Upload'),
                        ],
                        [
                            'route' =>  $this->routePrefix.'destroy',
                            'onclick'   =>  'deleteRecord(\''.route($this->routePrefix.'destroy',$data->id).'\',this)',
                            'class' =>  'fa fa-trash',
                            'text'  =>  __('Delete'),
                        ],
                    ]);
                })
                ->make(true);
        }
        
        return $this->view($this->viewDir.'index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->viewData['title'] = __('New '.$this->resourceName);
        $this->viewData['submit'] = __('Add '.$this->resourceName);

        
        return $this->view($this->viewDir.'form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventFormRequest $request, EventService $eventService)
    {
        $RequestedData = $request->validated();
        if($eventService->adminCreate($RequestedData)){
            return $this->WithSuccess($this->routePrefix.'index',__('Successfully added '.$this->resourceName));
        } else {
            return $this->WithSuccess($this->routePrefix.'create',__('Couldn\'t add '.$this->resourceName));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \MaxDev\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $this->viewData['title'] = __('View '.$this->resourceName);
        $this->viewData['row'] = $event;
        $this->viewData['buttons'] = $this->template->generateButtons($this->routePrefix, $event, 'show');
        return $this->view($this->viewDir.'show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MaxDev\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $this->viewData['title'] = __('Edit '.$this->resourceName).' '.$event->name;
        $this->viewData['submit'] = __('Save '.$this->resourceName);
        $this->viewData['row'] = $event;
        
        return $this->view($this->viewDir.'form');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MaxDev\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(EventFormRequest $request, Event $event, EventService $eventService)
    {
        $RequestedData = $request->validated();

        if($eventService->adminUpdate($RequestedData, $event)){
            return $this->WithSuccess($this->routePrefix.'index',__('Successfully Updated '.$this->resourceName));
        } else {
            return $this->WithError([$this->routePrefix.'edit', $event->id],__('Could not update '.$this->resourceName));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MaxDev\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event, EventService $eventService)
    {
        $status = $eventService->adminDelete($event);

        if(request()->ajax()){
            return response()->json([
                'type'      =>      (($status)?'success':'error'),
                'msg'       =>      (($status)?__('Successfully deleted '.$this->resourceName):__('Could not delete '.$this->resourceName)),
            ]);
        } else {
            if($status)
                return $this->WithSuccess($this->routePrefix.'index',__('Successfully deleted '.$this->resourceName));
            else
                return $this->WithError($this->routePrefix.'index',__('Could not delete '.$this->resourceName));
        }
    }
    
    public function upload(Event $event)
    {
        $this->viewData['title'] = __('Upload '.$this->resourceName).' '.$event->name;
        $this->viewData['submit'] = __('Save '.$this->resourceName);
        $this->viewData['row'] = $event;
        
        return $this->view($this->viewDir.'upload');
    }

    public function import(Request $request){
       
        Excel::import(new ContactsImport,$request->file('upload_file')->store('temp'));
        
        return $this->WithSuccess($this->routePrefix.'index',__('Successfully Updated '.$this->resourceName));

    }

}
