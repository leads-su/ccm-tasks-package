<?php

namespace ConsulConfigManager\Tasks\Http\Requests\PipelineTask;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PipelineTaskCreateUpdateRequest
 * @package ConsulConfigManager\Tasks\Http\Requests\PipelineTask
 */
class PipelineTaskCreateUpdateRequest extends FormRequest
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
            'order'         =>  ['required', 'integer'],
        ];
    }
}
