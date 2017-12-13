var product = new Array();
//product[1].goodsname = "woeifjwoef";

function displayPage(page)
{
  for (var i = (page - 1) * 5; i < page * 5; ++i)
  {
    //$("#cell" + i + " img").attr('src', product[i].goodsphoto1);
    $("#cell" + i + " h3").val(product[i].goodsname);
    $("#cell" + i + " h5:first").val(product[i].price);
    $("#cell" + i + " h5:first").next().val(product[i].newness);
    $("#cell" + i + "small").val(product[i].descriptor1 + product[i].descriptor2);
    $("#cell" + i + "p").val(product[i].description);
  }
}

$(function(){
  
  $.ajax({
		type: 'post',
		url: 'php/tag_search.php',
		data: {type: 1, descriptor1: 1},
		success: function(data) {
      alert(JSON.parse(data));
			product = JSON.parse(data).slice();
		},
		error: function(xhr) {
			alert(JSON.stringify(xhr));
		}
	});
  
  displayPage(1);
});

/*
$("#prev").click(function() {
  var cur = $("#page > .active").val();
  if (cur === 1) 
  {
    return;
  }
  else
  {
    $("#page > .active").prev().addClass('active');
    $("#page > .active").removeClass('active');
  }
});

$("#next").click(function() {
  var cur = $("#page > .active").val();
  if (cur === 5) 
  {
    return;
  }
  else
  {
    $("#page > .active").next().addClass('active');
    $("#page > .active").removeClass('active');
  }
});

$("#page > .num").click(function() {
  $("#page > .active").removeClass('active');
  this.addClass('active');
  displayPage($(this).find('a').val());
});

var type = $('.list-group');
type.find('a').click(function() {
   $.ajax({
		type: 'post',
		url: 'php/tag_search.php',
		data: {type: type.attr('tag1'), descriptor1: this.Attr('tag2')},
		success: function(data) {
			product = JSON.parse(data);
		},
		error: function(xhr) {
			alert(JSON.stringify(xhr));
		}
	});
  
  displayPage(1);
});

$('#search').click(function() {
   $.ajax({
		type: 'post',
		url: 'php/tag_search.php',
		data: {content: $('#content').val()},
		success: function(data) {
			product = JSON.parse(data);
		},
		error: function(xhr) {
			alert(JSON.stringify(xhr));
		}
	});
  
  displayPage(1);
});
*/
