<?php
// Breadcrumb
$_['text_extension'] = 'Extensions';

// Admin Panel
$_['heading_title'] = 'Bankart Payment Gateway';
$_['text_bankart_gateway_creditcard'] = '<img src="./view/image/bankart_gateway/creditcard.png" />';
$_['error_mandatory_fields'] = 'Please fill in all mandatory fields.';
$_['error_instalment_amount'] = 'Minimum instalment amount must be greater than zero.';
$_['text_edit'] = 'Edit the settings';

// Configuration
$_['text_enabled'] = 'Enabled';
$_['text_disabled'] = 'Disabled';
$_['text_instalments'] = 'instalments';
$_['config_status'] = 'Status';
$_['config_status_desc'] = 'Activate payment method Credit Card to make it available for your consumers.';
$_['config_title'] = 'Title';
$_['config_title_desc'] = 'Payment method name as displayed for the consumer during checkout.';
$_['config_sort_order'] = 'Sort Order';
$_['config_sort_order_desc'] = 'Order of payment methods as displayed on payment page.';
$_['config_geo_zone'] = 'Geo Zone';
$_['config_total'] = 'Total';
$_['config_total_help'] = 'The checkout total the order must reach before this payment method becomes active.';

$_['text_credentials'] = 'API Credentials';
$_['config_api_host'] = 'Gateway Host';

$_['config_cc_title'] = 'Title';
$_['config_cc_api_user'] = 'User';
$_['config_cc_api_password'] = 'Password';
$_['config_cc_status_desc'] = 'Activate card type to make it available for your consumers.<br>API Key and Secret have to be set to make the option appear during checkout.<br>If seamless is enabled Integration Key is required as well.';
$_['config_cc_api_key'] = 'Key';
$_['config_cc_api_secret'] = 'Secret';
$_['config_cc_integration_key'] = 'Integration Key';
$_['config_cc_seamless'] = 'Seamless';
$_['config_cc_seamless_desc'] = 'Use seamless form instead of redirects.';
$_['config_cc_method'] = 'Method';
$_['config_cc_method_debit'] = 'Debit';
$_['config_cc_method_preauthorize'] = 'Preauthorize';
$_['config_cc_instalments_number'] = 'Instalments';
$_['config_cc_instalments_number_desc'] = 'Note that only specific cards support at point of sale!';
$_['config_cc_instalments_number_help'] = 'This number depends on the payment method and may be set in the contract with your acquirer/bank.';
$_['config_cc_instalments_min_amt'] = 'Minimum instalment';
$_['config_cc_instalments_min_amt_desc'] = 'Upper limit of instalments is calculated based on this amount';
$_['config_cc_instalments_geo_zone'] = 'Instalments Geo Zone';
$_['config_cc_instalments_geo_zone_help'] = 'Limit the display of instalments to a specific zone';

$_['text_success'] = 'Your modifications are saved!';

// Order status configuration
$_['order_status_management_title'] = 'Order status';

$_['help_order_status_started'] = 'This status is set when the customer clicks the order confirmation button.';
$_['label_order_status_started'] = 'Payment started';

$_['help_order_status_failed'] = 'This status is set when the payment process fails either because the payment was declined or some other error.';
$_['label_order_status_failed'] = 'Payment failed';

$_['help_order_status_preauthorized'] = 'This status is set when a preauthorization is approved, but payment is not yet captured';
$_['label_order_status_preauthorized'] = 'Preauthorization approved';

$_['help_order_status_voided'] = 'This status is set when an approved preauthorization is voided';
$_['label_order_status_voided'] = 'Preauthorization voided';

$_['help_order_status_captured'] = 'This status is set when an approved preauthorized payment is captured';
$_['label_order_status_captured'] = 'Payment captured';

$_['help_order_status_debit_approved'] = 'This status is set when a debit payment is approved, a status similar to a captured preauthorization.';
$_['label_order_status_debit_approved'] = 'Debit approved';