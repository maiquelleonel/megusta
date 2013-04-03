
/* Let IE understand tags */
document.createElement('header');
document.createElement('nav');
document.createElement('article');
document.createElement('aside');
document.createElement('footer');
document.createElement('time');

/* when j is pressed we go to the next post */

var h2top = 0;

function go_to_next_post(){

	scrollTop = jQuery(window).scrollTop();
 	jQuery('#main .post').each(function(i, h2){ /* loop through article headings */
        h2top = jQuery(h2).offset().top ; /* get article heading top */
        if ( scrollTop < h2top-50 ) { /* compare if document is below heading */
        	jQuery.scrollTo( h2top - 30, 400); /* scroll to in .8 of a second */
            return false; /* exit function */
        }
    });

    if( parseInt( h2top ) - 30 == parseInt( scrollTop ) ){
        go_to( 'next' );
    }
}

function go_to( classes ){
    jQuery(function(){
        jQuery('div.pag ul.b_pag.center.p_b li').each(function(){
            if( jQuery( 'a' , this ).hasClass( classes ) ){
                document.location.href= jQuery( 'a' , this ).attr('href');
            }
        });
    });
}

function go_to_prev_post(){
	/* when k is pressed we go to the previous post */
	/* first add the reverse plugin to reverse the headings: */

	jQuery.fn.reverse = function() {
		return this.pushStack(this.get().reverse(), arguments);
	};

	scrollTop = jQuery(window).scrollTop();



	jQuery('.post').reverse().each(function(i, h2){ /* loop through article headings */
        h2top = jQuery(h2).offset().top; /* get article heading top */
        if (scrollTop > h2top) { /* compare if document is above heading */
            jQuery.scrollTo(h2top-30, 400); /* scroll to in .8 of a second */
            return false; /* exit function */
        }
	});

    if( parseInt( h2top ) - 30 == parseInt( scrollTop ) || scrollTop == 0 ){
        go_to( 'prev' );
    }

}

/* comments */
function go_to_comments(){
    var scrollTop = jQuery(window).scrollTop();

    var comments = jQuery('#comments').offset().top;
    if (scrollTop < comments - 50 || scrollTop > comments - 50 ) { /* compare if document is above heading */
        jQuery.scrollTo( comments - 50 , 400); /* scroll to in .8 of a second */
        return false; /* exit function */
    }
    return false;
}

function open_next_post_comments(){

	scrollTop = jQuery(window).scrollTop();
	jQuery('#main .post').each(function(i, h3){ /* loop through article headings */
	  h2top = jQuery(h3).offset().top ; /* get article heading top */

	  if ( scrollTop < h2top ) { /* compare if document is below heading */
		if(jQuery(h3).find('h2 a , h1 a').hasClass('simplemodal-nsfw')){
			jQuery(h3).find('h2 a , h1 a').click();
		}else{
			var permalink = (jQuery(h3).find('h2 a , h1 a').attr('href'));
			if( jQuery('#comments').length > 0 ){
				go_to_comments();
				return false;
			}
			if(permalink){
				window.location.href = permalink + '#comments';
			}
		}	
		return false; /* exit function */
	  }
	});
}

/* preview post */
function open_next_post(){

	scrollTop = jQuery(window).scrollTop();
	jQuery('#main .post').each(function(i, h3){ /* loop through article headings */
	  h2top = jQuery(h3).offset().top ; /* get article heading top */

	  if (scrollTop<h2top) { /* compare if document is below heading */
		if(jQuery(h3).find('h2 a , h1 a').hasClass('simplemodal-nsfw')){
			jQuery(h3).find('h2 a , h1 a').click();
		}else{	
		
			var permalink = (jQuery(h3).find('h2 a , h1 a').attr('href'));
			if(permalink){
				window.location.href = permalink;
			}
		}
		return false; /* exit function */
	  }
	});
}



function like(){
	scrollTop = jQuery(window).scrollTop();
 	jQuery('#main .post').each(function(i, h2){ /* loop through article headings */

        h2top = jQuery(h2).offset().top ; /* get article heading top */

        if (scrollTop<h2top-19) { /* compare if document is below heading */

        	jQuery(this).find('div.set-like.voteaction').click();
        	return false; /* exit function */
        }
    });
}

function share(){
	scrollTop = jQuery(window).scrollTop();
 	jQuery('#main .post').each(function(i, h2){ /* loop through article headings */
        h2top = jQuery(h2).offset().top ; /* get article heading top */
        if (scrollTop<h2top-10) { /* compare if document is below heading */
        	jQuery.scrollTo(h2top-30, 800); // scroll to in .8 of a second
            return false; /* exit function */
        }
    });

}

