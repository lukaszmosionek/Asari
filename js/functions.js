function send_ajax(post){
		var path = '../wp-content/plugins/asari';
	
		$( ".ajax_loader" ).after().html('<img src="'+path+'/images/ajax-loader.gif">');
		$.post( path+'/ajax.php',{'post':post}, function( data ) {	
			  $( ".result" ).html( data );
			  $( ".ajax_loader" ).html('');
		});
}

function send_ajax_inteval(Mthis,restart=0){
		var path = '../wp-content/plugins/asari';
						
	if( restart ){
			$.post( path+'/ajax.php',{'post':'reset_is_updated'});
	}					
		
	function is_all_updated() {
			$.ajaxSetup({async:false});  //execute synchronously

			var returnData = null;  //define returnData outside the scope of the callback function

			var path = '../wp-content/plugins/asari';
			$.post( path+'/ajax.php',{'post':'is_all_updated'}, function( data ) {	
				returnData= data;
			});

			$.ajaxSetup({async:true});  //return to default setting

			return returnData;

	}
		
		
	var GoPosts = function (){	
		//$.ajaxSetup({async:false});
		var path = '../wp-content/plugins/asari';
		
		$( ".ajax_loader" ).html('<img src="'+path+'/images/ajax-loader.gif">');	
		Mthis.attr("disabled", true);	
		
		$.post( path+'/ajax.php',{'post':'show_counter'}, Callback);
	}
	
	var Callback = function( data ) {	
					  $( ".result" ).html( data );
					  
					  $( ".ajax_loader" ).html('');
					  Mthis.attr("disabled", false);	
						
					  if( is_all_updated()>0 )	{
						GoPosts();
					  }
	}
	GoPosts();
		
		
		
					 
				  
		
		
/* 		$( ".result" ).html('<img src="'+path+'/images/ajax-loader.gif">');
		while(){
			
		} */
}