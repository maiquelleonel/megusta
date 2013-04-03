    /* Fixed article header */
	jQuery(function () {
		var msie6 = jQuery.browser == 'msie' && jQuery.browser.version < 7;
		if (!msie6 && jQuery('.voteaction').length != 20) {
            var top = jQuery('#voteaction').offset().top - parseFloat(jQuery('#voteaction').css('margin-top').replace(/auto/gi, 0));
            jQuery(window).scroll(function (event) {
                // what the y position of the scroll is
                var y = jQuery(this).scrollTop();
                // whether that's below the form
                if (y >= top-20) {
                    // if so, ad the fixed class
                    jQuery('#voteaction').addClass('fixed');
                } else {
                    // otherwise remove it
                    jQuery('#voteaction').removeClass('fixed');
                }
            });
		}
	});

	/* Fixed article header */
	jQuery(function () {
		var msie6 = jQuery.browser == 'msie' && jQuery.browser.version < 7;
		if(jQuery('#sticky-bar').length != 0){
			if (!msie6 && jQuery('.sticky-bar').length != 60) {
				var top = jQuery('#sticky-bar').offset().top - parseFloat(jQuery('#sticky-bar').css('margin-top').replace(/auto/gi, 0));
				jQuery(window).scroll(function (event) {
					// what the y position of the scroll is
					var y = jQuery(this).scrollTop();
					// whether that's below the form
					if (y >= top-60) {
						// if so, ad the fixed class
						jQuery('#sticky-bar').addClass('fixed');
					} else {
						// otherwise remove it
						jQuery('#sticky-bar').removeClass('fixed');
					}
				});
			}
		}
	});