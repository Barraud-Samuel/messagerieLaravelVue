@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('conversations.users', ['users' => $users])
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        {{$user->name}}
                    </div>
                    <div class="card-body conversations">
                        @foreach($messages as $message)
                            <div class="row">
                                <div class="col-lg-10">
                                    <p>
                                        <strong>{{$message->from->name}}</strong><br>
                                        {{$message->content}}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                        <form action="" method="post">
                            {{csrf_field()}}
                            <div class="form-group">
                                <textarea name="content" placeholder="Ecrivez votre message" class="form-control"></textarea>
                            </div>
                            <button class="btn btn-primary" type="submit">Envoyer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

