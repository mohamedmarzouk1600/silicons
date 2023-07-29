<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated DummyDateTime
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Modules\Administrators;

use Illuminate\Http\Request;
use MaxDev\Models\Contact;
use MaxDev\Modules\Administrators\Requests\ContactFormRequest;
use Yajra\DataTables\DataTables;
use MaxDev\Models\Event;
use MaxDev\Services\ContactService;

class ContactController extends AdministratorsController
{
    public $viewDir = 'admin.contact.';
    public $routePrefix = 'admin.contact.';
    public $resourceName = 'contact';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ContactService $contactService)
    {
        $this->viewData['title'] = __($this->resourceName);
        $this->viewData['tableColumns'] = [__('ID'),__('Email'),__('Phone'),__('Type'),__('Event_id'),__('Action')];
        $this->viewData['Cols'] = ['id','email','phone','type','event_id','action'];
        $this->viewData['buttons'] = $this->template->generateButtons($this->routePrefix, null, 'index');

        if($request->isDataTable){
            $eloquentData = $contactService->adminIndex();
            // Filtering
            if($request->input('order.0.column') !== false && isset( $this->viewData['Cols'][$request->input('order.0.column')]) ){
                $column_name=$this->viewData['Cols'][$request->input('order.0.column')];
                switch ($column_name) {
                    case 'email':
                        $Filterby='email';
                    break;

                    case 'phone':
                        $Filterby='phone';
                    break;

                    case 'type':
                        $Filterby='type';
                    break;

                    case 'event_id':
                        $Filterby='event_id';
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

            if(isset($request->email) && $request->email){
                $attr=$request->email;
                $eloquentData->where('email', 'LIKE', '%'.$attr.'%');
            }

            if(isset($request->phone) && $request->phone){
                $attr=$request->phone;
                $eloquentData->where('phone', 'LIKE', '%'.$attr.'%');
            }

            if(isset($request->type) && $request->type){
                $attr=$request->type;
                $eloquentData->where('type', 'LIKE', '%'.$attr.'%');
            }

            if(isset($request->event_id) && $request->event_id){
                $attr=$request->event_id;
                $eloquentData->where('event_id', 'LIKE', '%'.$attr.'%');
            }


            if($request->input('search.value')){
                $name=$request->input('search.value');
                $eloquentData->where(function($query)use($name){
                    $query->where('id',$name)
                    ->orWhere('email','LIKE','%'.$name.'%')
                    ->orWhere('phone','LIKE','%'.$name.'%')
                    ->orWhere('type','LIKE','%'.$name.'%')
                    ->orWhere('event_id','LIKE','%'.$name.'%')
                    ;
                });
            }


            // DataTables
            return DataTables::of($eloquentData)
                ->rawColumns(['action'])
                ->addColumn('id',function($data){
                    return $data->id;
                })
                ->addColumn('email',function($data){
                    return $data->email;
                })
                ->addColumn('phone',function($data){
                    return $data->phone;
                })
                ->addColumn('type',function($data){
                    return $data->type;
                })
                ->addColumn('event_id',function($data){
                    return $data->event_id;
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
                            'route' =>  $this->routePrefix.'destroy',
                            'onclick'   =>  'deleteRecord(\''.route($this->routePrefix.'destroy',$data->id).'\',this)',
                            'class' =>  'fa fa-trash',
                            'text'  =>  __('Delete'),
                        ],
                    ]);
                })
                ->make(true);
        }
                $this->viewData['event'] = event::all()->pluck('name','id');
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

                $this->viewData['event'] = event::all()->pluck('name','id');
        return $this->view($this->viewDir.'form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactFormRequest $request, ContactService $contactService)
    {
        $RequestedData = $request->validated();
        if($contactService->adminCreate($RequestedData)){
            return $this->WithSuccess($this->routePrefix.'index',__('Successfully added '.$this->resourceName));
        } else {
            return $this->WithSuccess($this->routePrefix.'create',__('Couldn\'t add '.$this->resourceName));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \MaxDev\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        $this->viewData['title'] = __('View '.$this->resourceName);
        $this->viewData['row'] = $contact;
        $this->viewData['buttons'] = $this->template->generateButtons($this->routePrefix, $contact, 'show');
        return $this->view($this->viewDir.'show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MaxDev\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        $this->viewData['title'] = __('Edit '.$this->resourceName).' '.$contact->name;
        $this->viewData['submit'] = __('Save '.$this->resourceName);
        $this->viewData['row'] = $contact;
                $this->viewData['event'] = event::all()->pluck('name','id');
        return $this->view($this->viewDir.'form');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MaxDev\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(ContactFormRequest $request, Contact $contact, ContactService $contactService)
    {
        $RequestedData = $request->validated();

        if($contactService->adminUpdate($RequestedData, $contact)){
            return $this->WithSuccess($this->routePrefix.'index',__('Successfully Updated '.$this->resourceName));
        } else {
            return $this->WithError([$this->routePrefix.'edit', $contact->id],__('Could not update '.$this->resourceName));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MaxDev\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact, ContactService $contactService)
    {
        $status = $contactService->adminDelete($contact);

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
}
