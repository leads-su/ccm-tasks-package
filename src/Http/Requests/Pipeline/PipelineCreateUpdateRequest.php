<?php

namespace ConsulConfigManager\Tasks\Http\Requests\Pipeline;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PipelineCreateUpdateRequest
 * @package ConsulConfigManager\Tasks\Http\Requests\Pipeline
 */
class PipelineCreateUpdateRequest extends FormRequest
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
            'tasks'         =>  ['sometimes', 'required', 'array'],
            'tasks.*.uuid'  =>  ['sometimes', 'required', 'string'],
        ];
    }
}
