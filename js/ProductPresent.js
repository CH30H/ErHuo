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

    // // a test sentence
    // alert("Get gid = " + gid);

    // // a test sentence
    // document.getElementById("photo1").src = "resource/sample3.jpg";

    $.ajax({
        type: 'post',
        url:  'php/goodinfo.php',
        data: {'gid': gid},
        success: function (data) {
            // alert(data);
            var info = JSON.parse(data);
            if (info.status == 0) {
                document.getElementById('goodsphoto1').src = info.goodsphoto1;
                document.getElementById('goodsphoto2').src = info.goodsphoto2;
                document.getElementById('goodsphoto3').src = info.goodsphoto3;
                document.getElementById('goodsname').innerHTML = info.goodsname;
                document.getElementById('price').innerHTML = "出价： ￥" + info.price;
                document.getElementById('newness').innerHTML = "新旧程度： " + info.newness;
            }
            else if (info.status == 1) {
                alert("该商品已下架！点击返回商品浏览页面");
                self.location = "Shop.html";
            }
            else {
                alert("something is wrong...");
            }
        },
        error: function (xhr) {
            alert(JSON.stringify(xhr));
        }
    });
});

$('#buy').click(function () {
    var Request = new Object();
    Request = GetRequest();
    var gid;
    gid = Request['gid'];
    $.ajax({
        type: 'post',
        url: 'php/buy.php',
        data: {"gid" : gid},
        success: function (data) {
            alert(data);
            var obj = JSON.parse(data);
            if(obj.status == 0) {
                alert("您的购买请求已经发送给卖家，请等候卖家邮件回复！");
            }
            else if(obj.status == 1) {
                alert("该商品已下架，请挑选其他商品！");
            }
            else if(obj.status == 2) {
                alert("登录超时，请重新登录！");
            }
            // if(obj.status == 3) {
            else {    
                alert("系统异常...");
            }
        },
        error: function (xhr) {
            alert(JSON.stringify(xhr));
        }
    });
});