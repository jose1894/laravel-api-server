<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TipoPolizaRequest extends FormRequest
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
        
        $id = "";
        if (request()->method() === 'PATCH' || request()->method() === 'PUT'){
            $id = $this->tipo_poliza;
        }

        return [
            'descripcion' => 'required|max:100|unique:tipo_poliza,id,'.$id
        ];
    }
}
