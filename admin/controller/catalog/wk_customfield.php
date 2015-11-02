<?php 
	class ControllerCatalogWkcustomfield extends Controller{
		public function index(){
               	$this->language->load("catalog/wk_customfield");

               	$data['token']  = $this->session->data['token'];
               	$data['entry_option_value']  = $this->language->get('entry_option_value');
               	
                $this->load->model("wkcustomfield/wkcustomfield");
				$wkcustomFields = array();
				$data['wkcustomFields'] = $this->model_wkcustomfield_wkcustomfield->getOptionList();
					if(isset($this->request->get['product_id'])){
						$data['wkPreCustomFields'] = array();
						$wkPreCustomFieldOptions = array();
						$wkPreCustomFields = $this->model_wkcustomfield_wkcustomfield->getProductFields($this->request->get['product_id']);
                                foreach($wkPreCustomFields as $field){
                                    $wkPreCustomFieldOptions = $this->model_wkcustomfield_wkcustomfield->getOptions($field['fieldId']);
                                            if($field['fieldType'] == 'select' || $field['fieldType'] == 'checkbox' || $field['fieldType'] == 'radio' ){
                                                $wkPreCustomProductFieldOptions = $this->model_wkcustomfield_wkcustomfield->getProductFieldOptions($this->request->get['product_id'],$field['fieldId'],$field['id']);
                                            }else{
                                                $wkPreCustomProductFieldOptions = $this->model_wkcustomfield_wkcustomfield->getProductFieldOptionValue($this->request->get['product_id'],$field['fieldId'],$field['id']);
                                            }

                                            $data['wkPreCustomFields'][] = array(
                                                'fieldId'       => $field['fieldId'],
                                                'fieldName'     => $field['fieldName'],
                                                'fieldType'     => $field['fieldType'],
                                                'fieldDes'      => $field['fieldDescription'],
                                                'productFieldId'      => $field['id'],
                                                'isRequired'    => $field['isRequired'],
                                                'fieldOptions'  => $wkPreCustomProductFieldOptions,
                                                'preFieldOptions' => $wkPreCustomFieldOptions,
                                            );
                                }
                    }
			$this->response->setOutput($this->load->view('catalog/wk_customfield.tpl', $data));
		}
	}
?>