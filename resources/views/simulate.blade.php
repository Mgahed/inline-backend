@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 mt-5">
                @if (session('fail'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{session('fail')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">{{ __('Simulate') }}</div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Queue</th>
                                    <th scope="col">Current turn</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr>
                                    <td>{{$branch_service->queue}}</td>
                                    <td>{{$branch_service->current_turn}}</td>
                                    <td>
                                        @if ($branch_service->queue <= $branch_service->current_turn)
                                            <button class="btn btn-success disabled" style="cursor: not-allowed;">Next turn</button>
                                        @else
                                            <a href="{{route('simulate.increment.turn',$branch_service->id)}}"
                                               class="btn btn-success">Next turn</a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('simulate.reset',$branch_service->id)}}"
                                           class="btn btn-danger">Reset</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
