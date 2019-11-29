<?php

/*
Plugin Name: Woocommerce Geo Price by Drol
Description: 
Author: Drol
Version: 0.1
*/
add_action( 'woocommerce_product_options_pricing', 'addGeoPrice' );
function addGeoPrice() {
	
$region = array('Амурская область',
'Архангельская область',
'Астраханская область',
'Белгородская область',
'Брянская область','Владимирская область','Волгоградская область','Вологодская область','Воронежская область','Ивановская область','Иркутская область','Калининградская область','Калужская область','Кемеровская область','Кировская область','Костромская область','Курганская область','Курская область','Ленинградская область','Липецкая область','Магаданская область','Московская область','Мурманская область','Нижегородская область','Новгородская область','Новосибирская область','Омская область','Оренбургская область','Орловская область','Пензенская область','Псковская область','Ростовская область','Рязанская область','Самарская область','Саратовская область','Сахалинская область','Свердловская область','Смоленская область','Тамбовская область','Тверская область','Томская область','Тульская область','Тюменская область','Ульяновская область','Челябинская область','Ярославская область','Еврейская автономная область');
$region[] = 'Алматы';
$region[] = 'Атырауская область';
$region[] = 'Астана';
$region[] = 'Карагандинская область';
$region[] = 'Туркестанская область';
$region[] = 'Восточно-Казахстанская область';
$region[] = 'Мангистауская область';
$region[] = 'Алматинская область';
$region[] = 'Западно-Казахстанская область';
$region[] = 'Актюбинская область';
$region[] = 'Павлодарская область';
$region[] = 'Костанайская область';
$region[] = 'Акмолинская область';
$region[] = 'Кызылординская область';
$region[] = 'Жамбылская область';
$region[] = 'Северо-Казахстанская область';
		global $post;

		$regionPrices = maybe_unserialize( get_post_meta( $post->ID, 'region_prices', true ) );
		if ( ! $regionPrices ) {
			$regionPrices = array();
		}
		
		?>
        <hr>
        <p class="form-field">
            <label>Региональные цены:</label>
            <button class="button wgp-add-row" type="button" title="Добавить">&plus; Добавить</button>
        </p>

        <table class="wgp-table">
            <tbody>
            <tr class="wgp-row-template">
                <td>
                    <select name="wgp_region[]" disabled>
                        <option>-- Регион --</option>
						<?php foreach ( $region as $regionName ) : ?>
                            <option value="<?= $regionName ?>"><?= $regionName ?></option>
						<?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="text" name="wgp_price[]" value="" placeholder="Цена" disabled>
                </td>
                <td>
                    <button class="button remove" type="button" title="Удалить">&minus;</button>
                </td>
            </tr>
			<?php foreach ( $regionPrices as $rpRegionName => $rpPrice ): ?>
                <tr>
                    <td>
                        <select name="wgp_region[]">
                            <option>-- Регион --</option>
							<?php foreach ( $region as $regionName ) : ?>
                                <option value="<?= $regionName ?>" <?= ( $rpRegionName == $regionName ) ? 'selected' : '' ?>><?= $regionName ?></option>
							<?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="wgp_price[]" value="<?= $rpPrice ?>" placeholder="Цена">
                    </td>
                    <td>
                        <button class="button remove" type="button" title="Удалить">&minus;</button>
                    </td>
                </tr>
			<?php endforeach; ?>
            </tbody>

        </table>

        <script type="text/javascript">
            (function ($) {

                $('.wgp-add-row').click(function () {
                    let row = $('.wgp-row-template').clone();

                    row.find('select, input').prop('disabled', false);
                    row.removeClass('wgp-row-template');

                    $('.wgp-table tbody').append(row);

                    return false;
                });

                $(document).on('click', '.wgp-table button.remove', function () {
                    $(this).closest('tr').remove();
                    return false;
                });

                $(document).on('keypress', 'input[name^=wgp_priceD]', function (e) {
                    //Если символ - не цифра не пишем её
                    // tab, backspace, 0-9
                    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
                        return false;
                    }
                });
            })(jQuery);

        </script>

        <style>
            .wgp-table select {
                width: 200px;
            }

            .wgp-table input[type=text] {
                width: 200px;
            }

            .wgp-table {
                margin-left: 8px;
                margin-bottom: 20px;
            }

            .wgp-row-template {
                display: none;
            }
        </style>

		<?php
	}
		add_action( 'save_post', 'saveRegionPrices' );
	function saveRegionPrices( $postId ) {

		if ( wp_verify_nonce( $_POST['_inline_edit'], 'inlineeditnonce' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['wgp_region'], $_POST['wgp_price'] ) ) {
			$regionPrices = array();


			foreach ( $_POST['wgp_region'] as $num => $regionName ) {
				if ( isset( $_POST['wgp_price'][ $num ] ) && $price = floatval( $_POST['wgp_price'][ $num ] ) ) {
					$regionPrices[ $regionName ] = $price;
				}
			}

			update_post_meta( $postId, 'region_prices', maybe_serialize( $regionPrices ) );

		} else {
			delete_post_meta( $postId, 'region_prices' );
		}

	}
function my_price($price, $product){	
$id = $product->get_id();
	
		$ip = $_SERVER['REMOTE_ADDR'];
	$url = 'http://api.sypexgeo.net/json/'.$ip;
	$ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch); 	
		$object = json_decode($output);
			   
		
			 $regionPrices = maybe_unserialize( get_post_meta( $id, 'region_prices', true ) );
			 if($regionPrices){
					foreach ( $regionPrices as $rpRegionName => $rpPrice ){
						if($object->region->name_ru == $rpRegionName){
							$price = $rpPrice;
						}
					}
			 }
return $price;
}
add_filter('woocommerce_get_price', 'my_price',100,2);