function setCookie(c_name,value,exdays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}

jQuery(document).ready(function(){

	jQuery(document).focus();
	jQuery(document).keyup(function(e){

		if (jQuery(e.target).is("textarea, input[type=text], input[type=password]")) return true;

		/* list all CTRL + key combinations you want to disable */
        var forbiddenKeys = new Array( 'j' , 'k' , 'v' , 'c' , 'l' , 's' , 'r' );

        if(window.event)
        {
                key = window.event.keyCode;     /* IE */
                if(window.event.ctrlKey)
                        isCtrl = true;
                else
                        isCtrl = false;
        }else{
                key = e.which;     /* firefox & others */
                if(e.ctrlKey)
                        isCtrl = true;
                else
                        isCtrl = false;
        }

        /* if ctrl is pressed check if other key is in forbidenKeys array */
        if(isCtrl)
        {
                for(i=0; i<forbiddenkeys.length; i++)
                {
                        /* case-insensitive comparation */
                        if(forbiddenKeys[i].toLowerCase() == String.fromCharCode(key).toLowerCase())
                        {
                                return false;
                        }
                }
        }

		if (e.keyCode) {
			key = e.keyCode; /* for all Browser */
		}else if(e.which) {
			key = e.which;   /* for ie */
		}

		switch(key)
		{
		case 74: /* J */
            if( jQuery('.hotkeys-meta.sticky-bar span.nav-next a').length > 0 ){
                document.location.href=jQuery('.hotkeys-meta.sticky-bar span.nav-next a').attr('href');
            }else{
                go_to_next_post();
            }
			jQuery(document).focus();
            break;
		case 75: /* K */
            if( jQuery('.hotkeys-meta.sticky-bar span.nav-previous a').length > 0 ){
                document.location.href=jQuery('.hotkeys-meta.sticky-bar span.nav-previous a').attr('href');
            }else{
                go_to_prev_post();
            }
			jQuery(document).focus();
            break;
		case 86: /* V */
			open_next_post();
			jQuery(document).focus();
            break;
		case 67: /* C */
			open_next_post_comments()
			jQuery(document).focus();
            break;
		case 76: /* L for like */
			like();
			jQuery(document).focus();
            break;
		case 83: /* S for Share */
			share();
			jQuery(document).focus();
            break;
		case 82: /* R for Random */
			act.go_random();
			jQuery(document).focus();
            break;
		case 39: /* right arrow */
			/*add here code for next page*/
			if(jQuery('.hotkeys-meta.sticky-bar span.nav-next a').attr('href')){
				document.location.href=jQuery('.hotkeys-meta.sticky-bar span.nav-next a').attr('href');
			}	
			jQuery(document).focus();
            break;
		case 37: /* left arrow */
			/*add here code for prev page*/ 
			if(jQuery('.hotkeys-meta.sticky-bar span.nav-previous a').attr('href')){
				document.location.href=jQuery('.hotkeys-meta.sticky-bar span.nav-previous a').attr('href');
			}	
			jQuery(document).focus();
            break;
        case 27 : {
            if( jQuery('#keyboard-container').length > 0 ){
                keyboard.hide();
            }
            jQuery(document).focus();
            break;
        }
		default:

		}
	});

	jQuery('#keywords').unbind('keyup');

    jQuery(function(){
        jQuery( 'nav.hotkeys-meta.nav a' ).click(function(){
            if( jQuery( this ).attr('href') == '#next' ){
                go_to_next_post();
                jQuery(document).focus();
            }else{
                go_to_prev_post();
                jQuery(document).focus();
            }
        });
    });

	/* toogle */
	/* Case when by default the toggle is closed */
	jQuery(".open_title").toggle(function(){
			jQuery(this).next('div').slideDown();
			jQuery(this).find('a').removeClass('show');
			jQuery(this).find('a').addClass('hide');
			jQuery(this).find('.title_closed').hide();
			jQuery(this).find('.title_open').show();
		}, function () {

			jQuery(this).next('div').slideUp();
			jQuery(this).find('a').removeClass('hide');
			jQuery(this).find('a').addClass('show');
			jQuery(this).find('.title_open').hide();
			jQuery(this).find('.title_closed').show();

	});

	/* Case when by default the toggle is oppened */
	jQuery(".close_title").toggle(function(){
			jQuery(this).next('div').slideUp();
			jQuery(this).find('a').removeClass('hide');
			jQuery(this).find('a').addClass('show');
			jQuery(this).find('.title_open').hide();
			jQuery(this).find('.title_closed').show();
		}, function () {
			jQuery(this).next('div').slideDown();
			jQuery(this).find('a').removeClass('show');
			jQuery(this).find('a').addClass('hide');
			jQuery(this).find('.title_closed').hide();
			jQuery(this).find('.title_open').show();
	});

	/* Accordion */
	jQuery('.cosmo-acc-container').hide();
	jQuery('.cosmo-acc-trigger:first').addClass('active').next().show();
	jQuery('.cosmo-acc-trigger').click(function(){
		if( jQuery(this).next().is(':hidden') ) {
			jQuery('.cosmo-acc-trigger').removeClass('active').next().slideUp();
			jQuery(this).toggleClass('active').next().slideDown();
		}
		return false;
	});

	//Superfish menu
	jQuery("ul.sf-menu").supersubs({
			minWidth:    12,
			maxWidth:    32,
			extraWidth:  1
		}).superfish({
			delay: 200,
			speed: 250
		});

	/* Hide Tooltip */
	jQuery(function() {
		jQuery('a.close').click(function() {
			jQuery(jQuery(this).attr('href')).slideUp();
            jQuery.cookie("tooltip" , 'closed' , {expires: 365, path: '/'});
            jQuery('.header-delimiter').removeClass('hidden');
			return false;
		});
	});

	/* Mosaic fade */
	jQuery('.readmore').mosaic();
	jQuery('.circle, .gallery-icon').mosaic({
		opacity:	0.5
	});
	jQuery('.fade').mosaic({
		animation:	'slide'
	});


	/* twitter widget */
	if (jQuery().slides) {
		jQuery(".dynamic .cosmo_twitter").slides({
			play: 5000,
			effect: 'fade',
			generatePagination: false,
			autoHeight: true
		});
	}


	/* show/hide color switcher */
	jQuery('.show_colors').toggle(function(){
		jQuery(".style_switcher").animate({
		    left: "10px"

		  }, 500 );
	}, function () {
		jQuery(".style_switcher").animate({
		    left: "-152px"

		  }, 500 );

	});

    /* widget tabber */
    jQuery( 'ul.widget_tabber li a' ).click(function(){
        jQuery(this).parent('li').parent('ul').find('li').removeClass('active');
        jQuery(this).parent('li').parent('ul').parent('div').find( 'div.tab_menu_content.tabs-container').fadeTo( 200 , 0 );
        jQuery(this).parent('li').parent('ul').parent('div').find( 'div.tab_menu_content.tabs-container').hide();
        jQuery( jQuery( this ).attr('href') + '_panel' ).fadeTo( 600 , 1 );
        jQuery( this ).parent('li').addClass('active');
    });

    //Comment form
	jQuery("#toggle_link").toggle(
		function() {
			jQuery("#commentform").show("fast");
			jQuery("#toggle_link");
		},
		function() {
			jQuery("#commentform").hide("fast");
			jQuery("#toggle_link");
		}
	);

    jQuery('.comment-reply-link').click(function() {
		jQuery("#commentform").show("fast");
	});


	 /*initialize tabs*/
	jQuery(function() { 
		jQuery('.cosmo-tabs').tabs({fxFade: true, fxSpeed: 'fast'}); 
		jQuery('.tabs-nav li:first-child a').click();
	});

	/*toogle*/
	/*Case when by default the toggle is closed */
	jQuery(".open_title").toggle(function(){ 
			jQuery(this).next('div').slideDown();
			jQuery(this).find('a').removeClass('show');
			jQuery(this).find('a').addClass('hide');
			jQuery(this).find('.title_closed').hide();
			jQuery(this).find('.title_open').show();
		}, function () {
		
			jQuery(this).next('div').slideUp();
			jQuery(this).find('a').removeClass('hide');
			jQuery(this).find('a').addClass('show');		 
			jQuery(this).find('.title_open').hide();
			jQuery(this).find('.title_closed').show();
			
	});

	/*Case when by default the toggle is oppened */		
	jQuery(".close_title").toggle(function(){ 
			jQuery(this).next('div').slideUp();
			jQuery(this).find('a').removeClass('hide');
			jQuery(this).find('a').addClass('show');		 
			jQuery(this).find('.title_open').hide();
			jQuery(this).find('.title_closed').show();
		}, function () {
			jQuery(this).next('div').slideDown();
			jQuery(this).find('a').removeClass('show');
			jQuery(this).find('a').addClass('hide');
			jQuery(this).find('.title_closed').hide();
			jQuery(this).find('.title_open').show();
			
	});	
	
	/*Accordion*/
	jQuery('.cosmo-acc-container').hide();
	jQuery('.cosmo-acc-trigger:first').addClass('active').next().show();
	jQuery('.cosmo-acc-trigger').click(function(){
		if( jQuery(this).next().is(':hidden') ) {
			jQuery('.cosmo-acc-trigger').removeClass('active').next().slideUp();
			jQuery(this).toggleClass('active').next().slideDown();
		}
		return false;
	}); 
	
	//Scroll to top
	jQuery(window).scroll(function() {
		if(jQuery(this).scrollTop() != 0) {
			jQuery('#toTop').fadeIn();	
		} else {
			jQuery('#toTop').fadeOut();
		}
	});
	jQuery('#toTop').click(function() {
		jQuery('body,html').animate({scrollTop:0},300);
	});
});


