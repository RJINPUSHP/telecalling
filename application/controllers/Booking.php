<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Booking (BookingController)
 * Booking Class to control booking related operations.
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 18 Jun 2022
 */
class Booking extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Booking_model', 'bm');
        $this->isLoggedIn();
        $this->module = 'Booking';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
     
     
     	function list()
	{
		$column = array("bookingId", "name", "mobile", "village", "state", "city", "booth", "createdDtm");
		$query = "SELECT * FROM tbl_booking WHERE isDeleted = '0' ";
 
		if (isset($_POST["search"]["value"]) && $_POST["search"]["value"]!='') {
			$query .= '	and name LIKE "%' . $_POST["search"]["value"] . '%" 
						OR mobile LIKE "%' . $_POST["search"]["value"] . '%" ';
            		}

		if (isset($_POST['order'])) {
			$query .= ' ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
		} else {
			$query .= ' ORDER BY bookingId ASC ';
		}

		$query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		$statement = $this->db->query($query);
		$result = $statement->result_array();
		$data = array();
		foreach ($result as $row) {
			$sub_array = array();
			$sub_array[] = $row['bookingId'];
			$sub_array[] = $row['name'];
			     $sub_array[] = $row['fathername'];
			$sub_array[] = $row['mobile'];
			$sub_array[] = $row['dob'];
			$sub_array[] = $row['gender'];
			$sub_array[] = $row['village'];
            // $sub_array[] = $row['state'];
            // $sub_array[] = $row['city'];
			$sub_array[] = date('d-m-Y', strtotime($row['createdDtm']));
            $action = '';
 			$action .= anchor('booking/delete/' . $row['bookingId'], '<i class="fa fa-times"></i>', array("class" => "btn btn-danger", "onclick" => "return confirm('Are you sure delete?')"));
			$sub_array[] = $action;
			$data[] = $sub_array;
		}

		$output = array(
			"draw"		=>	intval($_POST["draw"]),
			"recordsTotal"	=>	$this->count_all_data(),
			"recordsFiltered"	=>		$this->count_all_data(),
			"data"		=>	$data
		);
		echo json_encode($output);
	}

	function count_all_data()
	{
		$query = "SELECT * FROM tbl_booking WHERE isDeleted = '0' ";
		$row = $this->db->query($query);
		$rr = $row->result();
		return  count($rr);
	}



    public function index()
    {
        redirect('booking/bookingListing');
    }

    /**
     * This function is used to load the booking list
     */
    function bookingListing()
    {
        if (!$this->hasListAccess()) {
            $this->loadThis();
        } else {
            $searchText = '';
            if (!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;

            $this->load->library('pagination');

            $count = $this->bm->bookingListingCount($searchText);

            $returns = $this->paginationCompress("bookingListing/", $count, 10);

            $data['records'] = $this->bm->bookingListing($searchText, $returns["page"], $returns["segment"]);

            $this->global['pageTitle'] = 'Political Calling : Customer';

            $this->loadViews("booking/list", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function add()
    {
        if (!$this->hasCreateAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'Political Calling : Add New Customer';

            $this->loadViews("booking/add", $this->global, NULL, NULL);
        }
    }

    /**
     * This function is used to add new user to the system
     */
    function addNewBooking()
    {
        if (!$this->hasCreateAccess()) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('name', 'Name', 'trim|callback_html_clean|required|max_length[50]');
            $this->form_validation->set_rules('mobile', 'mobile', 'trim|callback_html_clean|required|max_length[1024]');

            if ($this->form_validation->run() == FALSE) {
                $this->add();
            } else {
                $name = $this->security->xss_clean($this->input->post('name'));
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                $village = $this->security->xss_clean($this->input->post('village'));
                $booth = $this->security->xss_clean($this->input->post('booth'));
                $state = $this->security->xss_clean($this->input->post('state'));
                $city = $this->security->xss_clean($this->input->post('city'));
                $dob = $this->security->xss_clean($this->input->post('dob'));
                $pincode = $this->security->xss_clean($this->input->post('pincode'));
                $gender = $this->security->xss_clean($this->input->post('gender')); 
                $fathername = $this->security->xss_clean($this->input->post('fathername')); 

                $bookingInfo = array(
                    'name' => $name,
                    'mobile' => $mobile,
                    'village' => $village,
                    'booth' => $booth,
                    'state' => $state,
                    'city' => $city,
                    'dob' => $dob,
                    'pincode' => $pincode,
                    'gender' => $gender,
                    'fathername' => $fathername,
                    'createdBy' => $this->vendorId,
                    'createdDtm' => date('Y-m-d H:i:s')
                );

                $result = $this->bm->addNewBooking($bookingInfo);

                if ($result > 0) {
                    $this->session->set_flashdata('success', 'New Customer created successfully');
                } else {
                    $this->session->set_flashdata('error', 'Customer creation failed');
                }

                redirect('booking/bookingListing');
            }
        }
    }


    /**
     * This function is used load booking edit information
     * @param number $bookingId : Optional : This is booking id
     */
    function edit($bookingId = NULL)
    {
        if (!$this->hasUpdateAccess()) {
            $this->loadThis();
        } else {
            if ($bookingId == null) {
                redirect('booking/bookingListing');
            }

            $data['bookingInfo'] = $this->bm->getBookingInfo($bookingId);

            $this->global['pageTitle'] = 'Political Calling : Edit Customer';

            $this->loadViews("booking/edit", $this->global, $data, NULL);
        }
    }


    /**
     * This function is used to edit the user information
     */
    function editBooking()
    {
        if (!$this->hasUpdateAccess()) {
            $this->loadThis();
        } else {
            $this->load->library('form_validation');

            $bookingId = $this->input->post('bookingId');

            $this->form_validation->set_rules('name', 'Name', 'trim|callback_html_clean|required|max_length[50]');
            $this->form_validation->set_rules('mobile', 'mobile', 'trim|callback_html_clean|required|max_length[1024]');

            if ($this->form_validation->run() == FALSE) {
                $this->edit($bookingId);
            } else {
                $name = $this->security->xss_clean($this->input->post('name'));
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                $village = $this->security->xss_clean($this->input->post('village'));
                $booth = $this->security->xss_clean($this->input->post('booth'));
                $state = $this->security->xss_clean($this->input->post('state'));
                $city = $this->security->xss_clean($this->input->post('city'));

                $bookingInfo = array(
                    'name' => $name,
                    'mobile' => $mobile,
                    'village' => $village,
                    'booth' => $booth,
                    'state' => $state,
                    'city' => $city,
                    'updatedBy' => $this->vendorId,
                    'updatedDtm' => date('Y-m-d H:i:s')
                );

                $result = $this->bm->editBooking($bookingInfo, $bookingId);

                if ($result == true) {
                    $this->session->set_flashdata('success', 'Customer updated successfully');
                } else {
                    $this->session->set_flashdata('error', 'Customer updation failed');
                }

                redirect('booking/bookingListing');
            }
        }
    }

    public function html_clean($s, $v)
    {
        return strip_tags((string) $s);
    }

    function delete($id)
    {       
        $userInfo = array('isDeleted' => 1, 'updatedBy' => $this->vendorId, 'updatedDtm' => date('Y-m-d H:i:s'));
        $result = $this->bm->delete($id, $userInfo);
        redirect('booking/bookingListing');

    }


}

?>