<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="ru">
<head>
	<meta charset="windows-1251">
	<title>index</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<? if(isset($_GET['vk'])) { ?>
	<script src="http://vk.com/js/api/xd_connection.js?2"></script>
<? } else { ?>
	<script src="http://vk.com/js/api/openapi.js?3" type="text/javascript"></script>
<? } ?>


<style>
body {
	padding-top: 20px;
	margin: 0;
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	font-size: 13px;
	line-height: 18px;
	color: #000;
	background: #fff;
}
a:focus {
	outline: thin dotted #333;
	outline: 5px auto -webkit-focus-ring-color;
	outline-offset: -2px;
}

a:hover,
a:active {
	outline: 0;
}
a {
	color: #000;
	text-decoration: underline;
	font-weight: bold;
}

input::-moz-focus-inner {
	padding: 0;
	border: 0;
}
p {
	margin: 0 0 9px;
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	font-size: 13px;
	line-height: 18px;
}
h1 {
	margin: 0;
	font-family: inherit;
	font-weight: bold;
	color: inherit;
	text-rendering: optimizelegibility;
	font-size: 24px;
	line-height: 36px;
}

label {
	display: block;
}

input {
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}

label,
input {
	font-size: 13px;
	font-weight: normal;
	line-height: 18px;
}

input:focus,
textarea:focus {
	outline: 0;
	outline: thin dotted \9;
	/* IE6-9 */
}
.container {
	width: 450px;
	margin: 0 auto;
}
#top {
	text-align: center;
}
#codes_form {
	padding-top: 20px;
}
#content {
	display: none;
}
.codes {
	margin: 0;
	vertical-align: middle;
	*overflow: visible;
	line-height: normal;
	display: inline-block;
	width: 394px;
	height: 18px;
	padding: 4px;
	margin-bottom: 8px;
	font-size: 13px;
	line-height: 18px;
	color: #000;
	border-color: #d8d8d8;
	border: 1px solid #ccc;
	-webkit-border-radius: 1px;
	-moz-border-radius: 1px;
	border-radius: 1px;
}
input[disabled], .disabled {
	background: #fff;
	color: #d8d8d8;
	cursor: not-allowed;
}
.status {
	float: left;
	vertical-align: middle;
	margin: 7px 7px 7px 0;
	display: inline-block;
	width: 27px;
	height: 14px;
	*margin-right: .3em;
	line-height: 14px;
	vertical-align: text-bottom;
}
.logo {
	width: 140px;
	height: 140px;
	padding: 5px;
	cursor: handler;
	cursor: pointer;
}
.wait {
	cursor: progress;
} 
<? if(!isset($_GET['vk'])) { ?>
#login, #logout {
	margin: 8px auto 0;
	background: url("./img/login.png") no-repeat;
}
<? } ?>	.ok, .remove {
	background: url("./img/login.png") no-repeat;
}
<? if(!isset($_GET['vk'])) { ?>	#login {
	width: 127px;
	height: 47px;
}
#logout {
	width: 87px;
	height: 33px;
	background-position: 0 -47px; 
}
<? } ?>
.ok {
	width: 27px;
	height: 22px;
	background-position: -87px -48px;
	margin-top: 4px;
}
.remove {
	width: 18px;
	height: 20px;
	background-position: -114px -49px;
	margin-top: 4px;
	margin-right: 12px;
	margin-left: 4px;
}
.load {
	background: url("./img/load.gif") no-repeat;
}
</style>
</head>
<body>
<div class="container">
	<center id="top"></center>
<? if(!isset($_GET['vk'])) { ?>
	<a href="" id="login"></a>
	<a href="" id="logout"></a>
<? } ?>
	
	<div id="logo"></div>
	<div id="content">
		<div id="getProfileUploadServer"></div>
		<form action="" id="codes_form">
			<label for="" class="codes_label">
				<i class="status"></i>
				<input type="text" class="codes" id="code0">
			</label>
			<label for="" class="codes_label">
				<i class="status"></i>
				<input type="text" class="codes" id="code1">
			</label>
			<label for="" class="codes_label">
				<i class="status"></i>
				<input type="text" class="codes" id="code2">
			</label>
			<label for="" class="codes_label">
				<i class="status"></i>
				<input type="text" class="codes" id="code3">
			</label>
			<label for="" class="codes_label">
				<i class="status"></i>
				<input type="text" class="codes" id="code4">
			</label>
		</form>
	</div>
	<marquee></marquee>
