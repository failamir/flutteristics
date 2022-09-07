@extends('layouts.admin')
@section('content')
@can('on_boarding_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.on-boardings.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.onBoarding.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'OnBoarding', 'route' => 'admin.on-boardings.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.onBoarding.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-OnBoarding">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.onBoarding.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.onBoarding.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.onBoarding.fields.image') }}
                        </th>
                        <th>
                            {{ trans('cruds.onBoarding.fields.icon') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($onBoardings as $key => $onBoarding)
                        <tr data-entry-id="{{ $onBoarding->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $onBoarding->id ?? '' }}
                            </td>
                            <td>
                                {{ $onBoarding->title ?? '' }}
                            </td>
                            <td>
                                @if($onBoarding->image)
                                    <a href="{{ $onBoarding->image->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $onBoarding->image->getUrl('thumb') }}">
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if($onBoarding->icon)
                                    <a href="{{ $onBoarding->icon->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $onBoarding->icon->getUrl('thumb') }}">
                                    </a>
                                @endif
                            </td>
                            <td>
                                @can('on_boarding_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.on-boardings.show', $onBoarding->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('on_boarding_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.on-boardings.edit', $onBoarding->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('on_boarding_delete')
                                    <form action="{{ route('admin.on-boardings.destroy', $onBoarding->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('on_boarding_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.on-boardings.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-OnBoarding:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection