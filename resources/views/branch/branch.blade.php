@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 mt-5">
                @if (session('fail'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{session('fail')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">{{ __('Assign service') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('assign.service') }}">
                            @csrf
                            <input type="hidden" name="branch_id" value="{{$branch->id}}">
                            <div class="form-group row">
                                <label for="service_id"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Name of service') }}</label>
                                <div class="col-md-6">
                                    <select id="service_id" name="service_id" class="form-control">
                                        @foreach ($services as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Assign') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-7 mt-5">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{session('success')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">{{ __('Services') }}</div>

                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @if (!$branch->services->count())
                            <div class="alert alert-danger"> No Services yet</div>
                        @else
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Cost</th>
                                        <th scope="col"></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($branch->services as $service)
                                        <tr>
                                            <td>{{$service->name}}</td>
                                            <td>{{$service->cost}}</td>
                                            <td><a href="{{route('simulate',[$branch->id,$service->id])}}"
                                                   class="btn btn-info">Simulate</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
