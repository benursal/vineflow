jQuery(function($){
	
	$('body').prepend('<div class="loader-container"><div class="loader"></div></div>');
	
	$('.ajax_add_to_cart').click(function(){
		
		var product_entry = $(this).parent().parent().parent();
		product_entry.addClass('added-item');
		
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
});