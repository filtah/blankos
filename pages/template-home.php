<?php /* template name: Home */ ?>
<?php get_header(); ?>

<pre>[PAGE:HOME]</pre>

<pre>	[TITLE]</pre>
<div style="margin-left: 8em;">
	<h1><?php the_title(); ?></h1>
</div>

<?php while ( have_posts() ) : the_post(); ?>

	<pre>	[CONTENT]</pre>
	<div style="margin-left: 8em;">
		<?php the_content(); ?>
	</div>

<?php endwhile; ?>

<?php get_footer(); ?>
