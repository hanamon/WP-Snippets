# WordPress Snippets Collection List

### 1. 우커머스 : 통원 기호 '원'으로 바꾸기

```php
add_filter('woocommerce_currency_symbol', 'change_won_currency_symbol', 10, 2);
function change_won_currency_symbol( $currency_symbol, $currency ) {
    switch( $currency ) {
        case 'KRW': $currency_symbol = '원'; break;
    }
    return $currency_symbol;
}
```

### 2. 우커머스 : 장바구니 비었을 때 상점으로 돌아가기 버튼 리디랙션 커스텀

```php
add_filter( 'woocommerce_return_to_shop_redirect', 'custom_woocommerce_return_to_shop_redirect' );
function custom_woocommerce_return_to_shop_redirect() {
    return site_url() . '/전체강의/';
}
```

### 3. 워드프레스에서 SVG 파일 사용할 수 있는 스니펫
```php
add_filter( 'mime_types', 'custom_upload_mimes' );
function custom_upload_mimes( $existing_mimes ) {
	$existing_mimes['svg'] = 'image/svg+xml';
	return $existing_mimes;
}
```
### 4. Custom.js 추가 연결할 수 있는 스니펫
```php
add_action( 'wp_enqueue_scripts', 'my_custom_scripts' );
function my_custom_scripts() {
    wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery' ),'',true );
}
```