/* grid / list switch */
jQuery(document).ready(function(){
    jQuery('span.list-grid  a.switch').click(function(){
        var self = this;
        jQuery('div.loop-container-view').html( '<img src="'+ themeurl +'/images/loading.gif"  style="background:none; text-align:center; float:none; width:auto; height:auto; margin:0px auto !important; clear:both; display:block;" />' );
        if( jQuery( this ).hasClass('swap') ){
            /* toogle grid -> list */
            jQuery.post( ajaxurl , { 
                    'action' : 'switch' , 
                    'template' : jQuery( self ).attr('rel') , 
                    'grid' : 0 , 
                    'query' : jQuery( '#query-' + jQuery( self ).attr('rel') ).val() 
                } , 
                function( result ){ 
                    jQuery('div.loop-container-view.grid').html( result );
                    if( typeof jQuery( self ).attr('index') !== "undefined" && jQuery( self ).attr('index') !== false ){
                        if( !( jQuery('div.clearfix.get-more p a').length ) ){
                            jQuery('div#content div.author-container').append('<div class="clearfix get-more"><p class="button"><a id="get-more" index="' + jQuery( self ).attr('index') + '" href="javascript:act.author( jQuery(\'#get-more\').attr(\'ptype\') , jQuery(\'#get-more\').attr(\'author\') , jQuery(\'#get-more\').attr(\'index\') , [] , 0 );">get more</a></p></div>');
                            jQuery('a#get-more').attr('ptype' , jQuery( self ).attr( 'ptype' ) );
                            jQuery('a#get-more').attr('author' , jQuery( self ).attr( 'author' ) );
                        }
                    }
                    jQuery( self ).removeClass('swap');
                    jQuery('div.loop-container-view.grid').addClass('list');
                    jQuery('div.loop-container-view.grid').removeClass('grid');
                    jQuery('div.grid-view').addClass('list-view');
                    jQuery('div.grid-view').removeClass('grid-view');
                    jQuery.cookie( "grid_" + jQuery( self ).attr('rel') , '' , {expires: 365, path: '/'});
                    jQuery('.readmore').mosaic();
                    
                    act.loadScripts();
                    
                }
            );
        }else{
            /* toogle list -> grid */
            jQuery.post( ajaxurl , { 
                    'action' : 'switch' , 
                    'template' : jQuery( self ).attr('rel') , 
                    'grid' : 1 , 
                    'query' : jQuery( '#query-' + jQuery( self ).attr('rel') ).val() 
                } , 
                function( result ){
                    jQuery('div.loop-container-view.list').html( result );
                    if( typeof jQuery( self ).attr('index') !== "undefined" && jQuery( self ).attr('index') !== false ){
                        if( !( jQuery('div.clearfix.get-more p a').length ) ){
                            jQuery('div#content div.author-container').append('<div class="clearfix get-more"><p class="button"><a id="get-more" index="' + jQuery( self ).attr('index') + '" href="javascript:act.author( jQuery(\'#get-more\').attr(\'ptype\') , jQuery(\'#get-more\').attr(\'author\') , jQuery(\'#get-more\').attr(\'index\') , [] , 0 );">get more</a></p></div>');
                            jQuery('a#get-more').attr('ptype' , jQuery( self ).attr( 'ptype' ) );
                            jQuery('a#get-more').attr('author' , jQuery( self ).attr( 'author' ) );
                        }
                    }
                    jQuery( self ).addClass('swap');
                    jQuery('div.loop-container-view.list').addClass('grid');
                    jQuery('div.loop-container-view.list').removeClass('list');
                    jQuery('div.list-view').addClass('grid-view');
                    jQuery('div.list-view').removeClass('list-view');
                    jQuery.cookie( "grid_" + jQuery( self ).attr('rel') , 'grid' , {expires: 365, path: '/'});
                    jQuery('.readmore').mosaic();
                }
            );
        }
    });
});

