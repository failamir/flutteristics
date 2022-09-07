<?php

namespace App\Http\Requests;

use App\Models\OnBoarding;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateOnBoardingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('on_boarding_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'nullable',
            ],
        ];
    }
}
