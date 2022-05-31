var token = localStorage.getItem('token');
var user_id = localStorage.getItem('user_id');
var user_name = localStorage.getItem('user_name');
 // Enable pusher logging - don't include this in production
Pusher.logToConsole = false;

var pusher = new Pusher('ac551db97aa903691aab', {
  cluster: 'ap2',
  authEndpoint: "/broadcasting/auth",
    auth: {
        headers: {
            Authorization: 'Bearer ' + token
        },
    },
});


function getUserChatrooms(){
    var roomIds = [];
    $.ajax({
        type:'GET',
        url:"/api/get-user-chatrooms",
        headers: {
            'Authorization': 'Bearer '+token
        },
        contentType: 'application/json',
    }).done(function(res){
        $('.list-discussions-js').empty();
        $.each(res.data,function(i,v){      
            listChatroom(v);
            roomIds.push(v.room_id);
        });
        if(roomIds.length !=0){
            $.each(roomIds,function(i,v){
                var channel = pusher.subscribe('private-newMessage.'+v);
                channel.bind('App\\Events\\NewMessage', function(data) {
                    if(user_id != data.message.sender_id){
                        getRoomConversations(v);
                        getUserChatrooms();
                    }
                });
                var channel = pusher.subscribe('private-editMessage.'+v);
                channel.bind('App\\Events\\EditMessage', function(data) {
                    if(user_id != data.editedmessage.sender_id){
                        getRoomConversations(v);
                        getUserChatrooms();
                    }
                });

                var channel = pusher.subscribe('private-deleteMessage.'+v);
                channel.bind('App\\Events\\DeleteMessage', function(data) {
                    if(user_id != data.deletedmessage.sender_id){
                        getRoomConversations(v);
                        getUserChatrooms();
                    }
                });

            })    
        }
        


    })
    .fail(function(error) {
        window.location.href = "/";
    });
}

function listChatroom(v){
    let name = v.room_name;
    var firstLetter = name.charAt(0);

    $('.list-discussions-js').append('<a class="text-reset nav-link p-0 mb-6 chatRoomOpen" href="/chatroom/'+v.room_id+'" ><div class="card card-active-listener"><div class="card-body"><div class="media" > <div class="avatar mr-5" style="padding:21px 0px 0px 19px">'+(firstLetter)+'</div><div class="media-body overflow-hidden"><div class="d-flex align-items-center mb-1"><h6 class="text-truncate mb-0 mr-auto">'+(v.room_name)+'</h6><p class="small text-muted text-nowrap ml-4">'+(v.display_last_message_time)+'</p></div><div class="text-truncate">'+(v.last_message !=null ? (v.last_message) : 'file' )+'</div></div></div></div>'+( v.unread_count != '0' ? '<div class="badge badge-circle badge-primary badge-border-light badge-top-right"><span>'+(v.unread_count)+'</span></div>' : '')+'</div></a>');
}


function getRoomConversations(chatroom_id,link=null){
    if(link == null){
        link = 'get-room-conversations';
    }
    let activeChatroomId = localStorage.getItem('chatroom_id');
    if(activeChatroomId == chatroom_id){
        $.ajax({
            type:'POST',
            url:"/api/"+link,
            data:{chatroom_id:chatroom_id},
            headers: {
                'Authorization': 'Bearer '+token
            }
        }).done(function(res){
            $('#messageBody').empty();
            data =  res.data.sort(function(a, b) { 
              return a.message_id - b.message_id;
            });
            localStorage.setItem('get-next-link',res.links.next);
            localStorage.setItem('get-prev-link',res.links.prev);
            $.each(data,function(i,v){
                if(v.sender_id == user_id){
                    if(v.is_image){
                        showImage(i,v,'right')

                    }else{
                        showRightMessage(i,v);
                    }
                }else{
                    if(v.is_image){
                        showImage(i,v,'left')
                    }else{
                        showLeftMessage(i,v);
                    }
                    // console.log();
                }
            });

        }).fail(function(error) {
            
        });

    }
    
}

