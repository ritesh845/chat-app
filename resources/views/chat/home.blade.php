@extends('chat.layouts.main')
@section('content')
@include('chat.components.sidebar')
<div class="main" data-mobile-height="">

    <!-- Default Page -->
    <div class="chat flex-column justify-content-center text-center">
        <div class="container-xxl">

            <div class="avatar avatar-lg mb-5 firstLetterHead" style="padding: 30px 0px">
                
            </div>

            <h6 >Hey! <span class="user_name"></span></h6>
            <p>Please select a chat to start messaging.</p>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        getUserChatrooms();      
        
		var firstLetterHead = user_name.charAt(0);
		$('.firstLetterHead').text(firstLetterHead);


        $('.user_name').empty().text(user_name);
    });
</script>
@endsection
