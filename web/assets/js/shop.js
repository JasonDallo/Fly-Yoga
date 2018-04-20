$(document).ready(function(){
	$('.cards').click(function(){
		$.ajax({
			url: "/shop/cards",
			method: "GET",
			success: function(data){
				$('.content-wrapper').empty();
				getUserConnected(data);
				
			}
		});
	});
	function getUserConnected(data){
		$.ajax({
			url: "/shop/cards/connected",
			method: "GET",
			success: function(user){
				FillShop(data, user);
			}
		});
	}

	function FillShop(data, user){
		$.each(data, function(index, value){
			$('.content-wrapper').append('<div class="helix-product-box" style="max-height: 321px;"><div class="img-wrapper"><a href="?id='+ value.id +'"><img src="../../img/shop/'+ value.id + '.jpg" alt="" class="img-responsive" style="width:281px;height:187px;"></a></div><div class="info-block item'+ value.id +'"><a href="?id='+ value.id +'"><h3>'+ value.name.substring(0, 23) +'</h3></a> <h5>'+ value.desc.substring(0,29) +'</h5><h4>'+ value.prix +'€</h4></div></div>');
			if (user[0] == 'true') {
				$('.item'+value.id).append('<form method="post" action="/shop/buy"><button type="submit" class="btn btn-primary">Ajouter au panier</button><input type="hidden" name="id" value="'+value.id+'"></form>')
			}
		});
		$('.cards').addClass('active');
		$('.shirt').removeClass('active');
	}
})