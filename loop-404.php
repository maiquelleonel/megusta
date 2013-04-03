<!-- if not found post or is 404 error -->
<article <?php post_class() ?>>
    <header class="entry-header">
        <!-- error title -->
        <h2 class="entry-title">
            <?php
                if( is_404() ){
                    _e( 'Error 404, page, post or resource can not be found' , 'cosmotheme' );
                }else{
                    if(is_search () ){
                        _e( 'Sorry, no results found.' , 'cosmotheme' );
                    }else{
                        _e( 'Sorry, no posts found' , 'cosmotheme' );
                    }
                }
            ?>
        </h2>
    </header>
    <!-- content -->
    <footer class="entry-footer">
        <div class="excerpt">
            <?php
                if( is_search () ){
                    _e( 'Unfortunately we did not find any results for your request.' , 'cosmotheme' );
                }else{
                    _e( 'We apologize but this page, post or resource does not exist or can not be found. Perhaps it is necessary to change the call method to this page, post or resource.' , 'cosmotheme' );
                }

                wp_link_pages();
            ?>
        </div>
    </footer>
</article>

