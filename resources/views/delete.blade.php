@extends('layouts.master')

@section('title', 'Delete a link')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <p style="text-align:center;"><strong>Success <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></strong></p>
                </div>
            @endif
            @if (count($errors) > 0)
                <div class="alert alert-danger" role="alert">
                    <strong>Oh snap!</strong>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Link information
                </div>
                <div class="panel-body">
                    <p>Your link id : <span class="label label-default">{{ $data['data']['id'] }}</span></p>
                    <p>Link : <a href="{{ url('/') }}/{{ $data['data']['id'] }}">{{ url('/') }}/{{ $data['data']['id'] }}</a></p>
                    <p>Status :
                        @if($data['status']['code'] === 200)
                            @if($data['data']['deleted'] === 0)
                                <span class="label label-success">Online</span>
                            @elseif($data['data']['deleted'] === 1)
                                @if($data['data']['delete_reason'] === 'user')
                                    <span class="label label-warning">Deleted (by user)</span>
                                @elseif($data['data']['delete_reason'] === 'dmca')
                                    <span class="label label-warning">Deleted (DMCA takedown)</span>
                                @endif
                            @endif
                        @elseif($data['status']['code'] === 404)
                            <span class="label label-default">Does not exist</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if(($data['status']['code'] === 200) && ($data['data']['deleted'] === 0))
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <span class="glyphicon glyphicon-alert" aria-hidden="true"></span> You will delete a link!
                    </div>
                    <div class="panel-body">
                        <p style="text-align:center;text-transform:uppercase;">Paste your delete code below</p>
                        <form action="{{ url('/') }}/{{ $data['data']['id'] }}/delete" method="post">
                            <div class="input-group">
                                <input type="text" class="form-control" name="delete_code" id="delete_code" placeholder="e.g., 1v1sBsnV">
                                <span class="input-group-btn">
                                    <button class="btn btn-danger" type="submit" >DELETE</button>
                                </span>
                                {{ csrf_field() }}
                            </div><!-- /input-group -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection