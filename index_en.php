<?
/*
Template Name: Главная английская
*/ 
?>
<?php include $_SERVER['DOCUMENT_ROOT']."/wp-content/themes/sorokin/header_en.php";?>

<?
	$img_id1 = get_post_meta($post->ID, 'первое_фото_сорокина', true);
	$src1 = wp_get_attachment_image_src($img_id1, "Full");	
	$img_id2 = get_post_meta($post->ID, 'второе_фото_сорокина', true);
	$src2 = wp_get_attachment_image_src($img_id2, "Full");		
?>

<section id = "about" class = "about">
	<div class = "br__br"><br/><br/></div>
	<div class = "about__img__mobile">
		<div id = "sorokin__slider__mobile" class = "owl-carousel">
			<?php echo do_shortcode( '[slideronelist]' ); ?>							
		</div>
	</div>
	<div class = "bg_img"><img src = "<?php bloginfo("template_url")?>/images/bg.jpg" /></div>
    <div class = "container"><!--
        <div class = "row">
            <div class = "col-md-12">
                <div class = "about__social">
                    <div class = "social eng_social">
                        <a class = "lang__version" href = "/">Русская версия</a>
                    </div>
                </div>
            </div>
        </div>-->
        <div class = "about__wrap">
            <div class = "row">
                <div class = "col-md-6">
                    <div class = "about__text">
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
						<?php the_content()?>
					<?php endwhile; else : ?>
						<p><?php _e( 'Извените, раздел находится в стадии наполнения' ); ?></p> 
					<?php endif; ?> 
                    </div>
                </div>
                <div class = "col-md-6">
                    <div class = "about__img">
						<div id = "sorokin__slider" class = "owl-carousel">
							<?php echo do_shortcode( '[slideronelist]' ); ?>						
						</div>
                    </div>
                </div>
            </div>
			
			<!--
            <div class = "row">
                <div class = "col-md-6">
                    <div class = "sert__nav">
                        <a class = "sert__nav__prev" href = "javascript:void(0);"><img src = "<?php bloginfo("template_url")?>/images/prev.png" /></a>
                        <a class = "sert__nav__next" href = "javascript:void(0);"><img src = "<?php bloginfo("template_url")?>/images/next1.png" /></a>
                    </div>
                </div>
                <div class = "col-md-6">
					<?php echo do_shortcode( '[sertificatslist]' ); ?>				
                </div>
            </div>-->
        </div>
    </div>
</section>

<section id = "services" class = "services">
    <div class = "container">
        <div class = "row">
            <div class = "col-md-6">
                <div class = "services__left">
                    <p><?=get_field('заголовок_блока_услуг')?></p>
                    <div class = "services__left__foto">
                        <img src = "<?=$src2[0]?>" />
                        <p><?=get_field('имя')?></p>
                    </div>
                </div>
            </div>
            <div class = "col-md-6">
				<?php echo do_shortcode( '[servicesenlist]' ); ?>
            </div>
        </div>
    </div>
</section>

<section id = "foto" class = "foto">
    
	<?php echo do_shortcode( '[fotoenlist]' ); ?>

</section>

<section id = "video" class = "video">
    <div class = "container">
        <div class = "row">
            <div class = "col-md-12">
                <p class = "video__head">Video</p>
            </div>
        </div>
    </div>
	<?php echo do_shortcode( '[videoenlist]' ); ?>
</section>
<?php include $_SERVER['DOCUMENT_ROOT']."/wp-content/themes/sorokin/footer_en.php";?>