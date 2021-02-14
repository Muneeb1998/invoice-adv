<?php

namespace App\Libraries;

/**
 * @author  Junaid Ahmed Khan (https://www.softwebz.com)
 */
class FormBuilder
{
	public $_ci;
	private $asteriskSpan = '<span class="asterisk">*</span>';
	private $aConf;

	public function __construct($_ci, array $aConf = [])
	{
		$this->_ci = $_ci;
		$this->aConf = (object) array_replace_recursive([
			'formDefaults' => 'front'
		], array_filter($aConf));
	}

	public function getForm($a, bool $inputsOnly = false, array $aExtra = [])
	{
		helper('form');
		$dOpt = array(
			'aForm' => array(
				'sUrl' => '',
				'aAttr' => '',
				'aInputsWrapper' => array(
					'pfx' => '',
					'sfx' => '',
				)
			)
		);
		$aDefCheckboxHtml = array();
		$aDefRadioHtml = array();
		$aDefFileHtml = array();
		$aDefTextareaHtml = array();
		$aDefButtonHtml = array();
		$aDefInputHtml = array();
		$sHtml = '';
		if (is_string($a)) {
			$a = call_user_func(array($this, $a), $aExtra);
		}
		if (!$inputsOnly) {
			$oOpt = (object) array_replace_recursive($dOpt, array_filter($a['aOpt']));
			$sHtml .= form_open($oOpt->aForm['sUrl'], $oOpt->aForm['aAttr']);
			$sHtml .= $oOpt->aForm['aInputsWrapper']['pfx'];
		}
		foreach ($a['aInputs'] as $k => $v) {
			$sTmpHtml = '';
			$aWrapper = (@$v['aWrapper'] ? @$v['aWrapper'] : $this->getDefaults('wrapper', $v['type']));
			$v = array_replace_recursive($this->getDefaults('attr', $v['type']), $v);
			if (@$v['adiClass']) {
				$v['class'] = $v['class'] . ' ' . $v['adiClass'];
				unset($v['adiClass']);
			}
			$sLabel = @$v['label'];
			$aLabelAttr = (@$v['aLabelAttr'] ? $v['aLabelAttr'] : array());
			$crequired = @$v['crequired'];
			unset($v['label']);
			unset($v['aLabelAttr']);
			unset($v['aWrapper']);
			unset($v['crequired']);
			if (!isset($v['wrapperAdiClass'])) {
				$v['wrapperAdiClass'] = '';
			}
			if (!isset($v['wrapperId'])) {
				$v['wrapperId'] = '';
			} else {
				$v['wrapperId'] = 'id=' . $v['wrapperId'];
			}
			if (!isset($v['placeholder']) && $sLabel) {
				$v['placeholder'] = $sLabel;
			} elseif (isset($v['placeholder']) && $v['placeholder'] == false) {
				unset($v['placeholder']);
			}
			$sTmpHtml = str_replace('{wrapperAdiClass}', $v['wrapperAdiClass'], $aWrapper['pfx']);
			$sTmpHtml = str_replace('{wrapperId}', $v['wrapperId'], $sTmpHtml);
			unset($v['wrapperAdiClass']);
			unset($v['wrapperId']);
			if (@$v['name'] && !@$v['id']) {
				$v['id'] = $v['name'];
			}
			switch ($v['type']) {
				case 'select':
					$tv = $v; //tmp values;
					// print_r($tv);
					// echo '<br>';
					unset($tv['class']);
					unset($tv['type']);
					unset($tv['name']);
					unset($tv['crequired']);
					unset($tv['adiClass']);
					unset($tv['aOpt']);
					unset($tv['aSelOpt']);
					unset($tv['id']);
					// print_r($tv);
					array_walk(
						$tv,
						function (&$v, $k) {
							$v = $k . '="' . $v . '"';
						}
					);
					$sTmpHtml .= form_dropdown($v['name'], $v['aOpt'], @$v['aSelOpt'], 'id="' . $v['id'] . '" class="' . $v['class'] . '" ' . implode(' ', $tv));
					break;
				case 'checkbox':
					$sTmpHtml .= form_checkbox($v);
					break;
				case 'radio':
					$sTmpHtml .= form_radio($v);
					break;
				case 'aHidden':
					foreach ($v['aFields'] as $k2 => $v2) {
						$v2['type'] = 'hidden';
						$v2['id'] = (@$v2['id'] ? $v2['id'] : $v2['name']);
						$sTmpHtml .= form_input($v2);
					}
					break;
				case 'file':
					$sTmpHtml .= form_upload($v);
					break;
				case 'textarea':
					$sTmpHtml .= form_textarea($v);
					break;
				case 'password':
					$sTmpHtml .= form_password($v);
					break;
				case 'inputsGroup':
					$sTmpHtml .= (@$v['groupPrefix'] ? $v['groupPrefix'] : '');
					$sTmpHtml .= $this->getForm($v, true);
					$sTmpHtml .= (@$v['groupSuffix'] ? $v['groupSuffix'] : '');
					break;
				case 'html':
					$sTmpHtml .= $v['html'];
					break;
				case 'button':
				case 'reset':
				case 'submit':
					$sTmpHtml .= form_button($v);
					break;
				default:
					$sTmpHtml .= form_input($v);
					break;
			}
			$sTmpHtml .= $aWrapper['sfx'];
			if ($sLabel && $sLabel != -1) {
				$sTmpHtml = str_replace('{labelText}', $sLabel, $sTmpHtml);
				$sLabel = form_label(
					$sLabel . (@$crequired ? $this->asteriskSpan : ''),
					(@$v['id'] ? $v['id'] : $v['name']),
					$aLabelAttr
				);
				$sTmpHtml = str_replace('{label}', $sLabel, $sTmpHtml);
			} else {
				$sTmpHtml = str_replace('{label}', '', $sTmpHtml);
			}
			if (isset($v['id'])) {
				$sTmpHtml = str_replace('{id}', $v['id'], $sTmpHtml);
			}
			$sHtml .= $sTmpHtml;
		}
		if (!$inputsOnly) {
			$sHtml .= $oOpt->aForm['aInputsWrapper']['sfx'];
			$sHtml .= form_close();
		}
		return $sHtml;
	}

