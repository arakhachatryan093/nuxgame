<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Special page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

</head>
<body>
<section class="container">
    <h1>Welcome, {{ $user->username }}!</h1>
    <div class="mt-2">
        <button data-user_id="{{$user->id}}" type="button" id="gen_new">Generate New Link</button>
    </div>
    <div class="mt-2">
        <button data-user_id="{{$user->id}}" type="button" id="del_link">Deactivate Link</button>
    </div>
    <div class="mt-2">
        <button data-user_id="{{$user->id}}" type="button" id="feel_lucky">Im Feeling Lucky</button>
    </div>
    <div class="mt-2">
        <button data-user_id="{{$user->id}}" type="button" id="history">History</button>
    </div>

    <div id="result" class="text-success mt-5">

    </div>

    <div id="last-three">

    </div>
</section>

<script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("click", "#gen_new", function (ev) {
            let user_id = $(this).data('user_id');

            if (confirm('Are you sure you want to regenerate the link ?')) {
                $.ajax({
                    url: `/links/` + user_id + '/regenerate',
                    method: 'GET',
                    success: function (data) {
                        alert('Link successfully regenerated, you will be redirected to the new link')
                        window.location.href = '/links/' + data.user.special_page_link + '';
                    }, error: function (err) {
                        alert('Try again later.')
                    }
                });
            }
        });

        $(document).on("click", "#del_link", function (ev) {
            let user_id = $(this).data('user_id');

            if (confirm('Are you sure you want to delete the link ?')) {
                $.ajax({
                    url: `/links/` + user_id + '/delete',
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        alert('Link successfully deleted')
                        location.href = '/';
                    }, error: function (err) {
                        alert('Try again later.')
                    }
                });
            }
        });

        $(document).on("click", "#feel_lucky", function (ev) {
            let user_id = $(this).data('user_id');

            $.ajax({
                url: `/links/` + user_id + '/lucky',
                method: 'GET',
                success: function (data) {
                    $('#result').html('');
                    let res_text = data.is_win ? 'Win' : 'Lose';
                    $('#result').append('<p>Random number: ' + data.random_number + '<p>')
                    $('#result').append('<p>Your result is: ' + res_text + '<p>')
                    $('#result').append('<p>Win amount: ' + data.win_amount + '<p>')

                }, error: function (err) {
                    alert('Try again later.')
                }
            });
        });

        $(document).on("click", "#history", function (ev) {
            let user_id = $(this).data('user_id');

            $.ajax({
                url: `/users/` + user_id + '/history',
                method: 'GET',
                success: function (data) {
                    $('#last-three').html('');
                    let append_html = "<div class='mt-2 text-center'><h2>Here is The result of last 3 executions</h2>";
                    if (data.user.latest_history.length > 0) {
                        data.user.latest_history.forEach(element => {
                            let res_text = element.is_win ? 'Win' : 'Lose';
                            append_html += '<p> Random number: ' + element.random_number + '</p>';
                            append_html += '<p> Result: ' + res_text + '</p>';
                            append_html += '<p> Win amount: ' + element.win_amount + '</p>';
                        });
                        append_html += "</div>";
                        $('#last-three').html(append_html);
                    }
                }, error: function (err) {
                    alert('Try again later.')
                }
            });
        });
    })
</script>
</body>


