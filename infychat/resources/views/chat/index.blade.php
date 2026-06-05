@extends('layouts.app')
@section('title')
    {{ __('messages.conversations') }}
@endsection
@section('page_css')

@php 
$baseUrl = "/infychat/public/"
@endphp


<!-- ✅ Styles with dynamic baseUrl -->
<link rel="stylesheet" id="dropzoneCss" href="{{$baseUrl}}assets/css/dropzone.css assets/js/dropzone.min.js">
<link rel="stylesheet" id="lightboxCss" href="{{$baseUrl}}assets/css/ekko-lightbox.css">
<link rel="stylesheet" id="videoJsCss" href="{{$baseUrl}}assets/css/video-js.css">
<link rel="stylesheet" id="conversationCss" href="{{$baseUrl}}assets/css/new-conversation.css">


    {{--    <link href="https://www.jsviews.com/samples/samples.css" rel="stylesheet" />--}}
@endsection
@section('content')
    <div class="page-container" style="padding: 0px;">
        <div class="chat-container chat">
            <div class="chat__inner" style="height: calc(100vh - 0px);">
                <!-- left section of chat area (chat person selection area) -->
                <div class="chat__people-wrapper chat__people-wrapper--responsive">
                    <div class="chat__people-wrapper-header">
                        <span class="h3 mb-0">{{ __('messages.conversations') }}</span>
                        <div class="d-flex chat__people-wrapper-btn-group">
                            <i class="nav-icon fa fa-bars align-top chat__people-wrapper-bar"></i>
                            @if($enableGroupSetting == 1)
                            <div class="chat__people-wrapper-button btn-create-group mr-2" data-toggle="modal"
                                 data-target="#createNewGroup">
                                <i class="nav-icon group-icon color-green" data-toggle="tooltip"
                                   data-placement="bottom"
                                   title="{{ __('messages.create_new_group') }}"><img
                                            src="{{asset('assets/icons/group30.png')}}"></i>
                            </div>
                            @endif
                            <div class="chat__people-wrapper-button" data-toggle="modal"
                                 data-target="#addNewChat">
                                <i class="nav-icon " data-toggle="tooltip" data-placement="bottom"
                                   title="{{ __('messages.new_conversation') }}"><img
                                            src="{{asset('assets/icons/chat30.png')}}"></i>
                            </div>
                        </div>
                    </div>
                    <div class="chat__search-wrapper">
                        <div class="chat__search clearfix chat__search--responsive">
                            <i class="fa fa-search"></i>
                            <input type="search" placeholder="{{ __('messages.search') }}" class="chat__search-input"
                                   id="searchUserInput">
                            <i class="fa fa-search d-lg-none chat__search-responsive-icon"></i>
                        </div>
                    </div>
                    <ul class="nav nav-tabs chat__tab-nav" id="chatTabs">
                        <li class="nav-item">
                            <a data-toggle="tab" id="activeChatTab" class="nav-link active" href="#chatPeopleBody">Active
                                Chat</a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" id="archiveChatTab" class="nav-link" href="#archivePeopleBody">Archive
                                Chat</a>
                        </li>
                    </ul>
                    <div class="tab-content chat__tab-content">
                        <div class="chat__people-body tab-pane fade in active show" id="chatPeopleBody">
                            <div id="infyLoader" class="infy-loader chat__people-body-loader">
                                @include('partials.infy-loader')
                            </div>
                            <div class="text-center no-conversation">
                                <div class="chat__no-conversation">
                                    <div class="text-center"><i class="fa fa-2x fa-user" aria-hidden="true"></i></div>
                                    {{ __('messages.no_conversation_found') }}
                                </div>
                            </div>
                        </div>
                        <div class="chat__people-body tab-pane fade in active" id="archivePeopleBody">
                            <div class="text-center no-archive-conversation">
                                <div class="chat__no-archive-conversation">
                                    <div class="text-center"><i class="fa fa-2x fa-user" aria-hidden="true"></i></div>
                                    {{ __('messages.no_conversation_found') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ left section of chat area -->


                <!-- right section of chat area (chat conversation area)-->
                <div class="chat__area-wrapper">
                    @include('chat.no-chat')
                </div>
                <!--/ right section of chat area-->

                <!-- profile section (chat profile section)-->
            @include('chat.chat_profile')
            @include('chat.msg_info')
            <!--/ profile section -->
            </div>
        </div>
        <!-- Modal -->
        <div id="addNewChat" class="modal fade" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title">
                            <i class="ti-user"></i>New Conversations @if($enableGroupSetting == 1) / Groups @endif</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <nav class="nav nav-tabs" id="myTab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-my-contacts-tab" data-toggle="tab"
                               href="#nav-my-contacts" role="tab" aria-controls="nav-my-contacts-tab"
                               aria-expanded="true"> <i class="ti-user"></i>{{ __('messages.my_contacts') }}</a> <a
                                    class="nav-item nav-link wrap-text" id="nav-users-tab" data-toggle="tab"
                                    href="#nav-users"
                                    role="tab" aria-controls="nav-users" aria-expanded="true">
                                <i class="ti-user"></i>{{ __('messages.new_conversation') }}
                            </a>
                            @if($enableGroupSetting == 1)
                            <a
                                    class="nav-item nav-link" id="nav-groups-tab" data-toggle="tab" href="#nav-groups"
                                    role="tab" aria-controls="nav-groups">{{ __('messages.group.groups') }}</a>
                            @endif
                                <a
                                    class="nav-item nav-link" id="nav-blocked-users-tab" data-toggle="tab"
                                    href="#nav-blocked-users" role="tab"
                                    aria-controls="nav-blocked-users">{{ __('messages.blocked_users') }}</a>
                        </nav>

                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-my-contacts" role="tabpanel"
                                 aria-labelledby="nav-my-contacts-tab">
                                <form class="mb-2">
                                    <input type="search" class="form-control search-input" id="searchMyContactForChat"
                                           placeholder="{{ __('messages.search') }}...">
                                </form>
                                <div class="form-group">
                                    <div class="col-sm-12 d-flex justify-content-around">
                                        {{-- <div class="custom-control custom-checkbox">
                                            <input name="my_contacts_filter" value="1" type="checkbox" class="custom-control-input group-type" id="male">
                                            <label class="custom-control-label" for="male">{{ __('messages.male') }}</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input name="my_contacts_filter" value="2" type="checkbox" class="custom-control-input group-type" id="female">
                                            <label class="custom-control-label" for="female">{{ __('messages.female') }}</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input name="my_contacts_filter" value="3" type="checkbox" class="custom-control-input group-type" id="online">
                                            <label class="custom-control-label" for="online">{{ __('messages.online') }}</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input name="my_contacts_filter" value="4" type="checkbox" class="custom-control-input group-type" id="offline">
                                            <label class="custom-control-label" for="offline">{{ __('messages.offline') }}</label>
                                        </div> --}}
                                    </div>
                                </div>
                                <div id="myContactListForAddPeople">
                                    <ul class="list-group user-list-chat-select" id="myContactListForChat"></ul>
                                    <div class="text-center no-my-contact new-conversation__no-my-contact">
                                        <div class="chat__not-selected">
                                            <div class="text-center"><i class="fa fa-2x fa-user" aria-hidden="true"></i>
                                            </div>
                                            {{ __('messages.no_user_found') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-users" role="tabpanel" aria-labelledby="nav-users-tab">
                                <form class="mb-2">
                                    <input type="search" class="form-control search-input" id="searchContactForChat"
                                           placeholder="{{ __('messages.search') }}...">
                                </form>
                                <div class="form-group">
                                    <div class="col-sm-12 d-flex justify-content-around">
                                        {{-- <div class="custom-control custom-checkbox">
                                            <input name="new_contact_gender" value="1" type="checkbox" class="custom-control-input group-type" id="newContactMale">
                                            <label class="custom-control-label" for="newContactMale">{{ __('messages.male') }}</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input name="new_contact_gender" value="2" type="checkbox" class="custom-control-input group-type" id="newContactFemale">
                                            <label class="custom-control-label" for="newContactFemale">{{ __('messages.female') }}</label>
                                        </div> --}}
                                    </div>
                                </div>
                                <div id="userListForAddPeople">
                                    <ul class="list-group user-list-chat-select" id="userListForChat"></ul>
                                    <div class="text-center no-user new-conversation__no-user">
                                        <div class="chat__not-selected">
                                            <div class="text-center"><i class="fa fa-2x fa-user" aria-hidden="true"></i>
                                            </div>
                                            {{ __('messages.no_user_found') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($enableGroupSetting == 1)
                            <div class="tab-pane fade" id="nav-groups" role="tabpanel" aria-labelledby="nav-groups-tab">
                                <form class="mb-2">
                                    <input type="search" class="form-control search-input" id="searchGroupsForChat"
                                           placeholder="{{ __('messages.search') }}...">
                                </form>
                                <div id="divGroupListForChat">
                                    <ul class="list-group user-list-chat-select" id="groupListForChat"></ul>
                                    <div class="text-center no-group new-conversation__no-group">
                                        <div class="chat__not-selected">
                                            <div class="text-center"><i class="fa fa-2x fa-users"
                                                                        aria-hidden="true"></i>
                                            </div>
                                            {{ __('messages.no_group_found') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="tab-pane fade show" id="nav-blocked-users" role="tabpanel"
                                 aria-labelledby="nav-blocked-users-tab">
                                <form class="mb-2">
                                    <input type="search" class="form-control search-input" id="searchBlockUsers"
                                           placeholder="{{ __('messages.search') }}...">
                                </form>
                                <div id="divOfBlockedUsers">
                                    <ul class="list-group user-list-chat-select" id="blockedUsersList"></ul>
                                    <div class="text-center no-blocked-user new-conversation__no-user">
                                        <div class="chat__not-selected">
                                            <div class="text-center"><i class="fa fa-2x fa-user" aria-hidden="true"></i>
                                            </div>
                                            <span id="noBlockedUsers">{{ __('messages.no_blocked_user_found') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('chat.group_modals')
        @include('chat.report_user_modal')
    </div>
    @include('chat.templates.conversation-template')
    @include('chat.templates.message')
    @include('chat.templates.no-messages-yet')
    @include('chat.templates.no-conversation')
    @include('chat.templates.group_details')
    @include('chat.templates.user_details')
    @include('chat.templates.group_listing')
    @include('chat.templates.group_members')
    @include('chat.templates.single_group_member')
    @include('chat.group_members_modal')
    @include('chat.templates.blocked_users_list')
    @include('chat.templates.add_chat_users_list')
    @include('chat.templates.badge_message_template')
    @include('chat.templates.member_options')
    @include('chat.templates.single_message')
    @include('chat.templates.contact_template')
    @include('chat.templates.conversations_list')
    @include('chat.templates.common_templates')
    @include('chat.templates.my_contacts_listing')
    @include('chat.templates.conversation-request')
    @include('chat.copyImageModal')
@endsection
@section('page_js')
<script id="dropzoneJs" src="{{$baseUrl}}assets/js/dropzone.min.js"></script>
    <script id="lightboxJs"  src="{{$baseUrl}}assets/js/ekko-lightbox.min.js"></script>
    <script id="videoJs"  src="{{$baseUrl}}assets/js/video.min.js"></script>
@endsection
@section('scripts')
<script>
   

baseUrl="/infychat/public/";
    let userURL = baseUrl + 'users/';
    let userListURL = baseUrl + 'users-list';
    let myContactsURL = baseUrl + 'my-contacts';
    let conversationListURL = baseUrl + 'conversations-list';
    let archiveConversationListURL = baseUrl + 'archive-conversations';
    let sendMessageURL = baseUrl + 'conversations';
    let fileUploadURL = baseUrl + 'file-upload';
    let imageUploadURL = baseUrl + 'image-upload';
    let blockedUsersListURL = baseUrl + 'blocked-users';
    let blockUsersByMeURL = baseUrl + 'users-blocked-by-me';
    let readMessageURL = baseUrl + 'read-message';
    let deleteConversationUrl = baseUrl + 'conversations/';
    let deleteMessageUrl = baseUrl + 'conversations/message/';
    let createGroupURL = baseUrl + 'groups';
    let getUsers = baseUrl + 'get-users';
    let groupsList = baseUrl + 'groups';
    let sendChatReqURL = baseUrl + 'send-chat-request';
    let acceptChatReqURL = baseUrl + 'accept-chat-request';
    let declineChatReqURL = baseUrl + 'decline-chat-request';
    let reportUserURL = baseUrl + 'report-user';
    let csrfToken = '{{ csrf_token() }}';
    let authUserName = '{{ Auth::user()->name }}';
    let authImgURL = '{{ Auth::user()->photo_url }}';
    let appName = '{{ getAppName() }}';
    let conversationId = '{{ $conversationId }}';
    let enableGroupSetting = '{{ isGroupChatEnabled() }}';
    let isUTCTimezone  = '{{ (config('app.timezone') == 'UTC') ? 1 : 0 }}';
    let timeZone  = '{{ config('app.timezone') }}';

    /** ✅ Icons URL */
    let pdfURL = baseUrl + 'assets/icons/pdf.png';
    let xlsURL = baseUrl + 'assets/icons/xls.png';
    let textURL = baseUrl + 'assets/icons/text.png';
    let docsURL = baseUrl + 'assets/icons/docs.png';
    let videoURL = baseUrl + 'assets/icons/video.png';
    let youtubeURL = baseUrl + 'assets/icons/youtube.png';
    let audioURL = baseUrl + 'assets/icons/audio.png';

    // ✅ Load main chat script after baseUrl is ready
    const chatScript = document.createElement('script');
    chatScript.src = baseUrl + "assets/js/chat.js";
    document.body.appendChild(chatScript);
</script>
@endsection
