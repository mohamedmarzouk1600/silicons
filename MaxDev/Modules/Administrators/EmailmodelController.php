<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated DummyDateTime
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Modules\Administrators;

use Illuminate\Http\Request;
use MaxDev\Models\EmailModel;
use MaxDev\Models\Contact;
use MaxDev\Models\ContactEmail;
use MaxDev\Models\Event;
use MaxDev\Modules\Administrators\Requests\EmailmodelFormRequest;
use Yajra\DataTables\DataTables;
use Mail;

use MaxDev\Services\EmailmodelService;

class EmailmodelController extends AdministratorsController
{
    public $viewDir = 'admin.emailmodel.';
    public $routePrefix = 'admin.emailmodel.';
    public $resourceName = 'emailmodel';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, EmailmodelService $emailmodelService)
    {
        $this->viewData['title'] = __($this->resourceName);
        $this->viewData['tableColumns'] = [__('ID'),__('Name'),__('Description'),__('Event'),__('Action')];
        $this->viewData['Cols'] = ['id','name','description','event_id','action'];
        $this->viewData['buttons'] = $this->template->generateButtons($this->routePrefix, null, 'index');

        if($request->isDataTable){
            $eloquentData = $emailmodelService->adminIndex();
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

            if(isset($request->event_id) && $request->event_id){
                $attr=$request->event_id;
                $eloquentData->where('event_id', 'LIKE', '%'.$attr.'%');
            }


            if($request->input('search.value')){
                $name=$request->input('search.value');
                $eloquentData->where(function($query)use($name){
                    $query->where('id',$name)
                    ->orWhere('name','LIKE','%'.$name.'%')
                    ->orWhere('description','LIKE','%'.$name.'%')
                    ;
                });
            }


            // DataTables
            return DataTables::of($eloquentData)
                ->rawColumns(['action','description'])
                ->addColumn('id',function($data){
                    return $data->id;
                })
                ->addColumn('name',function($data){
                    return $data->name;
                })
                ->addColumn('description',function($data){
                    return $data->description;
                })
                ->addColumn('event_id',function($data){
                    return optional($data->event)->name;
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
                            'route' =>  $this->routePrefix.'edit',
                            'url'   =>  route($this->routePrefix.'send.mail',$data->id),
                            'class' =>  'fa fa-envelope',
                            'text'  =>  __('Send Email'),
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

        $this->viewData['event'] = Event::all()->pluck('name','id');
        
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
        $this->viewData['event'] = Event::all()->pluck('name','id');

        
        return $this->view($this->viewDir.'form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmailmodelFormRequest $request, EmailmodelService $emailmodelService)
    {
        $RequestedData = $request->validated();
        if($emailmodelService->adminCreate($RequestedData)){
            return $this->WithSuccess($this->routePrefix.'index',__('Successfully added '.$this->resourceName));
        } else {
            return $this->WithSuccess($this->routePrefix.'create',__('Couldn\'t add '.$this->resourceName));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \MaxDev\Models\EmailModel  $emailmodel
     * @return \Illuminate\Http\Response
     */
    public function show(EmailModel $emailmodel)
    {
        $this->viewData['title'] = __('View '.$this->resourceName);
        $this->viewData['row'] = $emailmodel;
        $this->viewData['buttons'] = $this->template->generateButtons($this->routePrefix, $emailmodel, 'show');
        return $this->view($this->viewDir.'show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MaxDev\Models\EmailModel  $emailmodel
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailModel $emailmodel)
    {
        $this->viewData['title'] = __('Edit '.$this->resourceName).' '.$emailmodel->name;
        $this->viewData['submit'] = __('Save '.$this->resourceName);
        $this->viewData['row'] = $emailmodel;
        $this->viewData['event'] = Event::all()->pluck('name','id');
        
        return $this->view($this->viewDir.'form');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MaxDev\Models\EmailModel  $emailmodel
     * @return \Illuminate\Http\Response
     */
    public function update(EmailmodelFormRequest $request, EmailModel $emailmodel, EmailmodelService $emailmodelService)
    {
        $RequestedData = $request->validated();

        if($emailmodelService->adminUpdate($RequestedData, $emailmodel)){
            return $this->WithSuccess($this->routePrefix.'index',__('Successfully Updated '.$this->resourceName));
        } else {
            return $this->WithError([$this->routePrefix.'edit', $emailmodel->id],__('Could not update '.$this->resourceName));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MaxDev\Models\EmailModel  $emailmodel
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailModel $emailmodel, EmailmodelService $emailmodelService)
    {
        $status = $emailmodelService->adminDelete($emailmodel);

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

    public function sendMails(Request $request,$id, EmailmodelService $emailmodelService){
        $email = EmailModel::find($id);
        // $emailmodelService->insertContactEmail($email);
        for($i=0;$i<10;$i++){
            $j=$i*1000;
            $emils = Contact::select("email")->where('event_id',$email->event_id)->limit(1000)->offset($j)->pluck('email')->toArray();
            $title = $email->name;
            $content = $email->description;
            $event_id = $email->event_id;
            $route = route('form',['event'=>$event_id,'email'=>$id]);
            Mail::send('emails.send', ['title' => $title, 'content' => $content, 'route' => $route], function ($message) use($emils,$title)
            {

                $message->from('mohamedmarzouk16000@gmail.com', 'mohamed marzouk');
                $message->subject($title);
                $message->bcc($emils);

            });


            if( count(Mail::failures()) > 0 ) {
                ContactEmail::where('email_model_id',$id)->whereIn('email',Mail::failures())->update(['send_email'=>0]);
                // foreach(Mail::failures() as $email_address) {
                //     echo " - $email_address <br />";
                //  }
             
             }
        }
        return $this->WithSuccess($this->routePrefix.'index',__('Successfully Updated '.$this->resourceName));


    }

    
    
    
}
