<?php

namespace App\Http\Requests;

use App\Models\OnBoarding;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOnBoardingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('on_boarding_create');
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
