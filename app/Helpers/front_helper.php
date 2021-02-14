<?php
/**
 * @author  Junaid Ahmed Khan (https://www.softwebz.com)
 */
if (!function_exists('render'))
{
  function render(string $p, array $d = [], array $o = [])
  {
    $sess = session();
    $d['_t'] = 'default/';//Template;
    $d['_p'] = $p;//Page Name;
    $d['aIntJsHdr'][] = '
      var baseUrl = "'.base_url().'";
      var httpAssetsWa = "'.HTTP_ASSETS_WA.'";
      var siteUrl = "'.site_url().'";
      var apiUrl  = "'.API_URL.'";
      var siteVer = "'.SITE_VER.'";
    ';
    if($aAlrt = $sess->getFlashdata('aAlrt')) {
        $d['aIntJs'][] = '
          $(document).ready(function(){
            alertMsg("'.$aAlrt[0].'", "'.$aAlrt[1].'", "alert-fixed show");
          });
        ';
    }
    if (!@$d['metaTitle']) {
      $router = service('router');
      $controller  = $router->controllerName();
      $method = $router->methodName();
      if ($method === 'index') {
        $d['metaTitle'] = substr($controller, strrpos($controller, '\\') + 1).' - '.APP_NAME;
      }else{
        $d['metaTitle'] = $method.' - '.APP_NAME;
      }
    }
    return view($d['_t'].'handler',$d,$o);
  }
}

if (!function_exists('gImg'))
{

  /*
  gImg(HTTP_ASSETS_WA.'imgs/logo.png');
  **** AND ****
  gImg([
    'src'   => HTTP_ASSETS_WA.'imgs/logo.png',
    'sAttr' => 'class="img-responsive" id="myLogo"',
  ]);
  */
  function gImg($m) //Get Img
  {
    if (is_array($m)) {
      $a = array_replace_recursive(
        [//Default Options
          'sAttr'   => '',
        ],
        array_filter([
          'sAttr' => @$m['sAttr'],
          'src' => $m['src'],
        ])
      );
      return '<img src="'.$a['src'].'?v='.SITE_VER.'" '.$a['sAttr'].'>';
    }else{
      return $m.'?v='.SITE_VER;
    }
  }
}