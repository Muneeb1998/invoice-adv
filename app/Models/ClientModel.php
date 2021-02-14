<?php 
namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends model
{
    protected $table      = 'client' ;
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'name',
        'company',
        'addr1' ,
        'addr2' ,
        'city',
        'state',
        'zip',
        'country',
        'email',
        'web_addr',
        'phone_contact',
        'mobile_contact',
        'fax',
        'is_archive',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    public function setProp(array $a)
    {

        $act = '';
        switch (@$a['act']) {
            case 'c':
                
            case 'u':
                $act = $a['act'].'_';
                break;
            case 'un_c':
                $act = $a['act'].'_';
            break;
        }
        $this->validationRules = $act.$this->table;
    }
}