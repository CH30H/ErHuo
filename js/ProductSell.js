$('#mainCate').change(function () {
	$('#nextCate').removeAttr('disabled');
	var cur = $('#mainCate').val();
	if (cur === 'Book') {
		$('#nextCate').html('<option selected disabled>Select a Category</option>' +
			'<option>Textbook</option>' +
			'<option>Foreign Language</option>' +
			'<option>Art</option>' +
			'<option>Novel</option>' +
			'<option>Magazine</option>' +
			'<option>Manga</option>' +
			'<option>Reference Book</option>');
	}
	if (cur === 'Electronic Product') {
		$('#nextCate').html('<option selected disabled>Select a Category</option>' +
			'<option>Mobile Phone</option>' +
			'<option>Laptop</option>' +
			'<option>Tablet</option>' +
			'<option>Camera</option>' +
			'<option>Peripheral</option>' +
			'<option>PC Component</option>');
	}
	if (cur === 'Ticket') {
		$('#nextCate').html('<option selected disabled>Select a Category</option>' +
			'<option>Movie Ticket</option>' +
			'<option>Concert Ticket</option>' +
			'<option>Exhibition Ticket</option>' +
			'<option>Comics Show Ticket</option>');
	}
	if (cur === 'Commodity' || cur === 'Other') {
		$('#nextCate').attr('disabled', true);
	}
});

$('#submit').click(function () {
	var fd = new FormData();
  alert(fd);
	fd.append('name', $('#name').val());
	fd.append('price', $('#price').val());
	fd.append('newness', $('#newness').val());
	fd.append('mainCate', $('#mainCate').prop('selectedIndex'));
	fd.append('nextCate', $('#nextCate').prop('selectedIndex'));
	fd.append('photo1', $('#photo1')[0].files[0]);
	fd.append('photo2', $('#photo2')[0].files[0]);
	fd.append('photo3', $('#photo3')[0].files[0]);
	fd.append('description', $('#description').val());
  alert(fd);
	$.ajax({
		type: 'post',
		/* ? */
		url: 'php/sell.php',
		data: fd,
    processData:false,   //  告诉jquery不要处理发送的数据
    contentType:false,  
		success: function (data) {
			var obj = JSON.parse(data);
			if (obj.status === 0) {
				alert("上传成功！点击确定返回商品浏览页面");
				self.location = "Shop.html";
			}
			else if (obj.status === 1) {
				alert("上传失败！");
			}
			else if (obj.status === 2) {
				alert("登录超时，请重新登录");
				self.location = "LoginAndRegister.html";
			}
			else if (obj.status === 3) {
				alert("未知的错误...");
			}
		},
		error: function (xhr) {
			alert(JSON.stringify(xhr));
		}
	});
});
