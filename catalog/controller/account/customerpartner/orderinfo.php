<?php
class ControllerAccountCustomerpartnerOrderinfo extends Controller {

	private $data = array();

	public function index() {

		if (!$this->customer->isLogged()) {
			if(isset($this->request->get['order_id'])){
				$this->session->data['redirect'] = $this->url->link('account/customerpartner/orderinfo&order_id='.$this->request->get['order_id'], '', 'SSL');
			}
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('account/customerpartner');

		$data['chkIsPartner'] = $this->model_account_customerpartner->chkIsPartner();	
			
		if(!$data['chkIsPartner'])
			$this->response->redirect($this->url->link('account/account'));

		$this->language->load('account/customerpartner/orderinfo');				
		
		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}	

      	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			if($order_id){

				if(isset($this->request->post['tracking'])){
					$this->model_account_customerpartner->addOdrTracking($order_id,$this->request->post['tracking']);
					$this->session->data['success'] = $this->language->get('text_success');
				}

		 		$this->response->redirect($this->url->link('account/customerpartner/orderinfo&order_id='.$order_id, '', 'SSL'));	
			}			
		
		}

		if(isset($this->session->data['success'])){
			$data['success'] = $this->session->data['success'];
		}else{
			$data['success'] = '';
		}

		$data['order_id'] = $order_id;
							
		$this->load->model('account/order');
			
		$order_info = $this->model_account_customerpartner->getOrder($order_id);

		$this->document->setTitle($this->language->get('text_order'));
			
