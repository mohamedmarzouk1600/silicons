<?php

namespace MaxDev\Modules\Administrators\Requests;

use Illuminate\Foundation\Http\FormRequest;
use MaxDev\Enums\Status;
use MaxDev\Enums\UserGroupType;

class AdminFormRequest extends FormRequest
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
            'fullname'                  => 'required|max:200',
            'email'                     => 'required|email|unique:users,email',
            'password'                  => 'sometimes|confirmed',
            'status'                    => 'required|in:'.implode(',', Status::getValues()),
            'user_group'                => 'required|in:'.implode(',', UserGroupType::getValues()),
        ];

        switch ($this->method()) {
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
                    $rules['email'] = 'required|email|unique:users,email,'.$rowid;
                    $rules['password'] = '';
                    return $rules;

                }
            default:break;
        }
    }
}