</div>

<script>
var count=0, pic = new Image();
pic.src='./img/load.gif'; // предзагрузка загрузки
<? if(!isset($_GET['vk'])) { /* если с сайта */ ?>
VK.init({
  apiId: 3047603
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
	//console.log('user login');
	document.getElementById('login').style.display = 'none';
	document.getElementById('logout').style.display = 'block';
  } else {
	//console.log('user logout');
	document.getElementById('login').style.display = 'block';
	document.getElementById('logout').style.display = 'none';
	document.getElementById('content').style.display = 'none';
	document.getElementById('top').innerHTML='Ещё больше увлекательных конкурсов и ценных призов <br>для друзей "Степана" в группе <a href="http://vk.com/stepanlegenda" target="_blank">ВКонтакте</a>.<br><b>Для участия войдите с помощью своего аккаунта ВКонтакте.</b>';
  	document.getElementById('logo').style.display = 'none';
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
				if(data.banned!=0) {
					if(data.banned=='ip') {
						document.getElementById('content').innerHTML=i.first_name+', Ваш IP адрес заблокирован на сутки, так как Вы слишком часто вводили неправильные коды!';
					} else if(data.banned=='login') {
						document.getElementById('content').innerHTML=i.first_name+', Ваша учетная запись заблокирована за попытку перебора кодов!';
					}
				}
				count=data.codeCount;
				//console.log('user: ' + i.uid + ', user: ' + i.first_name + ' ' + i.last_name+', link: http://vk.com/' + i.screen_name+', photo: ' + i.photo_big+', кодов введено: ' + count);
				if(count==5) {
					document.getElementById('top').innerHTML='<h1>Вы стали членом команды Легендарного плавучего бара.</h1><p>Примите участие в <a href="http://vk.com/pages?oid=-25560758&p=%D0%94%D0%BE%D0%BB%D0%B3%D0%BE%D0%B6%D0%B4%D0%B0%D0%BD%D0%BD%D0%BE%D0%B5%20%D0%B2%D0%BE%D0%B7%D0%B2%D1%80%D0%B0%D1%89%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%9F%D0%BB%D0%B0%D0%B2%D0%B1%D0%B0%D1%80%D0%B0" target="_blank">конкурсе</a> чтобы получить свои два билета на главное путешествие лета!</p>';
					document.getElementById('content').style.display='none';
					document.getElementById('logo').style.display = 'block';
					var stat = data.status;
					document.getElementById('logo').innerHTML='<a href="http://vk.com/app2988039_48847976" target="_top"><img src="logo'+stat+'.png" class="logo" id="logo1"></a>';
					VK.api('photos.getProfileUploadServer', function(data) {
					document.getElementById('getProfileUploadServer').innerHTML=data.response.upload_url;
					if(data.response.upload_url) {
							$.post("vkSender.php", {upload_url: data.response.upload_url}, function(json){
								VK.api('photos.saveProfilePhoto', {server: json.server, photo: json.photo, hash: json.hash}, function(data2) {
									VK.api.call("showProfilePhotoBox", data2.response.photo_hash);
								});
							}, 'json');
					}
				});
				} else {
					//console.log('осталось: ' + (5-count));
					document.getElementById('content').style.display = 'block';
					document.getElementById('top').innerHTML='Введи пять кодов из под крышек продукта с промо этикеткой и получи доступ к уникальному контенту в группе "Легенда 1795"';
				}
				for (var j = 0; j < 5; j++) {
					if(data['code' + j] != undefined) {
						$('#code' + j).val(data['code' + j]);
						$('#code' + j).attr('disabled', 'disabled');
						$('#code' + j).prev().addClass('ok');
					}
				}
			});
		$(".codes").each(function(indx, element){
			$(element).keyup(function(e){
				var v = $(element).val();
				if(v.length == 8) {
					$(element).attr('disabled','disabled').addClass('disabled').blur();
					$(element).prev().addClass('load');
					$.post('add_code.php', {login: i.uid, codes: v}, function(data1){
						if(data1.banned!=0) {
							if(data1.banned=='ip') {
								document.getElementById('content').innerHTML=i.first_name+', Ваш IP адрес заблокирован на сутки, так как Вы слишком часто вводили неправильные коды!';
							} else if(data1.banned=='login') {
								document.getElementById('content').innerHTML=i.first_name+', Ваша учетная запись заблокирована за попытку перебора кодов!';
							}
						} else if(data1.blocked!=0) {
							document.getElementById('content').innerHTML=i.first_name+', Вы слишком часто вводите коды! Попробуйте снова через пару минут.';
						}
						if(data1.ok==1) {
							$(element).prev().removeClass('load remove').addClass('ok');
							count++;
							if(count==5) {
								var stat;
								$.getJSON('login.php', {login:i.uid}, function(d){
									stat = d.status;
									document.getElementById('top').innerHTML='<h1>Вы стали членом команды Легендарного плавучего бара.</h1><p>Примите участие в <a href="http://vk.com/pages?oid=-25560758&p=%D0%94%D0%BE%D0%BB%D0%B3%D0%BE%D0%B6%D0%B4%D0%B0%D0%BD%D0%BD%D0%BE%D0%B5%20%D0%B2%D0%BE%D0%B7%D0%B2%D1%80%D0%B0%D1%89%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%9F%D0%BB%D0%B0%D0%B2%D0%B1%D0%B0%D1%80%D0%B0" target="_blank">конкурсе</a> чтобы получить свои два билета на главное путешествие лета!</p>';
									document.getElementById('content').style.display='none';
									document.getElementById('logo').style.display = 'block';
									document.getElementById('logo').innerHTML='<a href="http://vk.com/app2988039_48847976" target="_top"><img src="logo'+stat+'.png" class="logo" id="logo1"></a>';
								});
								
							} else {
								//console.log('осталось: ' + (5-count));
							}
						} else {
							$(element).prev().removeClass('load ok').addClass('remove');
							$(element).removeAttr('disabled').removeClass('disabled').blur();
						}
					}, 'json');
				} else if(v.length > 8){
					$(this).prev().addClass('remove');
				}
			});
		});
			
		}
	} else {
		document.getElementById('content').innerHTML = 'error'; 
	}
}

