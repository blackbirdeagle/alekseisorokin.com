<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php bloginfo("template_url")?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url');?>">

    <link rel="stylesheet" href="<?php bloginfo("template_url")?>/plugins/owl-carousel/assets/owl.carousel.css">

    <link rel="stylesheet" href="<?php bloginfo("template_url")?>/plugins/owl-carousel/assets/owl.theme.default.css">

    <link rel="stylesheet" type="text/css" href="<?php bloginfo("template_url")?>/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />

    <link rel="stylesheet" type="text/css" href="<?php bloginfo("template_url")?>/plugins/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />

    <link rel="stylesheet" type="text/css" href="<?php bloginfo("template_url")?>/plugins/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />


	<title><?php kama_meta_title(" - "); ?></title>
	
	<!----description---->
    <?php kama_meta_description() ; ?>
	<!----keywords---->
    <?php kama_meta_keywords();?>
	
	<?php wp_head()?>
</head>
<body>

<div class = "float__menu">
    <a class = "hide__menu" href = "javascript:void(0);">X</a>
	<?php wp_nav_menu(
		array(
			'theme_location' => 'top__menu__en', 
			'walker'=> new True_Walker_Nav_Menu_Top(), 
			'menu_id' => '',
			'menu_class' => ''	
			)
		); 
	?>
</div>

<header class = "header">
    <div class = "container">
        <div class = "row">
            <div class = "col-md-12">
				<table style = "width: 100%;">
					<tr>
					<td>
                <div class = "header__wrap">
                    <a href = "javascript:void(0);" class = "show__menu"><img src = "<?php bloginfo("template_url")?>/images/menu.png" /></a>
					<?php wp_nav_menu(
						array(
							'theme_location' => 'top__menu__en', 
							'walker'=> new True_Walker_Nav_Menu_Top(), 
							'menu_id' => '',
							'menu_class' => 'top__menu'	
							)
						); 
					?>
                </div></td>
				<td>
				
					<a class = "lang__version" href = "/">Русская версия</a>
				
				</td>
				</tr>
				</table>	
				<div class = "clear"></div>
            </div>
        </div>
    </div>
</header>