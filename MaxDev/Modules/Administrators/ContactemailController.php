<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated DummyDateTime
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Modules\Administrators;

use Illuminate\Http\Request;
use MaxDev\Models\ContactEmail;
use MaxDev\Modules\Administrators\Requests\ContactemailFormRequest;
use Yajra\DataTables\DataTables;
use MaxDev\Models\EmailModel;
use MaxDev\Models\Contact;
use MaxDev\Services\ContactemailService;

class ContactemailController extends AdministratorsController
{
    public $viewDir = 'admin.contactemail.';
    public $routePrefix = 'admin.contactemail.';
    public $resourceName = 'contactemail';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ContactemailService $contactemailService)
    {
        $this->viewData['title'] = __($this->resourceName);
        $this->viewData['tableColumns'] = [__('ID'),__('Qr'),__('Send_email'),__('Send_message'),__('Scan_qr'),__('Email_model_id'),__('Contact_id'),__('Action')];
        $this->viewData['Cols'] = ['id','qr','send_email','send_message','scan_qr','email_model_id','contact_id','action'];
        $this->viewData['buttons'] = $this->template->generateButtons($this->routePrefix, null, 'index');

        if($request->isDataTable){
            $eloquentData = $contactemailService->adminIndex();
            // Filtering
            if($request->input('order.0.column') !== false && isset( $this->viewData['Cols'][$request->input('order.0.column')]) ){
                $column_name=$this->viewData['Cols'][$request->input('order.0.column')];
                switch ($column_name) {
                    case 'qr':
                        $Filterby='qr';
                    break;

                    case 'send_email':
                        $Filterby='send_email';
                    break;

                    case 'send_message':
                        $Filterby='send_message';
                    break;

                    case 'scan_qr':
                        $Filterby='scan_qr';
                    break;

                    case 'email_model_id':
                        $Filterby='email_model_id';
                    break;

                    case 'contact_id':
                        $Filterby='contact_id';
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

            if(isset($request->qr) && $request->qr){
                $attr=$request->qr;
                $eloquentData->where('qr', 'LIKE', '%'.$attr.'%');
            }

            if(isset($request->send_email) && $request->send_email){
                $attr=$request->send_email;
                $eloquentData->where('send_email', 'LIKE', '%'.$attr.'%');
            }

            if(isset($request->send_message) && $request->send_message){
                $attr=$request->send_message;
                $eloquentData->where('send_message', 'LIKE', '%'.$attr.'%');
            }

            if(isset($request->scan_qr) && $request->scan_qr){
                $attr=$request->scan_qr;
                $eloquentData->where('scan_qr', 'LIKE', '%'.$attr.'%');
            }

            if(isset($request->email_model_id) && $request->email_model_id){
                $attr=$request->email_model_id;
                $eloquentData->where('email_model_id', 'LIKE', '%'.$attr.'%');
            }

            if(isset($request->contact_id) && $request->contact_id){
                $attr=$request->contact_id;
                $eloquentData->where('contact_id', 'LIKE', '%'.$attr.'%');
            }


            if($request->input('search.value')){
                $name=$request->input('search.value');
                $eloquentData->where(function($query)use($name){
                    $query->where('id',$name)
                    ->orWhere('qr','LIKE','%'.$name.'%')
                    ->orWhere('send_email','LIKE','%'.$name.'%')
                    ->orWhere('send_message','LIKE','%'.$name.'%')
                    ->orWhere('scan_qr','LIKE','%'.$name.'%')
                    ->orWhere('email_model_id','LIKE','%'.$name.'%')
                    ->orWhere('contact_id','LIKE','%'.$name.'%')
                    ;
                });
            }


            // DataTables
            return DataTables::of($eloquentData)
                ->rawColumns(['action'])
                ->addColumn('id',function($data){
                    return $data->id;
                })
                ->addColumn('qr',function($data){
                    return $data->qr;
                })
                ->addColumn('send_email',function($data){
                    return $data->send_email;
                })
                ->addColumn('send_message',function($data){
                    return $data->send_message;
                })
                ->addColumn('scan_qr',function($data){
                    return $data->scan_qr;
                })
                ->addColumn('email_model_id',function($data){
                    return $data->email_model_id;
                })
                ->addColumn('contact_id',function($data){
                    return $data->contact_id;
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
                $this->viewData['emailModel'] = emailModel::all()->pluck('name','id');
        $this->viewData['contact'] = contact::all()->pluck('name','id');
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

                $this->viewData['emailModel'] = emailModel::all()->pluck('name','id');
        $this->viewData['contact'] = contact::all()->pluck('name','id');
        return $this->view($this->viewDir.'form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactemailFormRequest $request, ContactemailService $contactemailService)
    {
        $RequestedData = $request->validated();
        if($contactemailService->adminCreate($RequestedData)){
            return $this->WithSuccess($this->routePrefix.'index',__('Successfully added '.$this->resourceName));
        } else {
            return $this->WithSuccess($this->routePrefix.'create',__('Couldn\'t add '.$this->resourceName));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \MaxDev\Models\ContactEmail  $contactemail
     * @return \Illuminate\Http\Response
     */
    public function show(ContactEmail $contactemail)
    {
        $this->viewData['title'] = __('View '.$this->resourceName);
        $this->viewData['row'] = $contactemail;
        $this->viewData['buttons'] = $this->template->generateButtons($this->routePrefix, $contactemail, 'show');
        return $this->view($this->viewDir.'show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MaxDev\Models\ContactEmail  $contactemail
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactEmail $contactemail)
    {
        $this->viewData['title'] = __('Edit '.$this->resourceName).' '.$contactemail->name;
        $this->viewData['submit'] = __('Save '.$this->resourceName);
        $this->viewData['row'] = $contactemail;
                $this->viewData['emailModel'] = emailModel::all()->pluck('name','id');
        $this->viewData['contact'] = contact::all()->pluck('name','id');
        return $this->view($this->viewDir.'form');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MaxDev\Models\ContactEmail  $contactemail
     * @return \Illuminate\Http\Response
     */
    public function update(ContactemailFormRequest $request, ContactEmail $contactemail, ContactemailService $contactemailService)
    {
        $RequestedData = $request->validated();

        if($contactemailService->adminUpdate($RequestedData, $contactemail)){
            return $this->WithSuccess($this->routePrefix.'index',__('Successfully Updated '.$this->resourceName));
        } else {
            return $this->WithError([$this->routePrefix.'edit', $contactemail->id],__('Could not update '.$this->resourceName));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MaxDev\Models\ContactEmail  $contactemail
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactEmail $contactemail, ContactemailService $contactemailService)
    {
        $status = $contactemailService->adminDelete($contactemail);

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
