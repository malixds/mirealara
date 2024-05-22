@extends('layouts.default')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
    </script>

    </section>
    <div class="max-w-7xl px-5 py-16 md:px-10 md:py-16 lg:py-24 mx-auto">
        <h2 class="executor__name">{{$executor->name}}</h2>
    </div>
@endsection
