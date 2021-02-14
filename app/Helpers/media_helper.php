<?php
/**
 * @author  Junaid Ahmed Khan (https://www.softwebz.com)
 */
if (!function_exists('cuMedia'))
{
	function cuMedia(array $_a = [], $req, $modelHelper)
	{
		$r = [];
		$a = array_replace_recursive(
			[//Default Options
				'table' 	=> @$_a['table']??'media'??null,
				'upPath'	=> DIR_MEDIA,
				'uId'			=> @$_SESSION['userId'],
				'aFiles' 	=> [],
				'aVars' 	=> [],
			],
			array_filter([
				'table'  	=> @$_a['table'],
				'aFiles' 	=> @$_a['aFiles'],
				'upPath' 	=> @$_a['upPath'],
				'uId'			=> @$_a['uId'],
				'aRemove' => @$_a['aRemove'],
				'aFiles' 	=> @$_a['aFiles'],
				'aUnlinkIfHasNew' => @$_a['aUnlinkIfHasNew'],
				'aVars' 	=> @$_a['aVars'],
			])
		);
		$removePath = (@$a['aRemove']['path']??$a['upPath']??null);
		foreach ($a['aFiles'] as $k => $v)
		{
			if (strpos($k, '[]') !== false) {
				$files = $req->getFileMultiple(str_replace('[]', '', $k));
				// echo $k,'<pre>';
				// // print_r($this->aVars);
				// print_r($files);
				// echo '</pre>';
				// die();
			}else{
				$files = [$req->getFile($k)];
			}
			if (!$files) {
				continue;
			}
			foreach($files as $k2 => $file)
			{
				if ($file && $file->isValid() && ! $file->hasMoved())
				{
					$ext = $file->getExtension();
					$ext = empty($ext)?'':'.'.$ext;
					$g = $a['uId'].'_'.@$v['pfx'].time().'_'.bin2hex(random_bytes(10)).@$v['sfx'];//generated
					$nm = $g.$ext;//name
					$file->move($a['upPath'], $nm);
					// imgFixOrientation($a['upPath'].$nm);
					if (!@$v['noThumbnails']) {
						genThumbnails(
							$file,
							[
								'path' => $a['upPath'],
								'name' => $nm,
								'g'  	 => $g,
								'ext'	 => $ext
							]
						);
					}
					if ($a['table']) {
						// if (strpos($k, '[]') !== false) {
						// }
						if (@$v['aliasIdClause'] && !@$a['aVars'][$v['aliasIdClause']][$k2]) {
							$v['act'] = 'c';
						}elseif (@$a['aVars'][$v['aliasIdClause']][$k2]) {
							if (strpos($k, '[]') !== false && @$v['aliasIdClause']) {
								$v['id'] = $a['aVars'][$v['aliasIdClause']][$k2];
								$v['act'] = 'u';
							}else{
								$v['id'] = $a['aVars'][$v['aliasIdClause']];
								$v['act'] = 'u';
							}
						}
						// echo $k2.'<br>';
						// var_dump(strpos($k, '[]') !== false);
						// die();
						$oBuilder = $modelHelper->getBuilder($a['table'], ['act'=>'c']);
						if($a['uId'] && @$a['aUnlinkIfHasNew'][$v['type']]){
					    if ($a['aUnlinkIfHasNew'][$v['type']] === true) {
						    $oBuilder->select([
						    	'name'
						    ]);
								$oBuilder->where('type', 	$v['type']);
								$oBuilder->where('user_id', $a['uId']);
								$oBuilder->where('src', 	$v['src']);
								$oBuilder->where('src_id',$v['src_id']);
								if (@$v['id']) {
									$oBuilder->where('id', 		$v['id']);
								}
								$oFileForRemove = $oBuilder->get()->getRow();
								if ($oFileForRemove) {
									_unlinkMedia([
										'path' => $removePath,
										'name' => $oFileForRemove->name
									]);
								}
					    }else{
								_unlinkMedia([
									'path' => $removePath,
									'name' => $a['aUnlinkIfHasNew'][$v['type']]
								]);
					    }
						}
						$oBuilder->skipValidation();
						$aMediaParams = [
							'name'	=> $nm,
							'alt'		=> '',
							'ext'		=> $ext,
							'desc'	=> json_encode([
								'origName' => $file->getClientName()
							])
						];
						switch (@$v['act']) {
							case 'u':
								$oBuilder->where('type', 		$v['type']);
								$oBuilder->where('user_id', $a['uId']);
								$oBuilder->where('src', 		$v['src']);
								$oBuilder->where('src_id', 	$v['src_id']);
								if (@$v['id']) {
									$oBuilder->where('id', 		$v['id']);
								}
								// $oBuilder2 = clone $oBuilder;
								$iCount = $oBuilder->countAllResults(false);
								if($iCount > 1){
									throw new \Exception('Error: Updating media('.str_replace('[]', '', $k).') with same name.');
								}elseif($iCount < 1 && !@$v['createIfNot']){
									throw new \Exception('Error: Updating media('.str_replace('[]', '', $k).') with no record.');
								}elseif($iCount < 1 && @$v['createIfNot']) {
									$aMediaParams['type']		= $v['type'];
									$aMediaParams['user_id']= $a['uId'];
									$aMediaParams['src']		= $v['src'];
									$aMediaParams['src_id']	= $v['src_id'];
									$oBuilder->resetQuery();
									$iInserted = $oBuilder->insert($aMediaParams);
									if(!$iInserted){
										throw new \Exception('Error: Cannot create media.');
									}
								}else{
									$oBuilder->select([
							    	'id'
							    ]);
									$oRow = $oBuilder->get()->getRow();
									$oBuilder->set($aMediaParams);
									if(!$oBuilder->update($oRow->id)){
										throw new \Exception('Error: Cannot update media.');
									}
									$iInserted = $oRow->id;
								}
								// var_dump($iCount);
								// echo '<br>LINE:'.__LINE__.'<br>';
								break;
							default:
								$aMediaParams['type']		= $v['type'];
								$aMediaParams['user_id']= $a['uId'];
								$aMediaParams['src']		= $v['src'];
								$aMediaParams['src_id']	= $v['src_id'];
								$iInserted = $oBuilder->insert($aMediaParams);
								if(!$iInserted){
									throw new \Exception('Error: Cannot create media.');
								}
								break;
						}
					}
					$r[str_replace('[]', '', $k)][$k2] = $nm;
					// $r[str_replace('[]', '', $k)][$iInserted] = $nm;
					// if (@$r[str_replace('[]', '', $k)][$iInserted][$v['act']]) {
					$r['_r'][str_replace('[]', '', $k)][$v['act']][$k2] = [
						'id' 		=> $iInserted,
						'name' 	=> $nm,
						// 'index' => $k2,
						'origName' => $file->getClientName()
					];
					// }
					// $r[str_replace('[]', '', $k)][$iInserted][$v['act']][] = $nm;
				}
				unset($v['id']);
			}
		}
		if (@$a['aRemove']) {
			$aFilesForRemove = [];
			$oBuilder = $modelHelper->getBuilder($a['table'], ['act'=>'r']);
			// $oBuilder->skipValidation();
			if (@$a['aRemove']['aName']) {
		    $oBuilder->select([
		    	'id',
		    	'name'
		    ]);
				$oBuilder->where('user_id', $a['uId']);
				$oBuilder->whereIn('name', $a['aRemove']['aName']);
				$aFilesForRemove = $oBuilder->get()->getResultArray();
				// $sql = $oBuilder->getCompiledSelect();
				// echo $sql;
				// die();
			}
			if (@$a['aRemove']['aId']) {
		    $oBuilder->select([
		    	'id',
		    	'name'
		    ]);
				$oBuilder->where('user_id', $a['uId']);
				$oBuilder->whereIn('id', $a['aRemove']['aId']);
				$aFilesForRemove = array_merge($aFilesForRemove, $oBuilder->get()->getResultArray());
			}
			// print_r($aFilesForRemove);
			// die();
			$aDltdFiles = [];
			foreach ($aFilesForRemove as $k => $v) {
				if(!file_exists($removePath.$v['name'])){
					$aDltdFiles[] = $v['id'];
				} elseif (unlink($removePath.$v['name'])) {
					$aDltdFiles[] = $v['id'];
					_unlinkMedia([
						'path' => $removePath,
						'name' => $v['name']
					]);
				}
			}
			// $oBuilder->resetQuery();
			// echo '<pre>';
			// print_r($oBuilder);
			// echo '</pre>';
			// die();
			// echo $a['table'];
			// $oBuilder = model('App\Models\MediaModel')->builder();
			// $oBuilder = $modelHelper->getBuilder($a['table'], ['act'=>'r']);
			
			// echo '<pre>';
			// print_r($aDltdFiles);
			// echo '</pre>';
			// die();
			if (!empty($aDltdFiles)) {
				$oBuilder
					->where('user_id', $a['uId'])
					->whereIn('id', $aDltdFiles)
					->delete();
			}
				// ->getCompiledDelete();
			// $oBuilder
			// echo $oBuilder->getCompiledDelete();
			// die();
		}
		return $r;
	}
}

