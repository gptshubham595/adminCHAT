<?php
session_start();
if(empty($_SESSION['userLogin']) || $_SESSION['userLogin'] == ''){
    header("Location: ../index.html");
    die();
}
?>
<!doctype html>
<html>
  <head>
    <title>Manage site</title>
    <style>
      * { margin: 0; padding: 0; box-sizing: border-box; }
      body { font: 13px Helvetica, Arial; }
    </style>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  </head>
  <body>
	<div class="container my-4">
		<div class="row">
			<div class="col-md-6">
				<form id="connect" action="" class="text-center">
					<h4>Register New Bot</h4>
					<input class='form-control mb-1' id="name" type="text" placeholder="Bot Name" autocomplete="" />
					<input class='form-control mb-1' id="min" type="text" placeholder="Minimum Amount" autocomplete="" />
					<input class='form-control mb-1' id="max" type="text" placeholder="Maximum Amount" autocomplete="" />
					<input class='form-control mb-1' id="cmax" type="text" placeholder="Minimum Payout" autocomplete="" />
					<input class='form-control mb-1' id="cmin" type="text" placeholder="Maximum Payout" autocomplete="" />
				  <button class='btn btn-success'>Submit New Bot</button>
				</form>	
			</div>
			<div class="col-md-6">
				<form id="chat" action="" class="text-center">
					<h4>Send New Chat</h4>
					<input class='form-control mb-1' id="namec" type="text" placeholder="UserName" autocomplete="" />
					<input class='form-control mb-1' id="room" type="text" placeholder="Room Code" value="us" />
					<textarea class='form-control mb-1' id='message' rows='5' cols="10" placeholder="Message"></textarea>
				  <button class='btn btn-success'>Submit New Chat</button>
				</form>			
			</div>
			<div class="col-md-12">
				<form id="dis" action="" class="text-center">
					<h4>Disable Bot</h4>
					<input class='form-control mb-1' id="idb" type="text" placeholder="Bot Name" value="" autocomplete="" />
				  <button class='btn btn-success mb-4'>Disable Bot</button>
				</form>			
			</div>
			<hr/>
			<div class="col-md-12">
				<form id="del" action="" class="text-center">
					<h4>Delete User</h4>
					<input class='form-control mb-1' id="idd" type="text" placeholder="User ID" value="f52670448" autocomplete="" />
				  <button class='btn btn-success'>Delete User</button>
				</form>			
			</div>
		</div>
	</div>
    <script src="./socket.io.js"></script>
    <script src="./jquery.min.js"></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/URI.js/1.19.1/URI.min.js'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/js-sha256/0.3.2/sha256.min.js'></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
    <script src="./crypt.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
      $(function () {
        var socket = io.connect('ws://62.171.143.207'); //Socket Address
        var passphrase = 'node_modules/express/index.js'; // make sure this code is in your api files.
        var crypter = Crypt(passphrase);
		
		const key = "secret";
		
		setTimeout(() => {
			socket.emit('all_withdrawals');
		}, 2000);
		
        socket.on('all_withdrawals', function(data) {
			data = crypter.decrypt(data)
			console.log(data)
        });
        
        socket.on('disable_bot', function(data) {
			alert('Bot Disabled!')
        });
        
        $('#bust').click(function(){
            socket.emit('force_bust');
			alert('Game Busted!')
        });
        
        $('#chat').submit(function(){
			let data = {
				command: 'fake_chat_' + key,
				name: $('#namec').val(),
				room: $('#room').val(),
				message: $('#message').val()
			};
            socket.emit('message', crypter.encrypt(data));
			alert('Message Sended!')
            return false;
        });

        $('#connect').submit(function(){
			let data = {
				command: 'bot_register_' + key,
				name: $('#name').val(),
				min: $('#min').val(),
				max: $('#max').val(),
				cmax: parseFloat($('#cmax').val()) * 100,
				cmin: parseFloat($('#cmin').val()) * 100
			};
            socket.emit('message', crypter.encrypt(data));
			alert('succesfully Submited !')
            return false;
        });
        
        $('#dis').submit(function(){
			let data = {
				command: 'disable_bot_' + key,
				id: $('#idb').val()
			};
            socket.emit('message', crypter.encrypt(data));
            return false;
        });
        
        $('#del').submit(function(){
			let data = {
				command: 'delete_user_' + key,
				id: $('#idd').val()
			};
            socket.emit('message', crypter.encrypt(data));
            return false;
        });
      });
		
		function createUserList(data){
			var i = 0;
			for(var i in data){
				let user = data[i];
				let name = user.username;
				$('#users').append(name);
			}
		}

    </script>
  </body>
</html>
