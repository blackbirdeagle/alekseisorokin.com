<?php
/*
* Copyright by Alexander Afanasyev
* E-mail: blackbirdeagle@mail.ru
* Skype: al_sidorenko1
* */
function twentyfifteen_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Widget Area', 'twentyfifteen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentyfifteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'twentyfifteen_widgets_init' );

function ar_dump($arr){
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}

//SEO-ПАРАМЕТРЫ
/** мета заголовок (title):
	- Первый параметр функции это разделитель, второй название блога (если не указать берется из Настроек).
	- Для меток и категорий указывается в настройках в описании (в настройках, по аналогии с пунком 6 Platinum SEO Pack, см. выше) так: [title=Заголовок]
	- Для страниц или постов, если вы хотите чтобы заголовок страницы отличался от заголовка поста, то создайте произвольное поле title и впишите туда свое название
------------------------------------- */
function kama_meta_title ($sep=" | ",$bloginfo_name=''){
	global $wp_query,$post;
	if (!$bloginfo_name) $bloginfo_name = get_bloginfo('name');
	$wp_title = wp_title($sep, 0, 'right');

	if (is_category() || is_tag()){
		$desc = $wp_query->queried_object->description;
		if ($desc) preg_match ('!\[title=(.*)\]!iU',$desc,$match);
		$out = $match[1] ? $match[1] : ((is_tag())?"Метка:":"Категория:")." $wp_title";
	}
	elseif (is_singular()) $out = ($free_title = get_post_meta($post->ID, "title", true)) ? $free_title.$sep : $wp_title;
	elseif (is_author()) $out = "Статьи автора: $wp_title";
	elseif (is_day() || is_month() || is_year()) $out = "Архив за: $wp_title";
	elseif (is_search()) $out = 'Результаты поиска по запросу: '. strip_tags($_GET['s']) . $sep;
	elseif (is_404()) $out = "Ошибка 404 - страница не существует".$wp_title.$sep;

	$out = trim($out.$bloginfo_name);
	if ($paged = get_query_var('paged')) $out = "$out (страница $paged)";
	return print $out;
}

/** мета описание (description):
	- Для главной страницы описание указывается в функции, так: kama_meta_description ('Здесь описание блога');
	- Для страниц меток и категорий указывается в описании (в настройках, по аналогии с пунком 6 Platinum SEO Pack, см. выше), так: [description=текст, описание]
	- У постов сначала проверяется, произвольное поле description, если оно есть описание берется оттуда, потом проверяется поле "цитата", если цитаты нет, то описание берется как начальная часть контента.
	- вторым параметром в функции указывается колличество символов для описания: kama_meta_description ('Описание для главной страницы',200);
------------------------------------- */
function kama_meta_description ($home_description='',$maxchar=200){
	global $wp_query,$post;
	if (is_singular()){
		if ( $descript = get_post_meta($post->ID, "description", true) )
			$out = $descript;
		elseif ($post->post_excerpt!='')
			$out = trim(strip_tags($post->post_excerpt));
		else
			$out = trim(strip_tags($post->post_content));

		$char = iconv_strlen( $out, 'utf-8' );
		if ( $char > $maxchar ) {
			 $out = iconv_substr( $out, 0, $maxchar, 'utf-8' );
			 $words = split(' ', $out ); $maxwords = count($words) - 1; //убираем последнее слово, ибо оно в 99% случаев неполное
			 $out = join(' ', array_slice($words, 0, $maxwords)).' ...';
		 }
	}
	elseif (is_category() || is_tag()){
		$desc = $wp_query->queried_object->description;
		if ($desc) preg_match ('!\[description=(.*)\]!iU',$desc,$match);
		$out = $match[1]?$match[1]:'';
	}
	elseif (is_home()) $out=$home_description;
	if ($out){
		$out = str_replace( array("\n","\r"), ' ', strip_tags($out) );
		$out = preg_replace("@\[.*?\]@", '', $out); //удаляем шоткоды
		return print "<meta name='description' content='$out' />\n";
	}
	else return false;
}

