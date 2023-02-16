@extends('layouts.app')
@section ('content')
    <header>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</header>
    <div class="container">
        <div>
            Messages submitted: <span id="output"></span>
            Time started: {{  now()->toDateTimeString() }}
        </div>

        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 50%"></div>
        </div>
    </div>

<script>
    // Function to read the progress data and update the progress bar
    function updateProgress() {
        $.ajax({
            url: 'progress',
            success: function (progress) {
                var percentComplete = (progress.current_step / progress.total_steps) * 100;
            //    alert(percentComplete);
                $('.progress-bar').width(percentComplete + '%');
                $('.progress-bar').text(progress.current_step);
                document.getElementById("output").innerHTML =  progress.total_steps;
                if (percentComplete < 100) {
                    // Continue to read the progress data in intervals
                    setTimeout(updateProgress, 1000);
                }
            }
        });
    }

    // Start reading the progress data
   updateProgress();
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
            integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"
            integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha"
            crossorigin="anonymous"></script>


@endsection
