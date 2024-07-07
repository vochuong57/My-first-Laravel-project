<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\{ModuleTemplate};
use App\Rules\Check{ModuleTemplate}ChildrenRule;

class Delete{ModuleTemplate}Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        ${moduleTemplate}Id= $this->route('id');
        //echo ${moduleTemplate}Id; die();
        return [
            'name'=>[
                new Check{ModuleTemplate}ChildrenRule(${moduleTemplate}Id)
            ],
        ];
    }
}
