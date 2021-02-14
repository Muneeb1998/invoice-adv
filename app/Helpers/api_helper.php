<?php

/**
 * @author  Junaid Ahmed Khan (https://www.softwebz.com)
 */
if (!function_exists('appHash')) {
  function appHash(array $a = [])
  {
    $o = array_replace_recursive(
      [
        'len'   => 45,
        'pfx'   => '',
        'sfx'   => '',
        'id'    => '',
        'email' => '',
        'uname' => '',
        'hash'  => '',
      ],
      array_filter(
        [
          'len'   => @$a['o']['len'],
          'pfx'   => @$a['o']['pfx'],
          'sfx'   => @$a['o']['sfx'],
          'id'    => @$a['o']['id'],
          'email' => @$a['o']['email'],
          'uname' => @$a['o']['uname'],
          'hash'  => @$a['o']['hash'],
        ]
      )
    );
    $hash = hash(
      'md5',
      hash('md5', $o['id'] . time() . $o['hash'] . $o['email'] . uniqid() . $o['uname'])
    );
    $hash .= substr(str_replace('.', '', microtime(true)), 0, 13);
    $hash .= hash('md5', microtime(true)) . uniqid();
    if ($l = (intval((intval($o['len']) - 1) / 90))) {
      for ($i = 0; $i < $l; $i++) {
        $hash .= appHash([
          'o' => [
            'len' => 90,
          ]
        ]);
      }
    }
    $o['len'] = intval($o['len']) - strlen($o['pfx']) - strlen($o['sfx']);
    $hash = $o['pfx'] . substr($hash, 0, $o['len']) . $o['sfx'];
    return $hash;
  }
}

if (!function_exists('sLog')) //set log
{
  function sLog(array $a = [], int $iType = 0)
  {
    $request = \Config\Services::request();
    $aO = array_replace_recursive(
      [ //Default Options
        'file'  => 'other',
        'folder' => (@$_SESSION['userId'] ? 'user' : 'guest'),
        'aParamExclude' => [],
      ],
      array_filter([
        'file'  => (@$a['file'] ?? @$_SESSION['userId']),
        'folder' => @$a['folder'],
        'aParamExclude' => @$a['aParamExclude']
      ])
    );
    $logPath = WRITEPATH . 'logs/' . $aO['folder'] . '/' . date('y') . '/' . date('m') . '/' . date('d') . '/';
    file_exists($logPath) or mkdir($logPath, 0755, TRUE);
    $filepath = $logPath . $aO['file'] . '.log';
    $msg = '';
    $time = date('h:i:s A');
    $a['log']['userAgent'] = $request->getUserAgent()->getAgentString();
    $a['log']['ipAddress'] = $request->getIPAddress();
    $a['log']['uri'] = $_SERVER['REQUEST_URI'];
    $a['log']['method'] = $_SERVER["REQUEST_METHOD"];
    switch ($_SERVER["REQUEST_METHOD"]) {
      case 'GET':
      case 'POST':
        $a['log']['params'] = $request->getVar();
        break;
      case 'PUT':
      case 'DELETE':
        $a['log']['params'] = $request->getRawInput();
        break;
    }
    if (!empty($aO['aParamExclude'])) {
      foreach ($aO['aParamExclude'] as $k => $v) {
        unset($a['log']['params'][$v]);
      }
    }
    if (empty($a['log']['params'])) {
      unset($a['log']['params']);
    }
    switch ($iType) {
        // case 1://as text
        //   $msg .= $time.' --> '.$a['log'];
        //   break;
        // case 2://as print
        //   $msg .= json_encode(array($time => $a['log'])).',';
        //   break;
      default: //as json
        $msg .= json_encode(array($time => $a['log'])) . ',';
        break;
    }
    file_put_contents($filepath, $msg, FILE_APPEND | LOCK_EX);
  }
}

// if (!function_exists('gLog'))//get log
// {
//   function gLog(array $a = [])
//   {
//     $aO = array_replace_recursive(array(//Default Options
//         'file'   => 'other',
//         'y'      => date('y'),
//         'm'      => date('m'),
//         'd'      => date('d'),        
//       ),
//       array_filter(array(
//         'file'   => @$a['file'],
//         'y'      => @$a['y'],
//         'm'      => @$a['m'],
//         'd'      => @$a['d'],
//       )
//     ));
//     $logPath = WRITEPATH.'logs/users/'.$aO['y'].'/'.$aO['m'].'/'.$aO['d'].'/'.$aO['file'].'.log';
//     if (!file_exists($logPath)) {
//       throw new Exception('Error: Logs not found!');
//     }
//     $sData = file_get_contents($logPath, FILE_USE_INCLUDE_PATH, null);
//     $sData = "[".rtrim($sData,",")."]";
//     $aData = json_decode($sData);
//     return $aData;
//   }
// }

if (!function_exists('gtbin')) {
  //getThumbnailsByImgName
  function gtbin(string $img, bool $sizeIndex = false, bool $bOnlyExist = false, string $path = '')
  {
    if (!$img || empty($img)) {
      return '';
    }
    $aInfo = pathinfo($img);
    $aRes = [
      'orig' => $img
    ];
    if ($sizeIndex !== false) {
      return $aInfo["filename"] . "_" . THUMBNAILS[$sizeIndex] . "." . $aInfo["extension"];
    }
    foreach (THUMBNAILS as $k => $v) {
      $aRes[$v] = $aInfo["filename"] . "_" . $v . "." . $aInfo["extension"];
    }
    return $aRes;
  }
}

