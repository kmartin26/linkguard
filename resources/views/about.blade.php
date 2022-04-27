@extends('layouts.master')

@section('title', 'About')

@section('header')
    <div class="page-header">
        <h1>About</h1>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <p class="lead text-center">What about this website?</p>
            <div class="col-md-8 col-md-offset-2">
                <h3>Why use LinkGuard?</h3>
                <hr>
                <p class="text-justify">
                    &emsp;&emsp;&emsp;Every time you post something on the internet, it's been fetched, analysed and stored in databases to make it available in search engines.
                    Also, software or apps like <b>Facebook</b>, <b>Messenger</b>, <b>Telegram</b>, <b>Discord</b> (and many, many more...) fetch the link to make a preview of it or just censor it.
                    <br><br>
                    &emsp;&emsp;&emsp;That's why <b>LinkGuard</b> has been created, to give Internet user a possibility to keep anonym what they share!
                </p>
                <br>
                <h3>How do you block bots?</h3>
                <hr>
                <p class="text-justify">
                    &emsp;&emsp;&emsp;We use the very common <b>reCaptcha NoCaptcha</b> from <b>Google</b> to block bots. This solution has been proven around the world and offers a very good result in protecting content against robots.
                </p>
                <p class="text-center">
                    <i>Easy for Humans, Hard on bots!</i>
                </p>
                <h3>Free to use?</h3>
                <hr>
                <p class="text-justify">
                    &emsp;&emsp;&emsp;<b>YES SURE!</b> The service is maintained with own funds. However if you wish to support us, do not hesitate to contact us via the contact form.
                </p>
                <br><br>
            </div>
        </div>
    </div>
@endsection

@section('customJs')
    {!! NoCaptcha::renderJs() !!}
@endsection