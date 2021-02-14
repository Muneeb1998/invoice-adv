<?php

namespace App\Controllers;

use App\Libraries\TestLib;
use App\Libraries\FormBuilder;
use App\Libraries\PdfGenerate;
use App\Libraries\Ci3DbUtility;
use Dompdf\Dompdf;

class Test extends BaseController
{
	public function workWithLib()
	{
		$oTestLib = new TestLib();
		$oTestLib->test();
	}

	public function front()
	{
		// echo '<pre>';
		// print_r(get_defined_functions());
		// echo '</pre>';
		// die();
		// print_r($this->session->getFlashdata('aAlrt'));
		$aData['script'] = 'this is my script file';
		return render('test', $aData);
	}

	public function api()
	{
		$oSiteBuilder = $this->modelHelper->getBuilder('site', ['act' => 'c']);
		// echo '<pre>';
		// print_r($oSiteBuilder);
		// echo '</pre>';
		// die();
		// $oSiteBuilder->cleanRules();
		if (!$oSiteBuilder->validation->run([], 'createSite')) {
			echo 'first rule<br>';
			echo '<pre>';
			print_r($oSiteBuilder->validation->getErrors());
			echo '</pre>';
			die();
		}
		$validation =  \Config\Services::validation();
		if (!$validation->run([], 'c_site')) {
			echo 'second rule<br>';
			print_r($validation->getErrors());
			die();
		}
		echo 'working';
	}

	public function flashRd()
	{
		// $this->session->setFlashdata('aAlrt', ['Alert Key', 'Alert Val']);
		// return redirect()->to('front')->with('aAlrt', ['Alert Key', 'Alert Val']);
		return redirect()->to('front')->with('aNtfy', ['Notify Key', 'Notify Val']);
		// return redirect()->to(base_url('test/front'))->with('aNtfy', ['Notify Key', 'Notify Val']);
		// echo 'flashRd';
		// return redirect('test/front')->back();
	}

	// public function admin()
	// {
	// 	$aData['script'] = 'this is my script file';
	// 	return view('test', $aData);
	// }

	public function getConf()
	{
		// Creating new class by hand
		// $config = new \Config\Pager();
		// // Creating new class with config function
		// $config = config( 'Pager', false );

		// // Get shared instance with config function
		// $config = config( 'Pager' );

		// // Access config class with namespace
		// $config = config( 'Config\\Pager' );

		// // Access settings as class properties
		// $pageSize = $config->perPage;
		// var_dump($pageSize);

		echo '<pre>';
		// print_r(Config('App')->iPort);
		// print_r($this->request->config->iPort);
		echo '</pre>';
		die();
		$config = config('App');
		$pageSize = $config->iPort;
		var_dump($pageSize);
	}
	public function sess()
	{
		// $this->session->set([
		// 	'isLoggedIn' => true,
		// 	'userId'		 => 1
		// ]);
		// $this->session->get('userId');
		$this->session->destroy();
	}

	public function validation()
	{
		// echo lang('Validation.required');
		// die();
		$validation =  \Config\Services::validation();
		// print_r($imagefile);
		// echo '<pre>';
		// print_r($validation->getRuleGroup('testForm'));
		// // // print_r(get_class_methods($this));
		// // echo '</pre>';
		// // die();
		// $aRules = $validation->getRuleGroup('testForm');
		// $vars = [
		// 	'openHrs' 	=> ['12:00', '00.15'],
		// 	'closeHrs' 	=> ['12:00'],
		// ];
		$vars = $this->request->getVar();
		if (!$validation->run($vars, 'testForm')) {
			// if(!$this->validate($aRules)){
			print_r($validation->getErrors());
			die();
			// throw new \Exception($validation->getErrors());
		}
		print_r($vars);
		die();
	}

