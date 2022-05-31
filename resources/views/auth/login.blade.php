<!DOCTYPE html>
<html>
<head>
	<title>Espark Chat</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
</head>
<body style="background: #092756;">
	<div class="container mt-5">
		<div class="row " >
			<div class="col-md-6 m-auto" >
				<div class="card shadow-sm" style="position: absolute;top :100px">
					<div class="card-header border-0 text-center bg-white">
						<h6>Login</h6>
					</div>
					<div class="card-body">
						<div class="alert alert-danger d-none" id="errors">
							<span class="text-danger" id="span_error"></span>
						</div>
						<form id="form" autocomplete="off" >
							<div class="row">
								<div class="col-md-12 form-group">
									<label>Email Address</label>
									<input type="email" class="form-control" name="email" value="{{old('email')}}" required="required" placeholder="Email Address">
								</div>
								<div class="col-md-12 form-group">
									<label>Password</label>
									<input type="password" class="form-control" name="password" value="" required="required" placeholder="Password">
								</div>
								<div class="col-md-12 form-group">
									<button type="submit" class="btn btn-primary w-100 btn-sm"> Sign In </button>
								</div>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script>
	$(document).ready(function(){
		// var token = localStorage.getItem('token');
		// console.log(token);
		// if(token != undefined){
		// 	window.location.href = 'home';
		// }
		$('#form').on('submit',function(e){
			e.preventDefault();
			var userDetails = [];
			var formData = $('#form').serialize();
			$.ajax({
				type:'POST',
				url:'/api/authenticate',
				data:formData,
			}).done(function(res){
				var user = res.data.user;
				Object.keys(user).map(function(index){
					userDetails[index] = user[index]
				})
				localStorage.setItem('token',res.data.token);
				localStorage.setItem('user_id',userDetails.id);
				localStorage.setItem('user_name',userDetails.name);
				window.location.href = 'home'
				
		    })
		    .fail(function(error) {
		    	$('#errors').removeClass('d-none');
		    	$('#span_error').empty().html(error.responseJSON.errors)
		    });
		});
	});
</script>

</html>
