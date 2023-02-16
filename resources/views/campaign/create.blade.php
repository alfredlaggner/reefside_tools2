@extends('layouts.app')
@section ('content')
    <div class="container">
        <div class="card" style="width: 70rem;">
            <div class="card-body">
                <h5 class="card-title">New Campaign</h5>
                <h6 class="card-subtitle mb-2 text-muted"></h6>
                <p class="card-text">
                <form method="POST" action="{{ url('campaign') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="campaign_name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="campaign_name"
                               aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" name="message" placeholder="Enter SMS text here"
                                      id="smsText"></textarea>
                            <label for="floatingTextarea">SMS text</label>
                        </div>
                    </div>
{{--
                    <div class="mb-3">
                        <label for="formFile" name="phone_number_file" class="form-label">Phone numbers </label>
                        <input class="form-control" name="phone_number_file" type="file" id="formFile">
                    </div>
--}}
                    <div class="mb-3">
                        <label for="campaign_name" class="form-label">Send on</label>
                        <input type="datetime-local" name="send_date" class="form-control" id="campaign_time"
                               aria-describedby="emailHelp">
                    </div>

                    <button type="submit" name="submit_button" class="btn btn-primary">Save</button>
                </form>
                </p>
            </div>
            <div class="card-footer  d-flex justify-content-end">
                <a href="{{ URL::previous() }}" class="btn btn-primary">Return to Previous Screen</a>
            </div>
        </div>
@endsection