/*functions for style switcher*/

function changeBgColor(rd_id,element){

    if(element == "footer"){
		jQuery('.b_head').css('background-color', '#'+jQuery('#'+rd_id).val());
		jQuery('.b_body_f').css('background-color', '#'+jQuery('#'+rd_id).val());

		jQuery('#link-color').val('#'+jQuery('#'+rd_id).val());
		jQuery.cookie("b_f_color",'#' + jQuery('#'+rd_id).val(), {expires: 365, path: '/'});
    }
    else if(element == "content"){
    	jQuery('#main').css('background-color', '#'+jQuery('#'+rd_id).val());
    	jQuery('#content-link-color').val('#'+jQuery('#'+rd_id).val());
    	jQuery.cookie("content_bg_color",'#' + jQuery('#'+rd_id).val(), {expires: 365, path: '/'});
    }


    return false;
}

function setPickedColor(a,element){
	if(element == 'footer'){

		jQuery('.b_f_c').css('background-color', a);
		jQuery.cookie("megusta_footer_bg_color",a, {expires: 365, path: '/'}); /*de_css*/
	}
	else if(element == "content"){
		jQuery('body').css('background-color', a);
		jQuery.cookie("megusta_content_bg_color",a, {expires: 365, path: '/'});
	}

}

function setBgColor(rb_id,element){
	jQuery('#' + rb_id).trigger('click');
	changeBgColor(rb_id,element);
}

