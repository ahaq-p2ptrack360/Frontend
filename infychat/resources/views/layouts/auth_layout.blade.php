<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>@yield('title') | {{ getAppName() }}</title>
    <meta name="description" content="{{ getAppName() }} @yield('meta_content')">
    <meta name="keyword" content="CoreUI,Bootstrap,Admin,Template,InfyOm,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">

    <script>
        // ✅ Global base URL variable
        const baseUrl = "/infychat/public/";
    </script>

    <!-- ✅ Bootstrap & Theme CSS using baseUrl -->
    <link rel="stylesheet" id="bootstrapCss">
    <link rel="stylesheet" id="coreuiCss">
    <link rel="stylesheet" id="fontawesomeCss">
    <link rel="stylesheet" id="toastCss">

    @yield('page_css')
    @yield('css')
</head>

<body class="app flex-row align-items-center">
    @yield('content')

    <!-- ✅ JS placeholders -->
    <script id="jqueryJs"></script>
    <script id="popperJs"></script>
    <script id="bootstrapJs"></script>
    <script id="coreuiJs"></script>
    <script id="scrollbarJs"></script>
    <script id="toastJs"></script>
    <script id="authFormsJs"></script>
    <script id="customJs"></script>

    @yield('page_js')
    @yield('scripts')

    <!-- ✅ Dynamic asset loader -->
    <script>
        document.getElementById("bootstrapCss").href  = baseUrl + "assets/css/bootstrap.min.css";
        document.getElementById("coreuiCss").href     = baseUrl + "assets/css/coreui.min.css";
        document.getElementById("fontawesomeCss").href = baseUrl + "assets/css/font-awesome.css";
        document.getElementById("toastCss").href      = baseUrl + "assets/css/jquery.toast.min.css";

        document.getElementById("jqueryJs").src       = baseUrl + "assets/js/jquery.min.js";
        document.getElementById("popperJs").src       = baseUrl + "assets/js/popper.min.js";
        document.getElementById("bootstrapJs").src    = baseUrl + "assets/js/bootstrap.min.js";
        document.getElementById("coreuiJs").src       = baseUrl + "assets/js/coreui.min.js";
        document.getElementById("scrollbarJs").src    = baseUrl + "assets/js/perfect-scrollbar.min.js";
        document.getElementById("toastJs").src        = baseUrl + "assets/js/jquery.toast.min.js";
        document.getElementById("authFormsJs").src    = baseUrl + "assets/js/auth-forms.js";
        document.getElementById("customJs").src       = baseUrl + "assets/js/custom.js";
    </script>
</body>
</html>