/** метатег keywords:
	- Для главной страницы, ключевые слова указываются в функции так: kama_meta_keywords ('слово1, слово2, слово3');
	- Также можно вписать ключевые слова во второй параметр, они будут отображаться (добавляться) на всех страницах сайта: kama_meta_keywords ('<ключевики для главной>','<сквозные ключевики>');
	- Чтобы задать свои keywords для записи, создайте произвольное поле keywords и впишите в значения необходимые ключевые слова. Если такого поля у записи нет, то ключевые слова генерируются из меток и названия категории(й).
	- Для страниц меток и категорий ключевые слова указываетются в описании (в настройках, по аналогии с пунком 6 Platinum SEO Pack, см. выше) так: [keywords=слово1, слово2, слово3]
------------------------------------- */
function kama_meta_keywords ($home_keywords='',$def_keywords=''){
	global $wp_query,$post;
	/*if ( is_single() && !$out=get_post_meta($post->ID,'keywords',true) ){
		$out = '';
		$res = wp_get_object_terms( $post->ID, array('post_tag','category'), array('orderby' => 'none') ); // получаем категории и метки
		if ($res) foreach ($res as $tag) $out .= " {$tag->name}";
		$out = str_replace(' ',', ',trim($out));
		$out = "$out $def_keywords";
	}
	elseif (is_category() || is_tag()){
		$desc = $wp_query->queried_object->description;
		if ($desc) preg_match ('!\[keywords=(.*)\]!iU',$desc,$match);
		$out = $match[1]?$match[1]:'';
		$out = "$out $def_keywords";
	}
	elseif (is_home()){
		$out = $home_keywords;
	}*/
	$out=get_post_meta($post->ID,'keywords',true);
	if ($out) return print "<meta name='keywords' content='$out' />\n";
	return false;
}

//ВЫВОД Верхнего МЕНЮ
class True_Walker_Nav_Menu_Top extends Walker_Nav_Menu {


	function start_lvl( &$output, $depth ) {

		$indent = str_repeat( "\t", $depth );

		$submenu = ($depth > 0) ? ' sub-menu' : '';

		$output	   .= "\n$indent<ul class=\"dropdown-menu$submenu depth_$depth\">\n";
	}
	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output
	 * @param object $item Объект элемента меню, подробнее ниже.
	 * @param int $depth Уровень вложенности элемента меню.
	 * @param object $args Параметры функции wp_nav_menu
	 */
function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$li_attributes = '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		// managing divider: add divider class to an element to get a divider before it.

		$divider_class_position = array_search('divider', $classes);

		if($divider_class_position !== false){

			$output .= "<li class=\"divider\"></li>\n";

			unset($classes[$divider_class_position]);

		}

		$classes[] = ($args->has_children) ? 'dropdown' : '';

		$classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';

		$classes[] = 'menu-item-' . $item->ID;

		if($depth && $args->has_children){

			$classes[] = 'dropdown-submenu';

		}
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

		$class_names = ' class="' . esc_attr( $class_names ) . '"';



		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );

		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';



		$output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';



		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';

		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';

		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';

		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$attributes .= ($args->has_children) 	    ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';



		$item_output = $args->before;

		$item_output .= '<a'. $attributes .'>';

		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

		$item_output .= ($depth == 0 && $args->has_children) ? ' </a>' : '</a>';

		$item_output .= $args->after;
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

	}
	
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {

		//v($element);

		if ( !$element )

			return;

		$id_field = $this->db_fields['id'];

	//display this element

		if ( is_array( $args[0] ) )

			$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );

		else if ( is_object( $args[0] ) )

			$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );

		$cb_args = array_merge( array(&$output, $element, $depth), $args);

		call_user_func_array(array(&$this, 'start_el'), $cb_args);

		$id = $element->$id_field;
		// descend only when the depth is right and there are childrens for this element

		if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

			foreach( $children_elements[ $id ] as $child ){
				if ( !isset($newlevel) ) {

					$newlevel = true;

					//start the child delimiter

					$cb_args = array_merge( array(&$output, $depth), $args);

					call_user_func_array(array(&$this, 'start_lvl'), $cb_args);

				}

				$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );

			}

			unset( $children_elements[ $id ] );

		}
		if ( isset($newlevel) && $newlevel ){
			//end the child delimiter
			$cb_args = array_merge( array(&$output, $depth), $args);
			call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
		}
	//end this element

		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array(&$this, 'end_el'), $cb_args);
	}
}

