	</main>

	<footer class="site-footer" id="footer">
		<h2 class="screen-reader-text">Footer</h2>
		
<?php
    $the_query = new WP_Query( [
        'post_type'        => 'fct-section',
        'name'        => 'footer'
    ]);

    if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
?>		
		<div class="entry-content">
            <?php //the_content() // adds <p></p> after every </li>, so, an ugly crutch ?>
            <?php echo str_replace( ['<p>', '</p>'], '', apply_filters( 'the_content', get_the_content() ) ) ?>
		</div>
<?php
		}
        wp_reset_postdata();
	}
?>

	</footer>

<?php wp_footer(); ?>

</body>
</html>
