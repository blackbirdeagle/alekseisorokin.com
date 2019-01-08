<section id = "articles" class = "articles">
    <div class = "container">
        <div class = "row">
            <div class = "col-md-6">
                <div class = "art__left">
                    <p>Статьи<br>и публикации</p>
                    <div class="art__left__foto">
                        <img src="<?php bloginfo("template_url")?>/images/articles.jpg">
                    </div>
                </div>
            </div>
            <div class = "col-md-6">
				<?php echo do_shortcode( '[articlesrulist]' ); ?>                
            </div>
        </div>
    </div>
</section>

<?
	$arr = get_block();
?>

<footer id = "contacts" class = "footer">
    <div class = "container">
        <div class = "row">
            <div class = "col-md-6">
                <p class = "contacts__name"><?=$arr['h_ru']?></p>
				<a class = "spoiler" href = "javascript:void(0);">Подробнее</a>
				<p class = "contacts__constext"><?=$arr['cons_ru']?></p>
                <!--<a class = "contacts__email" href = "mailto:<?=$arr['email']?>"><?=$arr['email']?></a>-->
				<div class = "clear"></div>
                <div class = "footer__social">
					<?php echo do_shortcode( '[sociallist]' ); ?>
				</div>
            </div>
            <div class = "col-md-6">
                <div class = "contacts__form">
					<?php echo do_shortcode( '[contact-form-7 id="8" title="Заявка на обратный звонок"]' ); ?>
                </div>
                
            </div>
        </div>
        <div class = "row">
            <div class = "col-md-6 col-sm-6 col-xs-6 col-foot">
                <p class = "copyright"><?=$arr['copy_ru']?></p>
            </div>
            <div class = "col-md-6 col-sm-6 col-xs-6 col-foot">
                <a class = "develop" href = "http://www.colortime.ru/" target = "_blank">Разработка сайта Colortime</a>
            </div>
        </div>
    </div>
</footer>

<script src="<?php bloginfo("template_url")?>/js/jquery.min.js"></script>
<script src="<?php bloginfo("template_url")?>/js/bootstrap.min.js"></script>
<script src="<?php bloginfo("template_url")?>/plugins/owl-carousel/owl.carousel.js"></script>
<script src="<?php bloginfo("template_url")?>/plugins/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<script src="<?php bloginfo("template_url")?>/plugins/fancybox/source/jquery.fancybox.js?v=2.1.5"></script>
<script src="<?php bloginfo("template_url")?>/plugins/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script src="<?php bloginfo("template_url")?>/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script src="<?php bloginfo("template_url")?>/plugins/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<script type="text/javascript" src="<?php bloginfo("template_url")?>/js/maskedinput.js"></script>
<script src="<?php bloginfo("template_url")?>/js/script.js"></script>

<?php wp_footer()?>
</body>
</html>