	private function getDefaults(string $k, string $type, bool $attr = false)
	{
		switch ($this->aConf->formDefaults) {
			case 'admin':
				return $this->getDefaultsAdmin($k, $type, $attr);
				break;
			default:
				return $this->getDefaultsFront($k, $type, $attr);
				break;
		}
	}

	private function getDefaultsFront(string $k, string $type, bool $attr = false)
	{
		$a = array(
			'attr' => array(),
			'wrapper' => array()
		);

		$aDefInputAttr = array('class' => 'form-control');
		$aDefBtnAttr = array('class' => 'btn');
		$aDeNoAttr = array();

		$a['attr']['text'] = $aDefInputAttr;
		$a['attr']['number'] = $aDefInputAttr;
		$a['attr']['date'] = $aDefInputAttr;
		$a['attr']['tel'] = $aDefInputAttr;
		$a['attr']['email'] = $aDefInputAttr;
		$a['attr']['select'] = $aDefInputAttr;
		$a['attr']['checkbox'] = ['class' => ''];
		$a['attr']['radio'] = $aDefInputAttr;
		$a['attr']['file'] = $aDefInputAttr;
		$a['attr']['textarea'] = $aDefInputAttr;
		$a['attr']['password'] = $aDefInputAttr;
		$a['attr']['button'] = $aDefBtnAttr;
		$a['attr']['submit'] = $aDefBtnAttr;
		$a['attr']['reset'] = $aDefBtnAttr;
		$a['attr']['aHidden'] = $aDefBtnAttr;
		$a['attr']['inputsGroup'] = $aDefBtnAttr;
		$a['attr']['html'] = $aDeNoAttr;

		$aDefInputWrap = array(
			'pfx' => '<div class="{wrapperAdiClass} form-group" {wrapperId}><div class="col-xs-12 p-0 mb-5">{label}</div><div class="col-xs-12 p-0">',
			'sfx' => '<p class="in-error"></p></div></div>'
		);
		$aDefSelectWrap =  [
			'pfx' => '<div class="{wrapperAdiClass} form-group" {wrapperId}><div class="col-xs-12 p-0 mb-5">{label}</div><div class="col-xs-12 p-0">',
			'sfx' => '<p class="in-error"></p></div></div>'
		];
		$aDefBtnWrap = array(
			'pfx' => '<div class="form-group">',
			'sfx' => '</div>'
		);
		$aDefNoWrap = array(
			'pfx' => '',
			'sfx' => ''
		);
		$a['wrapper']['text'] = $aDefInputWrap;
		$a['wrapper']['number'] = $aDefInputWrap;
		$a['wrapper']['date'] = $aDefInputWrap;
		$a['wrapper']['tel'] = $aDefInputWrap;
		$a['wrapper']['email'] = $aDefInputWrap;
		$a['wrapper']['select'] = $aDefSelectWrap;
		$a['wrapper']['checkbox'] = [
			'pfx' => '<div class="{wrapperAdiClass} form-group"><label class="checkbox-inline">',
			'sfx' => '{labelText}<span class="checkmark"></span></label></div>'
		];
		$a['wrapper']['radio'] = $aDefInputWrap;
		$a['wrapper']['file'] = $aDefInputWrap;
		$a['wrapper']['textarea'] = $aDefInputWrap;
		$a['wrapper']['password'] = $aDefInputWrap;
		$a['wrapper']['button'] = $aDefBtnWrap;
		$a['wrapper']['submit'] = $aDefBtnWrap;
		$a['wrapper']['reset'] = $aDefBtnWrap;
		$a['wrapper']['aHidden'] = $aDefNoWrap;
		$a['wrapper']['inputsGroup'] = $aDefNoWrap;
		$a['wrapper']['html'] = $aDefNoWrap;
		return $a[$k][$type];
	}

