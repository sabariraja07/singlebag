@extends('layouts.app')
@section('head')
@include('layouts.partials.headersection',['title'=> trans('License') ])
@endsection

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12 mt-10 mb-10">
            <a href="{{ route('partner.license.create') }}" class="btn btn-success float-right" type="button">
                {{ __('Buy License') }}
            </a>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap card-table text-center">
                            <thead>
                                <tr>
                                    <th class="text-left">{{ __('Plan') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('License') }}</th>
                                    <th class="text-right">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="list font-size-base rowlink" data-link="row">
                                @foreach($posts ?? [] as $key => $row)
                                <tr>
                                    <td class="text-left"><a
                                            href="javascript:void(0);">{{ $row->name }}</a>
                                    </td>
                                    <td>{{ $row->price }}</td>
                                    <th>{{ $row->subscriptions_count ?? '0.00'}}</th>
                                    <td class="text-right">
                                        <a href="{{ route('partner.license.create_shop', $row->id) }}" class="btn btn-info" type="button">
                                            {{ __('Create Store') }}
                                        </a>
                                    </td>
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
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush
