<!doctype html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield("title","To Do App")</title>
    <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
    
    @yield("style")

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  </head>
  <body class="d-flex flex-column h-100 pt-5">
    @include("include.header")
    @yield("content")
    @include("include.footer")

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

    @yield('scripts')
  </body>
</html>
