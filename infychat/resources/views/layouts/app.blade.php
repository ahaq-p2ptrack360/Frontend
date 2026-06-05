<!DOCTYPE html>
<html>
<head>
    <script>
        // ✅ Set your base path here
        const basePath = '/infychat/public/';
    </script>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>@yield('title') | {{ getAppName() }}</title>
    <meta name="description" content="@yield('title') - {{getAppName()}}">
    <meta name="keyword" content="CoreUI,Bootstrap,Admin,Template,InfyOm,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <h1>hello</h1>

    <!-- ✅ Dynamic Paths -->
    <link rel="stylesheet" href="" id="bootstrap-css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css">
    <link rel="stylesheet" href="" id="coreui-css">
    <link rel="stylesheet" href="" id="icheck-css">
    <link rel="stylesheet" href="" id="toast-css">
    <link rel="stylesheet" href="" id="fontawesome-css">
    <link rel="stylesheet" href="" id="emoji-css">
    <link rel="stylesheet" href="" id="style-css">

    <!-- jQuery -->
    <script src="" id="jquery-js"></script>
    <script>
        // ✅ Automatically set all resource paths
        document.getElementById('bootstrap-css').href = basePath + 'assets/css/bootstrap.min.css';
        document.getElementById('coreui-css').href = basePath + 'assets/css/coreui.min.css';
        document.getElementById('icheck-css').href = basePath + 'assets/icheck/skins/all.css';
        document.getElementById('toast-css').href = basePath + 'assets/css/jquery.toast.min.css';
        document.getElementById('fontawesome-css').href = basePath + 'assets/css/font-awesome.css';
        document.getElementById('emoji-css').href = basePath + 'css/emojionearea.min.css';
        document.getElementById('style-css').href = basePath + 'assets/css/style.css';
        document.getElementById('jquery-js').src = basePath + 'assets/js/jquery.min.js';
        
    </script>
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script> -->

    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
        let webNotificationRoute = '{{ url('update-web-notifications') }}/';
        let currentUserId = '{{ getLoggedInUserId() }}';
        let isSubscribedBefore = '{{ !is_null(Auth::user()->is_subscribed) ? true : false }}';
        let oneSignalAppId = '{{ config('onesignal.app_id') }}';

        var OneSignal = window.OneSignal || [];
        OneSignal.push(function () {
            OneSignal.init({ appId: oneSignalAppId });

            let isPushEnabled = '{{Auth::user()->is_subscribed}}';
            if (!isPushEnabled) {
                OneSignal.setSubscription(false);
            }

            $('#webNotification').on('ifChanged', function () {
                let isSubscribed = ($(this).val()) ? false : true;

                if (isSubscribed) {
                    OneSignal.showNativePrompt();
                } else {
                    if (confirm('Are you sure to disable web notification ?')) {
                        OneSignal.getUserId(function (userId) {
                            OneSignal.setSubscription(false);
                            updateWebPushNotification(false, userId);
                        });
                    }
                }
            });

            OneSignal.on('customPromptClick', function (promptClickResult) {
                let result = promptClickResult.result;

                if (result == 'denied') {
                    updateWebPushNotification(false);
                    return;
                }

                OneSignal.getUserId(function (userId) {
                    updateWebPushNotification(true, userId);
                });
            });

            if (!isSubscribedBefore) {
                OneSignal.showNativePrompt();
            }
        });

        function updateWebPushNotification (isSubscribed, oneSignalPlayerId = null) {
            let data = {};
            data.is_subscribed = isSubscribed;
            if (oneSignalPlayerId) {
                data.player_id = oneSignalPlayerId;
            }

            $.ajax({
                url: webNotificationRoute,
                type: 'PUT',
                data: data,
                success: function (result) {
                    if (result.success) {
                        displayToastr('Success', 'success', result.message);
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function (result) {
                    displayToastr('Error', 'error', result.responseJSON.message);
                },
            });
        }
    </script>
</head>

<body class="app">
<div class="app-body" style="margin-top:0px;">
    <main class="main">
        @yield('content')
    </main>
</div>

@include('chat.templates.notification')
@include('partials.file-upload')
@include('partials.set_custom_status_modal')

<!-- ✅ Scripts -->
<script src="" id="popper-js"></script>
<script src="" id="bootstrap-js"></script>
<script src="" id="coreui-js"></script>
<script src="" id="toast-js"></script>
<script src="" id="sweetalert-js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/moment-timezone.min.js') }}"></script>
<script src="" id="icheck-js"></script>
<script src="https://www.jsviews.com/download/jsviews.min.js"></script>
<script src="{{ asset('js/emojionearea.js') }}"></script>

<script>
    document.getElementById('popper-js').src = basePath + 'assets/js/popper.min.js';
    document.getElementById('bootstrap-js').src = basePath + 'assets/js/bootstrap.min.js';
    document.getElementById('coreui-js').src = basePath + 'assets/js/coreui.min.js';
    document.getElementById('toast-js').src = basePath + 'assets/js/jquery.toast.min.js';
    document.getElementById('sweetalert-js').src = basePath + 'assets/js/sweetalert2.all.min.js';
    document.getElementById('icheck-js').src = basePath + 'assets/icheck/icheck.min.js';
</script>

@yield('scripts')
</body>
</html>
