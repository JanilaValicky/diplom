<!doctype html>
<title>{{ __('error.maintenance_title') }}</title>
<style>
    body {
        text-align: center;
        padding: 150px;
        background-color: #0F1321;
        color: #fff;
    }

    h1 {
        font-size: 50px;
    }

    body {
        font: 20px Helvetica, sans-serif;
        color: #fff;
    }

    article {
        display: block;
        text-align: left;
        width: 650px;
        margin: 0 auto;
        background-color: #1a2038;
        padding: 20px;
        border-radius: 10px;
    }

    a {
        color: #dc8100;
        text-decoration: none;
    }

    a:hover {
        color: #fff;
        text-decoration: none;
    }
</style>

<article>
    <h1>{{ __('error.maintenance') }}</h1>
    <div>
        <p>{{ __('error.maintenance_message') }}</p>
    </div>
</article>
