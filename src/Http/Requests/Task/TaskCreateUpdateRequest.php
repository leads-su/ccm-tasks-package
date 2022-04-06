<?php

namespace ConsulConfigManager\Tasks\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class TaskCreateUpdateRequest
 * @package ConsulConfigManager\Tasks\Http\Requests\Task
 */
class TaskCreateUpdateRequest extends FormRequest
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
            'name'              =>  ['required', 'string'],
            'description'       =>  ['required', 'string'],
            'fail_on_error'     =>  ['sometimes', 'required', 'boolean'],
            'actions'           =>  ['sometimes', 'required', 'array'],
            'actions.*.uuid'    =>  ['sometimes', 'required', 'string'],
        ];
    }
}
