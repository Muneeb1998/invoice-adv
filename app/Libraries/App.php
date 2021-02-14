<?php namespace App\Libraries;
/**
 * @author  Junaid Ahmed Khan (https://www.softwebz.com)
 */
class App
{
    protected $_ci;
    public function __construct($_ci)
    {
        $this->_ci = $_ci;
    }

    public function auth()
    {
        $res = false;
        if($this->_ci->session->get('isLoggedIn') && $this->_ci->session->get('userId')) {
            $res = $this->_ci->session->get('userId');
        }
        return $res;
    }

    public function authAdmin()
    {
        $res = false;
        if($this->_ci->session->get('isAdminLoggedIn') && $this->_ci->session->get('AdminId')) {
            $res = $this->_ci->session->get('adminId');
        }
        return $res;
    }

    public function strMinify($str, $pk = 1)
    {
        // pattern key as pk
        $aPatterns = array(
            "/\r|\n/",  //0 remove line breaks
            "/\s*/m",   //1 remove all spaces and line breaks
            "/\s\s+/m"  //2 remove line breaks and white spaces
        );
        $replace = '';
        return preg_replace($aPatterns[$pk], $replace, $str);
    }
}
?>