function setBgImage(rb_id){
	jQuery('#' + rb_id).trigger('click');
	changeBgImage(rb_id);
}

function doBoxed(obj){
	if(jQuery(obj).attr('checked') ){
		jQuery('body').addClass('larger');
		jQuery.cookie("megusta_boxed",'yes', {expires: 365, path: '/'});
		//setBoxedCookies('yes');
	}
	else{
		jQuery('body').removeClass('larger');
		jQuery.cookie("megusta_boxed",'no', {expires: 365, path: '/'});
		//setBoxedCookies('no');
	}


}

jQuery(document).ready(function(){
    if( jQuery( '#keyboard-container').length > 0 ){
        if( jQuery.cookie('demo_keyboard') != 'yes' ){
            setTimeout("keyboard.show()" , 3000 );
            jQuery.cookie("demo_keyboard",'yes', {expires: 365, path: '/'});
        }
    }
});

var keyboard = new Object();
keyboard.show = function( ){
    jQuery( document ).ready(function(){
        jQuery( '#lightbox-shadow' ).show();
        jQuery( '#keyboard-container #img' ).show();
        jQuery( '#keyboard-container img' ).show();
        jQuery( '#keyboard-container #img').css( {'left' : parseInt( jQuery(document).width() - 35 ) + 'px'});
        jQuery( '#keyboard-container #img').animate({'width' : '748px' , 'top' : '100px' , 'left' : parseInt( ( jQuery(document).width() - 748) / 2 ) + 'px' , 'zIndex' : 9999} , 200 );
        jQuery( '#keyboard-container img').animate({'width' : '748px'} , 200 );
        jQuery( '#keyboard-container #img p' ).css( {'width' : '748px'});
        jQuery( '#keyboard-container #img p' ).show( 'slow' );
    });
}
keyboard.hide = function( ){
    jQuery( document ).ready(function(){
        jQuery( '#keyboard-container #img p' ).hide();
        jQuery( '#keyboard-container img').animate({'width' : '50px'} , 500 );
        jQuery( '#keyboard-container #img').animate({'width' : '50px' , 'top' : '45px' , 'left' :  parseInt( jQuery(document).width() - 35 ) + 'px'} , 500 );
        jQuery( '#keyboard-container img' ).hide( 'slow' );
        jQuery( '#keyboard-container #img' ).hide( 'slow' );
        jQuery( '#lightbox-shadow' ).hide( );
    });
}
/*EOF functions for style switcher*/