@extends('chat.layouts.main')
@section('content')
@include('chat.components.sidebar')
<link href="{{asset('css/emojinarea.css')}}">
<script src="{{asset('js/emojinarea.js')}}"></script>
<div class="main main-visible" data-mobile-height="">
	<div id="chat-1" class="chat dropzone-form-js" >
	<!-- Chat: body -->
		<div class="chat-body">

			<div class="chat-header border-bottom py-4 py-lg-6 px-lg-8">
                <div class="container-xxl">
                    <div class="row align-items-center">
                        <!-- Close chat(mobile) -->
                        <div class="col-3 d-xl-none">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a class="text-muted px-0" href="#" data-chat="open">
                                        <i class="icon-md fe-chevron-left"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Chat photo -->
                        <div class="col-6 col-xl-6">
                            <div class="media text-center text-xl-left">
                                <div class="avatar avatar-sm d-none d-xl-inline-block mr-5 firstLetter" style="padding:21px 0px 0px 17px">
                                    
                                </div>

                                <div class="media-body align-self-center text-truncate">
                                    <h6 class="text-truncate mb-n1 room_name"></h6>
                                    <small class="text-muted parti hide"><span class="part_count"></span> participants</small>
                                </div>

                            </div>
                        </div>

                        <!-- Chat toolbar -->
                        <div class="col-3 col-xl-6 text-right">
                            <ul class="nav justify-content-end">
                                <li class="nav-item list-inline-item d-none d-xl-block mr-5">
                                    <a class="nav-link text-muted px-3" data-toggle="collapse" data-target="#chat-1-search" href="#" title="Search this chat">
                                        <i class="icon-md fe-search"></i>
                                    </a>
                                </li>

                                <li class="nav-item list-inline-item d-none d-xl-block mr-3">
                                    <a class="nav-link text-muted px-3" href="#" data-chat-sidebar-toggle="#chat-1-members" title="Add People">
                                        <i class="icon-md fe-user-plus"></i>
                                    </a>
                                </li>

                                <li class="nav-item list-inline-item d-none d-xl-block mr-0">
                                    <a class="nav-link text-muted px-3" href="#" data-chat-sidebar-toggle="#chat-1-info" title="Details">
                                        <i class="icon-md fe-more-vertical"></i>
                                    </a>
                                </li>

                                <!-- Mobile nav -->
                                <li class="nav-item list-inline-item d-block d-xl-none">
                                    <div class="dropdown">
                                        <a class="nav-link text-muted px-0" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon-md fe-more-vertical"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item d-flex align-items-center" data-toggle="collapse" data-target="#chat-1-search" href="#">
                                                Search <span class="ml-auto pl-5 fe-search"></span>
                                            </a>

                                            <a class="dropdown-item d-flex align-items-center" href="#" data-chat-sidebar-toggle="#chat-1-info">
                                                Chat Info <span class="ml-auto pl-5 fe-more-horizontal"></span>
                                            </a>

                                            <a class="dropdown-item d-flex align-items-center" href="#" data-chat-sidebar-toggle="#chat-1-members">
                                                Add Members <span class="ml-auto pl-5 fe-user-plus"></span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <!-- Mobile nav -->
                            </ul>
                        </div>

                    </div><!-- .row -->

                </div>
            </div>
            <div class="chat-content px-lg-8" id="chat-content">
                @include('chat.components.messageList')
               
                {{-- <div class="end-of-chat" id="end-of-chat"></div> --}}
            </div>
           
            @include('chat.components.sendMessage')
		</div>
	</div>
