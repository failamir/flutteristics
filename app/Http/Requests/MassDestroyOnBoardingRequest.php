<?php

namespace App\Http\Requests;

use App\Models\OnBoarding;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyOnBoardingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('on_boarding_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:on_boardings,id',
        ];
    }
}
