<?php namespace App\Libraries;
/**
 * @author  Junaid Ahmed Khan (https://www.softwebz.com)
 */
class DataTable
{
  public function __construct(array $a = [])
  {
    foreach ($a as $k => $v) {
      $this->{$k} = $v;
    }
  }

  public function g(array $a = [])
  {
    $o = (object) $a;
    unset($a);
    $aTable = explode(' as ', $o->sTable);
    $aTable[0] = str_replace(' ', '', lcfirst(ucwords(str_replace('_', ' ', $aTable[0]))));
    unset($o->sTable);
    // $o->sTable = $aTable[0];
    // $o->sTableAlias = $aTable[1];
    $bFiltered = false;
    // $o->aCols;
    $aBP = [];//$aBP
    if (isset($aTable[1])) {
      $aBP['tableAlias'] = $aTable[1];
    }
    $oBuilder = $this->modelHelper->getBuilder($aTable[0], $aBP);
    $oBuilder->select($o->aCols);
    if (isset($o->aJoin)) {
      foreach ($o->aJoin as $k => $v) {
        $oBuilder->join($v[0], $v[1], (isset($v[2]) ? $v[2] : ''));
      }
    }
     if (isset($o->order)) {
        $oBuilder->orderBy('id','desc');
    }
    if (isset($o->aWhere)) {
      foreach ($o->aWhere as $k => $v) {
        $oBuilder->where($v[0], $v[1]);
      }
    }
    if (isset($o->aWhereIn)) {
      foreach ($o->aWhereIn as $k => $v) {
        $oBuilder->whereIn($v[0], $v[1]);
      }
    }
    /*if (isset($this->aVars['advFilter']) && !empty($this->aVars['advFilter'])) {
      foreach ($this->aVars['advFilter'] as $k => $v) {
        switch ($k) {
          case 'equal':
            foreach($v as $key => $val){
              if (!empty($val)) {
                $bFiltered = true;
                $aParams['aWhere']['emp.'.$key] = $val;
              }
            }
            break;
          case 'match':
            foreach($v as $key => $val){
              if (!empty($val)) {
                $bFiltered = true;
                $aParams['aLike'][] = array(
                  'col'   => $key,
                  'match' => $val,
                  'opt'   => 'after'
                );
              }
            }
            break;
          case 'gt':
            foreach($v as $key => $val){
              if (!empty($val)) {
                $bFiltered = true;
                $aParams['aWhere']['emp.'.$key.'>'] = $this->setDateFormat($val);
              }
            }
            break;
          case 'lt':
            foreach($v as $key => $val){
              if (!empty($val)) {
                $bFiltered = true;
                $aParams['aWhere']['emp.'.$key.'<'] = $this->setDateFormat($val);
              }
            }
            break;
        }
      }
    }
    if ($bFiltered) {
      $aParamsTotal = $aParams;
      $aParamsTotal['getResultsCount'] = true;
      $totalRecords = $this->helper_model->getMany($aParamsTotal);
    }*/
    if(!empty($this->aVars['search']['value'])){
      $oBuilder->like($o->aCols[(isset($this->aVars['search']['column']) ? $this->aVars['search']['column'] : 1)], $this->aVars['search']['value'], 'after');
    }
    if (isset($this->aVars['order'])) {
      $oBuilder->orderBy(explode(' ', $o->aCols[explode(' ', $this->aVars['order'][0]['column'])[0]])[0], $this->aVars['order'][0]['dir']);
    }
    $totalRecords = $oBuilder->countAllResults(false);
    if(isset($this->aVars['start'])){
        
    $oBuilder->offset($this->aVars['start']);
    }
    $oBuilder->limit($this->aVars['length']);
    // die('t');
    if (isset($o->getCompiledSelect)) {
      print_r($oBuilder->getCompiledSelect());
      die();
    }
    $aRecord = $oBuilder->get()->getResultArray();
    $aData = [];
    if (isset($o->fnSetCol)) {
      $aData = call_user_func($o->fnSetCol, $aRecord);
      // $o->fnSetCol($aRecord);
    }else{
      foreach ($aRecord as $k => $v) {
        $aData[$k] = array_values((array)$v);
      }
    }
    return [
      'draw'            => intval($this->aVars['draw']),
      'recordsTotal'    => intval($totalRecords),
      'recordsFiltered' => intval((!empty($this->aVars['search']['value'])) ?count($aRecord):$totalRecords),
      'data'            => $aData
    ];
  }
}