</div>
<script>

    
    var token = localStorage.getItem('token');
	var user_id = localStorage.getItem('user_id');
    let chatroom_id = "{{$chatroom_id}}";
    localStorage.setItem('chatroom_id',"{{$chatroom_id}}");
    // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = false;

    // var pusher = new Pusher('ac551db97aa903691aab', {
    //   cluster: 'ap2',
    //   authEndpoint: "/broadcasting/auth",
    //   	auth: {
	   //      headers: {
	   //          Authorization: 'Bearer ' + token
	   //      },
	   //  },
    // });

    var channel = pusher.subscribe('private-newMessage.{{$chatroom_id}}');
    channel.bind('App\\Events\\NewMessage', function(data) {
        if(user_id != data.message.sender_id){
            getRoomConversations(chatroom_id);  
            readMessages(chatroom_id);
            getUserChatrooms();
         	// swal(data.message.sender_name + ' sent a message: '+data.message.message);  
        }
	});

    var channel = pusher.subscribe('private-editMessage.{{$chatroom_id}}');
    channel.bind('App\\Events\\EditMessage', function(data) {
        if(user_id != data.editedmessage.sender_id){
            getRoomConversations(chatroom_id);
            readMessages(chatroom_id);
            getUserChatrooms();
            // swal(data.message.sender_name + ' sent a message: '+data.message.message);  
        }
    });
    var channel = pusher.subscribe('private-deleteMessage.{{$chatroom_id}}');
    channel.bind('App\\Events\\DeleteMessage', function(data) {
        if(user_id != data.deletedmessage.sender_id){
            getRoomConversations(chatroom_id);
            readMessages(chatroom_id);
            getUserChatrooms();
            // swal(data.message.sender_name + ' sent a message: '+data.message.message);  
        }
    });


</script>

<script>
    $(document).ready(function(){
         getRoomDetails();
        function getRoomDetails(){
            $.ajax({
                type:'GET',
                url:"/api/get-room-details/"+chatroom_id,
                headers: {
                    'Authorization': 'Bearer '+token
                },
                contentType: 'application/json',
            }).done(function(res){
                $('.room_name').empty().text(res.data[0].room_name);
                let name = res.data[0].room_name;
                var firstLetter = name.charAt(0);
                $('.firstLetter').empty().text(firstLetter);
                if(res.data[0].room_type == 'group'){
                    $('.parti').removeClass('hide');
                    $('.part_count').empty().text(res.data[0].participants);
                }


                if(res.data[0].unread_count != 0){
                    readMessages(chatroom_id);
                }
                getUserChatrooms();
                getRoomConversations(chatroom_id);

                updateScrollTop();
            })
            .fail(function(error) {
                window.location.href = "/";
            });
        }

        $(document).on('click','.msgEdit',function(e){
            e.preventDefault();
            let message_id = $(this).data('id');
            let message = $(this).data('message');
            let index = $(this).data('index');
             $('#chat-input').val(message);
             $('#message_id').val(message_id);
             $('#message_index').val(index);
             $('#old_message').val(message);
             $('#cancleBtn').removeClass('hide');
             $('#sendBtn').addClass('hide');

        });
        $(document).on('click','.msgDelete',function(e){
            e.preventDefault();
            let message_id = $(this).data('id');
            let index = $(this).data('index');
            $.ajax({
                type:'POST',
                url:"/api/delete-message",
                data:{message_id:message_id,index:index},
                headers: {
                        'Authorization': 'Bearer '+token
                    }
            }).done(function(res){
                getRoomConversations(chatroom_id);
                getUserChatrooms();

            }).fail(function(error) {
                
            }); 
        });

        // var element = document.getElementById("chat-content");
        // console.log(element.lastElementChild);

        $('#chat-content').on('scroll',function(e){
            e.preventDefault();
            var scrollTop = $(this)[0].scrollTop;
            var scrollHeight = $(this)[0].scrollHeight;
            if(scrollTop == 0){
               let getNextLink = localStorage.getItem('get-next-link');
               if(getNextLink.split('/')[4] != null){
                getRoomConversations(chatroom_id,getNextLink.split('/')[4]);    
                // updateScrollTop();
               }
            }else if(scrollTop == scrollTop){
               let getPrevLink = localStorage.getItem('get-prev-link');
               if(getPrevLink.split('/')[4] != null){
                    getRoomConversations(chatroom_id,getPrevLink.split('/')[4]);    
                    // updateScrollTop();
               }  
            }

        });
        updateScrollTop();
        

        // $(document).on('click','.file_download',function(e){
        //     e.preventDefault();
        //     let message_id = $(this).data('id');
        //     $.ajax({
        //         type:'GET',
        //         url:"/api/download-file/"+message_id,
        //         headers: {
        //             'Authorization': 'Bearer '+token
        //         },
        //         contentType: 'application/json',
        //     }).done(function(res){
        //         console.log('asdasd');
        //     })
        //     .fail(function(error) {

        //     });

        // })

       
       
var firstLetterHead = user_name.charAt(0);
$('.firstLetterHead').text(firstLetterHead);


    });
	
</script>
@endsection