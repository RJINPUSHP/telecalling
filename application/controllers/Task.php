<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Task (TaskController)
 * Task Class to control task related operations.
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 19 Jun 2022
 */
class Task extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Task_model', 'tm');
        $this->isLoggedIn();
        $this->module = 'Task';
    }

    /**
     * This is default routing method
     * It routes to default listing page
     */
    public function index()
    {
        redirect('task/taskListing');
    }
    
     function count_all_data()
	{
		$query = "SELECT * FROM tbl_task WHERE isDeleted = '0' ";
		$row = $this->db->query($query);
		$rr = $row->result();
		return  count($rr);
	}
    function list()
	{
		$column = array("taskId", "name", "mobile", "callres", "callstatus", "callerId", "createdDtm");
		$query = "SELECT * FROM tbl_task WHERE isDeleted = '0' ";
  if (isset($_POST["search"]["value"]) && $_POST["search"]["value"]!='') {
			$query .= '	and name LIKE "%' . $_POST["search"]["value"] . '%" 
						OR mobile LIKE "%' . $_POST["search"]["value"] . '%" ';
            		}


		if (isset($_POST['order'])) {
			$query .= ' ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
		} else {
			$query .= ' ORDER BY taskId ASC ';
		}

		$query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		$statement = $this->db->query($query);
		$result = $statement->result_array();
		$data = array();
		foreach ($result as $key => $row) {
            $callerId = $row['callerId'];
            $q = $this->db->query("SELECT * FROM `tbl_booking` WHERE `bookingId`='$callerId' ");
            $userdata = $q->row();
			$sub_array = array();
			$sub_array[] = $row['taskId'];
			$sub_array[] = $row['name'];
            $sub_array[] = $userdata->fathername;

			$sub_array[] = $row['mobile'];
			$sub_array[] = $userdata->dob;
			$sub_array[] =$userdata->gender;

			$sub_array[] = $userdata->village;
            $sub_array[] = $row['callres'];
            $sub_array[] = $row['callstatus'];

            $createdBy = $row['createdBy'];
            $q1 = $this->db->query("SELECT * FROM `tbl_booking` WHERE `bookingId`='$createdBy' ");
            $agent = $q1->row();
            $sub_array[] =    $agent->name;
			$sub_array[] = date('d-m-Y', strtotime($row['createdDtm']));            
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
    
    
    /**
     * This function is used to load the task list
     */
    function taskListing()
    {
        if(!$this->hasListAccess())
        {
            $this->loadThis();
        }
        else
        {        
            $searchText = '';
            if(!empty($this->input->post('searchText'))) {
                $searchText = $this->security->xss_clean($this->input->post('searchText'));
            }
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->tm->taskListingCount($searchText);

			$returns = $this->paginationCompress ( "taskListing/", $count, 10 );
            
            $data['records'] = $this->tm->taskListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Political Calling : Task';
            
            $this->loadViews("task/listwithcsv", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function add()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = 'Political Calling : Add New Task';

            $this->loadViews("task/add", $this->global, NULL, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewTask()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('taskTitle','Task Title','trim|callback_html_clean|required|max_length[256]');
            $this->form_validation->set_rules('description','Description','trim|callback_html_clean|required|max_length[1024]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->add();
            }
            else
            {
                $taskTitle = $this->security->xss_clean($this->input->post('taskTitle'));
                $description = $this->security->xss_clean($this->input->post('description'));
                
                $taskInfo = array('taskTitle'=>$taskTitle, 'description'=>$description, 'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->tm->addNewTask($taskInfo);
                
                if($result > 0) {
                    $this->session->set_flashdata('success', 'New Task created successfully');
                } else {
                    $this->session->set_flashdata('error', 'Task creation failed');
                }
                
                redirect('task/taskListing');
            }
        }
    }

    
    /**
     * This function is used load task edit information
     * @param number $taskId : Optional : This is task id
     */
    function edit($taskId = NULL)
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            if($taskId == null)
            {
                redirect('task/taskListing');
            }
            
            $data['taskInfo'] = $this->tm->getTaskInfo($taskId);

            $this->global['pageTitle'] = 'Political Calling : Edit Task';
            
            $this->loadViews("task/edit", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editTask()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $taskId = $this->input->post('taskId');
            
            $this->form_validation->set_rules('taskTitle','Task Title','trim|callback_html_clean|required|max_length[256]');
            $this->form_validation->set_rules('description','Description','trim|callback_html_clean|required|max_length[1024]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->edit($taskId);
            }
            else
            {
                $taskTitle = $this->security->xss_clean($this->input->post('taskTitle'));
                $description = $this->security->xss_clean($this->input->post('description'));
                
                $taskInfo = array('taskTitle'=>$taskTitle, 'description'=>$description, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->tm->editTask($taskInfo, $taskId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Task updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Task updation failed');
                }
                
                redirect('task/taskListing');
            }
        }
    }

    public function html_clean($s, $v)
    {
        return strip_tags((string) $s);
    }
}

?>