register_nav_menus(array(
	'top__menu'     => 'Верхнее меню',
	'top__menu__en'     => 'Верхнее меню EN',
	
));

function my_wp_nav_menu_args( $args='' ){
	$args['container'] = '';
	return $args;
}
add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );

/*---------------Слайдер первого экрана-------------------*/
function sliderone(){
	 register_post_type('sliderone', array(
	  'public' => true,
	  'hierarchical' => true,
	  'supports' => array('title', 'thumbnail'),
	  'labels' => array(
	   'name' => 'Слайдер первого экрана',
	   'all_items' => 'Все слайды',
	   'add_new' => 'Добавить слайд',
	   'add_new_item' => 'Добавление слайда'
	  ),
	  'rewrite' => true
	 ));	
}

add_action('init', 'sliderone');

function sliderone_list(){
	$sliderone = get_posts(array(
		'numberposts' => -1,
		'orderby' => 'date',	
		'order' => 'asc',
		'post_status' => 'publish',
		'post_type' => 'sliderone',
	));	

	if($sliderone){
		foreach($sliderone as $key => $item){
			$img_id = get_post_meta($item->ID, 'изображение', true);
			$src = wp_get_attachment_image_src($img_id, "Full");			
		
			echo '<img src = "'.$src[0].'" />';
		}
	}
}

add_shortcode('slideronelist', 'sliderone_list');
/*---------------Сертификаты-------------------*/
function sertificats(){
	 register_post_type('sertificats', array(
	  'public' => true,
	  'hierarchical' => true,
	  'supports' => array('title', 'thumbnail'),
	  'labels' => array(
	   'name' => 'Сертификаты',
	   'all_items' => 'Все сертификаты',
	   'add_new' => 'Добавить сертификат',
	   'add_new_item' => 'Добавление сертификата'
	  ),
	  'rewrite' => true
	 ));	
}

add_action('init', 'sertificats');

function sertificats_list(){
	$sertificats = get_posts(array(
		'numberposts' => -1,
		'orderby' => 'date',	
		'order' => 'asc',
		'post_status' => 'publish',
		'post_type' => 'sertificats',
	));	

	if($sertificats){
		echo '<div id = "sert__slider" class = "owl-carousel">';
		foreach($sertificats as $key => $item){
			$img_id = get_post_meta($item->ID, 'изображение', true);
			$src = wp_get_attachment_image_src($img_id, "Full");			
		
			echo '<div class = "item"><a class = "fancybox" data-fancybox-group="gallery2" href = "'.$src[0].'"><img src = "'.$src[0].'" /></a></div>';
		}
		echo '</div>';
	}
}

add_shortcode('sertificatslist', 'sertificats_list');

/*---------------Услуги-------------------*/
function services(){
	 register_post_type('services', array(
	  'public' => true,
	  'hierarchical' => true,
	  'supports' => array('title', 'thumbnail'),
	  'labels' => array(
	   'name' => 'Услуги',
	   'all_items' => 'Все услуги',
	   'add_new' => 'Добавить услугу',
	   'add_new_item' => 'Добавление услуги'
	  ),
	  'rewrite' => true
	 ));	
}

add_action('init', 'services');

