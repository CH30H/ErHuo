$('.tip a').click(function () {
	$('#loginForm, #registerForm').animate({
		height: "toggle",
		opacity: "toggle"
	}, "middle");
});

$('#loginButton').click(function () {
	var public_key = '-----BEGIN PUBLIC KEY-----\
	MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC3//sR2tXw0wrC2DySx8vNGlqt\
	3Y7ldU9+LBLI6e1KS5lfc5jlTGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2kl\
	Bd6h4wrbbHA2XE1sq21ykja/Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o\
	2n1vP1D+tD3amHsK7QIDAQAB\
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
	MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC3//sR2tXw0wrC2DySx8vNGlqt\
	3Y7ldU9+LBLI6e1KS5lfc5jlTGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2kl\
	Bd6h4wrbbHA2XE1sq21ykja/Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o\
	2n1vP1D+tD3amHsK7QIDAQAB\
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
