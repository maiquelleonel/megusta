<form action="<?php echo home_url(); ?>/" method="get" id="searchform">
    <fieldset>
        <div id="searchbox">
            <input class="input" name="s" type="text" id="keywords" value="<?php _e('to search, type and hit enter','cosmotheme') ?>" onfocus="if (this.value == '<?php _e('to search, type and hit enter','cosmotheme') ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('to search, type and hit enter','cosmotheme') ?>';}">
		</div>
		<p class="button blue">
            <input type="submit" name="search" value="<?php _e('Search','cosmotheme') ?> ">
		</p>
	</fieldset>
</form>