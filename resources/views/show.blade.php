@extends('layouts.master')

@if(!empty($data))
    @section('title', 'Links have been unlocked')
@else
    @section('title', 'Request links unlock')
@endif


@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @if ($errors->has('g-recaptcha-response'))
                <div class="alert alert-danger" role="alert">
                    <strong>Oh snap! </strong>
                    {{ $errors->first('g-recaptcha-response') }}
                </div>
            @endif
            @if((!empty($data)) && ($data['status']['code'] === 200))
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        @if($data['data']['urls_nb'] === 1)
                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Unlocked {{ $data['data']['urls_nb'] }} url!
                        @else
                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Unlocked {{ $data['data']['urls_nb'] }} urls!
                        @endif
                    </div>
                    <div class="panel-body">
                        @foreach($data['data']['urls'] as $urls)
                            <p class="text-center show-link">
                                <a href="{{ $urls }}">{{ $urls }}</a>
                            </p>
                        @endforeach
                    </div>
                </div>
            @elseif((!empty($data)) && $data['status']['code'] === 403)
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> LINK UNAVAILABLE
                        </div>
                        <div class="panel-body">
                            @if($data['data']['deleted_reason'] === 'user')
                                <p style="text-align:center;font-size:medium;"><span class="label label-warning">Link deleted by user!</span></p>
                            @elseif($data['data']['deleted_reason'] === 'dmca')
                                <p style="text-align:center;font-size:medium;"><span class="label label-warning">Link deleted due to DMCA report!</span></p>
                            @endif
                        </div>
                    </div>
            @else
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Unlock protected urls!
                    </div>
                    <div class="panel-body">
                        <form action="{{ url('/') }}/{{ $id }}/show" method="post">
                            @if(!Session::has('g-recaptcha-passed'))
                                <div class="form-group">
                                    {!! NoCaptcha::display() !!}
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-thumbs-up"></span> Unlock</button>
                                </div>
                            @else
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-thumbs-up"></span> Unlock</button>
                                </div>
                            @endif
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('customJs')
    {!! NoCaptcha::renderJs() !!}
@endsection