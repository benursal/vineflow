jQuery(function($){
	
	//alert( app.max_items_in_cart );
	
	$('body').prepend('<div class="loader-container"><div class="loader"></div></div>');
	
	update_mini_cart();
	
	$('.ajax_add_to_cart').click(function(){
		
		var product_entry = $(this).parent().parent().parent();
		product_entry.addClass('added-item');
		
		app.item_count++;
		
		update_mini_cart();
		
	});
	
	$('.remove-from-cart').click(function(){
		var product_id = $(this).attr('data-product_id');
		var obj = $(this);
		
		show_loader();
		
		// call ajax
		$.ajax({
			url: app.ajax_url,
			type: 'post',
			data: {
				'action':'remove_item_from_cart', 
				'product_id' : product_id
			},
			success:function(data) {
				//alert('success ' + data);
				// This outputs the result of the ajax request
				console.log(data);
				
				if( data == 1 )
				{
					obj.parent().parent().parent().removeClass('added-item');
				}
				
				
				hide_loader();
				
				app.item_count--;
				update_mini_cart();
				
				
			},
			error: function(errorThrown){
				///alert('tae');
				console.log(errorThrown);
				
				hide_loader();
			}
		});	  
		
		
	});
	
	$('#my-cart').submit(function(e){
		update_description();
		return false;
	});
	
	//alert(app.ajax_url);
	//alert(  $('#form-cart').serialize() );

	function update_description()
	{	
		
		var input_data = [];
		
		$('.item-title').each(function(index, obj){
			
			item = {};
			
			item['key'] = $(obj).attr('name');
			item['value'] = $(obj).val();
			
			input_data.push(item);
		});
		
		
		console.log( input_data );

		show_loader();
		// call ajax
		$.ajax({
			url: app.ajax_url,
			type: 'post',
			dataType: 'jsonp',
			data: {
				'action':'update_item_description', 
				'cart_items' : jQuery.parseJSON(JSON.stringify(input_data))
			},
			success:function(data) {
				//alert('success ' + data);
				// This outputs the result of the ajax request
				console.log(data);
				hide_loader();
			},
			error: function(errorThrown){
				///alert('tae');
				console.log(errorThrown);
				hide_loader();
			}
		});	  
	}
	
	function show_loader()
	{
		$('.loader-container').css('visibility', 'visible');
	}
	
	function hide_loader()
	{
		$('.loader-container').css('visibility', 'hidden');
	}
	
	function update_mini_cart()
	{
		//var app.item_count = $('.added-item').length;
		var whats_left = app.max_items_in_cart - app.item_count;
		
		// update cart count
		$('#cart_item_count').text(app.item_count);
		$('#cart_num_items_needed').text(whats_left);
		
		if( whats_left <= 0 )
		{
			//$('.added-item .add_to_cart_button').hide();
			$('.add_to_cart_button').hide();
			//$('.remove-from-cart').hide();
		}
		else
		{
			$('.add_to_cart_button').show();
			$('.added-item .add_to_cart_button').hide();
			//$('.added-item .remove-from-cart').show();
		}
	}
});