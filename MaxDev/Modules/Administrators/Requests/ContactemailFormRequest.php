<?php

namespace MaxDev\Modules\Administrators\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactemailFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rowid = $this->segment(3);

        $rules = [
            'qr'                  => 'required',
            'send_email'                  => 'required',
            'send_message'                  => 'required',
            'scan_qr'                  => 'required',
            'email_model_id'                  => 'required|exists:email_models,id',
            'contact_id'                  => 'required|exists:contacts,id',
        ];

        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST': {
                //Create
                return $rules;
            }
            case 'PUT':
            case 'PATCH':
                {
                    //Update
                    return $rules;

                }
            default:break;
        }

    }
}
