<?php

include_once(DIR_SYSTEM . 'library/bankart/autoload.php');

use Bankart\BankartPlugin;

class ModelExtensionPaymentBankart extends Model
{
    private $prefix = BankartPlugin::PREFIX . '_';

    public function getMethod($address, $total)
    {
        //$base_url = $this->config->get('config_url');

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get($this->prefix . 'geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

        if ($this->config->get($this->prefix . 'order_total') > 0 && $this->config->get($this->prefix . 'order_total') > $total) {
            $status = false;
        } elseif (!$this->config->get($this->prefix . 'geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $this->load->language('extension/payment/bankart');
            //$logo = '<img src="' . $base_url . 'image/catalog/bankart_gateway/' . $this->type . '.png" />';
            $code = $this->session->data['language'];
            $code = substr($code, 0, 2);
            if (isset($code) && isset($this->config->get($this->prefix . 'title')[$code])) {
                $title = /*$logo . ' ' .*/ $this->config->get($this->prefix . 'title')[$code];
            }
            else $this->language->get('text_title');

            $method_data = [
                'code' => 'bankart',
                'title' => $title,
                'terms' => '',
                'sort_order' => $this->config->get($this->prefix . 'sort_order'),
            ];
        }

        return $method_data;
    }
}
