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