	public function dbActions()
	{
		// $oUserBuilder = model('App\Models\UserModel')->builder();
		// $oUserBuilder = $this->modelHelper->getBuilder('slug', ['act'=>'c']);
		$oUserBuilder = $this->modelHelper->getBuilder('user', ['act' => 'c']);
		// var_dump($oUserBuilder->delete(['id' => 14]));
		// die();
		// $rules = $oUserBuilder->getValidationRules();
		// $rules = $oUserBuilder->validate(['name'=>'junaid']);
		// 		$a = [
		// 	'first_name' 	=> 'junaid',
		// 	'last_name' 	=> 'khan',
		// 	'email' 			=> 'ahmed_junaid2@outlook.com',
		// 	// 'password' 		=> '123456',
		// 	'username' 		=> 'jak2',
		// ];
		// if(!$oUserBuilder->validation->run($a)){
		// 	echo '<pre>';
		// 	print_r($oUserBuilder->validation->getErrors());
		// 	echo '</pre>';
		// }
		// echo '<pre>';
		// print_r($rules);
		// // var_dump($rules);
		// echo '</pre>';
		// die();
		// echo '<pre>';
		// print_r($oUserBuilder);
		// // print_r($oUserBuilder->get()->getResult());
		// echo '</pre>';
		// die();
		// $rules = $oUserBuilder->getValidationRules();
		// $rules = $oUserBuilder->validation->getRuleGroup('c_user');
		// echo '<pre>';
		// // var_dump($oUserBuilder->validate($rules));
		// print_r($rules);
		// echo '</pre>';
		// die();
		$a = [
			'first_name' 	=> 'junaid',
			'last_name' 	=> 'khan',
			'username' 		=> 'jak3',
			'password' 		=> '1234',
			'pass_confirm' => '1234',
			// 'password' 		=> '$2y$10$xnV9lWa9cAxlYUZwjPvNLeiZJIGEAgqrFkQGAynS3FFSmZnuyjo5a',
			'email' 			=> 'ahmed_junaid@outlook.com12',
		];
		// $oSlugBuilder = $this->modelHelper->getBuilder('slug', ['act'=>'c']);
		// $slug = $this->generateSlug([
		// 	'name' => $a['first_name'].''.$a['last_name'],
		// 	'oSlugBuilder' => $oSlugBuilder
		// ]);
		// print_r($slug);
		// die();
		// if(!$oUserBuilder->validation->run($a, 'c_user')){
		// 	echo '<pre>';
		// 	print_r($oUserBuilder->validation->getErrors());
		// 	echo '</pre>';
		// }
		// if(!$oSlugBuilder->validation->run($a, 'c_slug')){
		// 	echo '<pre>';
		// 	print_r($oSlugBuilder->validation->getErrors());
		// 	echo '</pre>';
		// }
		// die();
		// echo '<pre>';
		// print_r($oUserBuilder->getValidationRules());
		// echo '</pre>';
		// die();
		$oUserBuilder->cleanRules();
		if (!$oUserBuilder->validate($a)) {
			// $this->aProtectedData['oValidationErrors'] = $oUserBuilder->validation->getErrors();
			print_r($oUserBuilder->validation->getErrors());
			die();
			// throw new \Exception('Error: Invalid form field(s).');
		}

		echo '<pre>';
		print_r($a);
		echo '</pre><br>';
		$pasHash = password_hash($a['password'], PASSWORD_BCRYPT);
		$aUserParams = [
			'first_name' => $a['first_name'],
			'last_name' => $a['last_name'],
			'username' => $a['username'],
			'password' => $pasHash,
			'pass_confirm' => $pasHash,
			'email' => $a['email'],
			'email4' => $a['email'],
		];
		echo '<pre>';
		print_r($aUserParams);
		echo '</pre><br>';
		// $iUserInserted = $oUserBuilder->insert($aUserParams);
		// var_dump($iUserInserted);
		// die();
		if (!$oUserBuilder->insert($a)) {
			echo '<pre>';
			// print_r($this->db);
			print_r($oUserBuilder->errors());
			echo '</pre>';
		}
		// var_dump($aResInsert);
		// echo '<pre>';
		// print_r($oUserBuilder);
		// echo '</pre>';
		// echo '<pre>';
		// print_r($oUserBuilder->set($aParams)->getCompiledInsert('user'));
		// echo '</pre>';
		die();
	}

