<?php

namespace App\Http\Requests;

use App\Rules\CadNumbersStringRule;
use Illuminate\Foundation\Http\FormRequest;

class PlotRegistryFormRequest extends FormRequest
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
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function rules()
    {
        return [
            'cadastralNumber' => [
                'required',
                new CadNumbersStringRule($this),
            ],
        ];
    }
}
