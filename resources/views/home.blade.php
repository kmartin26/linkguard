@extends('layouts.master')

@section('title', 'Protect your links from bots!')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <p class="lead">With LinkGuard, simply protect your links from bots with a simple Google reCaptcha ! More discretion, more privacy &#x1F609;</p>

            @if (count($errors) > 0)
                <div class="alert alert-danger" role="alert">
                    <strong>Oh snap!</strong>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <form action="{{ url('create') }}" method="post">
                <div class="form-group">
                    <label for="urls">Your URLs (1 per line)</label>
                    <textarea class="form-control" name="urls" id="urls" rows="10">{{ old('urls') }}</textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>
                </div>
                {{ csrf_field() }}
            </form>
        </div>
    </div>
@endsection