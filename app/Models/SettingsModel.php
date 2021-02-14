<?php 
namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends model
{
    protected $table      = 'settings' ;
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'admin_id',
        'company',
        'paypal_email',
        'mobile',
        'invoice_pattern',
        'invoice_logo',
        'street1',
        'street2',
        'city',
        'state',
        'zip_code',
        'country',
        'footer_email',
        'logo_size'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

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