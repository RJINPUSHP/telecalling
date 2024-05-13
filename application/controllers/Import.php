<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Import extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('Task_model');
        $this->isLoggedIn();
        $this->load->helper('form');
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Political Calling : Dashboard';
        $this->loadViews("import_form", $this->global, NULL, NULL);
    }
    public function do_import()
    {
        // Check if a CSV file was uploaded
 
            
               if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == UPLOAD_ERR_OK) {
         
            $file_path = $_FILES['csv_file']['tmp_name'];

            // Read the CSV file
            $csv_data = array_map('str_getcsv', file($file_path));

            // Remove header if exists
            $header = array_shift($csv_data);

            // Load the database library
            $this->load->database();

            // Insert data into the database
            foreach ($csv_data as $row) {
                if($row[0] && $row[1] && $row[2] && $row[3] && $row[4] && $row[5]){
                $data = array(
                    'name' => $row[0],
                    'mobile' => $row[1],
                    'village' => $row[2],
                    'booth' => $row[3],
                    'state' => $row[4],
                    'city' => $row[5],
                    'createdBy' => $this->vendorId,
                    'createdDtm' => date('Y-m-d H:i:s') 
                );
                
                $this->db->insert('tbl_booking', $data);
                }
            } 
          
        } else {
            // Handle file upload error
            $error = $_FILES['csv_file']['error'];
           
        }
          redirect('booking/bookingListing');
    }

}