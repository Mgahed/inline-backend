@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="mt-3">
            <h5>
                <div class="row">
                    <div class="col-md-6">
                        <img style="width: 50px;" src="{{asset($service_provider->image)}}"
                             alt="{{$service_provider->name}} image">
                        <span
                            class="text-muted">Service Provider Location:</span><br>
                        @if (strpos($service_provider->address, 'iframe'))
                            {!! $service_provider->address !!}
                        @else
                            <iframe src="{{ $service_provider->address }}" width="400" height="300" style="border:0;"
                                    allowfullscreen="" loading="lazy"></iframe>
                        @endif
                        <br><br>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted">Service Provider Name:</span> {{$service_provider->name}}<br><br>
                        <span class="text-muted">Service Provider Email:</span> <a
                            href="mailto:{{$service_provider->email}}">{{$service_provider->email}}</a><br><br>
                        <span class="text-muted">Service Provider phone number:</span> <a
                            href="tel:{{$service_provider->phone_number}}">{{$service_provider->phone_number}}</a>
                    </div>
                </div>
            </h5>
        </div>
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
                    <div class="card-header">{{ __('Add branch for') }} <span
                            class="text-danger">{{$service_provider->name}}</span></div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('add.branch') }}">
                            @csrf
                            <input type="hidden" name="provider_id" value="{{$service_provider->id}}">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" name="name"
                                           value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone_number"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

                                <div class="col-md-6">
                                    <input id="phone_number" type="text"
                                           class="form-control @error('phone_number') is-invalid @enderror"
                                           name="phone_number" value="{{ old('phone_number') }}" required>

                                    @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="lat"
                                       class="col-md-4 col-form-label text-md-right">{{ __('latitude') }}</label>

                                <div class="col-md-6">
                                    <input id="lat" type="text"
                                           class="form-control @error('lat') is-invalid @enderror"
                                           name="lat" value="{{ old('lat') }}" required>

                                    @error('lat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="lon"
                                       class="col-md-4 col-form-label text-md-right">{{ __('longitude') }}</label>

                                <div class="col-md-6">
                                    <input id="lon" type="text"
                                           class="form-control @error('lon') is-invalid @enderror"
                                           name="lon" value="{{ old('lon') }}" required>

                                    @error('lon')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="address"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

                                <div class="col-md-6">
                                    <input id="address" type="text"
                                           class="form-control @error('address') is-invalid @enderror" name="address"
                                           value="{{ old('address') }}" required autocomplete="new-address">

                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="start-time"
                                       class="col-md-4 col-form-label text-md-right">{{ __('start time') }}</label>

                                <div class="col-md-6">
                                    <input id="start-time" type="time" class="form-control @error('start_time') is-invalid @enderror" name="start_time"
                                           required autocomplete="new-type" value="{{ old('start_time') }}">
                                    @error('start_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="close-time"
                                       class="col-md-4 col-form-label text-md-right">{{ __('close time') }}</label>

                                <div class="col-md-6">
                                    <input id="close-time" type="time" class="form-control @error('close_time') is-invalid @enderror" name="close_time"
                                           required autocomplete="new-type" value="{{ old('close_time') }}">
                                    @error('close_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Add') }}
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
                    <div class="card-header"><span
                            class="text-danger">{{$service_provider->name}}</span> {{ __('Branches') }}</div>

                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @if (!$service_provider->branches->count())
                            <div class="alert alert-danger"> No branches for {{$service_provider->name}} yet</div>
                        @else
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone number</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Start Time</th>
                                        <th scope="col">Close Time</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($service_provider->branches as $branch)
                                        <tr>
                                            <td>{{$branch->name}}</td>
                                            <td><a href="mailto:{{$branch->email}}">{{$branch->email}}</a></td>
                                            <td><a href="tel:{{$branch->phone_number}}">{{$branch->phone_number}}</a>
                                            </td>
                                            <td><a target="_blank"
                                                   href="{{$branch->address}}">{{$branch->name}}
                                                    Location</a></td>
                                            <td>{{\Carbon\Carbon::createFromFormat('H:i:s',$branch->start_time)->format('h:i A')}}</td>
                                            <td>{{\Carbon\Carbon::createFromFormat('H:i:s',$branch->close_time)->format('h:i A')}}</td>
                                            <td><a class="btn btn-outline-info"
                                                   href="{{route('branch.services',$branch->id)}}">Services</a></td>
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
