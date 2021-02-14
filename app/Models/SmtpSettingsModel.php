<?php 
namespace App\Models;

use CodeIgniter\Model;

class SmtpSettingsModel extends model
{
    protected $table      = 'smtp_settings' ;
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'smtpHost',
        'smtpUsername',
        'smtpPass',
        'smtpPort',
        'bcc',
        'replyTo',
        'setFromEmail',
        'setFromName',
        'SMTPSecure',
        'auth',
        'admin_id'
    ];

    protected $useTimestamps = false;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

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