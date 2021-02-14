<?php 
namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends model
{
    protected $table      = 'payment' ;
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'invoice_id',
        'client_id',
        'payment_amount',
        'payment_currency',
        'is_mailed',
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