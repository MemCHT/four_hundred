<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

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
        $dateConfirmation = Carbon::createMidnightDate($this->input('published_year'), $this->input('published_month')+1, 0);

        return [
            'status_id' => 'required',
            'title' => 'required|max:40',
            'body' => 'required|max:400',
            'published_year' => 'required|date_format:Y',
            'published_month' => 'required|date_format:m',
            'published_date' => "required|lte:".$dateConfirmation->day, // 入力年月をもとに、日付の整合性チェック
        ];
    }

    /**
     * バリデーションエラーのカスタム属性
     */
    public function attributes(){
        return [
            'published_year' => '公開年',
            'published_month' => '公開月',
            'published_date' => '公開日'
        ];
    }
}
