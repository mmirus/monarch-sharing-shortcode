<?php
/*
Plugin Name: Monarch Sharing Shortcode
Plugin URI: https://github.com/mmirus/monarch-sharing-shortcode
Description: Provides a shortcode for adding inline Monarch sharing buttons.
Author: Matt Mirus
Author URI: https://github.com/mmirus
Version: 1.0.2
GitHub Plugin URI: https://github.com/mmirus/monarch-sharing-shortcode
*/

namespace MSS;

// Add Shortcode
function monarch_sharing_shortcode($atts) {
	// Attributes
	$atts = shortcode_atts(
		[
      'url'     => '',
      'center'  => false,
    ],
		$atts,
		'monarch_share'
	);
  
  return generate_inline_icons('et_social_inline_bottom', $atts['url'], $atts['center']);
}
add_shortcode('monarch_share', 'MSS\\monarch_sharing_shortcode');

// copied from monarch.php and edited to allow manually specifying URL
function generate_inline_icons($class = 'et_social_inline_bottom', $url = '', $center = false) {
  $monarch = $GLOBALS['et_monarch'];
  $monarch_options = $monarch->monarch_options;

  if ( isset( $monarch_options[ 'general_main_reset_postdata' ] ) && true == $monarch_options[ 'general_main_reset_postdata' ] ) {
    wp_reset_postdata();
  }

  $display_all_button = isset( $monarch_options[ 'sharing_inline_display_all' ] ) ? $monarch_options[ 'sharing_inline_display_all' ] : false;

  $inline_content = sprintf(
    '<div class="et_social_inline%10$s %12$s %14$s">
      <div class="et_social_networks et_social_%2$s et_social_%3$s et_social_%4$s et_social_%5$s et_social_no_animation%6$s%7$s%9$s%11$s%13$s">
        %8$s
        %1$s
      </div>
    </div>',
    $monarch->get_icons_list( 'inline', '', false, $display_all_button, false, '', $url ),
    'auto' == $monarch_options[ 'sharing_inline_col_number' ]
      ? 'autowidth'
      : esc_attr( $monarch_options[ 'sharing_inline_col_number' ] . 'col' ),
    esc_attr( $monarch_options[ 'sharing_inline_icon_style' ] ),
    esc_attr( $monarch_options[ 'sharing_inline_icon_shape' ] ),
    esc_attr( $monarch_options[ 'sharing_inline_icons_alignment' ] ), //#5
    true == $monarch_options[ 'sharing_inline_counts' ] ? ' et_social_withcounts' : '',
    true == $monarch_options[ 'sharing_inline_total' ] ? ' et_social_withtotalcount' : '',
    true == $monarch_options[ 'sharing_inline_total' ]
      ? sprintf(
        '<div class="et_social_totalcount">
          <span class="et_social_totalcount_count et_social_total_share" data-post_id="%2$s"></span>
          <span class="et_social_totalcount_label">%1$s</span>
        </div>',
        esc_html__( 'Shares', 'Monarch' ),
        esc_attr( get_the_ID() )
      )
      : '',
    true == $monarch_options[ 'sharing_inline_spacing' ] ? ' et_social_nospace' : '',
    true == $monarch_options[ 'sharing_inline_mobile' ] ? ' et_social_mobile_off' : ' et_social_mobile_on', //#10
    true == $monarch_options[ 'sharing_inline_network_names' ] ? ' et_social_withnetworknames' : '',
    esc_attr( $class ),
    esc_attr( sprintf( ' et_social_outer_%1$s', $monarch_options[ 'sharing_inline_outer_color' ] ) ), //#13
    true == $center ? 'mss-center' : ''
  );
  if ($center) {
    $inline_content .= '<style>.mss-center .et_social_networks{text-align:center!important}.mss-center .et_social_icons_container li{float:none!important;display:inline-block!important}</style>';
  }

  return $inline_content;
}
