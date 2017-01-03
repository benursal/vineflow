jQuery(function($){
	
	$('.btn-update-description').click(function(){
		
		alert('ff');
		/*$('.item-title').each(function(index, object){
			console.log('tae');
			
		});*/
		
		//update_description(this);
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
			},
			error: function(errorThrown){
				///alert('tae');
				console.log(errorThrown);
			}
		});	  
	}
});