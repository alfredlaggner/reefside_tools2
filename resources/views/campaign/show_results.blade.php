@extends('layouts.app')
@section ('content')
    <div class="container">
        <div class="card" style="width: 70rem;">
            <div class="card-body">
                <h5 class="card-title">Campaign Results</h5>
                <h6 class="card-subtitle mb-2 text-muted"></h6>
                <div class="card-text">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">Campagne</th>
                            <td>{{$results->id}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Name</th>
                            <td>{{$results->name}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Date</th>
                            <td>{{$results->sent_date}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Sent</th>
                            <td>{{$results->number_sent}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Delivered</th>
                            <td>{{$results->total_delivered}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Rejected</th>
                            <td>{{$results->total_rejected}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Cancelled</th>
                            <td>{{$results->total_cancelled}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Price</th>
                            <td>${{$results->total_price * -1}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer  d-flex justify-content-end">
                <a href="{{ URL::previous() }}" class="btn btn-primary">Return to Previous Screen</a>
            </div>
        </div>
@endsection