function services_ru_list(){
	$services = get_posts(array(
		'numberposts' => -1,
		'orderby' => 'date',	
		'order' => 'asc',
		'post_status' => 'publish',
		'post_type' => 'services',
	));	

	if($services){
		echo '<ul class = "services__list">';
		foreach($services as $key => $item){
			$ru_text = get_post_meta($item->ID, 'русский_текст', true);
						
			echo '<li>'.$ru_text.'</li>';
		}
		echo '</ul>';
	}
}

add_shortcode('servicesrulist', 'services_ru_list');

function services_en_list(){
	$services = get_posts(array(
		'numberposts' => -1,
		'orderby' => 'date',	
		'order' => 'asc',
		'post_status' => 'publish',
		'post_type' => 'services',
	));	

	if($services){
		echo '<ul class = "services__list">';
		foreach($services as $key => $item){
			$ru_text = get_post_meta($item->ID, 'английский_текст', true);
						
			echo '<li>'.$ru_text.'</li>';
		}
		echo '</ul>';
	}
}

add_shortcode('servicesenlist', 'services_en_list');

/*---------------Фото-------------------*/
function foto(){
	 register_post_type('foto', array(
	  'public' => true,
	  'hierarchical' => true,
	  'supports' => array('title', 'thumbnail'),
	  'labels' => array(
	   'name' => 'Фото',
	   'all_items' => 'Все фото',
	   'add_new' => 'Добавить фото',
	   'add_new_item' => 'Добавление фото'
	  ),
	  'rewrite' => true
	 ));	
}

add_action('init', 'foto');

function foto_en_list(){
	$foto = get_posts(array(
		'numberposts' => -1,
		'orderby' => 'date',	
		'order' => 'asc',
		'post_status' => 'publish',
		'post_type' => 'foto',
	));	

	if($foto){
		echo '<div id = "foto__slider" class = "owl-carousel">';
		foreach($foto as $key => $item){
			$en_name = get_post_meta($item->ID, 'английское_название', true);
			$img_id = get_post_meta($item->ID, 'изображение', true);
			$src = wp_get_attachment_image_src($img_id, "Full");						
			echo '
				<div class = "item">
					<a class = "fancybox" data-fancybox-group="gallery1" href = "'.$src[0].'"><img src = "'.$src[0].'" /></a>
					<div class = "foto__content">
						<p class = "foto__name">'.$en_name.'</p>
						<p class = "foto__auth">// Alexei Sorokin</p>
					</div>
				</div>			
			';
		}
		echo '</div>';
	}
}

add_shortcode('fotoenlist', 'foto_en_list');

function foto_ru_list(){
	$foto = get_posts(array(
		'numberposts' => -1,
		'orderby' => 'date',	
		'order' => 'asc',
		'post_status' => 'publish',
		'post_type' => 'foto',
	));	

	if($foto){
		echo '<div id = "foto__slider" class = "owl-carousel">';
		foreach($foto as $key => $item){
			$ru_name = get_post_meta($item->ID, 'русское_название', true);
			$img_id = get_post_meta($item->ID, 'изображение', true);
			$src = wp_get_attachment_image_src($img_id, "Full");						
			echo '
				<div class = "item">
					<a class = "fancybox" data-fancybox-group="gallery1" href = "'.$src[0].'"><img src = "'.$src[0].'" /></a>
					<div class = "foto__content">
						<p class = "foto__name">'.$ru_name.'</p>
						<p class = "foto__auth">// Алексей Сорокин</p>
					</div>
				</div>			
			';
		}
		echo '</div>';
	}
}

add_shortcode('fotorulist', 'foto_ru_list');

/*---------------Видео-------------------*/
function video(){
	 register_post_type('video', array(
	  'public' => true,
	  'hierarchical' => true,
	  'supports' => array('title', 'thumbnail'),
	  'labels' => array(
	   'name' => 'Видео',
	   'all_items' => 'Все видео',
	   'add_new' => 'Добавить видео',
	   'add_new_item' => 'Добавление видео'
	  ),
	  'rewrite' => true
	 ));	
}

add_action('init', 'video');

