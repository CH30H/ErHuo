$('.tip a').click(function () {
	$('#loginForm, #registerForm').animate({
		height: "toggle",
		opacity: "toggle"
	}, "middle");
});

$('#loginButton').click(function () {
	var public_key = '-----BEGIN PUBLIC KEY-----\
	MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDnPDaP0OezUiHniz29VrVLp4+E\
	jXTSw6I/RqKaSbsuVvoPUznmlUDvTFWC6cPi75kUGoObwvIpdixXoAeKMcgEvMTe\
	4PwyeljVq6DQmRSwj94/fGuE/IEE6TriSDlYkK6dTJJLkzzZm/cxIlRmxh52EAge\
	fweYmWVNZ8MjcvRT9wIDAQAB\
	-----END PUBLIC KEY-----';
	var key = RSA.getPublicKey(public_key);
	var encrypted_passwd = RSA.encrypt($('#lpasswd').val(), key);
	$.ajax({
		type: 'post',
		url: 'php/login.php',
		data: {uid: $('#lemail').val(), passwd: encrypted_passwd},
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
	var public_key = '-----BEGIN PUBLIC KEY-----\
	MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDnPDaP0OezUiHniz29VrVLp4+E\
	jXTSw6I/RqKaSbsuVvoPUznmlUDvTFWC6cPi75kUGoObwvIpdixXoAeKMcgEvMTe\
	4PwyeljVq6DQmRSwj94/fGuE/IEE6TriSDlYkK6dTJJLkzzZm/cxIlRmxh52EAge\
	fweYmWVNZ8MjcvRT9wIDAQAB\
	-----END PUBLIC KEY-----';
	var key = RSA.getPublicKey(public_key);
	var encrypted_passwd = RSA.encrypt($('#rpasswd').val(), key);
	var encrypted_wechatID = RSA.encrypt($('#rwechat').val(), key);
	$.ajax({
		type: 'post',
		url: 'php/sign.php',
		data: {uid: $('#remail').val(), passwd: encrypted_passwd, nickname: $('#rnick').val(), wechatID: encrypted_wechatID},
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
