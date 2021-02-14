<?php
namespace App\Controllers\Api\Pr;


/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;
// JAK Cutom:
use CodeIgniter\API\ResponseTrait;
use App\Libraries\App;
// JAK Cutom;
class BaseController extends Controller
{
	// JAK Cutom:
	use ResponseTrait;
	// JAK Cutom;
	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];
	/**
	 * Constructor.
	 */
	// JAK Cutom:
	protected $aVars = [];
	protected $aLogs = [];
	protected $iUserId;
	public $aProtectedData = [];
	// JAK Cutom;
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $r = (explode('/', uri_string(), 2));
		$this->session 	= \Config\Services::session();
		// JAK Cutom:
		$this->libraryApp 	= new App($this);
		$this->modelHelper 	= model('App\Models\HelperModel');
		$this->aVars = $this->request->getVar();
		helper('api');
        	helper('front');
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_TIME)) {
            // last request was more than 30 minutes ago
            session_unset();     // unset $_SESSION variable for the run-time 
            session_destroy();   // destroy session data in storage
        }
        $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
	}

	public function _remap(string $sP, ...$mP)
	{
		timer('a');
		try {
			// print_r($_COOKIE);
			// die();
			// var_dump(session_id());
			// die();
			if (!$this->iUserId = $this->libraryApp->auth()) {
				throw new \Exception("Error: Login required to access this route.");
			}
			if (!method_exists($this, AMP.$sP)) {
				return $this->failNotFound();
			}
			$res = array(
				'success'	=> true,
			);
			$res = $res + call_user_func(array($this, AMP.$sP), $mP);
		} catch (\Exception $e) {
			$res = array(
				'success' => false,
				'msg' => $e->getMessage()
			);
			if (@$this->aProtectedData['oValidationErrors'] && !empty($this->aProtectedData['oValidationErrors'])) {
				$res['oValidationErrors'] = $this->aProtectedData['oValidationErrors'];
			}
		}
		$res['execTime'] = timer('a')->getElapsedTime('a');
		$this->aLogs['res'] = $res;
		sLog([
			'log' => $this->aLogs,
		]);
		return $this->respondCreated($res);
	}
}
