<?php
class ControllerModuleMarketplace extends Controller {

	private $error = array(); 
	private $files_array = array();
	private $data = array();

	public function install() {
		$this->load->model('customerpartner/partner');
		$this->model_customerpartner_partner->createCustomerpartnerTable();
	}	

	public function getdir($controller_path = ''){

		$copy = $controller_path;
		$path = DIR_CATALOG.'controller';
		if($path != $controller_path)
			$controller_path = $path.'/'.$controller_path;

		if(is_dir($controller_path)){
			if($controller_path_files = opendir($controller_path)){
				while(($new_file = readdir($controller_path_files)) !== false){
					if($new_file != '.' AND $new_file!= '..'){
						if(is_dir($controller_path.'/'.$new_file)){
							if($copy)
								$new_file = $copy.'/'.$new_file;
								$this->getdir($new_file);
						}elseif($copy!='module' AND $copy!='payment' AND $copy!='shipping' AND $copy!='api' AND $copy!='feed' AND $copy!='tool'){ // to discard folders
							$chk = explode(".",$new_file);
							if(end($chk)=='php')
								$this->files_array [] = $copy.'/'.prev($chk);
						}
					}
				}
			}
		}
	}

	public function index() {   

		$this->load->language('module/marketplace');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/language');
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			if(isset($this->request->files['marketplace_default_image']) && $this->request->files['marketplace_default_image']['name']) {
				move_uploaded_file($this->request->files['marketplace_default_image']["tmp_name"], DIR_IMAGE . "catalog/" . $this->request->files['marketplace_default_image']["name"]);
				$this->request->post['marketplace_default_image_name'] = "catalog/".$this->request->files['marketplace_default_image']["name"];
			}

			if(isset($this->request->post['marketplace_SefUrlspath']))
				$this->request->post['marketplace_SefUrlspath'] = array_values($this->request->post['marketplace_SefUrlspath']);
			if(isset($this->request->post['marketplace_SefUrlsvalue']))
				$this->request->post['marketplace_SefUrlsvalue'] = array_values($this->request->post['marketplace_SefUrlsvalue']);	

			//remove blank tabs - checked heading
			if(isset($this->request->post['marketplace_tab']['heading'])){
				foreach ($this->request->post['marketplace_tab']['heading'] as $key => $value) {
					$left_this = false;
					foreach($value as $language_key => $language_value){
						if($language_value)
							$left_this = true;
					}
					if(!$left_this){
						unset($this->request->post['marketplace_tab']['heading'][$key]);
						unset($this->request->post['marketplace_tab']['description'][$key]);
					}
				}
			}

			$this->model_setting_setting->editSetting('marketplace', $this->request->post);			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->response->redirect($this->url->link('module/marketplace', 'token=' . $this->session->data['token'], 'SSL'));
			$this->response->redirect($this->url->link('module/marketplace', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_form'] = $this->language->get('text_form');	
		$data['text_divide_shipping'] = $this->language->get('text_divide_shipping');	

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_seo'] = $this->language->get('tab_seo');
		$data['tab_commission'] = $this->language->get('tab_commission');
		$data['tab_product'] = $this->language->get('tab_product');
		$data['tab_mpseo'] = $this->language->get('tab_mpseo');
		$data['tab_defaultseo'] = $this->language->get('tab_defaultseo');
		$data['tab_productseo'] = $this->language->get('tab_productseo');
		$data['tab_sell'] = $this->language->get('tab_sell');
		$data['tab_module'] = $this->language->get('tab_module');
		$data['tab_profile'] = $this->language->get('tab_profile');		
		$data['tab_tab'] = $this->language->get('tab_tab');
		$data['tab_mod_config'] = $this->language->get('tab_mod_config');	
		$data['tab_mod_config_account'] = $this->language->get('tab_mod_config_account');
		$data['tab_mod_config_product'] = $this->language->get('tab_mod_config_product');
		$data['tab_mail'] = $this->language->get('tab_mail');		

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_remove'] = $this->language->get('button_remove');

		$data['entry_mpinfo'] = $this->language->get('entry_mpinfo');
		$data['entry_useseo'] = $this->language->get('entry_useseo');
		$data['entry_seo_seller_details'] = $this->language->get('entry_seo_seller_details');
		$data['entry_seo_seller_detailsinfo'] = $this->language->get('entry_seo_seller_detailsinfo');
		$data['entry_seo_display_format'] = $this->language->get('entry_seo_display_format');
		$data['entry_seo_display_formatinfo'] = $this->language->get('entry_seo_display_formatinfo');
		$data['entry_seo_default_name'] = $this->language->get('entry_seo_default_name');
		$data['entry_seo_default_nameinfo'] = $this->language->get('entry_seo_default_nameinfo');
		$data['entry_seo_default_name_product'] = $this->language->get('entry_seo_default_name_product');
		$data['entry_seo_default_name_productinfo'] = $this->language->get('entry_seo_default_name_productinfo');
		$data['entry_seo_add_page_extension'] = $this->language->get('entry_seo_add_page_extension');
		$data['entry_seo_add_page_extensioninfo'] = $this->language->get('entry_seo_add_page_extensioninfo');
		$data['entry_seo_seller_name'] = $this->language->get('entry_seo_seller_name');
		$data['entry_seo_company_name'] = $this->language->get('entry_seo_company_name');
		$data['entry_seo_screen_name'] = $this->language->get('entry_seo_screen_name');
		$data['entry_only_product'] = $this->language->get('entry_only_product');
		$data['entry_seller_and_product'] = $this->language->get('entry_seller_and_product');
		$data['entry_product_and_seller'] = $this->language->get('entry_product_and_seller');
		$data['entry_useseo'] = $this->language->get('entry_useseo');
		$data['entry_wksell'] = $this->language->get('entry_wksell');
		$data['entry_productlist'] = $this->language->get('entry_productlist');
		$data['entry_profile'] = $this->language->get('entry_profile');
		$data['entry_addproduct'] = $this->language->get('entry_addproduct');
		$data['entry_add_shipping_mod'] = $this->language->get('entry_add_shipping_mod');
		$data['entry_dashboard'] = $this->language->get('entry_dashboard');
		$data['entry_orderlist'] = $this->language->get('entry_orderlist');
		$data['entry_wkorder_info'] = $this->language->get('entry_wkorder_info');
		$data['entry_soldlist'] = $this->language->get('entry_soldlist');
		$data['entry_soldinvoice'] = $this->language->get('entry_soldinvoice');
		$data['entry_editproduct'] = $this->language->get('entry_editproduct');
		$data['entry_storeprofile'] = $this->language->get('entry_storeprofile');
		$data['entry_collection'] = $this->language->get('entry_collection');
		$data['entry_feedback'] = $this->language->get('entry_feedback');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_downloads'] = $this->language->get('entry_downloads');
		$data['entry_commission'] = $this->language->get('entry_commission');
		$data['entry_productapprov'] = $this->language->get('entry_productapprov');
		$data['entry_partnerapprov'] = $this->language->get('entry_partnerapprov');
		$data['wkentry_mailtoseller'] = $this->language->get('wkentry_mailtoseller');
		$data['wkentry_pimagesize'] = $this->language->get('wkentry_pimagesize');
		$data['entry_priority_commission'] = $this->language->get('entry_priority_commission');
		$data['entry_fixed'] = $this->language->get('entry_fixed');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_category_child'] = $this->language->get('entry_category_child');
		$data['entry_commission_add'] = $this->language->get('entry_commission_add');
		$data['entry_both'] = $this->language->get('entry_both');

		$data['entry_admin_mail'] = $this->language->get('entry_admin_mail');
		$data['entry_customer_contact_seller'] = $this->language->get('entry_customer_contact_seller');
		$data['entry_hide_seller_email'] = $this->language->get('entry_hide_seller_email');
		$data['entry_mail_admin_customer_contact_seller'] = $this->language->get('entry_mail_admin_customer_contact_seller');

		$data['entry_product_add_email'] = $this->language->get('entry_product_add_email');
		$data['entry_download_size'] = $this->language->get('entry_download_size');
		$data['entry_image_ex'] = $this->language->get('entry_image_ex');
		$data['entry_download_ex'] = $this->language->get('entry_download_ex');
		$data['entry_no_of_images'] = $this->language->get('entry_no_of_images');
		$data['entry_no_of_download'] = $this->language->get('entry_no_of_download');
		$data['entry_alowed_product_columns'] = $this->language->get('entry_alowed_product_columns');
		$data['entry_alowed_product_tabs'] = $this->language->get('entry_alowed_product_tabs');
		$data['entry_commission_worked'] = $this->language->get('entry_commission_worked');
		$data['entry_customer_delete_product'] = $this->language->get('entry_customer_delete_product');
		$data['wkentry_seller_order_status'] = $this->language->get('wkentry_seller_order_status');
		$data['wkentry_seller_order_status_sequence'] = $this->language->get('wkentry_seller_order_status_sequence');
		$data['wkentry_seller_available_order_status'] = $this->language->get('wkentry_seller_available_order_status');
		$data['entry_becomepartner'] = $this->language->get('entry_becomepartner');
		$data['entry_transactions'] = $this->language->get('entry_transactions');		
		$data['entry_addseomore'] = $this->language->get('entry_addseomore');				
		$data['entry_addmore'] = $this->language->get('entry_addmore');			

		$data['entry_partnerapprovinfo'] = $this->language->get('entry_partnerapprovinfo');
		$data['entry_productapprovinfo'] = $this->language->get('entry_productapprovinfo');
		$data['entry_mailtosellerinfo'] = $this->language->get('entry_mailtosellerinfo');
		$data['entry_customer_delete_productinfo'] = $this->language->get('entry_customer_delete_productinfo');
		$data['wkentry_seller_order_statusinfo'] = $this->language->get('wkentry_seller_order_statusinfo');
		$data['wkentry_seller_order_status_sequenceinfo'] = $this->language->get('wkentry_seller_order_status_sequenceinfo');
		$data['wkentry_seller_available_order_statusinfo'] = $this->language->get('wkentry_seller_available_order_statusinfo');
		$data['entry_admin_mailinfo'] = $this->language->get('entry_admin_mailinfo');
		$data['entry_customer_contact_sellerinfo'] = $this->language->get('entry_customer_contact_sellerinfo');
		$data['entry_mail_admin_customer_contact_sellerinfo'] = $this->language->get('entry_mail_admin_customer_contact_sellerinfo');
		$data['entry_commission_workedinfo'] = $this->language->get('entry_commission_workedinfo');		
		$data['entry_priority_commissioninfo'] = $this->language->get('entry_priority_commissioninfo');
		$data['entry_product_add_emailinfo'] = $this->language->get('entry_product_add_emailinfo');
		$data['wkentry_pimagesizeinfo'] = $this->language->get('wkentry_pimagesizeinfo');
		$data['entry_download_sizeinfo'] = $this->language->get('entry_download_sizeinfo');
		$data['entry_image_exinfo'] = $this->language->get('entry_image_exinfo');		
		$data['entry_download_exinfo'] = $this->language->get('entry_download_exinfo');
		$data['entry_alowed_product_columnsinfo'] = $this->language->get('entry_alowed_product_columnsinfo');
		$data['entry_product_reapprove'] = $this->language->get('entry_product_reapprove');
		$data['entry_product_reapproveinfo'] = $this->language->get('entry_product_reapproveinfo');
		$data['entry_alowed_product_tabsinfo'] = $this->language->get('entry_alowed_product_tabsinfo');
		$data['entry_hide_seller_emailinfo'] = $this->language->get('entry_hide_seller_emailinfo');
		$data['entry_commission_addinfo'] = $this->language->get('entry_commission_addinfo');
		$data['entry_becomepartnerinfo'] = $this->language->get('entry_becomepartnerinfo');				
		$data['entry_addseomoreinfo'] = $this->language->get('entry_addseomoreinfo');				
		$data['entry_selectall'] = $this->language->get('entry_selectall');				
		$data['entry_deselectall'] = $this->language->get('entry_deselectall');				
		$data['entry_store'] = $this->language->get('entry_store');				
		$data['entry_route'] = $this->language->get('entry_route');				
		$data['entry_sellinfo'] = $this->language->get('entry_sellinfo');		
		$data['entry_text'] = $this->language->get('entry_text');		

		//customerpartnerlanguage
		$data['wkentry_sellh'] = $this->language->get('wkentry_sellh');
		$data['wkentry_sellb'] = $this->language->get('wkentry_sellb');
		$data['wkentry_selld'] = $this->language->get('wkentry_selld');
		$data['text_tab_title'] = $this->language->get('text_tab_title');
		$data['wkentry_show_partner'] = $this->language->get('wkentry_show_partner');
		$data['wkentry_show_products'] = $this->language->get('wkentry_show_products');
		$data['wkentry_yes'] = $this->language->get('wkentry_yes');
		$data['wkentry_no'] = $this->language->get('wkentry_no');
		$data['wkentry_add_tab'] = $this->language->get('wkentry_add_tab');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_info'] = $this->language->get('text_info');		
		$data['text_status'] = $this->language->get('text_status');

		//profile tab
		$data['text_info_profile'] = $this->language->get('text_info_profile');
		$data['entry_alowed_profile_columns'] = $this->language->get('entry_alowed_profile_columns');
		$data['entry_alowed_profile_columnsinfo'] = $this->language->get('entry_alowed_profile_columnsinfo');

		// mod config tab
		$data['entry_allowed_account_menu'] = $this->language->get('entry_allowed_account_menu');
		$data['entry_allowed_account_menuinfo'] = $this->language->get('entry_allowed_account_menuinfo');
		$data['entry_account_menu_sequence'] = $this->language->get('entry_account_menu_sequence');
		$data['entry_account_menu_sequenceinfo'] = $this->language->get('entry_account_menu_sequenceinfo');
		$data['entry_product_name_display'] = $this->language->get('entry_product_name_display');
		$data['entry_product_name_displayinfo'] = $this->language->get('entry_product_name_displayinfo');
		$data['entry_product_show_seller_product'] = $this->language->get('entry_product_show_seller_product');
		$data['entry_product_show_seller_productinfo'] = $this->language->get('entry_product_show_seller_productinfo');
		$data['entry_product_image_display'] = $this->language->get('entry_product_image_display');
		$data['entry_product_image_displayinfo'] = $this->language->get('entry_product_image_displayinfo');

		//mail tab
		$data['text_info_mail'] = $this->language->get('text_info_mail');
		$data['entry_mail_partner_admin'] = $this->language->get('entry_mail_partner_admin');
		$data['entry_mail_partner_request'] = $this->language->get('entry_mail_partner_request');
		$data['entry_mail_partner_request_info'] = $this->language->get('entry_mail_partner_request_info');
		$data['entry_mail_product_admin'] = $this->language->get('entry_mail_product_admin');
		$data['entry_mail_product_request'] = $this->language->get('entry_mail_product_request');
		$data['entry_mail_product_request_info'] = $this->language->get('entry_mail_product_request_info');
		$data['entry_mail_transaction'] = $this->language->get('entry_mail_transaction');
		$data['entry_mail_transaction_info'] = $this->language->get('entry_mail_transaction_info');
		$data['entry_mail_order'] = $this->language->get('entry_mail_order');
		$data['entry_mail_order_info'] = $this->language->get('entry_mail_order_info');
		$data['entry_mail_cutomer_to_seller'] = $this->language->get('entry_mail_cutomer_to_seller');
		$data['entry_mail_seller_to_admin'] = $this->language->get('entry_mail_seller_to_admin');
		$data['entry_mail_partner_approve'] = $this->language->get('entry_mail_partner_approve');
		$data['entry_mail_product_approve'] = $this->language->get('entry_mail_product_approve');
		$data['entry_seller_shipping_method'] = $this->language->get('entry_seller_shipping_method');
		$data['entry_seller_shipping_methodinfo'] = $this->language->get('entry_seller_shipping_methodinfo');
		$data['entry_complete_order_status'] = $this->language->get('entry_complete_order_status');
		$data['entry_complete_order_statusinfo'] = $this->language->get('entry_complete_order_statusinfo');
		$data['entry_divide_shipping_cost'] = $this->language->get('entry_divide_shipping_cost');
		$data['entry_divide_shipping_costinfo'] = $this->language->get('entry_divide_shipping_costinfo');
		$data['entry_mail_edit_product_seller'] = $this->language->get('entry_mail_edit_product_seller');
		$data['entry_mail_edit_product_sellerinfo'] = $this->language->get('entry_mail_edit_product_sellerinfo');
		$data['entry_mail_edit_product_admin'] = $this->language->get('entry_mail_edit_product_admin');
		$data['entry_mail_edit_product_admininfo'] = $this->language->get('entry_mail_edit_product_admininfo');

		$data['entry_remove'] = $this->language->get('entry_remove');
		$data['entry_profile_tab'] = $this->language->get('entry_profile_tab');
		$data['entry_store_tab'] = $this->language->get('entry_store_tab');
		$data['entry_collection_tab'] = $this->language->get('entry_collection_tab');
		$data['entry_review_tab'] = $this->language->get('entry_review_tab');
		$data['entry_product_review_tab'] = $this->language->get('entry_product_review_tab');
		$data['entry_location_tab'] = $this->language->get('entry_location_tab');
		$data['entry_customer_seller_profile'] = $this->language->get('entry_customer_seller_profile');
		$data['entry_default_image'] = $this->language->get('entry_default_image');
		$data['entry_default_imageinfo'] = $this->language->get('entry_default_imageinfo');
		$data['entry_mail_keywords'] = $this->language->get('entry_mail_keywords');

		$data['text_in_kbs'] = $this->language->get('text_in_kbs');

		$config_data = array(

				'marketplace_status',

				//general
				'marketplace_mailtoseller',
				'marketplace_mailadmincustomercontactseller',
				'marketplace_customercontactseller',	
				'marketplace_hideselleremail',
				'marketplace_adminmail',								
				'marketplace_productapprov',
				'marketplace_partnerapprov',
				'marketplace_sellerorderstatus',
				'marketplace_available_order_status',
				'marketplace_order_status_sequence',
				'marketplace_becomepartnerregistration',
				'marketplace_allowed_shipping_method',
				'marketplace_complete_order_status',
				'marketplace_divide_shipping',
				'marketplace_default_image_name',

				//product tab
				'marketplace_allowedproductcolumn',
				'marketplace_allowedproducttabs',
				'marketplace_imagesize',	
				'marketplace_noofimages',
				'marketplace_imageex',
				'marketplace_noofdownload',	
				'marketplace_downloadex',
				'marketplace_downloadsize',
				'marketplace_productaddemail',
				'marketplace_product_reapprove',
				'marketplace_sellerdeleteproduct',

				//seo tab
				'marketplace_useseo',
				'marketplace_wksell',
				'marketplace_productlist',
				'marketplace_profile',
				'marketplace_addproduct',
				'marketplace_add_shipping_mod',
				'marketplace_dashboard',
				'marketplace_orderlist',
				'marketplace_order_info',
				'marketplace_soldlist',
				'marketplace_soldinvoice',
				'marketplace_editproduct',
				'marketplace_storeprofile',
				'marketplace_collection',
				'marketplace_feedback',
				'marketplace_store',
				'marketplace_downloads',
				'marketplace_transactions',
				//sef tab 2
				'marketplace_SefUrlspath',
				'marketplace_SefUrlsvalue',
				// sef product tab
				'marketplace_product_seo_name',
				'marketplace_product_seo_format',
				'marketplace_product_seo_default_name',
				'marketplace_product_seo_product_name',
				'marketplace_product_seo_page_ext',

				//commission
				'marketplace_boxcommission',
				'marketplace_commission_add',
				'marketplace_commission',
				'marketplace_commissionworkedon',

				//sell tab				
				'marketplace_sellheader',
				'marketplace_sellbuttontitle',
				'marketplace_selldescription',
				'marketplace_showpartners',
				'marketplace_showproducts',
				'marketplace_tab',	

				//profile tab
				'marketplace_allowedprofilecolumn',
				'marketplace_allowed_public_seller_profile',
				// 'marketplace_profile_profile',
				'marketplace_profile_store',
				'marketplace_profile_collection',
				'marketplace_profile_review',
				'marketplace_profile_product_review',
				'marketplace_profile_location',

				// module Configuration
				'marketplace_allowed_account_menu',
				'marketplace_account_menu_sequence',
				'marketplace_product_name_display',
				'marketplace_product_show_seller_product',
				'marketplace_product_image_display',

				//mail tab
				'marketplace_mail_keywords',
				'marketplace_mail_partner_request',
				'marketplace_mail_product_request',
				'marketplace_mail_transaction',
				'marketplace_mail_order',
				'marketplace_mail_partner_admin',
				'marketplace_mail_product_admin',
				'marketplace_mail_cutomer_to_seller',
				'marketplace_mail_seller_to_admin',
				'marketplace_mail_partner_approve',
				'marketplace_mail_product_approve',
				'marketplace_mail_admin_on_edit',
				'marketplace_mail_seller_on_edit',

		);

		foreach ($config_data as $conf) {
			if (isset($this->request->post[$conf])) {
				$data[$conf] = $this->request->post[$conf];
			} else {
				$data[$conf] = $this->config->get($conf);
			}
		}
		
		$this->load->model('tool/image');

		if($this->config->get('marketplace_default_image_name')) {
			$data['marketplace_default_image_name'] = $this->config->get('marketplace_default_image_name');
			$data['marketplace_default_image'] = $this->model_tool_image->resize($this->config->get('marketplace_default_image_name'), 90, 90);
		}

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if(isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/marketplace', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('module/marketplace', 'token=' . $this->session->data['token'], 'SSL');
		$data['resinstall'] = $this->url->link('module/marketplace/resinstall&token=' . $this->session->data['token'], 'SSL');		
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
				
		$product_table = $this->db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".DB_DATABASE."' AND table_name = '".DB_PREFIX."product'")->rows;
		
		$product_table = array_slice($product_table, 1, -3);

		$data['product_table'] = array();

		foreach($product_table as $key => $value){
			$data['product_table'][] = $value['COLUMN_NAME'];
		}
		
		$data['product_table'][] = 'keyword';

		$data['product_tabs'] = array('special','discount','attribute','links','options','reward','custome-field');
	
		//folder path for SEF urls		
		$this->getdir();
		$data['paths'] = $this->files_array;		

		$data['profile_table'] = array();
		$profile_table = $this->db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".DB_DATABASE."' AND table_name = '".DB_PREFIX."customerpartner_to_customer'")->rows;

		$profile_table = array_slice($profile_table, 3, -1);
		if($profile_table[11]['COLUMN_NAME'] == 'companyname') {
			unset($profile_table[11]);
		}
		foreach($profile_table as $key => $value){
			$data['profile_table'][] = $value['COLUMN_NAME'];
		}

		$data['account_menu'] = array(
			'profile' => $this->language->get('entry_mod_profile'),
			'dashboard' => $this->language->get('entry_mod_dashboard'),
			'orderhistory' => $this->language->get('entry_mod_order'),
			'transaction' => $this->language->get('entry_mod_transaction'),
			'productlist' => $this->language->get('entry_mod_productlist'),
			'addproduct' => $this->language->get('entry_mod_addproduct'),
			'downloads' => $this->language->get('entry_mod_downloads'),
			'manageshipping' => $this->language->get('entry_mod_manageshipping'),
			'asktoadmin' => $this->language->get('entry_mod_asktoadmin'),
		);

		$data['publicSellerProfile'] = array(
			'store' => $this->language->get('entry_store_tab'),
			'collection' => $this->language->get('entry_collection_tab'),
			'review' => $this->language->get('entry_review_tab'),
			'productReview' => $this->language->get('entry_product_review_tab'),
			'location' => $this->language->get('entry_location_tab'),
		);


			/*
			Membership code
			Add memebership option to existing array
			 */
			if($this->config->get('wk_seller_group_status')) {
	        	$data['wk_seller_group_status'] = true;
	        	$data['account_menu']['membership'] = $this->language->get('entry_mod_membership');
	        	$data['marketplace_account_menu_sequence']['membership'] = $this->language->get('entry_mod_membership');
	        } else {
	        	$data['wk_seller_group_status'] = false;
	        	if(isset($data['marketplace_account_menu_sequence']['membership'])) {
	        		unset($data['marketplace_account_menu_sequence']['membership']);
	        	}
	        	if(isset($data['marketplace_account_menu_sequence']['membership'])) {
	        		unset($data['marketplace_account_menu_sequence']['membership']);
	        	}
	        }
	        /*
	        end here
	         */
 			
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['config_language_id'] = $this->config->get('config_language_id');

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('extension/extension');

		$shipping_methods = $this->model_extension_extension->getInstalled('shipping');
		foreach ($shipping_methods as $key => $shipping_method) {
			$file = glob(DIR_APPLICATION . 'controller/shipping/'.$shipping_method.'.php');
			if($file){
				$this->load->language('shipping/'.$shipping_method);
				$data['shipping_methods'][] = array(
					'code' => $shipping_method,
					'name' => $this->language->get('heading_title'),
				);
			}
		}

		//get total mail
		$this->load->model('customerpartner/mail');
		$data['mails'] = $this->model_customerpartner_mail->gettotal();

		$data['header'] = $this->load->controller('common/header');		
		$data['footer'] = $this->load->controller('common/footer');	
		$data['column_left'] = $this->load->controller('common/column_left');
				
		$this->response->setOutput($this->load->view('module/marketplace.tpl',$data));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/marketplace')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	public function resinstall() {
		$this->load->language('module/marketplace');

		if($this->validate()){			
			$this->load->model('customerpartner/partner');
			$this->model_customerpartner_partner->removeCustomerpartnerTable();
		}

		$this->session->data['success'] = $this->language->get('text_success');
		$this->response->redirect($this->url->link('module/marketplace', 'token=' . $this->session->data['token'], 'SSL'));		
	}
}
?>