if (!function_exists('mngRules')) {
  function mngRules(array $a = [])
  {
    $aRes = @$a['aRules'];
    switch ($a['method']) {
      case 'cSite':
        switch (@$a['aVars']['hours_type']) {
          case 'selected':
            $a['aRules']['open_hr.*']['rules'] = str_replace('if_exist', 'required', $a['aRules']['open_hr.*']['rules']);
            $a['aRules']['close_hr.*']['rules'] = str_replace('if_exist', 'required', $a['aRules']['close_hr.*']['rules']);
            $aRes = $a['aRules'];
            break;
        }
        break;
      case 'uSite':
        switch (@$a['aVars']['hours_type']) {
          case 'selected':
            $a['aRules']['open_hr.*']['rules'] = str_replace('if_exist', 'required', $a['aRules']['open_hr.*']['rules']);
            $a['aRules']['close_hr.*']['rules'] = str_replace('if_exist', 'required', $a['aRules']['close_hr.*']['rules']);
            $aRes = $a['aRules'];
            break;
        }
        $a['aRules']['close_hr.*']['rules'] = str_replace('if_exist', 'required', $a['aRules']['close_hr.*']['rules']);
        $aRes = $a['aRules'];
        break;
    }
    return $aRes;
  }
}

if (!function_exists('appHash2')) {
  function appHash2(array $a = [])
  {
    $o = array_replace_recursive(
      [
        'len'   => 100,
        'id'    => '',
        'email' => '',
      ],
      array_filter(
        [
          'len'   => @$a['o']['len'],
          'id'    => @$a['o']['id'],
          'email' => @$a['o']['email'],
        ]
      )
    );

    $chr = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_';
    $sRes = '';
    for ($i = 0; $i < ceil($o['len'] / strlen($chr)); $i++) {
      $sRes .= str_shuffle($chr);
      $sRes .= rand(1, 100) . uniqid() . str_replace(['.', ' '], ['', ''], microtime()) . uniqid();
      $sRes .= hash('md5', $o['id'] . time() . $o['email'] . uniqid() . microtime());
    }
    return substr($sRes, 0, $o['len']);
  }
}

if (!function_exists('generateAppHash2')) {
  function generateAppHash2(array $a = [])
  {
    while (true) {
      $hash = appHash2($a);
      if (is_string($a['model'])) {
        $oBuilder = $a['that']->modelHelper->getBuilder($a['model'], ['act' => 'r']);
      } else {
        $oBuilder = $a['model'];
      }
      $oBuilder->select('1');
      $oBuilder->where($a['col'], $hash);
      if (isset($a['aWhere'])) {
        foreach ($a['aWhere'] as $k => $v) {
          $oBuilder->where($k, $v);
        }
      }
      if ($oBuilder->get()->getRow() === null) {
        return $hash;
        break;
      }
    }
  }
}
if (!function_exists('dateConvert')) {
  function dateConvert(array $a = [])
  {
    switch ($a['rDate']) {
      case 'M dd yyyy':
        if (isset($a['gDate'])) {
          $aDate = explode('/', $a['date']);
          $monthNum  = $aDate[1];
          $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
          $monthName = $dateObj->format('F'); // March
          return $monthName . ' ' . $aDate[0] . ' ' . $aDate[2];
        } else {
          $aDate = explode('-', $a['date']);
          $monthNum  = $aDate[1];
          $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
          $monthName = $dateObj->format('F'); // March
          return $monthName . ' ' . $aDate[2] . ' ' . $aDate[0];
        }
        break;
      case 'yyyy-mm-dd':
        $aDate = explode('/', $a['date']);
        return $aDate[2] . '-' . $aDate[1] . '-' . $aDate[0];
        break;
    }
  }
}
if (!function_exists('uploadFile')) {
  function uploadFile($file, $upload_path = "uploads", $name = false, $file_size = '1000000')
  {

    $isfileUploladed = FALSE;
    if ($file->getError() != 0) {
      return $isfileUploladed;
    }
    if ($file->getPath()) {
      if (!$file->isValid()) {
        throw new \RuntimeException($file->getErrorString() . '(' . $file->getError() . ')');
      }
      if ($file->getSize() > $file_size) {
        throw new \RuntimeException('File Should not greater than ' . ($file_size / 1024) . "KB. ");
      }
      $file_exentsion = $file->getClientExtension();
      if (!in_array($file_exentsion, ALLOWED_FILES_TYPES)) {
        throw new \RuntimeException('File Format not Supported.');
      }

      $folder_path = 'uploads/' . $upload_path;
      $path = DIR_ASSETS . $folder_path;
      if (!file_exists($path)) {
        mkdir($path, 0777);
      }
      $ext = $file_exentsion;
      if ($name) {
        $newName = $name . '.' . $ext;
      } else {
        $newName = date("Y_m_d") . "_" . time() . "_" . rand() . '.' . $ext;
      }

      $file->move($path, $newName);
      $isfileUploladed = true;


      $isfileUploladed = $newName;
    }

    return $isfileUploladed;
  }
}
