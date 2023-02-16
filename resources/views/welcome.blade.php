@extends('layouts.app')
@section ('content')
    <div class="container-full">

        <div class="row row-gap-10">

            <div class="card">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="send_bulk-tab" data-bs-toggle="tab"
                                data-bs-target="#send_bulk"
                                type="button" role="tab" aria-controls="send_bulk" aria-selected="true">Send bulk
                            messages
                        </button>
                        <button class="nav-link" id="upload-tab" data-bs-toggle="tab"
                                data-bs-target="#upload"
                                type="button" role="tab" aria-controls="upload" aria-selected="false">Upload Phone
                            Numbers
                        </button>
                        <button class="nav-link" id="upload-twilio-tab" data-bs-toggle="tab"
                                data-bs-target="#twilio-log-upload"
                                type="button" role="tab" aria-controls="upload" aria-selected="false">Upload Twilio Log
                        </button>
                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-profile"
                                type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Send single
                            SMS
                        </button>
                    </div>
                </nav>

                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="send_bulk" role="tabpanel"
                         aria-labelledby="send_bulk-tab"
                         tabindex="0">


                        <div class="card">
                            <div class="col align-self-end ">
                                <a href="{{route("campaign.create")}}"
                                   role="button"
                                   class="btn btn-primary btn-sm"
                                   aria-disabled="false">
                                    Create New Campaign
                                </a>
                            </div>
                            <div class="card-header">
                                <b>Campaigns</b>
                            </div>
                            <div class="card-body">
                                <table class="table  table-bordered">
                                    <thead>
                                    <tr>
                                        <th scope="col">id</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Body</th>
                                        <th scope="col">Run at</th>
                                        <th scope="col">Done at</th>
                                        <th scope="col">Valid</th>
                                        <th scope="col">Sent</th>
                                        <th scope="col">Rejected</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($campagnes as $campagn)
                                        @php

                                            if ($campagn->sent_date === NULL)
                                             {
                                             $button_state = '  ';
                                             }
                                             else
                                                 {
/*                                             $button_state = ' disabled ';*/
                                                 $button_state = ' ';

                                                 }
                                        @endphp
                                        <tr>
                                            <td> {{ $campagn->id }} </td>
                                            <td> {{$campagn->name}}</td>
                                            <td> {{substr($campagn->message,0,20)}}</td>
                                            <td> {{$campagn->send_date}}</td>
                                            <td> {{$campagn->sent_date}}</td>
                                            <td> {{$campagn->number_valid}}</td>
                                            <td> {{$campagn->number_sent}}</td>
                                            <td> {{$campagn->number_refused}}</td>
                                            <td>
                                                <div class="btn-toolbar" role="toolbar" data-toggle="buttons"
                                                     aria-label="Toolbar with button groups">
                                                    <div class="btn-group" role="group" aria-label="First group"
                                                         data-toggle="buttons">
                                                        <form action="{{route('sms.bulk')}}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                   value="{{ $campagn->id }}">
                                                            <button type="submit" class="btn btn-success btn-sm"
                                                                    {{$button_state}}   style="margin-right: 5px;">
                                                                Run
                                                            </button>
                                                        </form>
                                                    </div>
                                                    {{--
                                                                                                        <div class="btn-group" role="group" aria-label="Second group"
                                                                                                             data-toggle="buttons">
                                                                                                            <form action="{{route('campaign/edit')}}" method="POST">
                                                                                                                @csrf
                                                                                                                <input type="hidden" name="id"
                                                                                                                       value="{{ $campagn->id }}">
                                                                                                                <button type="submit" class="btn btn-success btn-sm" style="margin-right: 5px;>
                                                                                                                Edit
                                                                                                                </button>
                                                                                                            </form>
                                                                                                        </div>
                                                    --}}
                                                    <div class="btn-group" role="group" aria-label="Second group"
                                                         data-toggle="buttons">
                                                        <form action="{{route('twilio.result')}}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                   value="{{ $campagn->id }}">
                                                            <button type="submit" class="btn btn-success btn-sm">Result
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>


                                                <div class="btn-group" role="group" aria-label="Second group"
                                                     data-toggle="buttons">
                                                    <form action="{{route('sms.verify')}}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="verify"
                                                               value="{{ $campagn->id }}">
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            Verify
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade show" id="upload" role="tabpanel"
                         aria-labelledby="upload-tab"
                         tabindex="0">
                        <div class="row">
                            <div class="col">

                                <div class="card">
                                    <div class="card-header">
                                        <b>Upload phone numbers</b>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('file.upload.post') }}" method="POST"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <input type="file" name="phones" class="form-control">
                                                </div>

                                                <div class="col-md-6">
                                                    <button type="submit" class="btn btn-success">Upload
                                                    </button>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="twilio-log-upload" role="tabpanel"
                         aria-labelledby="upload-tab"
                         tabindex="0">
                        <div class="row">
                            <div class="col">
                                <div class="card">
                                    <div class="card-header">
                                        <b>Upload twilio log</b>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('file.upload.smslog') }}" method="POST"
                                              enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <input type="file" name="smslogs" class="form-control">
                                                </div>

                                                <div class="col-md-6">
                                                    <button type="submit" class="btn btn-success">Upload
                                                    </button>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                         aria-labelledby="nav-profile-tab"
                         tabindex="0">
                        <div class="row">
                            <div class="col">
                                <div class="card">
                                    <div class="card-header">
                                        <b>Add Phone Number</b>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Enter Phone Number</label>
                                                <input type="tel" class="form-control" name="phone_number"
                                                       placeholder="Enter Phone Number">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Register User</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <b>Send SMS message</b>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="/custom">
                                    @csrf
                                    <div class="form-group">
                                        <label>Select users to notify</label>
                                        <select name="users[]" multiple class="form-control">
                                            @foreach ($users as $user)
                                                <option>{{$user->phone_number}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Notification Message</label>
                                        <textarea name="body" class="form-control" rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Send Notification</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade show" id="send_bulk2" role="tabpanel"
                         aria-labelledby="send_bulk-tab2"
                         tabindex="0">

                        <div class="row">
                            <div class="col">

                                <div class="card">
                                    <div class="card-header">
                                        <b>Upload phone numbers</b>
                                    </div>
                                    <div class="card-body">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
            integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"
            integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha"
            crossorigin="anonymous"></script>

    </body>
    @endsection

    </html>
