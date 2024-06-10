@extends('layouts.app')
@section('head')
    @include('layouts.partials.headersection', ['title' => 'PWA Settings'])
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-2">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-center table-borderless">
                            <thead>
                                <tr>
                                    <th>{{ __('S.NO') }}</th>
                                    <th>{{ __('APP Icon') }}</th>
                                    <th>{{ __('APP Title') }}</th>
                                    <th>{{ __('App Name') }}</th>
                                    <th>{{ __('APP Background Color') }}</th>
                                    <th>{{ __('APP Theme Color') }}</th>
                                    <th>{{ __('APP Main Language') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1;  @endphp
                                @foreach ($shop as $shops)
                                    @if (file_exists('uploads/' . $shops . '/manifest.json'))
                                        @php
                                        $pwa = file_get_contents('uploads/' . $shops . '/manifest.json');
                                        $pwa = json_decode($pwa); @endphp

                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td><img src="{{ asset('uploads/' . $shops . '/192x192.png') }}"
                                                    style="border-radius:50%;height:100px;width:100px" alt="Icon"></td>
                                            <td>{{ $pwa->name ?? '' }}</td>
                                            <td>{{ $pwa->short_name ?? '' }}</td>
                                            <td>{{ $pwa->background_color ?? '' }}</td>
                                            <td>{{ $pwa->theme_color ?? '' }}</td>
                                            <td>{{ $pwa->lang ?? '' }}</td>
                                            <td>{{ $pwa->pwa_status ?? 'Pending' }}</td>

                                            <td>
                                                <div class="dropdown d-inline">
                                                    <button class="btn btn-info dropdown-toggle" type="button"
                                                        id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        {{ __('Action') }}
                                                    </button>
                                                    <div class="dropdown-menu">

                                                        <a class="dropdown-item has-icon"
                                                            href="{{ route('admin.pwa.edit', $shops) }}"><i
                                                                class="fas fa-user-edit"></i> {{ __('Edit') }}</a>
                                                        <a class="dropdown-item has-icon"
                                                                href="{{ route('admin.pwa.download', $shops) }}"><i
                                                                    class="fas fa-download"></i> {{ __('Download') }}</a>        
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>{{ __('S.NO') }}</th>
                                    <th>{{ __('APP Icon') }}</th>
                                    <th>{{ __('APP Title') }}</th>
                                    <th>{{ __('App Name') }}</th>
                                    <th>{{ __('APP Background Color') }}</th>
                                    <th>{{ __('APP Theme Color') }}</th>
                                    <th>{{ __('APP Main Language') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
<script src="{{ asset('assets/js/form.js') }}"></script>
@endpush
