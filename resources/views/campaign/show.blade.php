@extends('layouts.app')
@section ('content')
    <body>
    <div class="container">
        <div class="row row-gap-3">">


            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="card">
                    <div class="card-header">
                        <b>Campaigns</b>
                    </div>
                    <div class="card-body">
                        <table class="table  table-striped">
                            <thead>
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Body</th>
                                <th scope="col">File name</th>
                                <th scope="col">Send date</th>
                                <th scope="col">Number sent</th>
                                <th scope="col">Number rejected</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($campagnes as $campagn)
                                <tr>
                                    <td> {{ $campagn->id }} </td>
                                    <td> {{$campagn->name}}</td>
                                    <td> {{$campagn->message}}</td>
                                    <td> {{$campagn->phone_number_file}}</td>
                                    <td> {{$campagn->send_date}}</td>
                                    <td> {{$campagn->number_sent}}</td>
                                    <td> {{$campagn->number_refused}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    </body>
@endsection