var extra = new Object();
extra.add = function( group , struct ){
    var data = new Array();
    var k = 0;
    for (var key in struct ) {
        if ( struct.hasOwnProperty( key ) ) {
            if( ( typeof struct[ key ] ).toString() != "string" ){
                for( var i = 0; i < struct[ key ].length; i++ ){
                    data[ k ] = { 'name' : extra.name( key + '#' + struct[ key ][i] ) , 'value' : extra.val( key + '#' + struct[ key ][i] ) }
                    k++;
                }
            }else{
                data[ k ] = { 'name' : extra.name( key + '#' + struct[ key ] ) , 'value' : extra.val( key + '#' + struct[ key ] ) }
                k++;
            }
        }
    }

    jQuery( document ).ready(function(){
        jQuery.post( ajaxurl , { "action" : 'extra_add' , "group" : group , "data" : data } , function( result ){ extra.clear( struct ); jQuery( '#container_' + group  ).html( result ); } );
    });
}

extra.del = function( group , index ){
    if(  confirm('You sure you want to delete this item from group ?') ){
        jQuery( document ).ready(function(){
            jQuery.post( ajaxurl , { "action" : 'extra_del' , "group" : group , "index" : index } , function( result ){ jQuery( '#container_' + group  ).html( result ); } );
        });
    }
}

extra.update = function( group , index , struct ){
    var data = new Array();
    var k = 0;
    for (var key in struct ) {
        if ( struct.hasOwnProperty( key ) ) {
            if( ( typeof struct[ key ] ).toString() != "string" ){
                for( var i = 0; i < struct[ key ].length; i++ ){
                    data[ k ] = { 'name' : extra.name( 'div#multiple_record_' + group + '_' + index + ' ' + key + '.' + struct[ key ][i] ) , 'value' : extra.val( 'div#multiple_record_' + group + '_' + index + ' ' + key + '.' + struct[ key ][i] ) }
                    k++;
                }
            }else{
                data[ k ] = { 'name' : extra.name( 'div#multiple_record_' + group + '_' + index + ' ' + key + '.' + struct[ key ] ) , 'value' : extra.val( 'div#multiple_record_' + group + '_' + index + ' ' + key + '.' + struct[ key ] ) }
                k++;
            }
        }
    }

    jQuery( document ).ready(function(){
        jQuery.post( ajaxurl , { "action" : 'extra_update' , "group" : group , "index": index , "data" : data } , function( result ){ jQuery( '#container_' + group  ).html( result ); } );
    });
}

extra.edite = function( group , index ){
    jQuery( document ).ready(function(){
        jQuery( 'div#multiple_record_' + group + '_' + index + ' .edit-action').hide();
        jQuery( 'div#multiple_record_' + group + '_' + index + ' .update-action').show();
        jQuery( 'div#multiple_record_' + group + '_' + index + ' .fvisible' ).show();
        jQuery( 'div#multiple_record_' + group + '_' + index + ' .lvisible' ).hide();
    });
}

extra.clear = function( struct ){
	jQuery( document ).ready(function(){
		for (var key in struct ) {
			if ( struct.hasOwnProperty( key ) ) {
				if( ( typeof struct[ key ] ).toString() != "string" ){
					for( var i = 0; i < struct[ key ].length; i++ ){
						jQuery( key + '#' + struct[ key ][i] ).val('');
					}
				}else{
					jQuery( key + '#' + struct[ key ] ).val('');
				}
			}
		}
	});
}
extra.name = function( selector ){
	var name = '';
	jQuery( document ).ready(function(){
		name = jQuery( selector ).attr( 'name' );
	});
	return name;
}
extra.val = function( selector ){
    var result = '';
    jQuery(document).ready(function(){
        if( jQuery( selector ).attr('type') == 'checkbox' || jQuery( selector ).attr('type') == 'radio' ){
            if( jQuery( selector ).is(':checked') ){
                result = jQuery( selector ).val();
            }else{
                result = '';
            }
        }else{
            result = jQuery( selector ).val();
        }
    });
    return result;
}