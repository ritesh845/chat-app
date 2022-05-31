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
    <style >
        .hide{
            display: none !important;
        }
    </style>
</head>

<body>

<div class="layout">

    <!-- Navbar -->
    <div class="navigation navbar navbar-light justify-content-center py-xl-7">

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

    </div>
    <div class="sidebar">
	    <div class="tab-content h-100" role="tablist">
	    	<div class="tab-pane fade h-100 show active" id="tab-content-dialogs" role="tabpanel">
                <div class="d-flex flex-column h-100">
                    <div class="hide-scrollbar">
                        <div class="container-fluid py-6">
                            <h2 class="font-bold mb-6">Chats</h2>
                            <form class="mb-6">
	                            <div class="input-group">
	                                <input type="text" class="form-control form-control-lg" placeholder="Search for messages or users..." aria-label="Search for messages or users...">
	                                <div class="input-group-append">
	                                    <button class="btn btn-lg btn-ico btn-secondary btn-minimal" type="submit">
	                                        <i class="fe-search"></i>
	                                    </button>
	                                </div>
	                            </div>
	                        </form>
                            <div class="row">
                            	<div class="col-md-12">
                            		<button class="btn btn-sm btn-primary" title="New Chat" id="addChatRoomBtn"><i class="fe-users"></i></button>
                            	</div>
                            </div>

                            @include('chat.chatroom_list')
                        

                           
                           
                        </div>
                    </div>
                </div>
            </div>
	    </div>
	</div>
     @include('chat.chatroom')

	<div class="modal" tabindex="-1" role="dialog" id="chatRoomModal">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Start New Chat</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
      		<div class="row">
      			<div class="col-md-12">
      				<select class="form-control" name="user_id" id="chatUsers">
      					<option value="">Select One</option>
      				</select>
      			</div>
      		</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-sm btn-primary" id="addChatroom">Start Chat</button>
	      </div>
	    </div>
	  </div>
	</div>

 
    <script src="{{asset('assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/plugins.bundle.js')}}"></script>
    <script src="{{asset('assets/js/template.js')}}"></script>
    <!-- Scripts -->

</body>
<script>
	$(document).ready(function(){
		var token = localStorage.getItem('token');
        var user_id = localStorage.getItem('user_id');
        var user_name = localStorage.getItem('user_name');
		function chatrooms(){
			// console.log('asdasd');
		}

		function get_chat_users(){
			$.ajax({
				type:'GET',
				url:"/api/get-chat-users",
				headers: {
	                'Authorization': 'Bearer '+token
	            },
	            contentType: 'application/json',
		    }).done(function(res){
		    	$('#chatUsers').empty();
		    	$('#chatUsers').append('<option value="">Select One</option>');
				$.each(res.data,function(i,v){
					$('#chatUsers').append('<option value="'+v.id+'">'+v.name+'</option>');
				});
				
		    })
		    .fail(function(error) {
		    	window.location.href = "/";
		    });
		}
		$('#addChatRoomBtn').on('click',function(){
			get_chat_users();
			$('#chatRoomModal').modal('show');

		});

		$('#addChatroom').on('click',function(e){
			e.preventDefault();
			var user_id = $('#chatUsers').val();
			if(user_id !=''){
				$.ajax({
					type:'POST',
					url:"/api/new-private-chat",
					data:{user_id:user_id},
					headers: {
		                'Authorization': 'Bearer '+token
		            }
			    }).done(function(res){
			    	// console.log(res);
                    window.location.reload();
			    }).fail(function(error) {
			    	
			    });
			}else{
				alert('Select One User');
			}
		});

	});
</script>

</html>
