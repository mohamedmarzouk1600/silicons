<?php

namespace MaxDev\Modules\Administrators\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use MaxDev\Enums\UserGroupType;

class AdminGroupFormRequest extends FormRequest
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
            'name'                  => 'required|max:200',
            'home_url'              => 'required',
            'url_index'             => 'sometimes',
            'status'                => 'required',
            'user_group'            => 'required|in:'.implode(',', UserGroupType::getValues()).'|unique:admin_groups,user_group',
            'permission.*'          => '',
            'question_category_id'  => 'sometimes|nullable|exists:question_categories,id'
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
                    $rules['user_group'] = 'required|in:'.implode(',', UserGroupType::getValues()).'|unique:admin_groups,user_group,'.$rowid;
                    return $rules;

                }
            default:break;
        }
    }
}
