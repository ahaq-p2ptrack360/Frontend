<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-sidebar="dark" data-sidebar-size="sm-hover" data-preloader="disable" card-layout="" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <title> @yield('title') | Vixon - Admin & Dashboard Template </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}">
    @include('layouts.head-css')
</head>
<style>
    /* Simple Chat Button - Fixed Position on RIGHT SIDE */
    .chat-fixed-button {
        position: fixed;
        bottom: 25px;
        right: 80px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        transition: all 0.3s ease-in-out;
        z-index: 1000;
    }

    .chat-fixed-button:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        background: linear-gradient(135deg, #0056b3, #004085);
    }

    .chat-fixed-button:active {
        transform: scale(0.95);
    }

    /* Tooltip on hover - RIGHT SIDE */
    .chat-fixed-button[data-tooltip]:hover::before {
        content: attr(data-tooltip);
        position: absolute;
        right: 70px;
        background-color: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        white-space: nowrap;
        font-size: 13px;
        font-weight: 500;
        z-index: 1001;
        pointer-events: none;
    }

    /* Chat button pulse animation */
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
        }
        70% {
            box-shadow: 0 0 0 15px rgba(0, 123, 255, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
        }
    }

    .chat-fixed-button {
        animation: pulse 2s infinite;
    }

    .chat-fixed-button:hover {
        animation: none;
    }
    
    /* ✅ CHAT IFRAME STYLES */
    #chatOffcanvas {
        width: 450px !important;
        transition: transform 0.3s ease-in-out !important;
    }
    
    #chatOffcanvas .offcanvas-body {
        padding: 0;
        overflow: hidden;
    }
    
    @media (max-width: 768px) {
        #chatOffcanvas {
            width: 100% !important;
        }
        
        /* Mobile pe button position adjust */
        .chat-fixed-button {
            right: 20px;
            bottom: 20px;
            width: 55px;
            height: 55px;
            font-size: 22px;
        }
    }
    
    .chat-iframe-container {
        width: 100%;
        height: 100%;
        border: none;
        background: white;
    }
    
    .chat-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        background: #f8f9fa;
        flex-direction: column;
        padding: 2rem;
    }
    
    .chat-loading-spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 1rem;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .chat-loading-message {
        color: #6c757d;
        font-size: 0.9rem;
        text-align: center;
    }

    #chatOffcanvas {
        transition: none;
    }
</style>

<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                   <!-- Simple Chat Button (Without FAB) -->
<button class="chat-fixed-button" data-bs-toggle="offcanvas" data-bs-target="#chatOffcanvas" data-tooltip="Chat">
    <i class="fa-solid fa-comment"></i>
</button>
                    
                    <!-- Chat Offcanvas -->
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="chatOffcanvas" aria-labelledby="chatOffcanvasLabel" data-bs-backdrop="false" data-bs-scroll="true">
                        <!-- <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="chatOffcanvasLabel">Chat Support</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div> -->
                        <div class="offcanvas-body p-0">
                            <!-- Loading indicator -->
                            <div class="chat-loading" id="chatLoading">
                                <div class="chat-loading-spinner"></div>
                                <p class="chat-loading-message">Loading chat...</p>
                            </div>
                            
                            <!-- Iframe -->
                            <iframe
                                id="chatIframe"
                                src=""
                                width="100%"
                                height="100%"
                                frameborder="0"
                                style="border:none; min-height: 80vh; display: none;"
                                title="Chat Support">
                            </iframe>
                        </div>
                    </div>
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    @include('layouts.customizer')

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')

    <script>
        // Safely pass company API URL to JS
        var companyApiUrl = @json(Auth::check() ? "api/companies/" . Auth::user()->company_id : "api/companies/0");
        
        // Chat credentials from Laravel auth
        var chatEmail = "{{ Auth::check() ? Auth::user()->email : '' }}";
        var chatPassword = "{{ Auth::check() ? Auth::user()->two_factor_secret : '' }}";
    </script>
</body>

