@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection', ['title' => ' Currency'])
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('admin.currencies.create') }}" class="btn btn-success">{{ __('Create Currency') }}</a>

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-nowrap card-table text-center">
                <thead>
                    <tr>

                        <th>{{ __('#') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Code') }}</th>
                        <th>{{ __('Symbol') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Created At') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                @php
                $i = 1;
                @endphp
                <tbody class="list font-size-base rowlink" data-link="row">
                    @foreach ($posts as $row)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $row->name ?? '' }}</td>
                        <td>{{ $row->code ?? '' }}</td>
                        <td>{{ $row->symbol ?? '' }}</td>
                        <td>
                            @if ($row->status == 0)
                            <span class="badge badge-danger">{{ __('Inactive') }}</span>
                            @elseif($row->status == 1)
                            <span class="badge badge-success">{{ __('Active') }}</span>
                            @endif
                        </td>
                        <td>{{ $row->created_at->format('d-F-Y') ?? '' }}</td>
                        <td><a href="{{ route('admin.currencies.edit', $row->id) }}" class="btn btn-info btn-sm text-center"><i class="far fa-edit"></i></a></td>

                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
        {{ $posts->appends($request->all())->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
@endsection