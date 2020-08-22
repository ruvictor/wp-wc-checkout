<?php
function vicode_custom_checkout_fields($fields){
    $fields['vicodemedia_extra_fields'] = array(
            'vicodemedia_add_field' => array(
                'type' => 'text',
                'required'      => true,
                'label' => __( 'Additional Field' )
                )
            );
    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'vicode_custom_checkout_fields' );
function vicodemedia_extra_checkout_fields(){
    $checkout = WC()->checkout(); ?>
    <div class="col2-set">
    <h3><?php _e( 'Additional Fields' ); ?></h3>
    <?php
       foreach ( $checkout->checkout_fields['vicodemedia_extra_fields'] as $key => $field ) : ?>
            <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
        <?php endforeach; ?>
    </div>
<?php }
add_action( 'woocommerce_checkout_after_customer_details' ,'vicodemedia_extra_checkout_fields' );



// saving data
function vicodemedia_save_extra_checkout_fields( $order_id, $posted ){
    // don't forget appropriate sanitization
    if( isset( $posted['vicodemedia_add_field'] ) ) {
        update_post_meta( $order_id, '_vicodemedia_add_field', sanitize_text_field( $posted['vicodemedia_add_field'] ) );
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'vicodemedia_save_extra_checkout_fields', 10, 2 );




// Display the Data to User
function vicodemedia_display_order_data( $order_id ){  ?>
    <h2><?php _e( 'Extra Information' ); ?></h2>
    <table class="shop_table shop_table_responsive additional_info">
        <tbody>
            <tr>
                <th><?php _e( 'Additional Field:' ); ?></th>
                <td><?php echo get_post_meta( $order_id, '_vicodemedia_add_field', true ); ?></td>
            </tr>
        </tbody>
    </table>
<?php }
add_action( 'woocommerce_thankyou', 'vicodemedia_display_order_data', 20 );
add_action( 'woocommerce_view_order', 'vicodemedia_display_order_data', 20 );



// display data on the Dashboard WC order details page
function vicodemedia_display_order_data_in_admin( $order ){  ?>
    <div class="order_data_column">
        <h4><?php _e( 'Additional Information', 'woocommerce' ); ?><a href="#" class="edit_address"><?php _e( 'Edit', 'woocommerce' ); ?></a></h4>
        <div class="address">
        <?php
            echo '<p><strong>' . __( 'Additional Field' ) . ':</strong>' . get_post_meta( $order->id, '_vicodemedia_add_field', true ) . '</p>'; ?>
        </div>
        <div class="edit_address">
            <?php woocommerce_wp_text_input( array( 'id' => '_vicodemedia_add_field', 'label' => __( 'Addtional Field' ), 'wrapper_class' => '_billing_company_field' ) ); ?>
        </div>
    </div>
<?php }
add_action( 'woocommerce_admin_order_data_after_order_details', 'vicodemedia_display_order_data_in_admin' );

function vicodemedia_save_extra_details( $post_id, $post ){
    update_post_meta( $post_id, '_vicodemedia_add_field', wc_clean( $_POST[ '_vicodemedia_add_field' ] ) );
}
// save data from admin
add_action( 'woocommerce_process_shop_order_meta', 'vicodemedia_save_extra_details', 45, 2 );




// add the field to email template
function vicodemedia_email_order_meta_fields( $fields, $sent_to_admin, $order ) {
    $fields['instagram'] = array(
                'label' => __( 'Additional Field' ),
                'value' => get_post_meta( $order->id, '_vicodemedia_add_field', true ),
            );
    return $fields;
}
add_filter('woocommerce_email_order_meta_fields', 'vicodemedia_email_order_meta_fields', 10, 3 );
?>