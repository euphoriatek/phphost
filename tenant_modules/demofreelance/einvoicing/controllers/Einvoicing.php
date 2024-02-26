<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Einvoicing\Invoice;
use Einvoicing\Presets;
use Einvoicing\Identifier;
use Einvoicing\Party;
use Einvoicing\InvoiceLine;
use Einvoicing\Writers\UblWriter;

class Einvoicing extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        show_404();
    }

    public function manage()
    {
        if (!has_permission('einvoicing', '', 'view')) {
            access_denied('einvoicing');
        }

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('einvoicing', 'table'));
        }

        $data['title'] = _l('einvoicing');
        $this->load->view('manage', $data);
    }

    public function generate_einvoice($invoice_id='')
    {
        if (!has_permission('einvoicing', '', 'create')) {
            access_denied('einvoicing');
        }

        $this->load->model('invoices_model');
        $this->load->model('clients_model');

        $invoiceData = $this->invoices_model->get($invoice_id);
        $customerCustomFieldElectronicAddress = get_custom_fields('customers', [
            'slug' => 'customers_electronic_address'
        ]);
        $customerCustomFieldElectronicAddressScheme = get_custom_fields('customers', [
            'slug' => 'customers_electronic_address_scheme'
        ]);

        if (empty($invoiceData)) {
            set_alert('danger', _l('einvoice_invalid_invoice'));
            redirect(admin_url('einvoicing/manage'));
        }

        if (empty($invoiceData->clientid)) {
            set_alert('danger', _l('einvoice_no_client_found_for_this_invoice'));
            redirect(admin_url('einvoicing/manage'));
        }

        if (empty(get_option('invoice_company_name'))) {
            set_alert('danger', _l('einvoice_invoice_company_name_required'));
            redirect(admin_url('einvoicing/manage'));
        }

        if (empty(get_option('company_vat'))) {
            set_alert('danger', _l('einvoice_invoice_company_vat_required'));
            redirect(admin_url('einvoicing/manage'));
        }

        if (empty(get_option('invoice_company_address'))) {
            set_alert('danger', _l('einvoice_invoice_invoice_company_address_required'));
            redirect(admin_url('einvoicing/manage'));
        }

        if (empty(get_option('invoice_company_city'))) {
            set_alert('danger', _l('einvoice_invoice_company_city_required'));
            redirect(admin_url('einvoicing/manage'));
        }

        if (empty(get_option('invoice_company_country_code'))) {
            set_alert('danger', _l('einvoice_invoice_company_country_code_required'));
            redirect(admin_url('einvoicing/manage'));
        }

        if (count($customerCustomFieldElectronicAddress) === 0) {
            set_alert('danger', _l('einvoice_customer_electronic_address_custom_field_is_required'));
            redirect(admin_url('einvoicing/manage'));
        }

        if (count($customerCustomFieldElectronicAddressScheme) === 0) {
            set_alert('danger', _l('einvoice_customer_electronic_address_scheme_custom_field_is_required'));
            redirect(admin_url('einvoicing/manage'));
        }

        if (empty(get_option('einvoicing_seller_company_id'))) {
            set_alert('danger', _l('einvoice_invoice_seller_company_id_required'));
            redirect(admin_url('einvoicing/manage'));
        }

        if (empty(get_option('einvoicing_seller_electronic_address'))) {
            set_alert('danger', _l('einvoice_invoice_seller_electronic_address_required'));
            redirect(admin_url('einvoicing/manage'));
        }

        $getInvoiceClientData = $this->clients_model->get($invoiceData->clientid);
        $clientElectronicAddress = get_custom_field_value($invoiceData->clientid, 'customers_electronic_address', 'customers');
        $clientElectronicAddressScheme = get_custom_field_value($invoiceData->clientid, 'customers_electronic_address_scheme', 'customers');

        if (empty($clientElectronicAddress)) {
            set_alert('danger', _l('einvoice_client_electronic_address_required'));
            redirect(admin_url('einvoicing/manage'));
        }
        if (empty($clientElectronicAddressScheme)) {
            set_alert('danger', _l('einvoice_client_electronic_address_scheme_required'));
            redirect(admin_url('einvoicing/manage'));
        }

        $clientCountryData = get_country($getInvoiceClientData->country);

        $preset = Presets\Peppol::class;

        if ($clientCountryData->iso2 == 'IT') { //Italy Customer
            $preset = Presets\CiusIt::class;
        }

        if ($clientCountryData->iso2 == 'RO') { //Romania Customer
            $preset = Presets\CiusRo::class;
        }

        if ($clientCountryData->iso2 == 'ES') { //Spain Customer
            $preset = Presets\CiusEsFace::class;
        }


        $inv = new Invoice($preset);

        $inv->setNumber(format_invoice_number($invoiceData->id))
            ->setIssueDate(new DateTime($invoiceData->date))
            ->setCurrency(get_currency($invoiceData->currency)->name)
            ->setVatCurrency(get_currency($invoiceData->currency)->name)
            ->setBuyerReference(format_invoice_number($invoiceData->id))
            ->setDueDate(new DateTime($invoiceData->duedate));

        $seller = new Party();
        $seller->setElectronicAddress(new Identifier(get_option('einvoicing_seller_electronic_address'), get_option('einvoicing_seller_electronic_address_scheme')))
            ->setCompanyId(new Identifier(get_option('einvoicing_seller_company_id'), get_option('einvoicing_seller_company_id_scheme')))
            ->setName(get_option('invoice_company_name'))
            ->setTradingName(get_option('invoice_company_name'))
            ->setVatNumber(get_option('company_vat'))
            ->setAddress([get_option('invoice_company_address')])
            ->setCity(get_option('invoice_company_city'))
            ->setCountry(get_option('invoice_company_country_code'));
        $inv->setSeller($seller);

        $buyer = new Party();
        $buyer->setElectronicAddress(new Identifier($clientElectronicAddress, $clientElectronicAddressScheme))
            ->setName($getInvoiceClientData->company)
            ->setCountry(get_country_short_name($getInvoiceClientData->country));
        $inv->setBuyer($buyer);

        if (count($invoiceData->items) > 0) {
            foreach ($invoiceData->items as $item) {

                $itemTax =  get_invoice_item_taxes($item['id']);

                $firstLine = new InvoiceLine();
                $firstLine->setName($item['description'])
                    ->setPrice((double)$item['rate'])
                    ->setVatRate($itemTax[0]['taxrate'] ?: 0)
                    ->setQuantity((double)$item['qty']);

                $inv->addLine($firstLine);

            }
        }

        $writer = new UblWriter();
        $document = $writer->export($inv);

        $path = FCPATH . 'modules/einvoicing/uploads/'.$invoiceData->id.'/';
        _maybe_create_upload_path($path);

        $fileName = 'E-Invoice - '. format_invoice_number($invoiceData->id) . '.xml';

        file_put_contents($path . "/".$fileName, $document);
        einvoicing_force_download($path . $fileName, null);

        redirect(admin_url('einvoicing/manage'));
    }

    public function settings()
    {
        if (!is_admin()) {
            access_denied('einvoicing');
        }

        if ($this->input->post()) {
            if (!is_admin()) {
                access_denied('settings');
            }
            $this->load->model('payment_modes_model');
            $this->load->model('settings_model');

            $post_data = $this->input->post();
            $tmpData = $this->input->post(null, false);

            $success = $this->settings_model->update($post_data);

            if ($success > 0) {
                set_alert('success', _l('settings_updated'));
            }

            redirect(admin_url('einvoicing/settings'), 'refresh');
        }

        $data['title'] = _l('einvoicing') . ' - ' . _l('settings');
        $this->load->view('settings', $data);
    }

}
