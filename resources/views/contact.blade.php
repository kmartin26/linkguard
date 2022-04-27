@extends('layouts.master')

@section('title', 'Contact Us')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <p class="lead text-center">Question? Feature? Idea?<br>Ask us everything.</p>

            <div class="col-md-8 col-md-offset-2">
                @if (count($errors) > 0)
                    <div class="alert alert-danger" role="alert">
                        <strong>Oh snap!</strong>
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif
                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <p style="text-align:center;"><strong>{{ session()->get('success') }}</strong></p>
                    </div>
                @endif

                <form action="{{ url('contact') }}" method="post">
                    <div class="form-group">
                        <label for="inputName">Name</label>
                        <input type="text" class="form-control" id="inputName" name="inputName" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="inputEmail">Email</label>
                        <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="inputSubject">Subject</label>
                        <input type="text" class="form-control" id="inputSubject" name="inputSubject" placeholder="Subject">
                    </div>
                    <div class="form-group">
                        <label for="inputMessage">Message</label>
                        <textarea class="form-control" id="inputMessage" name="inputMessage" rows="4" placeholder="Message"></textarea>
                    </div>
                    <div class="form-group">
                        {!! NoCaptcha::display() !!}
                        <button type="submit" class="btn btn-primary center-block">Submit</button>
                    </div>
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>
@endsection

@section('customJs')
    {!! NoCaptcha::renderJs() !!}
@endsection