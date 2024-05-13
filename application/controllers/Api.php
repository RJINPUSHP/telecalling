<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->model('Task_model');
        $this->load->model('Booking_model');

    }

    public function index()
    {
        // Your API logic goes here
        $data = array(
            'message' => 'This is an example API response',
            'timestamp' => time()
        );

        return $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function login()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[128]|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'message' => 'Enter your email address and password',
                'status' => false
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $email = strtolower($this->security->xss_clean($this->input->post('email')));
            $password = $this->input->post('password');
            $result = $this->login_model->loginMe($email, $password);
            if (!empty($result)) {
                $data = array(
                    'message' => 'Login',
                    'status' => true,
                    'data' => $result
                );
                $this->output->set_content_type('application/json')->set_output(json_encode($data));


            } else {
                $data = array(
                    'message' => 'Email or password mismatch',
                    'status' => false
                );
                $this->output->set_content_type('application/json')->set_output(json_encode($data));
            }
        }
    }
    public function dashboard()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('userid', 'user id', 'required|max_length[128]|trim');

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'message' => 'User id required ',
                'status' => false
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $userid = $this->input->post('userid');
            $current_date = date('Y-m-d');
            $data['todaycalls'] = $this->Task_model->getAllData('tbl_task', ['createdBy' => $userid, 'createdDtm like ' => "%$current_date%"]);
            $data['totalcalls'] = $this->Task_model->getAllData('tbl_task', ['createdBy' => $userid]);
            $data['todayanscalls'] = $this->Task_model->getAllData('tbl_task', ['createdBy' => $userid, 'callres' => 'Call answered', 'createdDtm like ' => "%$current_date%"]);
            $data['totalanscalls'] = $this->Task_model->getAllData('tbl_task', ['createdBy' => $userid, 'callres' => 'Call answered']);
            $data['todayunanscalls'] = $this->Task_model->getAllData('tbl_task', ['createdBy' => $userid, 'callres != ' => 'Call answered', 'createdDtm like ' => "%$current_date%"]);
            $data['totalunanscalls'] = $this->Task_model->getAllData('tbl_task', ['createdBy' => $userid, 'callres != ' => 'Call answered']);
            $data = array(
                'message' => 'Dashboard Message',
                'status' => true,
                'data' => $data
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }
    public function makeacall()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('userid', 'user id', 'required|max_length[128]|trim');

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'message' => 'User id required ',
                'status' => false
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $userid = $this->input->post('userid');

            $bid = $this->Booking_model->getRandomAllData(['createdBy' => $userid]);
            if (!empty($bid)) {
                $this->Booking_model->editBooking(['assign_to' => 1], $bid->bookingId);
            }
            $data = array(
                'message' => 'Make a call',
                'status' => true,
                'data' => $bid
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }
    public function submitfeedback()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required|max_length[128]|trim');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|max_length[128]|trim');
        $this->form_validation->set_rules('callres', 'Call Response', 'required|max_length[128]|trim');
        $this->form_validation->set_rules('callstatus', 'Call Status', 'max_length[128]|trim');
        $this->form_validation->set_rules('userid', 'User id', 'required|max_length[128]|trim');
        $this->form_validation->set_rules('callerId', 'Caller id', 'required|max_length[128]|trim');

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'message' => strip_tags($this->form_validation->error_string()),
                'status' => false
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        } else {
            $userid = $this->input->post('userid');
            $name = $this->input->post('name');
            $mobile = $this->input->post('mobile');
            $callres = $this->input->post('callres');
            $callstatus = $this->input->post('callstatus');
            
            $callerId = $this->input->post('callerId');
            
            $taskInfo = array(
                'callerId'=>$callerId,
                'name' => $name, 'mobile' => $mobile, 'callres' => $callres, 'callstatus' => $callstatus, 'createdBy' => $userid, 'createdDtm' => date('Y-m-d H:i:s'));
            $result = $this->Task_model->addNewTask($taskInfo);
            $data = array(
                'message' => 'Feedback added',
                'status' => true,
                'data' => $result
            );
            return $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }
}