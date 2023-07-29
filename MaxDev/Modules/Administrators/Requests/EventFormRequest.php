<?php

namespace MaxDev\Modules\Administrators\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventFormRequest extends FormRequest
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
            'name'                  => 'required',
            'description'                  => 'required',
            'from_date'                  => 'required|date_format:Y-m-d H:i:s',
            'to_date'                  => 'required|date_format:Y-m-d H:i:s|after:from_date',
            'lat'                  => 'required',
            'lng'                  => 'required',
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

    public function prepareForValidation()
    {
        $this->merge([
            'from_date' => date('Y-m-d H:i:s', strtotime($this->from_date))
        ]);

        $this->merge([
            'to_date' => date('Y-m-d H:i:s', strtotime($this->to_date))
        ]);
    }
}
