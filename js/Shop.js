var product;

function openNew(event)
{
  location.href = 'ProductPresent.html' + '?gid=' + event.data.gid;
}

function displayPage(page)
{
  for (var i = 1; i <= 5; ++i)
  {
    $("#cell" + ((i - 1) % 5 + 1)).css('display', 'none');
  }
  
  for (i = (page - 1) * 5 + 1; i <= Math.min(page * 5, product.length - 1); ++i)
  {
    $("#cell" + ((i - 1) % 5 + 1)).css('display', '');
    $("#cell" + ((i - 1) % 5 + 1) + " img").attr('src', "photos/" + product[i].goodsphoto1);
    $("#cell" + ((i - 1) % 5 + 1) + " h3").text(product[i].goodsname);
    $("#cell" + ((i - 1) % 5 + 1) + " h5:first").text("price: " + product[i].price);
    $("#cell" + ((i - 1) % 5 + 1) + " h5:first").next().text("newness: " + product[i].newness);
    $("#cell" + ((i - 1) % 5 + 1) + " small").text(product[i].descriptor1 + '-' + product[i].descriptor2);
    $("#cell" + ((i - 1) % 5 + 1) + " p").text(product[i].description);
    $("#cell" + ((i - 1) % 5 + 1)).click({gid: product[i].gid}, openNew);
  }
}

$(function(){
  $.ajax({
		type: 'post',
		url: 'php/tag_search2.php',
		data: {type: 0, descriptor1: 0},
		datatype: 'json',
		success: function(data) {
			product = JSON.parse(data).slice();
      displayPage(1);
		},
		error: function(xhr) {
			alert(JSON.stringify(xhr));
		}
	});
});

$("#prev").click(function() {
  var cur = $("#page .active").text();
  if (cur === 1) 
  {
    return;
  }
  else
  {
    var tar = $("#page .active").prev();
    $("#page .active").removeClass('active');
    tar.addClass('active');
    displayPage(parseInt(cur) - 1);
  }
});

$("#next").click(function() {
  var cur = $("#page .active").text();
  if (cur === 5) 
  {
    return;
  }
  else
  {
    var tar = $("#page .active").next();
    $("#page .active").removeClass('active');
    tar.addClass('active');
    displayPage(parseInt(cur) + 1);
  }
});

$("#page .num").click(function() {
  $("#page  .active").removeClass('active');
  $(this).addClass('active');
  displayPage(parseInt($(this).find('a').text()));
});

var tag1Name = ['', 'Book', 'Electronic Product', 'Ticket', 'Commodity', 'Other'];

$('.list-group a').click(function() {
   $.ajax({
		type: 'post',
		url: 'php/tag_search2.php',
		data: {type: tag1Name[$(this).attr('tag1')], descriptor1: $(this).text()},
		success: function(data) {
			product = JSON.parse(data).slice();
      displayPage(1);
		},
		error: function(xhr) {
			alert(JSON.stringify(xhr));
		}
	});
});

$('#search').click(function() {
   $.ajax({
		type: 'post',
		url: 'php/context_search2.php',
		data: {context: $('#content').val()},
		success: function(data) {
			product = JSON.parse(data).slice();
      displayPage(1);
		},
		error: function(xhr) {
			alert(JSON.stringify(xhr));
		}
	});
});

$('#sortPrice').click(function() {
  product.sort(function(x, y) {
    return x.price - y.price;
  });
  displayPage(1);
});

$('#sortNewness').click(function() {
  product.sort(function(x, y) {
    return y.newness - x.newness;
  });
  displayPage(1);
});

