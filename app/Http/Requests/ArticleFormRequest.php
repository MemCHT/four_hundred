<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleFormRequest extends FormRequest
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
        // dd($this->all());


        return [
            'status_id' => 'required',
            'title' => 'required|max:40',
            'body' => 'required|max:400',
            'published_year' => 'required',
            'published_month' => 'required',
            'published_day' => 'required',
        ];
    }
}
