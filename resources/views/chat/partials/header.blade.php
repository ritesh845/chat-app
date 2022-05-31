<!DOCTYPE html>
<html lang="en">

<!-- Head -->

<!-- Mirrored from themes.2the.me/Messenger-1.1/demo-light/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 26 Apr 2021 08:13:27 GMT -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no">
    <title>Espark Chat </title>
    <script src="{{asset('assets/js/libs/jquery.min.js')}}"></script>

    <!-- Template core CSS -->
    <link href="{{asset('assets/css/template.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{asset('dropzone/dist/dropzone.css')}}" rel="stylesheet">
    <style >
        .hide{
            display: none !important;
        }
        .select2-container{
            width: 100% !important;
        }
        .select2-selection{
            padding: 10px !important;
        }
        
    </style>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset('dropzone/dist/dropzone.js')}}"></script>
    <script src="{{asset('js/helpers.js')}}"></script>
</head>

<body>

<div class="layout">

    <!-- Navbar -->
   {{--  <div class="navigation navbar navbar-light justify-content-center py-xl-7">

        <!-- Brand -->
        <a href="#" class="d-none d-xl-block mb-6">
            <img src="{{asset('assets/images/brand.svg')}}" class="mx-auto fill-primary" data-inject-svg="" alt="" style="height: 46px;">
        </a>

        <!-- Menu -->
        <ul class="nav navbar-nav flex-row flex-xl-column flex-grow-1 justify-content-between justify-content-xl-center py-3 py-lg-0" role="tablist">

            <!-- Invisible item to center nav vertically -->
            <li class="nav-item d-none d-xl-block invisible flex-xl-grow-1">
                <a class="nav-link position-relative p-0 py-xl-3" href="#" title="">
                    <i class="icon-lg fe-x"></i>
                </a>
            </li>

            <!-- Create group -->
            <li class="nav-item">
                <a class="nav-link position-relative p-0 py-xl-3" data-toggle="tab" href="#tab-content-create-chat" title="Create chat" role="tab">
                    <i class="icon-lg fe-edit"></i>
                </a>
            </li>

            <!-- Friend -->
            <li class="nav-item mt-xl-9">
                <a class="nav-link position-relative p-0 py-xl-3" data-toggle="tab" href="#tab-content-friends" title="Friends" role="tab">
                    <i class="icon-lg fe-users"></i>
                </a>
            </li>

            <!-- Chats -->
            <li class="nav-item mt-xl-9">
                <a class="nav-link position-relative p-0 py-xl-3 active" data-toggle="tab" href="#tab-content-dialogs" title="Chats" role="tab">
                    <i class="icon-lg fe-message-square"></i>
                    <div class="badge badge-dot badge-primary badge-bottom-center"></div>
                </a>
            </li>

            <!-- Profile -->
            <li class="nav-item mt-xl-9">
                <a class="nav-link position-relative p-0 py-xl-3" data-toggle="tab" href="#tab-content-user" title="User" role="tab">
                    <i class="icon-lg fe-user"></i>
                </a>
            </li>

            <!-- Demo only: Documentation -->
            <li class="nav-item mt-xl-9 d-none d-xl-block flex-xl-grow-1">
                <a class="nav-link position-relative p-0 py-xl-3" data-toggle="tab" href="#tab-content-demos" title="Demos" role="tab">
                    <i class="icon-lg fe-layers"></i>
                </a>
            </li>

            <!-- Settings -->
            <li class="nav-item mt-xl-9">
                <a class="nav-link position-relative p-0 py-xl-3" href="settings.html" title="Settings">
                    <i class="icon-lg fe-settings"></i>
                </a>
            </li>

        </ul>
        <!-- Menu -->

    </div> --}}