<script>
$(document).ready(function() {
    // ✅ FAB BUTTON FUNCTIONALITY
    // const fabContainer = $('.fab-container');
    // const fabButton = $('.fab-button');
    // const fabActions = $('.fab-actions');

    // if (fabContainer.length && fabButton.length && fabActions.length) {
    //     fabContainer.on('mouseenter', function () {
    //         fabActions.addClass('show');
    //         fabButton.addClass('open');
    //     });

    //     fabContainer.on('mouseleave', function () {
    //         fabActions.removeClass('show');
    //         fabButton.removeClass('open');
    //     });
    // }
    
    // ✅ CHAT IFRAME HANDLING
    const chatOffcanvas = document.getElementById('chatOffcanvas');
    const chatIframe = document.getElementById('chatIframe');
    const chatLoading = document.getElementById('chatLoading');
    
    if (chatOffcanvas && chatIframe) {
        // Reset iframe when offcanvas is shown
        chatOffcanvas.addEventListener('show.bs.offcanvas', function() {
            // Show loading indicator
            chatLoading.style.display = 'flex';
            chatIframe.style.display = 'none';
            
            // Set iframe source
            const chatUrl = new URL('https://demo.p2ptrack360.com:8080/chat/public/conversations');
            chatUrl.searchParams.append('timestamp', Date.now());
            
            chatIframe.src = chatUrl.toString();
        });
        
        // Hide loading when iframe loads
        chatIframe.addEventListener('load', function() {
            setTimeout(() => {
                chatLoading.style.display = 'none';
                chatIframe.style.display = 'block';
                
                // Try auto-login after iframe loads
                attemptChatAutoLoginFromMain();
            }, 1000);
        });
        
        // Reset when offcanvas closes
        chatOffcanvas.addEventListener('hidden.bs.offcanvas', function() {
            // Clear iframe source to reset
            setTimeout(() => {
                chatIframe.src = '';
                chatLoading.style.display = 'flex';
                chatIframe.style.display = 'none';
            }, 300);
        });
    }
    
    // ✅ AUTO-LOGIN FUNCTION FROM MAIN PAGE
    function attemptChatAutoLoginFromMain() {
        try {
            const iframe = document.getElementById('chatIframe');
            if (!iframe) return;
            
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            
            if (!iframeDoc || !iframeDoc.body) {
                console.log('Chat iframe not loaded yet');
                setTimeout(attemptChatAutoLoginFromMain, 1000);
                return;
            }
            
            // Check if already logged in
            const chatElements = iframeDoc.querySelector('.chat-container, .messages-container, .conversations-list, .active-chats');
            if (chatElements) {
                console.log('Already logged in to chat');
                return;
            }
            
            // Look for login form
            const loginForm = iframeDoc.querySelector('form');
            const emailField = iframeDoc.querySelector('input[type="email"], input[name="email"], #email');
            const passwordField = iframeDoc.querySelector('input[type="password"], input[name="password"], #password');
            
            if (loginForm && emailField && passwordField) {
                console.log('Found chat login form, attempting auto-login');
                
                // Fill credentials
                emailField.value = chatEmail;
                passwordField.value = chatPassword;
                
                // Trigger change events
                emailField.dispatchEvent(new Event('change', { bubbles: true }));
                passwordField.dispatchEvent(new Event('change', { bubbles: true }));
                
                // Submit form
                setTimeout(() => {
                    const loginButton = iframeDoc.querySelector('button[type="submit"], input[type="submit"], #loginbtn');
                    if (loginButton) {
                        loginButton.click();
                    } else {
                        loginForm.submit();
                    }
                    console.log('Chat login form submitted');
                }, 500);
            }
        } catch (error) {
            console.log('Auto-login from main page failed:', error.message);
        }
    }
    
    window.addEventListener('message', function(event) {
        if (event.data && event.data.type === 'openChat') {
            const chatOffcanvas = new bootstrap.Offcanvas(document.getElementById('chatOffcanvas'));
            chatOffcanvas.show();
        }
    });
});

</script>
</html>
