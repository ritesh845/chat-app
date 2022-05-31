<div class="sidebar">
    <div class="tab-content h-100" role="tablist">
    	<div class="tab-pane fade h-100 show active" id="tab-content-dialogs" role="tabpanel">
            <div class="d-flex flex-column h-100">
                <div class="hide-scrollbar">
                    <div class="container-fluid p-0">
                        <div class="row pr-4 pl-4">
                          <div class="col-md-2 col-sm-2 col-xs-2 pt-3 mr-2">
                            <div class="avatar avatar-lg mb-5 firstLetterHead" style="padding: 31px 0px 0px 25px; font-size: 18px; background-color: #c0dfff !important">
                
                            </div>
                          </div>
                          <div class="col-md-8 col-sm-8 col-xs-8 pt-5">
                            
                            <h4 class="font-bold mb-6 user_name mt-2 pl-3"></h4>
                          </div>

                          <div class="col-md-1 col-sm-2 col-xs-2 pt-5 text-right">
                              <div class="dropdown">
                                <a class="text-dark" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fe-more-vertical"></i>
                                </a>

                                <div class="dropdown-menu">
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        Settings</span>
                                    </a>
                                    <a class="dropdown-item d-flex align-items-center signout" href="#">
                                        Signout</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                      </div>
                        <form class="mb-6 pr-4 pl-4">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-lg" placeholder="Search for messages or users..." aria-label="Search for messages or users...">
                                <div class="input-group-append">
                                    <button class="btn btn-lg btn-ico btn-secondary btn-minimal" type="submit">
                                        <i class="fe-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="row pr-4 pl-4">
                        	<div class="col-md-12">
                        		<button class="btn btn-sm btn-primary" title="New Chat" id="addChatRoomBtn"><i class="fe-user"></i> Add New Chat</button>
                            <button class="btn btn-sm btn-primary ml-3" title="New Group" id="addGroupChatRoomBtn"><i class="fe-users"></i> Create Group</button>
                        	</div>
                        </div>
                        <hr>
                        <nav class="nav d-block list-discussions-js mb-n6 pl-3" style="max-height: 450px; overflow-y: scroll;">
                        </nav>
                     
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
<div class="modal" tabindex="-1" role="dialog" id="groupChatRoomModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Start New Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12 form-group">
          <input type="text" name="group_name" class="form-control" placeholder="Group Name" id="group_name">
        </div>
        <div class="col-md-12 form-group">
          <select class="form-control select2" name="user_id[]" id="allUsers" multiple="multiple">
            <option value="">Select One</option>
          </select>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary" id="addNewGroupRoom">Start Chat</button>
      </div>
    </div>
  </div>
</div>
<script>
	$(document).ready(function(){
		var token = localStorage.getItem('token');
        var user_id = localStorage.getItem('user_id');
        var user_name = localStorage.getItem('user_name');

        $('.user_name').empty().text(user_name);
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
  					$('#chatUsers').append('<option value="'+v.user_id+'">'+v.user_name+'</option>');
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

    $('#addGroupChatRoomBtn').on('click',function(e){
        $('#groupChatRoomModal').modal('show');
        
    });
    $('#addNewGroupRoom').on('click',function(e){
	     e.preventDefault();
        var userIds = $('#allUsers').val();
        var group_name = $('#group_name').val();

        if(userIds.length !='0' && group_name !=''){
          $.ajax({
            type:'POST',
            url:"/api/create-new-group",
            data:{userIds:userIds,group_name:group_name},
            headers: {
                'Authorization': 'Bearer '+token
            }
            }).done(function(res){
              window.location.reload();
            }).fail(function(error) {
              
            });
        }else{
          alert('Select One User');
        }
    });

    getAllUsers();

    function getAllUsers(){
      $.ajax({
        type:'GET',
        url:"/api/get-all-users",
        headers: {
          'Authorization': 'Bearer '+token
        },
        contentType: 'application/json',
        }).done(function(res){
          $('#allUsers').empty();
          
          $.each(res.data,function(i,v){
            $('#allUsers').append('<option value="'+v.user_id+'">'+v.user_name+'</option>');
          });
        
        })
        .fail(function(error) {
          window.location.href = "/";
      });
    }

    $('.select2').select2({
          placeholder: "Select a one",
    });

    $('.signout').on('click',function(e){
      e.preventDefault();
      $.ajax({
        type:'POST',
        url:"/api/logout",
        headers: {
          'Authorization': 'Bearer '+token
        },
        contentType: 'application/json',
        }).done(function(res){
          window.location.href = '/';
        })
        .fail(function(error) {
          // window.location.href = "/";
        });

    })

  });
</script>