# WordPress Snippets Collection List

### 1. 우커머스 통원 기호 '원'으로 바꾸기

```php
add_filter('woocommerce_currency_symbol', 'change_won_currency_symbol', 10, 2);
function change_won_currency_symbol( $currency_symbol, $currency ) {
    switch( $currency ) {
        case 'KRW': $currency_symbol = '원'; break;
    }
    return $currency_symbol;
}
```