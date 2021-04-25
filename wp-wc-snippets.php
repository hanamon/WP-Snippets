<?php 

// 우커머스 통원 기호 '원'으로 바꾸기
add_filter('woocommerce_currency_symbol', 'change_won_currency_symbol', 10, 2);
function change_won_currency_symbol( $currency_symbol, $currency ) {
    switch( $currency ) {
        case 'KRW': $currency_symbol = '원'; break;
    }
    return $currency_symbol;
}

// 우커머스 장바구니 비었을 때 상점으로 돌아가기 버튼 리디랙션 커스텀
add_filter( 'woocommerce_return_to_shop_redirect', 'custom_woocommerce_return_to_shop_redirect' );
function custom_woocommerce_return_to_shop_redirect() {
    return site_url() . '/전체강의/';
}

// SVG 사용
add_filter( 'mime_types', 'custom_upload_mimes' );
function custom_upload_mimes( $existing_mimes ) {
	$existing_mimes['svg'] = 'image/svg+xml';
	return $existing_mimes;
}

// Custom.js 추가 연결
add_action( 'wp_enqueue_scripts', 'my_custom_scripts' );
function my_custom_scripts() {
    wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery' ),'',true );
}

// 메뉴 숏코드 사용 [menu name="menu_name"]
add_shortcode('menu', 'print_menu_shortcode');
function print_menu_shortcode($atts, $content = null) {
    extract(shortcode_atts(array( 'name' => null, 'class' => null ), $atts));
    return wp_nav_menu( array( 'menu' => $name, 'menu_class' => 'myMenuClass', 'echo' => false ) );
}

// 페이지 포스트 타입에 '페이지 카테고리' 추가
add_action( 'init', 'custom_taxonomies_with_page', 0 );
function custom_taxonomies_with_page() {
	// page-category
	register_taxonomy( 'page-category', array( 'page' ), array(
		'labels' => array(
			'name' => '페이지 카테고리',
			'label' => '페이지 카테고리',
			'menu_name' => '카테고리',
		),
		'hierarchical' => true, // Default: false
		'show_admin_column' => true, // Default: false
		'show_in_rest' => true,
	) );
}

// Divi 모듈에서 커스텀 포스트 타입 검색
add_action( 'wp_loaded', 'custom_remove_default_et_pb_custom_search' );
function custom_remove_default_et_pb_custom_search(){
	remove_action( 'pre_get_posts', 'et_pb_custom_search' );
	add_action( 'pre_get_posts', 'custom_et_pb_custom_search' );
}
function custom_et_pb_custom_search( $query = false ){
	if ( is_admin() || ! is_a( $query, 'WP_Query' ) || ! $query->is_search ) {
		return;
	}
	if( isset( $_GET['et_pb_searchform_submit'] ) ){
		$postTypes = array();
		if( ! isset($_GET['et_pb_include_posts'] ) && ! isset( $_GET['et_pb_include_pages'] ) ){
			$postTypes = array( 'post' );
		}
		if( isset( $_GET['et_pb_include_pages'] ) ){
			$postTypes = array( 'page' );
		}
		if( isset( $_GET['et_pb_include_posts'] ) ){
			$postTypes[] = 'post';
		}
		/* BEGIN Add custom post types */
		$postTypes[] = 'project';
		$postTypes[] = 'artwork';
		/* END Add custom post types */
		$query->set( 'post_type', $postTypes );
		if( ! empty( $_GET['et_pb_search_cat'] ) ){
			$categories_array = explode( ',', $_GET['et_pb_search_cat'] );
			$query->set( 'category__not_in', $categories_array );
		}
		if( isset( $_GET['et-posts-count'] ) ){
			$query->set( 'posts_per_page', (int) $_GET['et-posts-count'] );
		}
	}
}

// 관리자에 사용자 지정 분류 드롭 다운 표시
add_action('restrict_manage_posts', 'page_filter_custom_post_type_by_taxonomy');
function page_filter_custom_post_type_by_taxonomy() {
	global $typenow;
	$post_type = 'page'; 			// 게시물 유형 변경
	$taxonomy  = 'page-category'; 	// 분류법 변경
	if ($typenow == $post_type) {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => sprintf( __( '모든 %s', 'textdomain' ), $info_taxonomy->label ),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	};
}

// 관리자에서 분류별로 게시물 필터링
add_filter('parse_query', 'page_convert_id_to_term_in_query');
function page_convert_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'page';			// 게시물 유형 변경
	$taxonomy  = 'page-category'; 	// 분류법 변경
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}