	protected function generateSlug(array $a = [])
	{
		$slug = false;
		$name = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $a['name']));
		$oSlugBuilder = $a['oSlugBuilder'];
		// echo '<pre>'; 
		// var_dump();
		// // print_r($oSlugBuilder->getCompiledSelect());
		// echo '</pre>'; 
		// die();

		if (is_numeric($name)) {
			$name = '_' . $name;
		}
		$i = 0;
		// $oSlugBuilder->selectCount('id');
		print_r($slug);
		die();
		$oSlugBuilder->where('slug', $slug);
		// $oSlugBuilder->resetQuery();
		// $query = $oSlugBuilder->get();
		print_r($oSlugBuilder->countAllResults());
		die();
		// while(true){
		for ($i = 0; $i < 100; $i++) {
			if (!$slug) {
				$slug = $name;
			} elseif ($i > 0) {
				$slug = $name . '-' . $i;
			}
			$oSlugBuilder->select('id');
			// $oSlugBuilder->where('slug', $slug);
			// $query = $oSlugBuilder->get();
			print_r($oSlugBuilder->countAllResults());
			// print_r($query->getResult());
			// print_r($oSlugBuilder->getCompiledSelect());
			die();
			if (!$oSlugBuilder->countAllResults()) {
				// echo 'has';
				// die();
				return strtolower($slug);
				break;
			}
			$i++;
		}
	}

	public function apiHash()
	{
		for ($i = 0; $i < 5000; $i++) {
			$hash = appHash([
				'o' => [
					'len'  => '100',
					'email' => 'ahmed_junaid@outlook.com'
				]
			]);
			// echo rand(1111, 9999).'<br>';
			var_dump($hash);
			echo '<br>';
		}
	}

	public function dbBackup()
	{
		// $db = db_connect();
		// echo '<pre>';
		// print_r($db->listTables());
		// echo '</pre>';
		// die();
		ini_set('memory_limit', '10000M');
		set_time_limit(0);
		$path = WRITEPATH . 'database_backup/';
		$lastBackupFile = $path . 'last_backup.txt';
		$dateFormat = 'Y-m-d';
		$newfileName = $this->db->database . '-' . date('Y-m-d-H-i-s');
		if (!file_exists($lastBackupFile)) {
			file_put_contents($lastBackupFile, '');
		}
		$this->delOldBackup();
		$lastBackup = file_get_contents($lastBackupFile);
		if (strtotime($lastBackup) == strtotime(date($dateFormat))) {
			return false;
		}
		$oCi3DbUtility = new Ci3DbUtility();
		$prefs = [
			'format' => 'zip',
		];
		$backup = $oCi3DbUtility->backup($prefs);
		helper('filesystem');
		write_file($path . $newfileName . '.zip', $backup);
		file_put_contents($lastBackupFile, date($dateFormat));
		echo json_encode(['success' => true]);
		// var_dump(write_file($path.$newfileName . '.zip', $backup));
		// $util = (new \CodeIgniter\Database\Database())->loadUtils($db);
		// $a = $util->backup([
		// 	'format' => 'zip'
		// ]);
		// echo '<pre>';
		// print_r(get_class_methods($util->backup());
		// echo '</pre>';
	}

	protected function delOldBackup()
	{
		$removeBefore = 10;
		helper('directory');
		$path = WRITEPATH . 'database_backup/';
		$aFiles = directory_map($path);
		$beforeDate = date('Y-m-d', strtotime('-' . $removeBefore . ' days', strtotime(date('Y-m-d'))));
		foreach ($aFiles as $key => $file) {
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			if ($ext == 'zip') {
				$exFile = str_replace($this->db->database . '-', '', $file);
				$fileDate = substr($exFile, 0, 10);
				if (strtotime($beforeDate) >= strtotime($fileDate)) {
					unlink($path . $file);
				}
			}
		}
	}

	public function logs()
	{
		// var_dump($this->session->userId);
		// die();
		sLog([
			'log' => ['msg' => 'this is junaid khan'],
			'folder' => 'trace',
			// 'aParamExclude' => ['name']
		]);
	}

	public function checkEmail($userId = '',$s = false)
	{
		helper('email');
		try {
			if ($s) {
				sendEmail([
					'e'	 => 'test',
					'to' => $s,
					'user_id' => $userId
				]);
			} else {
				sendEmail([
					'e'	 => 'test',
					'to' => 'muneebmansoor98@gmail.com',
					'bcc' => '',
					'user_id' => $userId
				]);
				// sendEmail([
				// 	'e'	 => 'invoiceSend',
				// 	'to' => 'muneebmansoor98@gmail.com',
				// 	'invoice_no' => 'MM-01',
				// 	'client_name' => 'Muneeb',
				// 	'amount' => '10.00 USD',
				// 	'due_date' => 'JAN 18 2021',
				// 	'hash' => 'test'
				// ]);
			}
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}

	public function addMedia()
	{
		try {
			// echo '<pre>';
			// // print_r(gtbin('20_1595357809_ae699e0f17dba0c7f9bd.png', 0));
			// print_r(gtbin('20_1595357809_ae699e0f17dba0c7f9bd.png'));
			// echo '</pre>';
			// die();
			// helper('media');
			// var_dump(isImage(['ext' => 'txt']));
			// genThumbnails([
			// ]);
			// die();
			// $validation =  \Config\Services::validation();
			// // $imagefile = $this->request->getFiles();
			// $vars = $this->request->getVar();
			// // print_r($imagefile);
			// // echo '<pre>';
			// // print_r($validation->getRuleGroup('testForm'));
			// // // print_r(get_class_methods($this));
			// // echo '</pre>';
			// // die();
			// $aRules = $validation->getRuleGroup('testForm');
			// if(!$validation->run($vars, 'testForm')){
			// 	// if(!$this->validate($aRules)){
			// 		print_r($validation->getErrors());
			// 		die();
			//     // throw new \Exception($validation->getErrors());
			// }
			// print_r($_FILES);
			// die();
			helper('media');
			$rCuMedia = cuMedia(
				[
					// 'table'  => false,
					// 'upPath'=> WRITEPATH.'uploads/gallery/',
					// 'uId'=> 888,
					'aFiles' => [
						// 'product_imgs[]' => [
						// 	'pfx' => 'pfx',
						// 	'sfx'	=> 'sfx',
						// 	'type'	=> 'product',
						// 	'src'	=> 'product',
						// 	'src_id'=> 12,
						// ],
						'logo' => [
							'type'	=> 'logo',
							'src'	=> 'site',
							// 'src'	=> 'website',
							'src_id' => 3,
							'act' 	=> 'u'
						],
						// 'profile_img' => [
						// 	'type'	=> 'dp',
						// 	'src'	=> 'user',
						// 	'src_id'=> 1,
						// ],
					],
					'aRemove' => [
						// 'path'=> WRITEPATH.'uploads/gallery/',
						'aName' => ['20_1595360260_d33b20170086aa48dddc.png'],
						// 'aId' 	=> [3]
					],
					'aUnlinkIfHasNew' => [
						'logo' => true,
						// 'logo' => '1_1596089196_e3c9f725d746a2548a89.png'
						// 'abc' => ''
					]
				],
				$this->request,
				$this->modelHelper
			);
			echo '<pre>';
			print_r($rCuMedia);
			echo '</pre>';
			// sendEmail([
			// 	'e'	 => 'sendOtpEmail',
			// 	'to' => 'toponedeveloper@gmail.com',
			// 	'tmplt' => 'otpemail',
			// 	'tmpltVars' => [
			// 		'name' => 'junaid ahmed',
			// 		'otp'	 => 123456
			// 	]
			// ]);
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}

	public function insertBatch()
	{
		$oSiBuHrBuilder = $this->modelHelper->getBuilder(
			'SiteBusinessHour',
			[
				'act' => 'c'
			]
		);
		$aSiteHourParams = [
			'su' => [
				// 'site_id' => 1,
				'day' => 'su',
				'open' => '01:00',
				'close' => '06:00',
			],
			'mo' => [
				// 'site_id' => 1,
				'day' => 'mo',
				'open' => '02:00',
				'close' => '07:00',
			],
			'tu' => [
				// 'site_id' => 1,
				'day' => 'tu',
				'open' => '03:00',
				'close' => '08:00',
			],
			'we' => [
				// 'site_id' => 1,
				'day' => 'we',
				'open' => '04:00',
				'close' => '09:00',
			],
			'th' => [
				// 'site_id' => 1,
				'day' => 'th',
				'open' => '05:00',
				'close' => '10:00',
			],
			'fr' => [
				// 'site_id' => 1,
				'day' => 'fr',
				'open' => '06:00',
				'close' => '11:00',
			],
			'sa' => [
				// 'site_id' => 1,
				'day' => 'sa',
				'open' => '07:00',
				'close' => '12:00',
			]
		];
		$oSiBuHrBuilder->skipValidation();
		$oSiBuHrBuilder->where('site_id', 1);
		$iSiBuHrInserted = $oSiBuHrBuilder->updateBatch($aSiteHourParams, 'day');
	}

	public function getCount()
	{
		$oSiBuHrBuilder = $this->modelHelper->getBuilder(
			'SiteBusinessHour',
			[
				'act' => 'c'
			]
		);
		$oSiBuHrBuilder->skipValidation();
		// $oSiBuHrBuilder->selectCount('id');
		$oSiBuHrBuilder->select([
			'day',
			'open',
			'close'
		]);
		$oSiBuHrBuilder->where('site_id', 1);
		$query = $oSiBuHrBuilder->get();
		echo '<pre>';
		print_r(json_encode($query->getResult()));
		echo '</pre>';
		// print_r($query->getRow());
	}

	public function getStatus()
	{
		$ts = _ts([
			't' => 'site',
			'k'	=> 2,
			// 'v'	=> 'pending_review',
		]);
		echo '<pre>';
		print_r($ts);
		echo '</pre>';
	}

	public function formBuilder()
	{
		$oFormBuilder = new FormBuilder($this);
		$aData['form'] = $oFormBuilder->getForm('login');
		$this->prettyHtml($aData['form']);
		// print_r(htmlspecialchars($aData['form']));
		// print_r($aData['form']);
	}

	protected function prettyHtml($s)
	{
		// $dom = new \DOMDocument();
		// $dom->preserveWhiteSpace = false;
		// $dom->loadHTML($s,LIBXML_HTML_NOIMPLIED);
		// $dom->formatOutput = true;
		// return $dom->saveXML($dom->documentElement);
		$dom = new \DOMDocument();
		$dom->loadXML($s);
		$dom->formatOutput = true;
		echo '<pre>' . htmlspecialchars($dom->saveHTML()) . '</pre>';
	}
	public function pdf()
	{
		// reference the Dompdf namespace
		// instantiate and use the dompdf class
		$dompdf = new Dompdf();
		$dompdf->loadHtml(view('default/pdf'));

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'portrait');

		// Render the HTML as PDF
		$dompdf->render();

		// Output the generated PDF to Browser
		$dompdf->stream("dompdf_out.pdf", array("Attachment" => false));

		exit(0);
	}
	public function btn()
	{
		echo '<a href="' . site_url("Pdf") . '" target="__blank" class="btn btn-primary">Download PDF</a>';
	}
	function pdfLib()
	{
		$oFormBuilder = new PdfGenerate([
			'formDefaults' => 'muneeb',
		]);
		$oFormBuilder->generate();
	}
	function pdfToZip()
	{
		session_write_close();
		$client = \Config\Services::curlrequest();
		// $res = $client->request('GET', API_URL . 'pr/pdfData', [
		// 	'form_params' => [
		// 		'foo' => 'bar',
		// 		'baz' => ['hi', 'there']
		// 	]
		// ]);
		$res = $client->request('POST', API_URL . 'pr/pdfData', [
			'form_params' => [
				'foo' => 'bar',
				'baz' => ['hi', 'there']
			]
		]);
		$aRes = json_decode($res->getBody(), true);
		print_r($aRes);
	}
}
