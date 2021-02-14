<?php 
namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends model
{
    protected $table      = 'admin' ;
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'name',
        'role',
        'email',
        'last_login_at',
        'password',
        'identity'
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