function showLeftMessage(i,v){
    $('#messageBody').append( '<div class="message"><div class="message-body"><div class="message-row"> '+(v.room_type == 'group' ? '<div style="font-size:12px">'+(v.sender_name)+'</div>' : '')+' <div class="d-flex align-items-center">'+(v.message_deleted !=0 ? '<div class="message-content bg-light"><div>'+(v.message_deleted)+' </div></div>' :  '<div class="message-content '+(v.is_file == 1 ? 'bg-light' : 'bg-light')+' text-dark"> '+(v.is_file ==1 ? '<div class="media"><a href="javascript:void(0)" class="icon-shape mr-5"><i class="fe-paperclip"></i></a><div class="media-body overflow-hidden flex-fill"><a href="/api/download-file/'+(v.message_id)+'" class="d-block text-truncate font-medium text-reset file_download" data-id="'+(v.message_id)+'">'+(v.file_name)+'</a><ul class="list-inline small mb-0"><li class="list-inline-item"><span class="t">'+(v.file_size)+'</span></li></ul></div></div>' : ' <div>'+(v.message)+' </div> ')+'<div class="mt-1"><small class="opacity-65">'+(v.created_at)+'</small></div></div>')+''+(v.msg_props !=null ? (JSON.parse(v.msg_props).edited ? '<div class="edit ml-3"><i class="text-muted fa fa-pencil"> </i></div>' : ''): '' )+'  </div></div> </div></div>');
 }

function showRightMessage(i,v){
     $('#messageBody').append('<div class="message message-right"><div class="message-body"><div class="message-row"><div class="d-flex align-items-center justify-content-end">'+( v.message_deleted !=0 ? '<div class="message-content bg-secondary"><div>You deleted this message</div>' : (v.msg_props !=null ? (JSON.parse(v.msg_props).edited ? '<div class="edit"><i class="fa fa-pencil text-muted"></i></div>' : '') : '' )+' <div class="dropdown"><a class="text-muted opacity-60 mr-3" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fe-more-vertical"></i></a><div class="dropdown-menu">'+(v.is_file == 1 ? '' : '<a class="dropdown-item d-flex align-items-center msgEdit" href="javascript:void(0)" data-id="'+(v.message_id)+'" data-message="'+(v.message)+'" data-index="'+i+'">Edit <span class="ml-auto fe-edit-3"></span></a>' )+'<a class="dropdown-item d-flex align-items-center msgDelete" href="javascript:void(0)" data-id="'+(v.message_id)+'"  data-index="'+i+'">Delete <span class="ml-auto fe-trash-2"></span></a></div></div><div class="message-content text-dark" style="background-color:#dbf4fd"> '+(v.is_file == 1 ? '<div class="media"><a href="javascript:void(0)" class="icon-shape mr-5"><i class="fe-paperclip"></i></a><div class="media-body overflow-hidden flex-fill"><a href="/api/download-file/'+(v.message_id)+'" class="d-block text-truncate font-medium text-reset file_download" data-id="'+(v.message_id)+'">'+(v.file_name)+'</a><ul class="list-inline small mb-0"><li class="list-inline-item"><span class="t">'+(v.file_size)+'</span></li></ul></div></div>' : '<div>'+(v.message)+'</div>' ) +'<div class="mt-1"><small class="opacity-65">'+(v.created_at)+'</small> </div></div></div>')+'</div></div></div>');
}

function showImage(i,v,pos){
    $('#messageBody').append('<div class="message message-'+(pos)+'"><div class="message-body"><div class="message-row"><div class="d-flex align-items-center '+(pos == 'left' ? '' : 'justify-content-end')+'">'+( v.message_deleted !=0 ? '<div class="message-content bg-secondary"><div>You deleted this message</div>'  : (pos == 'right'  ? '<div class="dropdown"><a class="text-muted opacity-60 mr-3" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fe-more-vertical"></i></a><div class="dropdown-menu"><a class="dropdown-item d-flex align-items-center msgDelete" href="javascript:void(0)" data-id="'+(v.message_id)+'"  data-index="'+i+'">Delete <span class="ml-auto fe-trash-2"></span></a></div></div>' : '' ) +'<a href="/api/download-file/'+(v.message_id)+'"><img src="'+v.file_url+'" width="200" height="150" /></a>')+ '</div></div></div>');
}

function readMessages(chatroom_id){
    $.ajax({
        type:'GET',
        url:"/api/read-messages/"+chatroom_id,
        headers: {
            'Authorization': 'Bearer '+token
        }
    }).done(function(res){
        // console.log(res);
    }).fail(function(error) {
        
    });
}

function updateScrollTop(){

    $('#chat-content').stop ().animate ({
        scrollTop: $('#chat-content')[0].scrollHeight *5
    });
}