VK.Auth.getLoginStatus(authInfo, true);
<? } else { /* если из приложения */ ?>


		
VK.init(function() {

	VK.loadParams(document.location.href);
	var viewer_id = VK.params.viewer_id;
	VK.callMethod('showInstallBox',4);
	VK.api("getProfiles", {uids:viewer_id,fields:"screen_name,photo_big"}, function(data) {
		var i = data.response[0];
		$.getJSON('login.php', {login:i.uid}, function(data) {
			if(data.banned!=0) {
				if(data.banned=='ip') {
					document.getElementById('content').innerHTML=i.first_name+', Ваш IP адрес заблокирован на сутки, так как Вы слишком часто вводили неправильные коды!';
				} else if(data.banned=='login') {
					document.getElementById('content').innerHTML=i.first_name+', Ваша учетная запись заблокирована за попытку перебора кодов!';
				}
			} else if(data.blocked!=0) {
				document.getElementById('content').innerHTML=i.first_name+', Вы слишком часто вводите коды! Попробуйте снова через пару минут.';
			}

			count=data.codeCount;
			//console.log('user: '+i.uid+', user: ' + i.first_name + ' ' + i.last_name+', link: http://vk.com/' + i.screen_name+', photo: ' + i.photo_big+', кодов введено: ' + count);
			if(count==5) {
				var stat = data.status;
				document.getElementById('top').innerHTML='<h1>Вы стали членом команды Легендарного плавучего бара.</h1><p>Примите участие в <a href="http://vk.com/pages?oid=-25560758&p=%D0%94%D0%BE%D0%BB%D0%B3%D0%BE%D0%B6%D0%B4%D0%B0%D0%BD%D0%BD%D0%BE%D0%B5%20%D0%B2%D0%BE%D0%B7%D0%B2%D1%80%D0%B0%D1%89%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%9F%D0%BB%D0%B0%D0%B2%D0%B1%D0%B0%D1%80%D0%B0" target="_blank">конкурсе</a> чтобы получить свои два билета на главное путешествие лета!</p>';
				document.getElementById('content').style.display='none';
				document.getElementById('logo').innerHTML='<img src="logo'+stat+'.png" class="logo" id="logo1">';
				$(".logo").each(function(indx, element) {
					$(element).click(function(event){
						$('*').addClass('wait');
						VK.api('photos.getProfileUploadServer', function(data) {
							if(data.response.upload_url) {
								$.post("vkSender.php", {upload_url: data.response.upload_url, login: i.uid, ava: i.photo_big, logo: stat}, function(json){
									VK.api('photos.saveProfilePhoto', {server: json.server, photo: json.photo, hash: json.hash});
									$('*').removeClass('wait');
								}, 'json');
							}
						});
					});
				});
			} else {
				//console.log('осталось: ' + (5-count));
				document.getElementById('content').style.display = 'block';
				document.getElementById('top').innerHTML='Введи пять кодов из под крышек продукта с промо этикеткой и получи доступ к уникальному контенту в группе "Легенда 1795"!';
			}
			for (var j = 0; j < 5; j++) {
				if(data['code' + j] != undefined) {
					$('#code' + j).val(data['code' + j]);
					$('#code' + j).attr('disabled', 'disabled');
					$('#code' + j).prev().addClass('ok');
				}
			}
		});
		$(".codes").each(function(indx, element){
			$(element).keyup(function(e){
				var v = $(element).val();
				if(v.length == 8) {
					$(element).attr('disabled','disabled').addClass('disabled').blur();
					$(element).prev().addClass('load');
					$.post('add_code.php', {login: i.uid, codes: v}, function(data1){
						if(data1.banned!=0){
							if(data1.banned=='ip') {
								document.getElementById('content').innerHTML=i.first_name+', Ваш IP адрес заблокирован на сутки, так как Вы слишком часто вводили неправильные коды!';
							} else if(data1.banned=='login') {
								document.getElementById('content').innerHTML=i.first_name+', Ваша учетная запись заблокирована за попытку перебора кодов!';
							}
						} else if(data1.blocked!=0) {
							document.getElementById('content').innerHTML=i.first_name+', Вы слишком часто вводите коды! Попробуйте снова через пару минут.';
						}
						if(data1.ok==1) {
							$(element).prev().removeClass('load remove').addClass('ok');
							count++;
							if(count==5) {
								var stat = 1;
								$.getJSON('login.php', {login:i.uid}, function(d){
									stat = d.status;
									document.getElementById('top').innerHTML='<h1>Вы стали членом команды Легендарного плавучего бара.</h1><p>Примите участие в <a href="http://vk.com/pages?oid=-25560758&p=%D0%94%D0%BE%D0%BB%D0%B3%D0%BE%D0%B6%D0%B4%D0%B0%D0%BD%D0%BD%D0%BE%D0%B5%20%D0%B2%D0%BE%D0%B7%D0%B2%D1%80%D0%B0%D1%89%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%9F%D0%BB%D0%B0%D0%B2%D0%B1%D0%B0%D1%80%D0%B0" target="_blank">конкурсе</a> чтобы получить свои два билета на главное путешествие лета!</p>';
									document.getElementById('content').style.display = 'none';
									document.getElementById('logo').innerHTML='<img src="logo'+stat+'.png" class="logo" id="logo1">';
									$(".logo").each(function(indx, element) {
										$(element).click(function(event){
											$('*').addClass('wait');
											VK.api('photos.getProfileUploadServer', function(data) {
												if(data.response.upload_url) {
													$.post("vkSender.php", {upload_url: data.response.upload_url, login: i.uid, ava: i.photo_big, logo: stat}, function(json){
														VK.api('photos.saveProfilePhoto', {server: json.server, photo: json.photo, hash: json.hash});
														$('*').removeClass('wait');
													}, 'json');
												}
											});
										});
									});
								});
								
							} else {
							//	console.log('осталось: ' + (5-count));
							}
						} else {
							$(element).prev().removeClass('load ok').addClass('remove');
							$(element).removeAttr('disabled').removeClass('disabled').blur();
						}
					}, 'json');
				} else if(v.length > 8){
					$(this).prev().addClass('remove');
				}
			});
		});
	});
});
<? } ?>
</script>
</body>
</html>
