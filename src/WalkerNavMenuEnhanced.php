<?php
/**
 * Create a nav menu with very basic markup.
 *
 * Adapted from original by Thomas Scholz http://toscho.de
 *
 * @version 1.0
 */

namespace CubicMushroom\WordPress\NavMenu;


class WalkerNavMenuEnhanced extends \Walker_Nav_Menu {

	/**
	 * Start the element output.
	 *
	 * @param  string $output Passed by reference. Used to append additional content.
	 * @param  object $item   Menu item data object.
	 * @param  int    $depth  Depth of menu item. May be used for padding.
	 * @param  array  $args   Additional strings.
	 *
	 * @return void
	 */
	public function start_el( &$output, $item, $depth, $args ) {

		global $wp_query;

		// depth dependent classes
		$depth_classes     = array(
			( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
			( $depth >= 2 ? 'sub-sub-menu-item' : '' ),
			( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
			'menu-item-depth-' . $depth
		);
		$depth_class_names = esc_attr( implode( ' ', $depth_classes ) );

		// passed classes
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		if ( 'page' === $item->object ) {
			$classes[] = 'page-' . $item->object_id;
		}
		$frontpage_id = get_option( 'page_on_front' );
		if ( $frontpage_id === $item->object_id ) {
			$classes[] = 'home';
		}
		$class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

		// build html
		$output .= '<li id="nav-menu-item-' . $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';

		$attributes = '';

		! empty ( $item->attr_title )
		// Avoid redundant titles
		and $item->attr_title !== $item->title
		and $attributes .= ' title="' . esc_attr( $item->attr_title ) . '"';

		! empty ( $item->url )
		and $attributes .= ' href="' . esc_attr( $item->url ) . '"';

		$attributes  = trim( $attributes );
		$title       = apply_filters( 'the_title', $item->title, $item->ID );
		$item_output = "$args->before<a $attributes>$args->link_before$title</a>"
			. "$args->link_after$args->after";

		// Since $output is called by reference we don't need to return anything.
		$output .= apply_filters(
			'walker_nav_menu_start_el'
			, $item_output
			, $item
			, $depth
			, $args
		);
	}

	/**
	 * @see Walker::end_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 *
	 * @return void
	 */
	function end_el( &$output ) {
		$output .= '</li>';
	}

	/**
	 * @see Walker::start_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 *
	 * @return void
	 */
	public function start_lvl( &$output ) {
		$output .= '<ul class="sub-menu">';
	}

	/**
	 * @see Walker::end_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 *
	 * @return void
	 */
	public function end_lvl( &$output ) {
		$output .= '</ul>';
	}
}