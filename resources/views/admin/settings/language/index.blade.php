@extends('layouts.app')
@section('head')
    @include('layouts.partials.headersection', ['title' => 'Language'])
    <style>
        .lt-bg-green-400 {
        --tw-bg-opacity: 1;
        background-color: rgb(74 222 128/var(--tw-bg-opacity));
        }
        .lt-border-green-500 {
        --tw-border-opacity: 1;
        border-color: rgb(34 197 94/var(--tw-border-opacity));
        }
        .lt-bg-red-400 {
        --tw-bg-opacity: 1;
        background-color: red;
        }
        .lt-border-red-500 {
        --tw-border-opacity: 1;
        border-color: red;
        }
        .lt-border {
        border-width: 1px;
        }
        .lt-rounded-full {
        border-radius: 9999px;
        }
        .lt-w-3 {
        width: .75rem;
        }
        .lt-h-3 {
        height: .75rem;
        }
        .lt-block {
        display: block;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mt-2">
            <div class="card">
                <div class="card-body">
                    <div class="col-sm-12 text-right mb-4">
                        <a href="{{ route('admin.site.language_create') }}" class="btn btn-success">{{ __('Create Language
                        ') }}</a>
                      </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-center table-borderless">
                            <thead>
                                <tr>
                                    <th>{{ __('Default') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($language as $languages)
                                        <tr>
                                            <td>
                                                @if($languages->default == '1')
                                                    <span class="lt-block lt-w-3 lt-h-3 lt-border lt-rounded-full lt-border-green-500 lt-bg-green-400" style=" margin: auto; "></span>
                                                @else
                                                    <span class="lt-block lt-w-3 lt-h-3 lt-border lt-rounded-full lt-border-red-500 lt-bg-red-400" style=" margin: auto; "></span>
                                                @endif
                                            </td>
                                            <td>{{ $languages->name }}</td>
                                            <td>{{ $languages->code }}</td>
                                            <td>
                                                <div class="dropdown d-inline">
                                                    <button class="btn btn-info dropdown-toggle" type="button"
                                                        id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        {{ __('Action') }}
                                                    </button>
                                                    <div class="dropdown-menu">

                                                        <a class="dropdown-item has-icon"
                                                            href="{{ route('admin.site.language_edit',$languages->id) }}"><i
                                                                class="fas fa-user-edit"></i> {{ __('Edit') }}</a>
                                                        {{-- <a class="dropdown-item has-icon"
                                                                href=""><i
                                                                    class="fas fa-download"></i> {{ __('Delete') }}</a>         --}}
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>{{ __('Default') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Code') }}</th>
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