function video_ru_list(){
	$video = get_posts(array(
		'numberposts' => -1,
		'orderby' => 'date',	
		'order' => 'asc',
		'post_status' => 'publish',
		'post_type' => 'video',
	));	

	if($video){
		echo '<div id = "video__slider" class = "owl-carousel">';
		foreach($video as $key => $item){
			$ru_name = get_post_meta($item->ID, 'русское_название', true);
			$video = get_post_meta($item->ID, 'код_видео', true);
						
			echo '
				<div class = "item">
					<div class = "video-block">
						'.$video.'
					</div>
					<p class = "video__name">'.$ru_name.'</p>
					<p class = "foto__auth">// Алексей Сорокин</p>
				</div>			
			';
		}
		echo '</div>';
	}
}

add_shortcode('videorulist', 'video_ru_list');

function video_en_list(){
	$video = get_posts(array(
		'numberposts' => -1,
		'orderby' => 'date',	
		'order' => 'asc',
		'post_status' => 'publish',
		'post_type' => 'video',
	));	

	if($video){
		echo '<div id = "video__slider" class = "owl-carousel">';
		foreach($video as $key => $item){
			$ru_name = get_post_meta($item->ID, 'английское_название', true);
			$video = get_post_meta($item->ID, 'код_видео', true);
						
			echo '
				<div class = "item">
					<div class = "video-block">
						'.$video.'
					</div>
					<p class = "video__name">'.$ru_name.'</p>
					<p class = "foto__auth">// Alexei Sorokin</p>
				</div>			
			';
		}
		echo '</div>';
	}
}

add_shortcode('videoenlist', 'video_en_list');

/*---------------Социальные сети-------------------*/
function social(){
	 register_post_type('social', array(
	  'public' => true,
	  'hierarchical' => true,
	  'supports' => array('title', 'thumbnail'),
	  'labels' => array(
	   'name' => 'Социальные сети',
	   'all_items' => 'Все сети',
	   'add_new' => 'Добавить сеть',
	   'add_new_item' => 'Добавление сети'
	  ),
	  'rewrite' => true
	 ));	
}

add_action('init', 'social');

function social_list(){
	$social = get_posts(array(
		'numberposts' => -1,
		'orderby' => 'date',	
		'order' => 'asc',
		'post_status' => 'publish',
		'post_type' => 'social',
	));	

	if($social){
		echo '<ul>';
		foreach($social as $key => $item){
			$link = get_post_meta($item->ID, 'ссылка', true);
			$img_id = get_post_meta($item->ID, 'изображение', true);
			$src = wp_get_attachment_image_src($img_id, "Full");						
			echo '
				<li><a href = "'.$link.'" target = "_blank"><img src = "'.$src[0].'" /></a></li>
			';
		}
		echo '</ul>';
	}
}

add_shortcode('sociallist', 'social_list');

/*-----------Вывод статей на русском-------------------*/
function articles_ru_list(){
	$articles = get_posts(array(
		'numberposts' => -1,
		'orderby' => 'date',	
		'order' => 'asc',
		'post_status' => 'publish',
		'post_type' => 'post',
		'category'    => 3,
	));
	
	if($articles){
		$i = 1; $j = 1;
		foreach($articles as $key => $item){
			$preview__text = get_post_meta($item->ID, 'краткий_текст', true);
			
			if($i % 4 == 1){
				echo '<ul id = "articles__list__'.$j.'" class = "articles__list">';
			}
			
			echo '
				<li>
					<a href = "'.get_permalink($item->ID).'">
						<p class = "articles__list__name">'.$item->post_title.'</p>
						<p class = "articles__list__text">'.$preview__text.'</p>
					</a>
				</li>			
			';			
			
			if($i % 4 == 0){
				echo '</ul>';
				$j++;
			}
			
			$i++;
		}
		
		echo '<ul class = "art__pagin">';
		$i = 1; $j = 1;
		foreach($articles as $key => $item){
			if($i % 4 == 0){
				if($j == 1){
					echo '<li><a page = "articles__list__'.$j.'" class = "active" href = "javascript:void(0);">'.$j.'</a></li>';
				}else{
					echo '<li><a page = "articles__list__'.$j.'" href = "javascript:void(0);">'.$j.'</a></li>';
				}
				$j++;
			}	
			$i++;	
		}
		echo '</ul>';
	}
}

