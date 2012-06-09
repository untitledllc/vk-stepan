
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>index</title>
	<link rel="stylesheet" href="./css/bootstrap.css">
    <link rel="stylesheet" href="./css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<? if(isset($_GET['vk'])) { ?>
	<script src="http://vk.com/js/api/xd_connection.js?2"></script>
<? } else { ?>
	<script src="http://vk.com/js/api/openapi.js"></script>
<? } ?>
	<style>
	body {
		padding-top: 20px;
	}
	#codes_form {
		padding-top: 20px;
	}
	.container {
		width: 600px;
		margin: 0 auto;
	}
	#content {
		display: none;
	}
	.status {
		float: left;
		vertical-align: middle;
		margin: 7px 7px 7px 0;
	}
<? if(!isset($_GET['vk'])) { ?>
	#login, #logout {
		height: 21px;
		background: url("./img/login.png") no-repeat;
	}
	#login {
		width: 125px;
	}
	#logout {
		width: 126px;
		background-position: 0 -21px;
	}
<? } ?>
	.load {
		background: url("./img/load.gif") no-repeat;
	}
    </style>
</head>
<body>
<div class="container">
	<span id="top"></span>
<? if(!isset($_GET['vk'])) { ?>
	<a href="" id="login"></a>
	<a href="" id="logout"></a>
<? } ?>
	<div id="content">
		<div id="getProfileUploadServer"></div>
		<div id="saveProfilePhoto"></div>
		<form action="" id="codes_form">
			<label for="" class="codes_label">
				<i class="icon-black status"></i>
				<input type="text" class="codes" id="code0">
			</label>
			<label for="" class="codes_label">
				<i class="icon-black status"></i>
				<input type="text" class="codes" id="code1">
			</label>
			<label for="" class="codes_label">
				<i class="icon-black status"></i>
				<input type="text" class="codes" id="code2">
			</label>
			<label for="" class="codes_label">
				<i class="icon-black status"></i>
				<input type="text" class="codes" id="code3">
			</label>
			<label for="" class="codes_label">
				<i class="icon-black status"></i>
				<input type="text" class="codes" id="code4">
			</label>
		</form>
	</div>
