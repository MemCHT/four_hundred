<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ProfileFormRequest extends FormRequest
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
        $dateConfirmation = Carbon::createMidnightDate($this->input('birth_year'), $this->input('birth_month')+1, 0);

        return [
            'name' => ['required', 'string', 'max:255'],
            'icon' => [
                'nullable',
                'file',
                'image',
                'mimes:jpeg,png',
                'dimensions:min_width=200,min_height=200',
                'max:5120'// 5MB
            ],
            'email' => ['required', Rule::unique('users')->ignore(Auth::guard('user')->user()->id)],
            'birth_year' => ['required','date_format:Y'],
            'birth_month' => ['required', 'date_format:m'],
            'birth_date' => ['required','gte:1','lte:'.$dateConfirmation->day], // 2020/12/23_日付のみだとdateのほうが良い（birthdayという別の意味と被るので）。
        ];
    }
}
