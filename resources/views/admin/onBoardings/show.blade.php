@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.onBoarding.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.on-boardings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.onBoarding.fields.id') }}
                        </th>
                        <td>
                            {{ $onBoarding->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.onBoarding.fields.title') }}
                        </th>
                        <td>
                            {{ $onBoarding->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.onBoarding.fields.description') }}
                        </th>
                        <td>
                            {!! $onBoarding->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.onBoarding.fields.image') }}
                        </th>
                        <td>
                            @if($onBoarding->image)
                                <a href="{{ $onBoarding->image->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $onBoarding->image->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.on-boardings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection