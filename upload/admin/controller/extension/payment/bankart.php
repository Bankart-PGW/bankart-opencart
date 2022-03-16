<?php

include_once(DIR_SYSTEM . 'library/bankart/autoload.php');

use Bankart\BankartGateway;
use Bankart\BankartPlugin;

final class ControllerExtensionPaymentBankart extends Controller
{
    use BankartGateway;

    private $prefix = BankartPlugin::PREFIX . '_';

    private $error = array();

    private $cardTypeList = array();

    private $default = [
        'status' => 0,
        'title' => 'Payment Card',
        'sort_order' => 1,
        'api_host' => 'https://gateway.bankart.si',
        'order_status_started' => 1,
        'order_status_failed' => 10,
        'order_status_preauthorized' => 2,
        'order_status_voided' => 16,
        'order_status_captured' => 15,
        'order_status_debit_approved' => 15, 
    ];

    private $config_fields = [
        'status',
        'api_host',
        'sort_order',
        'geo_zone_id',
        'order_total'
    ];

    private $multi_lang_fields = [
        'title',
    ];

    private $mandatory_fields = [
        'status',
        'api_host',
    ];

    private $language_fields = [
        'text_enabled',
        'text_disabled',
        'text_instalments',
        'config_status',
        'config_status_desc',
        'config_title',
        'config_title_desc',
        'config_sort_order',
        'config_sort_order_desc',
        'config_geo_zone',
        'config_total',
        'config_total_help',

        'text_credentials',
        'config_api_host',
        'config_cc_title',
        'config_cc_api_user',
        'config_cc_api_password',
        'config_cc_api_key',
        'config_cc_api_secret',
        'config_cc_integration_key',
        'config_cc_method',
        'config_cc_instalments_number',
        'config_cc_instalments_number_desc',
        'config_cc_instalments_number_help',
        'config_cc_instalments_min_amt',
        'config_cc_instalments_min_amt_desc',
        'config_cc_instalments_geo_zone',
        'config_cc_instalments_geo_zone_help',

        'order_status_management_title',
    ];

    private $bankart_order_states = [
        'started',
        'failed',
        'preauthorized',
        'voided',
        'captured',
        'debit_approved',
    ];

    private $instalments = [
        6,
        12,
        24,
        36,
    ];

    public function index()
    {
        $this->load->language('extension/payment/bankart');
        $this->load->model('setting/setting');

        $this->document->setTitle($this->language->get('heading_title'));

        $creditCards = $this->getCreditCards();
        foreach ($creditCards as $creditCard) {
            $this->mandatory_fields[] = 'cc_status_' . $creditCard['type'];
            $this->cardTypeList[] = $creditCard['type'];
        }
        foreach($this->bankart_order_states as $order_status) {
            $this->mandatory_fields[] = 'order_status_' . $order_status;
        }

        $data['prefix'] = $this->prefix;

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->validate($this->request->post)) {
                $this->model_setting_setting->editSetting(rtrim($this->prefix, '_'), $this->request->post);
                $this->session->data['success'] = $this->language->get('text_success');

                $this->response->redirect($this->url->link('marketplace/extension',
                    'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
            }
            else {
                $data['error'] = $this->error['warning'];
                $creditCards = $this->updateCreditCards($creditCards);
            }
        }
        $data['credit_cards'] = $creditCards;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $data['action'] = $this->url->link('extension/payment/bankart', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
        $data['user_token'] = $this->session->data['user_token'];

        $methods = $this->getMethods();
        $data['methods'] = [];
        foreach ($methods as $method) {
            $data['methods'][$method] = $this->language->get('config_cc_method_' . $method);
        }

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $this->load->model('localisation/geo_zone');
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        $data['instalment_numbers'] = $this->instalments;
 
        $plugin = new BankartPlugin();
        $data = array_merge(
            $data,
            $this->getBreadcrumbData(),
            $this->getLanguageData(),
            $this->getConfigData(),
            $this->getOrderStatusData(),
            $plugin->getTemplateData()
        );
        $data = $this->loadConfigBlocks($data);

        $this->response->setOutput($this->load->view('extension/payment/bankart', $data));
    }

    private function getBreadcrumbData()
    {
        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true),
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/bankart', 'user_token=' . $this->session->data['user_token'], true),
        ];

        return $data;
    }

    private function getOrderStatusData()
    {
        $data = [];
        foreach($this->bankart_order_states as $order_status) {
            $data['bankart_order_states'][$order_status]['name'] = $order_status;
            $data['bankart_order_states'][$order_status]['help'] = $this->language->get('help_order_status_' . $order_status);
            $data['bankart_order_states'][$order_status]['label'] = $this->language->get('label_order_status_' . $order_status);
            $data['bankart_order_states'][$order_status]['config'] = $this->getConfigCheckPost('order_status_' . $order_status);
        }
        return $data;
    }

    private function getLanguageData()
    {
        $data = [];
        foreach ($this->language_fields as $field_text) {
            $data[$field_text] = $this->language->get($field_text);
        }
        return $data;
    }

    private function getConfigData()
    {
        $data = [];
        foreach ($this->config_fields as $config_field) {
            $config_value = $this->getConfigCheckPost($config_field);
            $data[$config_field] = is_array($config_value) ? $config_value : (string)$config_value;
        }
        return $data;
    }

    private function loadConfigBlocks($data)
    {
        $data = array_merge($data, $this->getConfigFields($this->multi_lang_fields, $this->prefix, $this->default));
        return $data;
    }

    private function validate($formFields)
    {
        if (!$this->user->hasPermission('modify', 'extension/payment/bankart')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->mandatory_fields as $field) {
            if (!array_key_exists($this->prefix . $field, $formFields) ||
                $formFields[$this->prefix . $field] === '') {
                    $this->error['warning'] = $this->language->get('error_mandatory_fields');
            }
        }

        foreach ($this->cardTypeList as $cardType) {
            if ($formFields[$this->prefix . 'cc_instalments_' . $cardType] > 1 && 
                $formFields[$this->prefix . 'cc_instalments_amt_' . $cardType] <= 0) {
                    $this->error['warning'] = $this->language->get('error_instalment_amount');
                }
        }
        return !$this->error;
    }

    private function getConfigFields($fields, $prefix, $default)
    {
        $this->load->model('localisation/language');

        $language_codes = [];
        foreach ($this->model_localisation_language->getLanguages() as $language) {
            array_push($language_codes, preg_split('/[-_]/', $language['code'])[0]);
        }

        $keys = [];
        foreach ($fields as $field) {
            foreach ($language_codes as $code) {
                $keys[$field][$code] = $default[$field];
                if (is_array($this->config->get($prefix . $field)) &&
                    array_key_exists($code, $this->config->get($prefix . $field))) {
                    $keys[$field][$code] = $this->config->get($prefix . $field)[$code];
                }
            }
        }
        return $keys;
    }

    // updates creditCards array with POST values in case of an error

    private function updateCreditCards($creditCards) {
        foreach($creditCards as $cardType) {
            foreach($cardType as $key => $value) {
                $field = $this->prefix . 'cc_' . $key . '_' . $cardType['type'];
                if (!empty($this->request->post[$field])) {
                    $creditCards[$cardType['type']][$key] = $this->request->post[$field];
                }
            }
        }
        return $creditCards;
    }

    // when retreiving a database value it checks for POST value first

    private function getConfigCheckPost($key) {
        if(!empty($this->request->post[$this->prefix . $key])) {
            return $this->request->post[$this->prefix . $key];
        }
        return $this->getConfig($key);
    }
}