<?php

namespace ConsulConfigManager\Tasks\Http\Requests\Action;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ActionCreateUpdateRequest
 * @package ConsulConfigManager\Tasks\Http\Requests\Action
 */
class ActionCreateUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'          =>  ['required', 'string'],
            'description'   =>  ['required', 'string'],
            'type'          =>  ['required', 'integer'],
            'command'       =>  ['required', 'string'],
            'arguments'     =>  ['required', 'array'],
            'working_dir'   =>  ['nullable', 'string'],
            'run_as'        =>  ['nullable', 'string'],
            'use_sudo'      =>  ['boolean'],
            'fail_on_error' =>  ['boolean'],
        ];
    }
}
