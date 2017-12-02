$('.tip a').click(function () {
	$('#loginForm, #registerForm').animate({
		height: "toggle",
		opacity: "toggle"
	}, "middle");
});

$('#loginButton').click(function () {
	$.ajax({
		type: 'post',
		url: 'php/login.php',
		data: {uid: $('#lemail').val(), passwd: $('#lpasswd').val()},
		success: function(data) {
			var obj = JSON.parse(data);
			if (obj.status === 0) { alert("登录成功！"); }
			if (obj.status === 1) { alert("用户名不存在！"); }
			if (obj.status === 2) { alert("密码错误！"); }
		},
		error: function(xhr) {
			alert(JSON.stringify(xhr));
		}
	});
});

$('#regButton').click(function () {
	$.ajax({
		type: 'post',
		url: 'php/sign.php',
		data: {uid: $('#remail').val(), passwd: $('#rpasswd').val(), nickname: $('#rnick').val(), wechatID: $('#rwechat').val()},
		success: function(data) {
			var obj = JSON.parse(data);
			if (obj.status === 0) {
				alert('注册成功!');
				self.location = "Shop.html"; 
			}
			if (obj.status === 1) { alert('邮箱已存在!'); }
		},
		error: function(xhr) {
			alert(JSON.stringify(xhr));
		}
	});
});
