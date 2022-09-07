<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreOnBoardingRequest;
use App\Http\Requests\UpdateOnBoardingRequest;
use App\Http\Resources\Admin\OnBoardingResource;
use App\Models\OnBoarding;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnBoardingApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('on_boarding_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OnBoardingResource(OnBoarding::all());
    }

    public function store(StoreOnBoardingRequest $request)
    {
        $onBoarding = OnBoarding::create($request->all());

        if ($request->input('image', false)) {
            $onBoarding->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
        }

        if ($request->input('icon', false)) {
            $onBoarding->addMedia(storage_path('tmp/uploads/' . basename($request->input('icon'))))->toMediaCollection('icon');
        }

        return (new OnBoardingResource($onBoarding))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(OnBoarding $onBoarding)
    {
        abort_if(Gate::denies('on_boarding_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OnBoardingResource($onBoarding);
    }

    public function update(UpdateOnBoardingRequest $request, OnBoarding $onBoarding)
    {
        $onBoarding->update($request->all());

        if ($request->input('image', false)) {
            if (!$onBoarding->image || $request->input('image') !== $onBoarding->image->file_name) {
                if ($onBoarding->image) {
                    $onBoarding->image->delete();
                }
                $onBoarding->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
            }
        } elseif ($onBoarding->image) {
            $onBoarding->image->delete();
        }

        if ($request->input('icon', false)) {
            if (!$onBoarding->icon || $request->input('icon') !== $onBoarding->icon->file_name) {
                if ($onBoarding->icon) {
                    $onBoarding->icon->delete();
                }
                $onBoarding->addMedia(storage_path('tmp/uploads/' . basename($request->input('icon'))))->toMediaCollection('icon');
            }
        } elseif ($onBoarding->icon) {
            $onBoarding->icon->delete();
        }

        return (new OnBoardingResource($onBoarding))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(OnBoarding $onBoarding)
    {
        abort_if(Gate::denies('on_boarding_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $onBoarding->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
