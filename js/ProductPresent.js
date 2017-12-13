function GetRequest() {
    var url = location.search;
    var theRequest = new Object();
    if (url.indexOf("?") != -1) {
        var str = url.substr(1);
        strs = str.split("&");
        for(var i = 0; i < strs.length; i ++) {
            theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
        }
    }
    return theRequest;
}

$( document ).ready(function() {
    var Request = new Object();
    Request = GetRequest();
    var gid;
    gid = Request['gid'];

    // a test sentence
    alert("Get gid = " + gid);

    // a test sentence
    document.getElementById("photo1").src = "resource/sample3.jpg";

    $.ajax({
        type: 'post',
        url:  'php/productinfo.php',
        data: {'gid': gid},
        success: function (data) {
            var info = JSON.parse(data);
            if (info.status == 0) {
                document.getElementById('标签id').innerHTML = '要修改的文本内容';
            }
            if (info.status == 1) {
                alert("该商品已下架！");
            }
        }
    });
});

$('#buy').click(function () {
    document.getElementById("photo2").src = "resource/sample3.jpg";
// 	var fd = new FormData();
//     alert(fd);
// 	fd.append('name', $('#name').val());
// 	fd.append('price', $('#price').val());
// 	fd.append('newness', $('#newness').val());
// 	fd.append('mainCate', $('#mainCate').prop('selectedIndex'));
// 	fd.append('nextCate', $('#nextCate').prop('selectedIndex'));
// 	fd.append('photo1', $('#photo1')[0].files[0]);
// 	fd.append('photo2', $('#photo2')[0].files[0]);
// 	fd.append('photo3', $('#photo3')[0].files[0]);
// 	fd.append('description', $('#description').val());
//   alert(fd);
// 	$.ajax({
// 		type: 'post',
// 		/* ? */
// 		url: 'php/sell.php',
// 		data: fd,
//     processData:false,   //  告诉jquery不要处理发送的数据
//     contentType:false,  
// 		success: function (data) {
// 			var obj = JSON.parse(data);
// 			if (obj.status === 0) {
// 				alert("上传成功！");
// 			}
// 			if (obj.status === 1) {
// 				alert("上传失败！");
// 			}
// 		},
// 		error: function (xhr) {
// 			alert(JSON.stringify(xhr));
// 		}
// 	});
});