<form action="/api/upload-file" method="post" enctype="multipart/form-data" class="dropzone hide">
    <input type="hidden" name="chatroom_id" value="{{$chatroom_id}}">
</form>
<p></p>
<div class="chat-footer border-top py-4 py-lg-6 px-lg-8">

    <div class="container-xxl">
        
        <form id="formSendMessage" autocomplete="off">

        <div class="form-row align-items-center">
            <div class="input-group-append ">
                <button id="chat-upload-btn-1" class="btn btn-ico btn-secondary btn-minimal bg-transparent border-0 dropzone-button-js"  type="button">
                    <img src="{{asset('assets/images/paperclip.svg')}}" data-inject-svg="" alt="">
                </button>
            </div>

            <div class="col">
                <div class="input-group">

                    <!-- Textarea -->
                    <textarea id="chat-input" class="form-control bg-transparent border-0" placeholder="Type your message..." rows="1" data-emoji-input="" data-autosize="true"  name="message" style="resize: none;"></textarea>

                    <!-- Emoji button -->
                   {{--  <div class="input-group-append">
                        <button class="btn btn-ico btn-secondary btn-minimal bg-transparent border-0" type="button" data-emoji-btn="">
                            <img src="{{asset('assets/images/smile.svg')}}" data-inject-svg="" alt="">
                        </button>
                    </div> --}}

                    <!-- Upload button -->

                    

                </div>

            </div>
            <!-- Submit button -->
            <div class="col-auto">
                <input type="hidden" name="message_id" id="message_id" value="">  
                <input type="hidden" name="is_file" value="0" id="is_file">  
                <input type="hidden" name="old_message" id="old_message" value="">  
                <input type="hidden" name="message_index" id="message_index" value="">  
                <button class="btn btn-ico btn-primary rounded-circle" type="submit" id="sendBtn" title="Send Message">
                    <span class="fe-send"></span>
                </button>
                <a href="javascript:void(0)" class="btn btn-ico btn-secondary rounded-circle hide" id="cancleBtn" title="Cancel Edit">
                    <span class="fe-times">&times;</span>
                </a>
            </div>

        </div>
        </form>

    </div>
</div>
<!-- Chat: Footer -->
</div>


<script>
    $(document).ready(function(){
        var token = localStorage.getItem('token');
        var user_id = localStorage.getItem('user_id');
        let chatroom_id = "{{$chatroom_id}}";

            // $("#chat-input").emojioneArea();

        var myDropzone = new Dropzone('.dropzone', {
              url: "/api/upload-file",                        
              autoProcessQueue: false,
              headers:{"Authorization":'Bearer ' + token},
              addRemoveLinks: true, 
              maxFiles: 5, 
              clickable: true, 
        });


       
        
        $('#formSendMessage').submit(function(e){
            e.preventDefault();
            
            let message = $('#chat-input').val();
            let chatroom_id = "{{$chatroom_id}}";
            let quoting = false;

            let message_id = $('#message_id').val();
            let index = $('#message_index').val();
            let is_file = $('#is_file').val();
            
            if(message_id ==''){
                myDropzone.processQueue();
                myDropzone.on("complete", function(file) {
                  myDropzone.removeFile(file);
                  getRoomConversations(chatroom_id);
                  getUserChatrooms();
                });
                $('.dropzone').addClass('hide');
                if(message !=''){
                    $.ajax({
                        type:'POST',
                        url:"/api/send-message",
                        data:{message:message,chatroom_id:chatroom_id,quoting:quoting},
                        headers: {
                                'Authorization': 'Bearer '+token
                            }
                        }).done(function(res){
                            $('#chat-input').val('');
                            getRoomConversations(chatroom_id);
                            getUserChatrooms();

                        }).fail(function(error) {
                            
                    });
                }
               
            }else{
                $.ajax({
                    type:'POST',
                    url:"/api/edit-message",
                    data:{message:message,chatroom_id:chatroom_id,quoting:quoting,message_id:message_id,index:index,is_file:is_file},
                    headers: {
                            'Authorization': 'Bearer '+token
                        }
                }).done(function(res){
                        $('#chat-input').val('');
                        getRoomConversations(chatroom_id);
                        getUserChatrooms();
                    // console.log(res);

                }).fail(function(error) {
                    
                }); 
            }
           
        }) 

        $('.dropzone-button-js').on('click',function(e){
            e.preventDefault();
             let is_file = $('input[name="is_file"]').val();
             if(is_file !=0){
                $('input[name="is_file"]').val(0);
             }else{
                $('input[name="is_file"]').val(1);
             }  
            $('.dropzone').toggleClass('hide');
        })
       

        $('#chat-input').on('keyup',function(e){
            e.preventDefault();
            let message = $(this).val();
            let oldMessage = $('#old_message').val();
            let message_id = $('#message_id').val();
            if(message_id !=''){

                if(oldMessage == message){
                    $('#cancleBtn').removeClass('hide');
                    $('#sendBtn').addClass('hide');
                }else{
                    $('#cancleBtn').addClass('hide');
                    $('#sendBtn').removeClass('hide');
                }
                if(message ==''){
                    $('#cancleBtn').removeClass('hide');
                    $('#sendBtn').addClass('hide');
                }
            }

        })

        $('#cancleBtn').on('click',function(e){
            e.preventDefault();
            $('#old_message').val('');
            $('#chat-input').val('');
            $('#message_id').val('');
            $('#message_index').val('');
            $('#cancleBtn').addClass('hide');
            $('#sendBtn').removeClass('hide');

        });


      

    });
    
</script>