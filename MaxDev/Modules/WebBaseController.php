<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 10/7/2018 9:10 PM
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Modules;
use Illuminate\Http\Request;
use MaxDev\Models\EmailModel;
use MaxDev\Models\Contact;
use MaxDev\Models\ContactEmail;
use MaxDev\Modules\Administrators\Requests\GetFormRequest;
use Mail;

use App\Http\Controllers\Controller;

class WebBaseController extends Controller
{
    public $viewData = [];

    public function WithSuccess($route, $msg)
    {
        if (is_array($route)) {
            return redirect()->route($route[0], $route[1])
                ->with('status', true)
                ->with('msg', $msg);
        } else {
            return redirect()->route($route)
                ->with('status', true)
                ->with('msg', $msg);
        }
    }

    public function WithError($route, $msg)
    {
        if (is_array($route)) {
            return redirect()->route($route[0], $route[1])
                ->with('status', false)
                ->with('msg', $msg);
        } else {
            return redirect()->route($route)
                ->with('status', false)
                ->with('msg', $msg);
        }
    }

    public function view($view, $data=[])
    {
        return view($view, array_merge($this->viewData, $data));
    }

    public function apiResponse()
    {
        return (new \MaxDev\Modules\APIController());
    }

    public function getForm($event,$email)
    {
        return view('form',['event_id'=>$event,'email_id'=>$email]);
    }

    public function form(GetFormRequest $request)
    {
        $contactEmail = $this->insertContactEmail($request->validated());
        $title = $contactEmail->email->name;
        $content = $contactEmail->email->description;

        Mail::send('emails.send', ['title' => $title, 'content' => $content, 'qr' => $contactEmail->qr], function ($message) use($contactEmail,$title)
            {

                $message->from('mohamedmarzouk1600@gmail.com', 'mohamed marzouk');
                $message->subject($title);
                $message->to($contactEmail->contact->email);

            });
        return $this->WithSuccess('form',__('Successfully send QR code .'));

    }

    public function insertContactEmail($data)
    {
       $contact = Contact::where(['email'=>$data['email'],'event_id'=>$data['event_id']])->first();
       
        $arr['email_model_id'] = $data['email_id'];
        $arr['contact_id'] = $contact->id;
        $arr['qr'] = $this->generateQr();
            
        return ContactEmail::create($arr);
    }

    public function generateQr() {
    	$qr = mt_rand(100000, 99999999);
    	if(ContactEmail::where('qr',$qr)->count() > 0) {
    		$this->generateQr();
    	} else {
    		return $qr;
    	}
    }
}