add_shortcode('articlesrulist', 'articles_ru_list');

/*-----------Вывод статей на английском-------------------*/
function articles_en_list(){
	$articles = get_posts(array(
		'numberposts' => -1,
		'orderby' => 'date',	
		'order' => 'asc',
		'post_status' => 'publish',
		'post_type' => 'post',
		'category'    => 4,
	));
	
	if($articles){
		$i = 1; $j = 1;
		foreach($articles as $key => $item){
			$preview__text = get_post_meta($item->ID, 'краткий_текст', true);
			
			if($i % 4 == 1){
				echo '<ul id = "articles__list__'.$j.'" class = "articles__list">';
			}
			
			echo '
				<li>
					<a href = "'.get_permalink($item->ID).'">
						<p class = "articles__list__name">'.$item->post_title.'</p>
						<p class = "articles__list__text">'.$preview__text.'</p>
					</a>
				</li>			
			';			
			
			if($i % 4 == 0){
				echo '</ul>';
				$j++;
			}
			
			$i++;
		}
		
		echo '<ul class = "art__pagin">';
		$i = 1; $j = 1;
		foreach($articles as $key => $item){
			if($i % 4 == 0){
				if($j == 1){
					echo '<li><a page = "articles__list__'.$j.'" class = "active" href = "javascript:void(0);">'.$j.'</a></li>';
				}else{
					echo '<li><a page = "articles__list__'.$j.'" href = "javascript:void(0);">'.$j.'</a></li>';
				}
				$j++;
			}	
			$i++;	
		}
		echo '</ul>';
	}
}

add_shortcode('articlesenlist', 'articles_en_list');

/*---------------Блок с формой-------------------*/
function block(){
	 register_post_type('block', array(
	  'public' => true,
	  'hierarchical' => true,
	  'supports' => array('title', 'thumbnail'),
	  'labels' => array(
	   'name' => 'Блок с формой',
	   'all_items' => 'Все элементы',
	   'add_new' => 'Добавить элемент',
	   'add_new_item' => 'Добавление элемента'
	  ),
	  'rewrite' => true
	 ));	
}

add_action('init', 'block');

function get_block(){
	$block = get_posts(array(
		'numberposts' => -1,
		'orderby' => 'date',	
		'order' => 'asc',
		'post_status' => 'publish',
		'post_type' => 'block',
	));	

	if($block){

		foreach($block as $key => $item){
			$h_ru = get_post_meta($item->ID, 'заголовок_ru', true);
			$h_en = get_post_meta($item->ID, 'заголовок_en', true);
			$cons_ru = get_post_meta($item->ID, 'текст_консультации_ru', true);
			$cons_en = get_post_meta($item->ID, 'текст_консультации_en', true);
			$email = get_post_meta($item->ID, 'email', true);
			$text_policy_ru = get_post_meta($item->ID, 'текст_политики_ru', true);
			$text_policy_en = get_post_meta($item->ID, 'текст_политики_en', true);
			$copy_ru = get_post_meta($item->ID, 'копирайт_ru', true);
			$copy_en = get_post_meta($item->ID, 'копирайт_en', true);
			
				
			break;
		}

	}
	
	$mass = array(
		'h_ru' => $h_ru,
		'h_en' => $h_en,
		'cons_ru' => $cons_ru,
		'cons_en' => $cons_en,
		'email' => $email,
		'text_policy_ru' => $text_policy_ru,
		'text_policy_en' => $text_policy_en,
		'copy_ru' => $copy_ru,
		'copy_en' => $copy_en
	);
	
	return $mass;
}

?>