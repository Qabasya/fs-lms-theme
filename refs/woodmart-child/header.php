<?php
	/**
		* The Header template for our theme
	*/
?><!DOCTYPE html>
<html lang="ru-RU">
	<head>
		<meta charset="UTF-8">
		<?php wp_head(); ?>
		<meta name="keywords" content="егэ по информатике в калининграде,подготовка к ЕГЭ информатика Калининград,курсы ЕГЭ информатика Калининград,занятия по ЕГЭ информатика Калининград,где подготовиться к ЕГЭ информатика Калининград,очные курсы ЕГЭ информатика Калининград,репетитор информатика,репетитор ЕГЭ информатика Калининград,егэ информатика пробник,пробный егэ,курсы для школьников ЕГЭ информатика,подготовка ребёнка к ЕГЭ информатика,отзывы курсы егэ информатика,ОГЭ информатика Калининград,курсы ОГЭ информатика Калининград,репетитор ОГЭ информатика Калининград,программирование Калининград,программирование 10 класс Калининград" />
		<!-- Yandex.RTB -->
		<script>window.yaContextCb=window.yaContextCb||[]</script>
		<script src="https://yandex.ru/ads/system/context.js" async></script>
	</head>
	
	<body <?php body_class(); ?>>
		<!-- Yandex.RTB R-A-17268387-2 -->
		<script>
			window.yaContextCb.push(() => {
				Ya.Context.AdvManager.render({
					"blockId": "R-A-17268387-2",
					"type": "fullscreen",
					"platform": "touch"
				})
			})
		</script>
		<?php if ( function_exists( 'wp_body_open' ) ) : ?>
		<?php wp_body_open(); ?>
		<?php endif; ?>
		
		<?php do_action( 'woodmart_after_body_open' ); ?>
		
		<div class="website-wrapper">
			<?php if ( woodmart_needs_header() ) : ?>
			<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) : ?>
			<header <?php woodmart_get_header_classes(); // phpcs:ignore ?>>
				<?php whb_generate_header(); ?>
			</header>
			<?php endif ?>
			
			<?php woodmart_page_top_part(); ?>
			<?php endif ?>
			
				