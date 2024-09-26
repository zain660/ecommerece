<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.amazy.pages.chat.chat_include.css')
</head>

<body class="chats-tab-open">
    <!-- Main Layout Start -->
    <div class="main-layout">
        <!-- Sidebar Start -->
        <aside class="sidebar">
            <!-- Tab Content Start -->
            <div class="tab-content">
                <!-- Chat Tab Content Start -->
                <div class="tab-pane active" id="chats-content">
                    <div class="d-flex flex-column h-100">
                        <div class="hide-scrollbar h-100" id="chatContactsList">

                            <!-- Chat Header Start -->
                            <div class="sidebar-header sticky-top p-2">

                                <div class="d-flex justify-content-between align-items-center">
                                    <!-- Chat Tab Pane Title Start -->
                                    <h5 class="font-weight-semibold mb-0">Chats</h5>
                                    <!-- Chat Tab Pane Title End -->

                                    <ul class="nav flex-nowrap">

                                        <li class="nav-item list-inline-item mr-1">
                                            <a class="nav-link text-muted px-1" href="#" title="Notifications"
                                                role="button" data-toggle="modal" data-target="#notificationModal">
                                                <!-- Default :: Inline SVG -->
                                                <svg class="hw-20" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                </svg>

                                                <!-- Alternate :: External File link -->
                                                <!-- <img class="injectable hw-20" src="./../assets/media/heroicons/outline/bell.svg" alt=""> -->
                                            </a>
                                        </li>

                                        <li class="nav-item list-inline-item d-block d-xl-none mr-1">
                                            <a class="nav-link text-muted px-1" href="#" title="Appbar"
                                                data-toggle-appbar="">
                                                <!-- Default :: Inline SVG -->
                                                <svg class="hw-20" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path
                                                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                                    </path>
                                                </svg>

                                                <!-- Alternate :: External File link -->
                                                <!-- <img class="hw-20" src="./../assets/media/heroicons/outline/view-grid.svg" alt="" class="injectable hw-20"> -->
                                            </a>
                                        </li>
                                    </ul>
                                </div>


                                <!-- Sidebar Header Start -->
                                <div class="sidebar-sub-header">
                                    <!-- Sidebar Search Start -->
                                    <form class="form-inline">
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control search border-right-0 transparent-bg pr-0"
                                                placeholder="Search users...">
                                            <div class="input-group-append">
                                                <div class="input-group-text transparent-bg border-left-0"
                                                    role="button">
                                                    <!-- Default :: Inline SVG -->
                                                    <svg class="text-muted hw-20" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>

                                                    <!-- Alternate :: External File link -->
                                                    <!-- <img class="injectable hw-20" src="./../assets/media/heroicons/outline/search.svg" alt=""> -->
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- Sidebar Search End -->
                                </div>
                                <!-- Sidebar Header End -->
                            </div>
                            <!-- Chat Header End -->

                            <!-- Chat Contact List Start -->
                            @php
                                $chat_convo = App\Models\ChatInquiry::where('buyer_id', auth()->user()->id)->get();
                            @endphp
                            <ul class="contacts-list" id="chatContactTab" data-chat-list="">
                                <!-- Chat Item Start -->
                                @if ($chat_convo->count() > 0)
                                    @foreach ($chat_convo as $item)
                                        <li class="contacts-item friends"
                                            @if ($item->seller_id == $seller_id) style="background-color: {{ $data->base_color }}; color:white;" @endif>
                                            <a class="contacts-link" href="javascript:;">
                                                <div class="avatar avatar-online">
                                                    <img src="{{ asset($get_seller_details->avatar) }}" alt="">
                                                </div>
                                                <div class="contacts-content">
                                                    <div class="contacts-info">
                                                        <h6 class="chat-name text-truncate">
                                                            {{ $get_seller_details->first_name }} - {{$product_details->product_name}}</h6>
                                                        <div class="chat-time">{{ $item->created_at->diffForHumans() }}
                                                        </div>
                                                    </div>
                                                    <div class="contacts-texts">
                                                        <p class="text-truncate">{{ $item->initial_msg }}</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="contacts-item friends">
                                        <div class="contacts-content">
                                            <div class="contacts-info">
                                                <h6 class="chat-name text-truncate">No data found.</h6>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                                <!-- Chat Item End -->
                            </ul>
                            <!-- Chat Contact List End -->
                        </div>
                    </div>
                </div>
                <!-- Chats Tab Content End -->
            </div>
            <!-- Tab Content End -->
        </aside>
        <!-- Sidebar End -->
        <div class="modal modal-lg-fullscreen fade" id="notificationModal" tabindex="-1" role="dialog"
            aria-labelledby="notificationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-dialog-zoom">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0 hide-scrollbar">
                        <div class="row">

                            <div class="col-12">
                                <!-- List Group Start -->
                                <ul class="list-group list-group-flush  py-2">
                                    <!-- List Group Item Start -->
                                    <div id="notifiy"></div>
                                    <!-- List Group Item End -->


                                </ul>
                                <!-- List Group End -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <a type="button" data-dismiss="modal" aria-label="Close"
                            class="btn btn-link text-muted">Close</a>
                    </div>
                </div>
            </div>
        </div>


</body>

</html>
