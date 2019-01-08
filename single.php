<?php
$cat = get_the_category( $post->ID );

if($cat[0]->cat_ID == 3){
	get_header();
}else{
	include $_SERVER['DOCUMENT_ROOT']."/wp-content/themes/sorokin/header_en.php";
}

?>


<section class = "content">
	<div class = "container">
		<div class = "row">
			<div class = "col-md-12">
				<h1><?=$post->post_title?></h1>
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<?php the_content()?>
				<?php endwhile; else : ?>
					<p><?php _e( 'Извените, раздел находится в стадии наполнения' ); ?></p> 
				<?php endif; ?> 				
			</div>
		</div>
	</div>
</section>


<?php 
if($cat[0]->cat_ID == 3){
	get_footer();
}else{
	include $_SERVER['DOCUMENT_ROOT']."/wp-content/themes/sorokin/footer_en.php";
}

?>