		$data['breadcrumbs'] = array();
	
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
			'separator' => false
		); 
	
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),        	
			'separator' => $this->language->get('text_separator')
		);
		
		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
					
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/customerpartner/orderlist', $url, 'SSL'),      	
			'separator' => $this->language->get('text_separator')
		);
		
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_order'),
			'href'      => $this->url->link('account/customerpartner/orderinfo', 'order_id=' . $order_id . $url, 'SSL'),
			'separator' => $this->language->get('text_separator')
		);		
				
  		$data['heading_title'] = $this->language->get('text_order');
  		$data['error_page_order'] = $this->language->get('error_page_order');
		$data['text_order_detail'] = $this->language->get('text_order_detail');
		$data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_date_added'] = $this->language->get('text_date_added');
  		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['text_shipping_address'] = $this->language->get('text_shipping_address');
  		$data['text_payment_method'] = $this->language->get('text_payment_method');
  		$data['text_payment_address'] = $this->language->get('text_payment_address');
  		$data['text_history'] = $this->language->get('text_history');
		$data['text_comment'] = $this->language->get('text_comment');
		$data['text_wait'] = $this->language->get('text_wait');
		$data['text_not_paid'] = $this->language->get('text_not_paid');
		$data['text_paid'] = $this->language->get('text_paid');

		$data['column_tracking_no'] = $this->language->get('column_tracking_no');
  		$data['column_name'] = $this->language->get('column_name');
  		$data['column_model'] = $this->language->get('column_model');
  		$data['column_quantity'] = $this->language->get('column_quantity');
  		$data['column_price'] = $this->language->get('column_price');
  		$data['column_total'] = $this->language->get('column_total');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_date_added'] = $this->language->get('column_date_added');
  		$data['column_status'] = $this->language->get('column_status');
  		$data['column_comment'] = $this->language->get('column_comment');
  		$data['column_transaction_status'] = $this->language->get('column_transaction_status');
		
		$data['button_invoice'] = $this->language->get('button_invoice');
  		$data['button_back'] = $this->language->get('button_back');
  		$data['button_add_history'] = $this->language->get('button_add_history');

  		$data['history_info'] = $this->language->get('history_info');
  		$data['order_status_info'] = $this->language->get('order_status_info');
  		$data['order_status_error'] = $this->language->get('order_status_error');

  		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_notify'] = $this->language->get('entry_notify');
		$data['entry_comment'] = $this->language->get('entry_comment');
		$data['entry_notifyadmin'] = $this->language->get('entry_notifyadmin');

		$data['errorPage'] = false;

		if ($order_info) {
			
			$data['wksellerorderstatus'] = $this->config->get('marketplace_sellerorderstatus'); 

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}
	
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
			
			if ($order_info['payment_address_format']) {
      			$format = $order_info['payment_address_format'];
    		} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
		
    		$find = array(
	  			'{firstname}',
	  			'{lastname}',
	  			'{company}',
      			'{address_1}',
      			'{address_2}',
     			'{city}',
      			'{postcode}',
      			'{zone}',
				'{zone_code}',
      			'{country}'
			);
	
			$replace = array(
	  			'firstname' => $order_info['payment_firstname'],
	  			'lastname'  => $order_info['payment_lastname'],
	  			'company'   => $order_info['payment_company'],
      			'address_1' => $order_info['payment_address_1'],
      			'address_2' => $order_info['payment_address_2'],
      			'city'      => $order_info['payment_city'],
      			'postcode'  => $order_info['payment_postcode'],
      			'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
      			'country'   => $order_info['payment_country']  
			);
			
			$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

      		$data['payment_method'] = $order_info['payment_method'];
			
			if ($order_info['shipping_address_format']) {
      			$format = $order_info['shipping_address_format'];
    		} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
		
    		$find = array(
	  			'{firstname}',
	  			'{lastname}',
	  			'{company}',
      			'{address_1}',
      			'{address_2}',
     			'{city}',
      			'{postcode}',
      			'{zone}',
				'{zone_code}',
      			'{country}'
			);
	
			$replace = array(
	  			'firstname' => $order_info['shipping_firstname'],
	  			'lastname'  => $order_info['shipping_lastname'],
	  			'company'   => $order_info['shipping_company'],
      			'address_1' => $order_info['shipping_address_1'],
      			'address_2' => $order_info['shipping_address_2'],
      			'city'      => $order_info['shipping_city'],
      			'postcode'  => $order_info['shipping_postcode'],
      			'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
      			'country'   => $order_info['shipping_country']  
			);

			$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$data['shipping_method'] = $order_info['shipping_method'];
			
			$data['products'] = array();
			
			$products = $this->model_account_customerpartner->getSellerOrderProducts($order_id);

      		foreach ($products as $product) {
				$option_data = array();
				
				$options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);

         		foreach ($options as $option) {
          			if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
					}
					
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);					
        		}

        		$product_tracking = $this->model_account_customerpartner->getOdrTracking($data['order_id'],$product['product_id'],$this->customer->getId());

        		if($product['paid_status'] == 1) {
        			$paid_status = $this->language->get('text_paid');
        		} else {
        			$paid_status = $this->language->get('text_not_paid');
        		}

        		$data['products'][] = array(
          			'product_id'     => $product['product_id'],
          			'name'     => $product['name'],
          			'model'    => $product['model'],
          			'option'   => $option_data,
          			'tracking' => isset($product_tracking['tracking']) ? $product_tracking['tracking'] : '',
          			'quantity' => $product['quantity'],
          			'paid_status' => $paid_status,
          			'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
        		);
      		}

			// Voucher
			$data['vouchers'] = array();
			
			$vouchers = $this->model_account_order->getOrderVouchers($order_id);
			
			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}
			
      		$data['totals'] = array();

      		$totals = $this->model_account_customerpartner->getOrderTotals($order_id);

      		if($totals AND isset($totals[0]['total']))      			
				$data['totals'][]['total'] = $this->currency->format($totals[0]['total'], $order_info['currency_code'], 1);
			
			$data['comment'] = nl2br($order_info['comment']);
			
			$data['histories'] = array();

			$results = $this->model_account_customerpartner->getOrderHistories($order_id);

      		foreach ($results as $result) {
        		$data['histories'][] = array(
          			'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
          			'status'     => $result['status'],
          			'comment'    => nl2br($result['comment'])
        		);
      		}      		
      		
      		//list of status

      		$this->load->model('localisation/order_status');
      		
      		if($this->config->get('marketplace_available_order_status')) {
      			$data['marketplace_available_order_status'] = $this->config->get('marketplace_available_order_status');
      			$data['marketplace_order_status_sequence'] = $this->config->get('marketplace_order_status_sequence');
      		}
      		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

      		$data['order_status_id'] = $order_info['order_status_id'];

      	}else{
      		$data['errorPage'] = true;
      	}
      	
  		$data['action'] = $this->url->link('account/customerpartner/orderinfo&order_id='.$order_id, '', 'SSL');      	
  		$data['continue'] = $this->url->link('account/customerpartner/orderlist', '', 'SSL');
  		$data['order_invoice'] = $this->url->link('account/customerpartner/soldinvoice&order_id='.$order_id, '', 'SSL');

  		/*
  		Access according to membership plan
  		 */
  		$data['isMember'] = true;
		if($this->config->get('wk_seller_group_status')) {
      		$data['wk_seller_group_status'] = true;
      		$this->load->model('account/customer_group');
			$isMember = $this->model_account_customer_group->getSellerMembershipGroup($this->customer->getId());
			if($isMember) {
				$allowedAccountMenu = $this->model_account_customer_group->getaccountMenu($isMember['gid']);
				if($allowedAccountMenu['value']) {
					$accountMenu = explode(',',$allowedAccountMenu['value']);
					if($accountMenu && !in_array('orderhistory:orderhistory', $accountMenu)) {
						$data['isMember'] = false;
					}
				}
			} else {
				$data['isMember'] = false;
			}
      	} else {
      		if(!in_array('orderhistory', $this->config->get('marketplace_allowed_account_menu'))) {
      			$this->response->redirect($this->url->link('account/account','', 'SSL'));
      		}
      	}
      	/*
      	end here
      	 */

  		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
						
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/customerpartner/orderinfo.tpl')) {
			$this->response->setOutput($this->load->view( $this->config->get('config_template') . '/template/account/customerpartner/orderinfo.tpl' , $data));			
		} else {
			$this->response->setOutput($this->load->view('default/template/account/customerpartner/orderinfo.tpl' , $data));
		}		
    		
	}

	public function history() {

    	$this->language->load('account/customerpartner/orderinfo');				
		$this->load->model('checkout/order');
		
		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST' AND isset($this->request->get['order_id'])) {

			if($this->request->post['notify']) {
				$this->model_checkout_order->addOrderHistory($this->request->get['order_id'], $this->request->post['order_status_id'],$this->request->post['comment'],$this->request->post['notify']);
			}

			if($this->request->post['notifyadmin']) {
				$comment = 'wk_admin_comment____'.$this->request->post['comment'];
				$this->model_checkout_order->addOrderHistory($this->request->get['order_id'], $this->request->post['order_status_id'],$comment,$this->request->post['notifyadmin']);
			}

			$json['success'] = $this->language->get('text_success_history');			
		}
				
		$this->response->setOutput(json_encode($json));
  	}
}
?>