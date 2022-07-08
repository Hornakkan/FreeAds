<!-- <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Star Ads : the best Star Wars ads related website">
    <link rel="shortcut icon" href="#" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Document</title>
</head>
<body> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
@extends('layouts.app')

@section('content')    
    <div class="user-detail">
        @if(Auth::user() !== NULL && Auth::user()->id == $user->id)
        <form action="{{ route('user.update', $user->id) }}" method="POST">
            @csrf
            <p>
                <label for="name">Your name</label>
                <input type="text" id="name" name="name" value="{{ $user->name }}">
            </p>
            <p>
                <label for="nickname">Your nickname</label>
                <input type="text" id="nickname" name="nickname" value="{{ $user->nickname }}">
            </p>
            <p>
                <label for="email">Your e-mail</label>
                <input type="email" id="email" name="email" value="{{ $user->email }}">
            </p>
            <p>
                <label for="password">Your password</label>
                <input type="password" id="password" name="password" value="">
            </p>
            <p>
                <label for="phone_number">Your phone number</label>
                <input type="tel" id="phone_number" name="phone_number" value="{{ $user->phone_number }}">
            </p>
            <p class="btn_update_usr"><button class="btn btn-warning" type="submit" id="update" name="update">Update user</button></p>
        </form>

        <!-- <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="btn_del_usr">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" type="submit" id="delete" name="delete">Delete user</button>
        </form> -->
        <!-- Modal pour confirmation du delete -->
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteUserModal">
            Delete profile
        </button>
        <div id="deleteUserModal" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete your user profile</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You're about to delete your profile. This will also delete all your ads</p>
                        <p>Are you sure ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                        <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="btn_del_usr">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit" id="delete" name="delete">Delete profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>         
        @else
            <!-- <meta http-equiv="refresh" content="3;url={{ route('user.show', Auth::user()->id) }}" /> -->
            <div class="error-msg">
                <p>You can only edit your own profile.</p>
                <p>Redirecting in <span id="counter">3</span>...</p>
            </div>
            
            <script>
                setInterval(function() {
                    var div = document.querySelector("#counter");
                    var count = div.textContent * 1 - 1;
                    div.textContent = count;
                    if (count <= 0) {
                        window.location.replace("{{ route('user.show', Auth::user()->id) }}");
                    }
                }, 1000);
            </script>
            
        @endif
    </div>
    <p class="back-lnk"><a href="{{ route('ads.index') }}"><i class="bi bi-box-arrow-in-left"></i> Back</a></p>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<!-- </body>
</html> -->