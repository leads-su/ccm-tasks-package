<?php

namespace ConsulConfigManager\Tasks\Http\Requests\PipelinePermission;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PipelinePermissionCreateDeleteRequest
 * @package ConsulConfigManager\Tasks\Http\Requests\Permission
 */
class PipelinePermissionCreateDeleteRequest extends FormRequest
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
            'user_id'           =>  ['required', 'integer'],
            'pipeline_uuid'     =>  ['required', 'string'],
        ];
    }
}
