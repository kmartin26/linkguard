@extends('layouts.master')

@section('title', 'Link created')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-lock" aria-hidden="true"></span> Your protected link is ready!
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <input type="text" class="form-control" id="link" value="{{ $data['data']['link'] }}">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button" aria-label="Copied!" data-clipboard-target="#link">Copy to clipboard!</button>
                        </span>
                    </div><!-- /input-group -->
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Protected link content!
                </div>
                <div class="panel-body">
                    <p>Your link ID : <code>{{ $data['data']['id'] }}</code></p>
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>URL</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data['data']['urls'] as $key => $protected_link)
                            <tr>
                                <th scope="row">{{ $key+1 }}</th>
                                <td>{{ $protected_link }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span> How to delete your link ?
                </div>
                <div class="panel-body">
                    <p>Your delete code <strong>(save it)</strong> : <code>{{ $data['data']['delete_code'] }}</code></p>
                    <p>To delete this link access the one below and enter your delete code!</p>
                    <p style="text-align:center;font-size:large;"><a href="{{ $data['data']['link'] }}/delete">{{ $data['data']['link'] }}/delete</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customJs')
    <!-- CDN clipboard.js -->
    <script async src="{{ asset('js/clipboard.min.js') }}"></scriptasync>
    <script>
        $('button').tooltip({
            trigger: 'click',
            placement: 'bottom'
        });

        function setTooltip(btn, message) {
            $(btn).tooltip('hide')
                .attr('data-original-title', message)
                .tooltip('show');
        }

        function hideTooltip(btn) {
            setTimeout(function() {
                $(btn).tooltip('hide');
            }, 1000);
        }

        // Clipboard

        var clipboard = new Clipboard('button');

        clipboard.on('success', function(e) {
            setTooltip(e.trigger, 'Copied!');
            hideTooltip(e.trigger);
        });

        clipboard.on('error', function(e) {
            setTooltip(e.trigger, 'Press Ctrl+C to copy');
            hideTooltip(e.trigger);
        });
    </script>
@endsection