</div>
<script>
var count=0, pic = new Image();
pic.src='./img/load.gif'; // предзагрузка загрузки
<? if(!isset($_GET['vk'])) { /* если с сайта */ ?>
VK.init({
  apiId: 2988039
});
$('#login').click(function(event){
	event.preventDefault();
	VK.Auth.login(authInfo, 4);
});
$('#logout').click(function(event){
	event.preventDefault();
	VK.Auth.logout(authInfo);
});
function authInfo(response) {
  if (response.session) {
	console.log('user login');
	document.getElementById('login').style.display = 'none';
	document.getElementById('logout').style.display = 'block';
  } else {
	console.log('user logout');
	document.getElementById('login').style.display = 'block';
	document.getElementById('logout').style.display = 'none';
	document.getElementById('content').style.display = 'none';
	document.getElementById('top').innerHTML='Ещё больше увлекательных конкурсов и ценных призов для друзей "Степана" в группе ВКонтакте.<br><b>Для участия войдите с помощью своего аккаунта ВКонтакте.</b>';
  }
}
VK.Observer.subscribe('auth.login', function(response) {
	var photo = 'return { me: API.users.get({uids: API.getVariable({key: 1280}), fields: "screen_name,photo_big"})[0]};';
	VK.Api.call('execute', {'code': photo}, _getProfile);
});
function _getProfile(data) {
	if (data.response) {
		if (data.response.me) {
			var i = data.response.me;
			$.getJSON('login.php', {login:i.uid}, function(data){
				count=data.codeCount;
				console.log('user: ' + i.uid + ', user: ' + i.first_name + ' ' + i.last_name+', link: http://vk.com/' + i.screen_name+', photo: ' + i.photo_big+', кодов введено: ' + count);
				if(count==5) {
					document.getElementById('top').innerHTML='<h1>Вы стали другом Степана Разина!</h1><p>Теперь вам доступен уникальный контент <a href="http://vk.com/page-25560758_43897020" target="_blank">в группе бренда ВКонтакте</a>. Выберите символ, который будет добавлен к вашей аватарке, в знак дружбы с легендарным пиво.</p>';
					VK.api('photos.getProfileUploadServer', function(data) {
						alert("here1");
						document.getElementById('getProfileUploadServer').innerHTML=data.response.upload_url;
						if(data.response.upload_url) {
								$.post("vkSender.php", {upload_url: data.response.upload_url, login: i.uid, ava: i.photo_big, logo: 1}, function(json){
									VK.api('photos.saveProfilePhoto', {server: json.server, photo: json.photo, hash: json.hash}, function(data2) {
										VK.api.call("showProfilePhotoBox", data2.response.photo_hash);
									});
								}, 'json');
						}
					});
				
				} else {
					console.log('осталось: ' + (5-count));
					document.getElementById('content').style.display = 'block';
					document.getElementById('top').innerHTML='Введи 5 кодов из-под крышек пива "Степан Разин" и получи доступ к уникальному контенту в группе "Степана" ВКонтакте!';
				}
				for (var j = 0; j < 5; j++) {
					if(data['code' + j] != undefined) {
						$('#code' + j).val(data['code' + j]);
						$('#code' + j).attr('disabled', 'disabled');
						$('#code' + j).prev().addClass('icon-ok');
					}
				}
			});
			
		$(".codes").each(function(indx, element){
			$(element).keyup(function(e){
				var v = $(element).val();
				if(v.length == 8) {
					$(element).attr('disabled','disabled').addClass('disabled').blur();
					$(element).prev().addClass('load');
					$.getJSON('add_code.php', {login: i.uid, codes: v}, function(data1){
						if(data1) {
							$(element).prev().removeClass('load icon-ok').addClass('icon-remove');
							$(element).removeAttr('disabled').removeClass('disabled').blur();
						}
						else {
							$(element).prev().removeClass('load icon-remove').addClass('icon-ok');
							count++;
							if(count==5) {
								
								document.getElementById('top').innerHTML='<h1>Вы стали другом Степана Разина!</h1><p>Теперь вам доступен уникальный контент <a href="http://vk.com/page-25560758_43897020" target="_blank">в группе бренда ВКонтакте</a>. Выберите символ, который будет добавлен к вашей аватарке, в знак дружбы с легендарным пиво.</p>';
								document.getElementById('content').style.display = 'none';
								VK.api('photos.getProfileUploadServer', function(data) {
									alert("here2");
									document.getElementById('getProfileUploadServer').innerHTML=data.response.upload_url;
									if(data.response.upload_url) {
											$.post("vkSender.php", {upload_url: data.response.upload_url, login: i.uid, ava: i.photo_big, logo: 1}, function(json){
												VK.api('photos.saveProfilePhoto', {server: json.server, photo: json.photo, hash: json.hash}, function(data2) {
													VK.api.call("showProfilePhotoBox", data2.response.photo_hash);
												});
											}, 'json');
									}
								});
							} else {
								console.log('осталось: ' + (5-count));
							}
						}
					});
				} else if(v.length > 8){
					$(this).prev().addClass('icon-remove');
				}
			});
		});
			
		}
	} else {
		document.getElementById('content').innerHTML = 'error'; 
	}
}

VK.Auth.getLoginStatus(authInfo);
<? } else { /* если из приложения */ ?>
VK.init(function() {
	
	VK.loadParams(document.location.href);
	var viewer_id = VK.params.viewer_id;
	VK.callMethod('showInstallBox',4);
	VK.api("getProfiles", {uids:viewer_id,fields:"screen_name,photo_big"}, function(data0) {
		var i = data0.response[0];
		$.getJSON('login.php', {login:i.uid}, function(data) {
			count=data.codeCount;
			console.log('user: '+i.uid+', user: ' + i.first_name + ' ' + i.last_name+', link: http://vk.com/' + i.screen_name+', photo: ' + i.photo_big+', кодов введено: ' + count);
			if(count==5) {
				document.getElementById('top').innerHTML='<h1>Вы стали другом Степана Разина!</h1><p>Теперь вам доступен уникальный контент <a href="http://vk.com/page-25560758_43897020" target="_blank">в группе бренда ВКонтакте</a>. Выберите символ, который будет добавлен к вашей аватарке, в знак дружбы с легендарным пиво.</p>';
				VK.api('photos.getProfileUploadServer', function(data) {					
					if(data.response.upload_url) {
						$.post("vkSender.php", {upload_url: data.response.upload_url, login: i.uid, ava: i.photo_big, logo: 1}, function(json){
							VK.api('photos.saveProfilePhoto', {server: json.server, photo: json.photo, hash: json.hash});
						}, 'json');
					}
				});
			} else {
				console.log('осталось: ' + (5-count));
				document.getElementById('content').style.display = 'block';
				document.getElementById('top').innerHTML='Введи 5 кодов из-под крышек пива "Степан Разин" и получи доступ к уникальному контенту в группе "Степана" ВКонтакте!';
			}
			for (var j = 0; j < 5; j++) {
				if(data['code' + j] != undefined) {
					$('#code' + j).val(data['code' + j]);
					$('#code' + j).attr('disabled', 'disabled');
					$('#code' + j).prev().addClass('icon-ok');
				}
			}
		});
		
		$(".codes").each(function(indx, element){
			$(element).keyup(function(e){
				var v = $(element).val();
				if(v.length == 8) {
					$(element).attr('disabled','disabled').addClass('disabled').blur();
					$(element).prev().addClass('load');
					$.getJSON('add_code.php', {login: i.uid, codes: v}, function(data1){
						if(data1) {
							$(element).prev().removeClass('load icon-ok').addClass('icon-remove');
							$(element).removeAttr('disabled').removeClass('disabled').blur();
						}
						else {
							$(element).prev().removeClass('load icon-remove').addClass('icon-ok');
							count++;
							if(count==5) {
								document.getElementById('top').innerHTML='<h1>Вы стали другом Степана Разина!</h1><p>Теперь вам доступен уникальный контент <a href="http://vk.com/page-25560758_43897020" target="_blank">в группе бренда ВКонтакте</a>. Выберите символ, который будет добавлен к вашей аватарке, в знак дружбы с легендарным пиво.</p>';
								document.getElementById('content').style.display = 'none';
								VK.api('photos.getProfileUploadServer', function(data) {
									if(data.response.upload_url) {
										$.post("vkSender.php", {upload_url: data.response.upload_url, login: i.uid, ava: i.photo_big, logo: 1}, function(json){
											VK.api('photos.saveProfilePhoto', {server: json.server, photo: json.photo, hash: json.hash});
										}, 'json');
									}
								});
							} else {
								console.log('осталось: ' + (5-count));
							}
						}
					});
				} else if(v.length > 8){
					$(this).prev().addClass('icon-remove');
				}
			});
		});
	});
});
<? } ?>
</script>
</body>
</html>
