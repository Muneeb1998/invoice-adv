<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @author  Junaid Ahmed Khan (https://www.softwebz.com)
 */
class PdfGenerate
{
    // protected $_ci;
    private $aConf;
    public function __construct(array $aConf = [])
    {
        // $this->_ci = $_ci;
        $this->aConf = $aConf;
    }
     function generate()
    {
        session_write_close();
        $client = \Config\Services::curlrequest();
        $res = $client->get(API_URL . 'pu/verifyFormHash/' . $this->aConf['sHash']);
        $aRes = json_decode($res->getBody(), true); 
        // print_r($aRes);
        // die();
        if (!$aRes['success']) {
            return redirect()
                ->to(site_url('notfound'));
        }
        $aData['aInvoiceData'] =  $aRes['data'];
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        // echo view('default/pdf', $aData);
        // die();
        $dompdf->loadHtml(view('default/pdf', $aData));
        // (Optional) Setup the paper size and orientation
        // die('lib');
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        if (isset($this->aConf['output'])) {
            $pdfString = $dompdf->output();
            return $pdfString;
        }
        $dompdf->stream("invoice-" . $aData['aInvoiceData']['invoice_no'] . '.pdf', array("Attachment" => 0));
        exit(0);
        // $dompdf->stream("invoice-" . $aData['aInvoiceData']['invoice_no'], array("Attachment" => 0));
        // Output the generated PDF to Browser
    }
    public function pdfToZip()
    {
        $aData = $this->aConf;
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $path = DIR_ASSETS . 'pdf/';
        foreach ($aData as $k => $v) {
            $dompdf = new Dompdf($options);
            $v['aInvoiceData'] = $v;
            $dompdf->loadHtml(view('default/pdf', $v));
            $dompdf->setPaper("a4", "portrait");
            $dompdf->render();
            $output = $dompdf->output();
            $pdfFileName = $v['invoice_no'] . '.pdf';
            unset($dompdf);
            if (!file_exists($path)) {
                mkdir($path, 0777);
            }
            file_put_contents($path . $pdfFileName, $output);
        }
        $zip = new \ZipArchive;
        $filename = "assets/invoice.zip";
        if (file_exists(FCPATH . $filename)) {
                unlink(FCPATH . $filename);
        }
        if ($zip->open($filename, \ZipArchive::CREATE) !== TRUE) {
            exit("cannot open <$filename>\n");
        }
        $dir = 'assets/pdf/';
        // $filename = "myzipfile.zip";

        $this->createZip($zip, $dir);
        $zip->close();
        $this->deleteDir($dir);
        return [];
        // if (file_exists(FCPATH . $filename)) {
        //     header('Content-Type: application/zip');
        //     header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        //     header('Content-Length: ' . filesize($filename));
            
        //     flush();
        //     readfile($filename);
        //     // delete file
        //     unlink(FCPATH . $filename);
        // }
    }
    function createZip($zip, $dir)
    {
        // echo $dir;
        // die();
        if (is_dir($dir)) {

            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {

                    // If file
                    if (is_file($dir . $file)) {
                        if ($file != '' && $file != '.' && $file != '..') {
                            $zip->addFile($dir . $file);
                        }
                    } else {
                        // If directory
                        if (is_dir($dir . $file)) {

                            if ($file != '' && $file != '.' && $file != '..') {

                                // Add empty directory
                                $zip->addEmptyDir($dir . $file);

                                $folder = $dir . $file . '/';

                                // Read data of the folder
                                $this->createZip($zip, $folder);
                            }
                        }
                    }
                }
                closedir($dh);
            }
        }
    }
    function deleteDir($dir)
    {
        if (is_dir($dir)) {

            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {

                    // If file
                    if (is_file($dir . $file)) {
                        if ($file != '' && $file != '.' && $file != '..') {
                            unlink(FCPATH . $dir . $file);
                        }
                    } else {
                    }
                }
                closedir($dh);
            }
        }
    }
}