	private function getDefaultsAdmin(string $k, string $type, bool $attr = false)
	{
		$a = array(
			'attr' => array(),
			'wrapper' => array()
		);

		$aDefInputAttr = array('class' => 'form-control');
		$aDefBtnAttr = array('class' => 'btn');
		$aDeNoAttr = array();

		$a['attr']['text'] = $aDefInputAttr;
		$a['attr']['number'] = $aDefInputAttr;
		$a['attr']['date'] = $aDefInputAttr;
		$a['attr']['tel'] = $aDefInputAttr;
		$a['attr']['email'] = $aDefInputAttr;
		$a['attr']['select'] = $aDefInputAttr;
		$a['attr']['checkbox'] = $aDefInputAttr;
		$a['attr']['radio'] = $aDefInputAttr;
		$a['attr']['file'] = $aDefInputAttr;
		$a['attr']['textarea'] = $aDefInputAttr;
		$a['attr']['password'] = $aDefInputAttr;
		$a['attr']['button'] = $aDefBtnAttr;
		$a['attr']['submit'] = $aDefBtnAttr;
		$a['attr']['reset'] = $aDefBtnAttr;
		$a['attr']['aHidden'] = $aDefBtnAttr;
		$a['attr']['inputsGroup'] = $aDefBtnAttr;
		$a['attr']['html'] = $aDeNoAttr;

		$aDefInputWrap = array(
			'pfx' => '<div class="form-group">{label}<div class="form-control-wrap">',
			'sfx' => '</div></div>'
		);
		$aDefBtnWrap = array(
			'pfx' => '<div class="form-group">',
			'sfx' => '</div>'
		);
		$aDefNoWrap = array(
			'pfx' => '',
			'sfx' => ''
		);
		$a['wrapper']['text'] = $aDefInputWrap;
		$a['wrapper']['number'] = $aDefInputWrap;
		$a['wrapper']['date'] = $aDefInputWrap;
		$a['wrapper']['tel'] = $aDefInputWrap;
		$a['wrapper']['email'] = $aDefInputWrap;
		$a['wrapper']['select'] = $aDefInputWrap;
		$a['wrapper']['checkbox'] = $aDefInputWrap;
		$a['wrapper']['radio'] = $aDefInputWrap;
		$a['wrapper']['file'] = $aDefInputWrap;
		$a['wrapper']['textarea'] = $aDefInputWrap;
		$a['wrapper']['password'] = $aDefInputWrap;
		$a['wrapper']['button'] = $aDefBtnWrap;
		$a['wrapper']['submit'] = $aDefBtnWrap;
		$a['wrapper']['reset'] = $aDefBtnWrap;
		$a['wrapper']['aHidden'] = $aDefNoWrap;
		$a['wrapper']['inputsGroup'] = $aDefNoWrap;
		$a['wrapper']['html'] = $aDefNoWrap;
		return $a[$k][$type];
	}

