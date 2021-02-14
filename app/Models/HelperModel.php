<?php namespace App\Models;
// use CodeIgniter\Model;
/**
 * @author  Junaid Ahmed Khan (https://www.softwebz.com)
 */
class HelperModel
{
  public function getBuilder(string $sTable, array $a = [])
  {
  	$class = '\App\Models\\'.ucfirst($sTable).'Model';
  	// echo $class;
  	// die();
    $o = new $class();
    $o->setProp($a);
    // $o = model('App\Models\\'.ucfirst($sTable).'Model')/*->builder()*/;
    return $o; 
  }
}
?>