<?php

namespace MaxDev\Modules\Administrators\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetFormRequest extends FormRequest
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
            'email'                  => 'required|exists:contacts,email,event_id,' . $this->event_id,
            'phone'                  => 'required',
            'event_id'                  => 'required|exists:events,id',
            'email_id'                  => 'required|exists:email_models,id',
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