if (!function_exists('genThumbnails'))
{
	function genThumbnails($file, array $a = [])
	{
		$aAllowedExt = ['.jpg', '.jpeg', '.png'];
		if (!in_array($a['ext'], $aAllowedExt)) {
			return false;
		}
		// $oImg = \Config\Services::image();
		$oImg = \Config\Services::image('gd');
		// $oImg = \Config\Services::image('imagick');
		foreach (THUMBNAILS as $k => $v) {
      $aSize = explode('x', $v);
      $nm = $a['g'].'_'.$v.$a['ext'];
			$oImg->withFile($a['path'].$a['name']);
			$oImg->resize($aSize[0], $aSize[1], true);
			$oImg->reorient();
			$oImg->save($a['path'].$nm);
		}
	}
}

/*if (!function_exists('imgFixOrientation'))
{
	function imgFixOrientation($path)
	{
		// file:///E:/xampp7.2/htdocs/ci4-app/userguide/docs/libraries/images.html?highlight=image#processing-an-image
		$oImg = \Config\Services::image('gd');
    $oImg->withFile($path);
    $oImg->reorient();
    // $oImg->rotate(90);
    // $oImg->crop(100, 100, 0, 0);
    $oImg->save($path);
	}
}*/

if (!function_exists('imgFixOrientation'))
{
	function imgFixOrientation(string $path)
  {
    if (!function_exists('exif_read_data')) return false;
    $oldErrorLevel = error_reporting();
    error_reporting($oldErrorLevel & ~E_WARNING);
    
    $image = imagecreatefromjpeg($path);
    $exif = exif_read_data($path, "IFD0");
    if($exif === false){
      return false;
    }
    
    error_reporting($oldErrorLevel);
    if (empty($exif['Orientation'])){
      return false;
    }

    switch ($exif['Orientation']){
      case 3:
        $image = imagerotate($image, 180, 0);
          break;
      case 6:
        $image = imagerotate($image, - 90, 0);
          break;
      case 8:
        $image = imagerotate($image, 90, 0);
          break;
    }
    imagejpeg($image, $path);
    return true;
  }
}

if (!function_exists('_unlinkMedia'))
{
	function _unlinkMedia(array $a)
	{
		if (is_array($a['name'])) {
			foreach ($a['name'] as $k => $v) {
				_unlinkMedia([
					'path' => $a['path'],
					'name' => $v
				]);
			}
		}else{
			$aThumbnails = gtbin($a['name']);
			foreach ($aThumbnails as $k2 => $v2) {
				if (file_exists($a['path'].$v2)) {
					unlink($a['path'].$v2);
				}
			}
		}
	}
}