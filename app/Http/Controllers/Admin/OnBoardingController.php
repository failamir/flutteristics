<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyOnBoardingRequest;
use App\Http\Requests\StoreOnBoardingRequest;
use App\Http\Requests\UpdateOnBoardingRequest;
use App\Models\OnBoarding;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class OnBoardingController extends Controller
{
    use MediaUploadingTrait;
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('on_boarding_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $onBoardings = OnBoarding::with(['media'])->get();

        return view('admin.onBoardings.index', compact('onBoardings'));
    }

    public function create()
    {
        abort_if(Gate::denies('on_boarding_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.onBoardings.create');
    }

    public function store(StoreOnBoardingRequest $request)
    {
        $onBoarding = OnBoarding::create($request->all());

        if ($request->input('image', false)) {
            $onBoarding->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $onBoarding->id]);
        }

        return redirect()->route('admin.on-boardings.index');
    }

    public function edit(OnBoarding $onBoarding)
    {
        abort_if(Gate::denies('on_boarding_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.onBoardings.edit', compact('onBoarding'));
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

        return redirect()->route('admin.on-boardings.index');
    }

    public function show(OnBoarding $onBoarding)
    {
        abort_if(Gate::denies('on_boarding_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.onBoardings.show', compact('onBoarding'));
    }

    public function destroy(OnBoarding $onBoarding)
    {
        abort_if(Gate::denies('on_boarding_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $onBoarding->delete();

        return back();
    }

    public function massDestroy(MassDestroyOnBoardingRequest $request)
    {
        OnBoarding::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('on_boarding_create') && Gate::denies('on_boarding_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new OnBoarding();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
