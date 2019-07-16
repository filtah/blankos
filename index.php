<?php get_header(); ?>

<pre>[INDEX]</pre>

<?php setup_postdata( $post = get_queried_object_id() ); ?>
<pre>	[TITLE]</pre>
<div style="margin-left: 8em;">
	<h1><?php the_title(); ?></h1>
</div>

<pre>	[CONTENT]</pre>
<div style="margin-left: 8em;">
	<?php the_content(); ?>
</div>
<?php wp_reset_postdata(); ?>


<pre>	[LOOP]</pre>
<?php while ( have_posts() ) : the_post(); ?>

	<pre>		[TITLE]</pre>
	<div style="margin-left: 16em;">
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	</div>

	<pre>		[CONTENT]</pre>
	<div style="margin-left: 16em;">
		<?php the_content(); ?>
	</div>

	<div style="margin-left: 8em; max-width: 50em;"><hr></div>

<?php endwhile; ?>

<?php get_footer(); ?>