	// Custom Defined Forms
	private function login(array $aExtra = [])
	{
		return [
			'aOpt' => [
				'aForm' => [
					'sUrl' 	=> '',
					'aAttr' => [
						'class' 	=> 'needs-validation',
						'method' 	=> 'post',
						'novalidate' => ''
					],
				]
			],
			'aInputs' => [
				[
					'label' => 'Email',
					'type'  => 'email',
					'name'	=> 'email',
					'crequired' => 'true',
					'placeholder' => 'Required',
					'adiClass' => 'min_height50',
					'required' => true,
					'aWrapper' => [
						// 'pfx' => '<div class="form-label-group">',
						// 'sfx' => '{label}<div class="valid-feedback">Passed<ion-icon name="checkmark-circle"></ion-icon></div><div class="invalid-feedback">Please enter Email</div></div>'
					]
				],
				[
					'label' => 'Password',
					'type'  => 'password',
					'name'	=> 'password',
					'crequired' => 'true',
					'placeholder' => 'Required',
					'adiClass' => 'min_height50',
					'required' => true,
					'aWrapper' => [
						// 'pfx' => '<div class="input-form"><div class="form-label-group eye">',
						// 'sfx' => '{label}<div class="valid-feedback">Passed<ion-icon name="checkmark-circle"></ion-icon></div><div class="invalid-feedback">Please enter Password</div><i class="ion ion-ios-eye"></i></div></div>'
					]
				],
				[
					'type' => 'html',
					'html' => '<a href="' . base_url('forgot_password') . '" class="forgotpass">Forgot your password?</a>'
				],
				[
					'type'  => 'submit',
					'content' => 'Log In',
					'class' => 'btn btn-primary save-btn',
					'aWrapper' => [
						'pfx' => '<div class="form-group">',
						'sfx' => '</div>'
					]
				],
				// [
				// 	'type' => 'checkbox',
				// 	'adiClass' => 'custom-control-input',
				// 	'checked' => true,
				// 	'name' => 'light',
				// 	'aWrapper' => [
				// 		'pfx' => '<div class="rem-pass"><label class="custom-control custom-checkbox radiobt">',
				// 		'sfx' => '<span class="custom-control-indicator"></span></label><span class="sp3">Remember Me</span><div class="clear"></div></div>'
				// 	]
				// ],
				[
					'type' => 'html',
					'html' => '<div class="pravacy"><p>Copyright Â© 2020 Connects. All rights reserved.</p></div>'
				]
			]
		];
	}
	private function addClient(array $aExtra = [])
	{
		return [
			'aOpt' => [
				'aForm' => [
					'sUrl' 	=> '',
					'aAttr' => [
						'class' 	=> 'col s12',
						'method' 	=> 'post',
						'novalidate' => '',
						'id' => 'client'
					],
				]
			],
			'aInputs' => [
				[
					'type' => 'inputsGroup',
					'groupPrefix' => '<div class="clearfix">',
					'groupSuffix' => '</div><div class="row" ><div class="input-field col s12"><input type="hidden" name="id" value=""><button class="btn btn-primary m-t-15 waves-effect" style="float: right;margin-right: 12px;"><div class="preloader pl-size-xs d-none"><div class="spinner-layer pl-red-grey"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div>Add Client</button></div></div>',
					'aInputs' => [
						// Personal Information
						[
							'type' => 'html',
							'html' => '<h3 class="group-titile col-sm-12">Personal Information</h3>',
						],
						[
							'label' => 'Client Name',
							'type'  => 'text',
							'name'	=> 'name',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Company',
							'type'  => 'text',
							'name'	=> 'company',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Street Address 1',
							'type'  => 'text',
							'name'	=> 'addr1',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Email Address',
							'type'  => 'email',
							'name'	=> 'email',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Street Address 2',
							'type'  => 'text',
							'name'	=> 'addr2',
							// 'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							// 'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Web Address',
							'type'  => 'text',
							'name'	=> 'web_addr',
							// 'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							// 'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'City',
							'type'  => 'text',
							'name'	=> 'city',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Phone Number',
							'type'  => 'text',
							'name'	=> 'phone_contact',
							// 'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							// 'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'State',
							'type'  => 'text',
							'name'	=> 'state',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Mobile Number',
							'type'  => 'text',
							'name'	=> 'mobile_contact',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Zip Code',
							'type'  => 'number',
							'name'	=> 'zip',
							'adiClass' => 'form-control',
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Fax Number',
							'type'  => 'text',
							'name'	=> 'fax',
							'adiClass' => 'form-control',
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'type' => 'select',
							'label' => 'Country',
							'name' => 'country',
							'adiClass' => 'country w-100',
							'aOpt' => [
								'' => '---Select Country---',
							],
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
					]
				]
			]
		];
	}
	private function addAdmin(array $aExtra = [])
	{
		return [
			'aOpt' => [
				'aForm' => [
					'sUrl' 	=> '',
					'aAttr' => [
						'class' 	=> 'col s12',
						'method' 	=> 'post',
						'novalidate' => '',
						'id' => 'admin'
					],
				]
			],
			'aInputs' => [
				[
					'type' => 'inputsGroup',
					'groupPrefix' => '<div class="row clearfix">',
					'groupSuffix' => '</div><div class="row" style="float: right;margin-right: 10px;"><div class="input-field col s12"><input type="hidden" name="id" value=""><button class="btn btn-primary m-t-15 waves-effect"><div class="preloader pl-size-xs d-none"><div class="spinner-layer pl-red-grey"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div>Add Admin</button></div></div>',
					'aInputs' => [
						[
							'label' => 'Name',
							'type'  => 'text',
							'name'	=> 'name',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="col-sm-6"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div>'
							],
						],
						[
							'label' => 'Email',
							'type'  => 'email',
							'name'	=> 'email',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="col-sm-6"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div>'
							],
						],
						[
							'label' => 'Password',
							'type'  => 'text',
							'name'	=> 'password',
							'crequired' => 'true',
							// 'adiClass' => 'form-control',
							'required' => true,
							'aWrapper' => [
								'pfx' => ' <div class="col-sm-6">',
								'sfx' => '</div>'
							],
						],
						// [
						// 	'type' => 'select',
						// 	'label' => 'Role',
						// 	'name' => 'role',
						// 	'required' => true,
						// 	'crequired' => true,
						// 	'adiClass' => 'form-control',
						// 	'aOpt' => [
						// 		'a' => 'Admin',
						// 		'sa' => 'Super Admin',
						// 	],
						// 	'aWrapper' => [
						// 		'pfx' => '<div class="col-sm-6"><div class="col-sm-6">{label}</div><div class="col-sm-6"><div class=""><div class="form-line">',
						// 		'sfx' => '</div></div></div></div>'
						// 	],
						// ],
					]
				]
			]
		];
	}
	private function info(array $aExtra = [])
	{
		return [
			'aOpt' => [
				'aForm' => [
					'sUrl' 	=> '',
					'aAttr' => [
						'class' 	=> 'col s12',
						'method' 	=> 'post',
						'novalidate' => '',
						'id' => 'info'
					],
				]
			],
			'aInputs' => [
				[
					'type' => 'inputsGroup',
					'groupPrefix' => '<div class="clearfix">',
					'groupSuffix' => '</div><div class="row" ><div class="input-field col s12"><input type="hidden" name="id" value=""><button class="btn btn-primary m-t-15 waves-effect" style="float: right;margin-right: 12px;"><div class="preloader pl-size-xs d-none"><div class="spinner-layer pl-red-grey"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div>Save Settings</button></div></div>',
					'aInputs' => [
						// Personal Information
						[
							'type' => 'html',
							'html' => '<h3 class="group-titile col-sm-12">Personal Information</h3>',
						],
						[
							'label' => 'Company',
							'type'  => 'text',
							'name'	=> 'company',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'PayPal Email',
							'type'  => 'text',
							'name'	=> 'paypal_email',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '<div class="panel panel-primary" style="margin: 0;margin-top: 5px;">
								<a style="text-decoration: none;" class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#collapseTwo_1" aria-expanded="false" aria-controls="collapseTwo_1">
								<div class="panel-heading" role="tab" id="headingTwo_1" style="padding: 6px;background: var(--siteBgColor);color: white;"
									<h4 class="panel-title">
											PayPal Instructions
										</h4>
									</div>
								</a>
								<div id="collapseTwo_1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_1">
									<div class="panel-body">
									login papal account
										<ol>
										<li> Click on the setting button
										<li> Click on seller tools
										<li> Click on the update button of instant payment notifications
										<li> Click on the edit setting button
										<li> ' . site_url("paypal/notify.php") . ' write it in notify URL input and enable IPN message
										<ol>
									</div>
								</div>
							</div></div></div></div></div>'
							],
						],
						[
							'label' => 'Mobile Number',
							'type'  => 'text',
							'name'	=> 'mobile',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Invoice No Pattern',
							'type'  => 'text',
							'name'	=> 'invoice_pattern',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Footer Email',
							'type'  => 'email',
							'name'	=> 'footer_email',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Invoice Logo',
							'type'  => 'file',
							'name'	=> 'invoice_logo',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
                        	[
							'label' => 'Logo Size',
							'type'  => 'number',
							'name'	=> 'logo_size',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						// Address
						[
							'type' => 'html',
							'html' => '<h3 class="group-titile col-sm-12">Address Information</h3>',
						],
						[
							'label' => 'Street Address 1',
							'type'  => 'text',
							'name'	=> 'street1',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Street Address 2',
							'type'  => 'text',
							'name'	=> 'street2',
							// 'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							// 'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'City',
							'type'  => 'text',
							'name'	=> 'city',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'State',
							'type'  => 'text',
							'name'	=> 'state',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Zip Code',
							'type'  => 'number',
							'name'	=> 'zip_code',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'type' => 'select',
							'label' => 'Country',
							'name' => 'country',
							'required' => true,
							'crequired' => true,
							'adiClass' => 'country w-100',
							'aOpt' => [
								'' => '---Select Country---',
							],
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
					]
				]
			]
		];
	}
	private function password(array $aExtra = [])
	{
		return [
			'aOpt' => [
				'aForm' => [
					'sUrl' 	=> '',
					'aAttr' => [
						'class' 	=> 'col s12',
						'method' 	=> 'post',
						'novalidate' => '',
						'id' => 'chnagepassword'
					],
				]
			],
			'aInputs' => [
				[
					'type' => 'inputsGroup',
					'groupPrefix' => '<div class="row clearfix">',
					'groupSuffix' => '</div><div class="row" style="float: right;margin-right: 10px;><div class="input-field col s12"><input type="hidden" name="id" value=""><button class="btn btn-primary m-t-15 waves-effect"><div class="preloader pl-size-xs d-none"><div class="spinner-layer pl-red-grey"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div>Change Passoword</button></div></div>',
					'aInputs' => [
						[
							'label' => 'Last Password',
							'type'  => 'text',
							'name'	=> 'pass',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="col-sm-6"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div>'
							],
						],
						[
							'label' => 'New Password',
							'type'  => 'text',
							'name'	=> 'npass',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="col-sm-6"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div>'
							],
						],
						[
							'label' => 'Re-type Password',
							'type'  => 'text',
							'name'	=> 'rpass',
							'crequired' => 'true',
							// 'adiClass' => 'form-control',
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="col-sm-6"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div>'
							],
						],
						// [
						// 	'type' => 'select',
						// 	'label' => 'Role',
						// 	'name' => 'role',
						// 	'required' => true,
						// 	'crequired' => true,
						// 	'adiClass' => 'form-control',
						// 	'aOpt' => [
						// 		'a' => 'Admin',
						// 		'sa' => 'Super Admin',
						// 	],
						// 	'aWrapper' => [
						// 		'pfx' => '<div class="col-sm-6"><div class="col-sm-6">{label}</div><div class="col-sm-6"><div class=""><div class="form-line">',
						// 		'sfx' => '</div></div></div></div>'
						// 	],
						// ],
					]
				]
			]
		];
	}
	private function mailSetting(array $aExtra = [])
	{
		return [
			'aOpt' => [
				'aForm' => [
					'sUrl' 	=> '',
					'aAttr' => [
						'class' 	=> 'col s12',
						'method' 	=> 'post',
						'novalidate' => '',
						'id' => 'mailSettings'
					],
				]
			],
			'aInputs' => [
				[
					'type' => 'inputsGroup',
					'groupPrefix' => '<div class="row clearfix">',
					'groupSuffix' => '</div><div class="row" style="float: right;margin-right: 10px;><div class="input-field col s12"><input type="hidden" name="id" value=""><button class="btn btn-primary m-t-15 waves-effect"><div class="preloader pl-size-xs d-none"><div class="spinner-layer pl-red-grey"><div class="circle-clipper left"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div>Save</button></div></div>',
					'aInputs' => [
						// [
						// 	'label' => 'Smtp Host',
						// 	'type'  => 'text',
						// 	'name'	=> 'smtpHost',
						// 	'crequired' => 'true',
						// 	'adiClass' => 'form-control',
                        //     'disabled' => 'disabled',
                        //     'value' => 'smtp.gmail.com',
						// 	// 'aLabelAttr' => ['class' => 'test'],
						// 	'required' => true,
						// 	'aWrapper' => [
						// 		'pfx' => '<div class="m-0 col-sm-6">
						// 		<div class="col-sm-12 m-0">{label}</div>
						// 		<div class="col-sm-12"><div class=""><div class="form-line">',
						// 		'sfx' => '</div></div></div></div>'
						// 	],
						// ],
						// [
						// 	'label' => 'Smtp Username',
						// 	'type'  => 'text',
						// 	'name'	=> 'smtpUsername',
						// 	'crequired' => 'true',
						// 	'adiClass' => 'form-control',
						// 	// 'aLabelAttr' => ['class' => 'test'],
						// 	'required' => true,
						// 	'aWrapper' => [
						// 		'pfx' => '<div class="m-0 col-sm-6">
						// 		<div class="col-sm-12 m-0">{label}</div>
						// 		<div class="col-sm-12"><div class=""><div class="form-line">',
						// 		'sfx' => '</div></div></div></div>'
						// 	],
						// ],
						// [
						// 	'label' => 'Smtp Password',
						// 	'type'  => 'text',
						// 	'name'	=> 'smtpPass',
						// 	'crequired' => 'true',
						// 	'adiClass' => 'form-control',
						// 	// 'aLabelAttr' => ['class' => 'test'],
						// 	'required' => true,
						// 	'aWrapper' => [
						// 		'pfx' => '<div class="m-0 col-sm-6">
						// 		<div class="col-sm-12 m-0">{label}</div>
						// 		<div class="col-sm-12"><div class=""><div class="form-line">',
						// 		'sfx' => '</div></div></div></div>'
						// 	],
						// ],
						// [
						// 	'label' => 'Smtp Port',
						// 	'type'  => 'text',
						// 	'name'	=> 'smtpPort',
                        //     'disabled' => 'disabled',
                        //     'value' => '587',
						// 	'crequired' => 'true',
						// 	'adiClass' => 'form-control',
						// 	// 'aLabelAttr' => ['class' => 'test'],
						// 	'required' => true,
						// 	'aWrapper' => [
						// 		'pfx' => '<div class="m-0 col-sm-6">
						// 		<div class="col-sm-12 m-0">{label}</div>
						// 		<div class="col-sm-12"><div class=""><div class="form-line">',
						// 		'sfx' => '</div></div></div></div>'
						// 	],
						// ],
						[
							'label' => 'BCC',
							'type'  => 'text',
							'name'	=> 'bcc',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6">
								<div class="col-sm-12 m-0">{label}</div>
								<div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Reply To',
							'type'  => 'text',
							'name'	=> 'replyTo',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6">
								<div class="col-sm-12 m-0">{label}</div>
								<div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Email From',
							'type'  => 'text',
							'name'	=> 'setFromEmail',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6">
								<div class="col-sm-12 m-0">{label}</div>
								<div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Display Name',
							'type'  => 'text',
							'name'	=> 'setFromName',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-6">
								<div class="col-sm-12 m-0">{label}</div>
								<div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						// [
						// 	'label' => 'SMTP Secure',
						// 	'type'  => 'select',
                        //     'disabled' => 'disabled',
						// 	'name'	=> 'SMTPSecure',
						// 	'crequired' => 'true',
						// 	'adiClass' => 'currency',
						// 	'aOpt' => [
						// 		'tls' => 'TLS',
						// 		'0' => 'No',
						// 		'ssl' => 'SSL',
						// 	],
						// 	'required' => true,
						// 	'aWrapper' => [
						// 		'pfx' => '<div class="m-0 col-sm-6">
						// 		<div class="col-sm-12 m-0">{label}</div>
						// 		<div class="col-sm-12"><div class=""><div class="form-line">',
						// 		'sfx' => '</div></div></div></div>'
						// 	],
						// ],
						// [
						// 	'label' => 'Use authentication',
						// 	'type'  => 'checkbox',
                        //     'disabled' => 'disabled',
						// 	'checked' => true,
						// 	'adiClass' => 'in',
						// 	'name'	=> 'auth',
						// 	'aWrapper' => [
						// 		'pfx' => '<div class="m-0 col-sm-6"><div class="col-sm-12 m-0" style="opacity: 0;">{label}</div><div class="input-field col s6"><p class="p-v-xs">',
						// 		'sfx' => '<label for="auth">{labelText}</label></p></div>'
						// 	]
						// ],
					]
				]
			]
		];
	}
	private function addInvoice(array $aExtra = [])
	{
		return [
			'aOpt' => [
				'aForm' => [
					'sUrl' 	=> '',
					'aAttr' => [
						'class' 	=> 'col s12',
						'method' 	=> 'post',
						'novalidate' => '',
						'id' => 'invoice'
					],
				]
			],
			'aInputs' => [
				[
					'type' => 'inputsGroup',
					'groupPrefix' => '<div class="row clearfix">',
					'groupSuffix' => '</div><div class="row" style="float: right;margin-right: 10px;><div class="input-field col s12"><input type="hidden" name="bcc" value=""><input type="hidden" name="status" value="0"><input type="hidden" name="msg" value=""><input type="hidden" name="id" value=""></div></div>',
					'aInputs' => [
						[
							'type' => 'select',
							'label' => 'Client',
							'name' => 'client_name',
							'required' => true,
							'crequired' => true,
							'adiClass' => 'client w-100',
							'aOpt' => [
								'' => '---Select Client---',
							],
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-9 col-lg-9"><div class="m-0 col-sm-4"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div><div class="m-0 col-sm-1 p-0"><div class="col-sm-12 m-0" style="opacity: 0;">{label}</div><div class="col-sm-12 p-0"><a href="'.site_url("client/add").'" target="blank"><i class="material-icons" style="color: var(--siteBgColor);">add</i></a></div></div></div>'
							],
						],
						// [
						// 	'label' => 'Invoice Name',
						// 	'type'  => 'text',
						// 	'name'	=> 'invoice_name',
						// 	'crequired' => 'true',
						// 	'adiClass' => 'form-control',
						// 	'value' => 'Invoice',
						// 	// 'aLabelAttr' => ['class' => 'test'],
						// 	'required' => true,
						// 	'aWrapper' => [
						// 		'pfx' => '<div class="m-0 col-sm-6  d-none"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
						// 		'sfx' => '</div></div></div></div></div>'
						// 	],
						// ],
						[
							'type' => 'html',
							'html' => '<div class="m-0 col-sm-3"><img style="width:' . $this->aConf->logoSize . '%;" class="img-logo" src="' . HTTP_ASSETS . 'uploads/invoice-logo/' . $this->aConf->userId . '.png?v='.time().'"></div>'
						],
						[
							'label' => 'Invoice No',
							'type'  => 'text',
							'name'	=> 'invoice_no',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							'readonly' => 'readonly',
							'value' => $this->aConf->invoiceNo,
							// 'aLabelAttr' => ['class' => 'test'],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-12 f-left partition"><div class="m-0 col-sm-4"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Currency',
							'type'  => 'select',
							'name'	=> 'currency',
							'crequired' => 'true',
							'adiClass' => 'currency w-25',
							'aOpt' => [
								'' => '---Select Currency---',
							],
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="m-0 col-sm-4 f-right"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div></div>'
							],
						],
						[
							'type' => 'html',
							'html' => '<div class="m-0 col-sm-12 partition" style="margin-top:10px !important;">
							<div class="col-sm-12 col-lg-6">
							<div class="col-sm-12 m-0">
							<h3 class="d-inline-block f-left" style="font-size:15px;font-weight: 200;">From:</h3>
							</div>
							<div class="col-sm-12">
							<div id="from-data" class="d-inline-block" style="font-size:15px">
							<p>' . $this->aConf->name . '</p>
							<p id="from">' . $this->aConf->email . '</p>
							</div>
							</div>
							<div class="col-sm-12 m-0">
							<h3 class="d-inline-block f-left" style="font-size:15px;font-weight: 200;">To:</h3>
							</div>
							<div class="col-sm-12">
							<div id="to-data" class="d-inline-block" style="font-size:15px"></div>
							</div>
							</div>'
						],
						[
							'type' => 'text',
							'label' => 'Date',
							'name' => 'issue_date',
							'required' => true,
							'crequired' => true,
							'adiClass' => 'client w-100',
							'aWrapper' => [
								'pfx' => '<div class="col-sm-12 col-lg-6"><div class="col-sm-12 m-0"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => 'Invoice Due',
							'type'  => 'select',
							'name'	=> 'invoice_due',
							'crequired' => 'true',
							'adiClass' => 'invoice-due w-100',
							'aOpt' => INVOICE_DUE,
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="col-sm-12 m-0"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div>'
							],
						],
						[
							'label' => '',
							'type'  => 'text',
							'name'	=> 'invoice_due_in',
							'crequired' => 'true',
							'adiClass' => 'w-100 d-none',
							'required' => true,
							'aWrapper' => [
								'pfx' => '<div class="col-sm-12 m-0"><div class="col-sm-12"><div class=""><div class="form-line">',
								'sfx' => '</div></div></div></div></div>'
							],
						],
						// [
						// 	'label' => 'Purchase Order Number',
						// 	'type'  => 'text',
						// 	'name'	=> 'order_number',
						// 	'crequired' => 'true',
						// 	'adiClass' => 'w-100',
						// 	'required' => true,
						// 	'aWrapper' => [
						// 		'pfx' => '<div class="col-sm-12 m-0"><div class="col-sm-12 m-0">{label}</div><div class="col-sm-12"><div class=""><div class="form-line">',
						// 		'sfx' => '</div></div></div></div></div></div>'
						// 	],
						// ],
						[
							'type' => 'html',
							'html' => '
							<div class="m-0 col-sm-12 partition" style="margin-top:10px !important;">
							<div class="col-sm-4 col-lg-5 m-0">
							<h3 class="d-inline-block f-left" style="font-size:15px;font-weight: 200;">Description</h3>
							</div>
							<div class="col-sm-3 col-lg-3 m-0">
							<h3 class="d-inline-block f-left" style="font-size:15px;font-weight: 200;">Quantity</h3>
							</div>
							<div class="col-sm-3 col-lg-3 m-0">
							<h3 class="d-inline-block f-left" style="font-size:15px;font-weight: 200;">Amount</h3>
							</div>
							<div class="col-sm-2 col-lg-1 m-0"></div>
							</div>'
						],
						[
							'type' => 'html',
							'html' => '
							<div class="col-sm-12 items"><div id="clone" class="m-0 col-sm-12 partition" style="margin-top:10px !important;">
							<div class="col-sm-4 col-lg-5 m-0">
							<textarea class="w-100 desciption" placeholder="Item Name & Desciption" style="padding-left: 5px;" name="item_desciption[]"></textarea>
							</div>'
						],
						[
							'label' => 'Quantity',
							'type'  => 'number',
							'name'	=> 'quantity[]',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							'min' => '1',
							'value' => '1',
							'readonly' => 'readonly',
							'required' => true,
							'aWrapper' => [
								'pfx' => '
								<div class="col-sm-3 col-lg-3 m-0">
								<div class="col-sm-12">
								<div class="">
								<div class="form-line">',
								'sfx' => '
								</div>
								</div>
								</div>
								</div>'
							],
						],
						// [
						// 	'label' => 'Rate',
						// 	'type'  => 'number',
						// 	'name'	=> 'rate[]',
						// 	'crequired' => 'true',
						// 	'adiClass' => 'form-control',
						// 	'min' => "1",
						// 	'required' => true,
						// 	'aWrapper' => [
						// 		'pfx' => '
						// 		<div class="col-sm-2 col-lg-2 m-0">
						// 		<div class="col-sm-12">
						// 		<div class="">
						// 		<div class="form-line">',
						// 		'sfx' => '
						// 		</div>
						// 		</div>
						// 		</div>
						// 		</div>'
						// 	],
						// ],
						[
							'label' => 'Amount',
							'type'  => 'number',
							'name'	=> 'amount[]',
							'crequired' => 'true',
							'adiClass' => 'form-control',
							'min' => '1',
							'required' => true,
							'aWrapper' => [
								'pfx' => '
								<div class="col-sm-3 col-lg-3 m-0">
								<div class="col-sm-12">
								<div class="">
								<div class="form-line">',
								'sfx' => '
								</div>
								</div>
								</div>
								</div>'
							],
						],
						[
							'type' => 'html',
							'html' => '<div class="m-0 col-sm-1 delete d-none"><i class="material-icons">delete</i></div></div></div>'
						],
						[
							'type' => 'html',
							'html' => '<div class="col-sm-12"><button type="button" class="btn btn-primary m-t-15 waves-effect" id="addItem">Add Line</button></div>'
						],
						[
							'type' => 'html',
							'html' => '
							<div class="col-sm-12 mb-0" style="margin-top: 30px;">
							<div class="col-sm-4"></div>
							<div class="col-sm-8 mb-0">
							<div class="col-sm-12 partition">
							<div class="col-sm-10" style="margin-bottom: 2px;">Sub Total</div>
							<div class="col-sm-2" id="subTotal" style="margin-bottom: 2px;">0.00</div>
							</div>

							<div class="col-sm-12 discountClone p-0">
							<div class="col-sm-12 partition d-none" id="discount">
							<div class="col-sm-10" style="margin-bottom: 15px;">
							<i class="material-icons del-discount" style="color:var(--siteBgColor);font-size: 15px">cancel</i>
                            <input type="text" name="discount_name" class="form-control d-inline-block" style="width: 65% !important;" required="" placeholder="Discount Name" >	
							<input type="text" name="discount_rate" class="form-control d-inline-block" required="" placeholder="Discount Rate" style="width: 28% !important;" id="discountRate">
							</div>
							<div class="col-sm-2" id="discountAmount" style="margin-bottom: 2px;">0.00</div>
							</div>
							</div>

							<div class="col-sm-12">
							<div class="col-sm-10" style="margin-bottom: 2px;"><b>Total (<span class="currencyUnit">USD<span></b>)</div>
							<div class="col-sm-2" id="total" style="margin-bottom: 2px;">0.00</div>
							</div>
							<div class="col-sm-12" style="border: 3px solid var(--siteBgColor);padding: 9px 15px 6px 0px;">
							<div class="col-sm-10" style="margin-bottom: 2px;padding-left: 0;"><span style="padding: 10px 15px 10px 15px;background-color: var(--siteBgColor);color: white;">Balance</span><span class="currencyUnit" style="margin-left: 5px;"> USD</span></div>
							<div class="col-sm-2" id="balance" style="margin-bottom: 2px;">0.00</div>
							</div>
							</div>
							</div>'
						],
						[
							'type' => 'html',
							'html' => '<div class="m-0 col-sm-12">
							<div class="f-right">
							<i class="material-icons add-discount" style="color:var(--siteBgColor);font-size: 35px;margin-right: 10px;">add_circle</i>
							</div>
							</div>'
						],
						[
							'type' => 'html',
							'html' => '<div class="m-0 col-sm-12" style="margin-left: 13px !important;">Invoice Footer Note</div><div class="m-0 col-sm-12"><div class="col-sm-12"><textarea name="footer" class="w-100 footer" style="padding-left:5px" placeholder="Footer Desciption"></textarea></div></div>'
						],
						[
							'type' => 'html',
							'html' => '<div class="m-0 col-sm-12" style="margin-left: 13px !important;"><p style="margin-top: 21px;font-weight: 600;">Our PayPal Id: '.$this->aConf->footerEmail.'</p><div>'
						],
					]
				]
			]
		];
	}
}
