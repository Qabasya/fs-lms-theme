<?php
/**
 * Страница «Заказ получен» (thank-you).
 *
 * Переопределение шаблона плагина
 * (`woocommerce/templates/checkout/thankyou.php`, версия 8.1.0) по макету
 * `Спасибо за заказ - мокап.dc.html`: по указанию пользователя (2026-09-05)
 * на странице остаётся только благодарность — галочка, заголовок, две строки
 * текста и кнопка на главную. Сводку заказа, реквизиты и адрес плагин
 * печатает с хука `woocommerce_thankyou` (`woocommerce_order_details_table`),
 * он снят в `inc/Checkout.php` — сам хук оставлен, на нём висят платёжные
 * шлюзы со своими инструкциями после оплаты.
 *
 * В вывод шаблон попадает через `templates/order-confirmation.html` темы —
 * подробности в том же комментарии в `inc/Checkout.php`.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package fs-lms-theme
 * @version 8.1.0
 *
 * @var WC_Order|false $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order fs-thankyou">

	<?php
	if ( $order instanceof WC_Order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<div class="fs-thankyou__icon fs-thankyou__icon--failed" aria-hidden="true">
				<svg width="36" height="36" viewBox="0 0 24 24" fill="none">
					<path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" />
				</svg>
			</div>

			<h1 class="fs-thankyou__title"><?php esc_html_e( 'Оплата не прошла', 'fs-lms-theme' ); ?></h1>

			<p class="fs-thankyou__text woocommerce-thankyou-order-failed">
				<?php esc_html_e( 'Банк отклонил транзакцию. Попробуйте оплатить заказ ещё раз или свяжитесь с нами.', 'fs-lms-theme' ); ?>
			</p>

			<?php
			/**
			 * Подпись ссылки — в одну строку с тегом: вывод шорткода
			 * (`[woocommerce_checkout]` в `templates/order-confirmation.html`)
			 * проходит через `wpautop`, и перенос строки сразу после `<a>`
			 * превращался в `<br>` ВНУТРИ кнопки.
			 */
			?>
			<p class="fs-thankyou__actions woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="fs-thankyou__button"><?php esc_html_e( 'Оплатить', 'fs-lms-theme' ); ?></a>
			</p>

		<?php else : ?>

			<div class="fs-thankyou__icon" aria-hidden="true">
				<svg width="36" height="36" viewBox="0 0 24 24" fill="none">
					<path d="m5 12.5 4.5 4.5L19 7.5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</div>

			<?php
			/**
			 * Заголовок про оплату — только когда заказ действительно оплачен.
			 * У неоплаченного (`pending`/`on-hold`) статуса тот же текст был
			 * бы неправдой: платёж ещё не прошёл.
			 */
			?>
			<h1 class="fs-thankyou__title">
				<?php
				echo $order->is_paid()
					? esc_html__( 'Оплата прошла успешно', 'fs-lms-theme' )
					: esc_html__( 'Заказ принят', 'fs-lms-theme' );
				?>
			</h1>

			<p class="fs-thankyou__text">
				<?php esc_html_e( 'Спасибо за заказ!', 'fs-lms-theme' ); ?><br>
				<?php
				echo $order->is_paid()
					? esc_html__( 'Чек об оплате придёт вам на указанную при оформлении почту.', 'fs-lms-theme' )
					: esc_html__( 'Мы отправили подтверждение на указанную при оформлении почту.', 'fs-lms-theme' );
				?>
			</p>

			<p class="fs-thankyou__actions">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fs-thankyou__button"><?php esc_html_e( 'На главную', 'fs-lms-theme' ); ?></a>
			</p>

		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

		<div class="fs-thankyou__icon" aria-hidden="true">
			<svg width="36" height="36" viewBox="0 0 24 24" fill="none">
				<path d="m5 12.5 4.5 4.5L19 7.5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
		</div>

		<h1 class="fs-thankyou__title"><?php esc_html_e( 'Заказ принят', 'fs-lms-theme' ); ?></h1>

		<p class="fs-thankyou__text"><?php esc_html_e( 'Спасибо за заказ!', 'fs-lms-theme' ); ?></p>

		<p class="fs-thankyou__actions">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fs-thankyou__button">
				<?php esc_html_e( 'На главную', 'fs-lms-theme' ); ?>
			</a>
		</p>

	<?php endif; ?>

</div>
