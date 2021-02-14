<?php 
namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends model
{
    protected $table      = 'invoice' ;
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'client_id',
        'invoice_no',
        'currency',
        'issue_date',
        'invoice_due',
        'invoice_due_in',
        'item_desciption',
        'quantity',
        'amount',
        'discount_rate',
        'access_hash',
        'to',
        'from',
        'sub_total',
        'discount_name',
        'discount_amount',
        'total',
        'status',
        'created_by',
        'footer',
        'unpaid_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
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