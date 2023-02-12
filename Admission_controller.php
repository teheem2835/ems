<?php
class Admission_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->model('admission_model');
    }
    public function create_admission()
    {
        $page_data['get_branch'] = $this->admission_model->get_branch();
        $page_data['get_department'] = $this->admission_model->get_department();
        $page_data['get_guardian'] = $this->admission_model->get_guardian();
        $page_data['get_year'] = $this->admission_model->get_year();

        $this->load->model('admission_model');
        $this->load->model('User_model');
        $this->load->model('guardian_model');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('gender', 'Student Gender', 'required');
        $this->form_validation->set_rules('D_O_B', 'Date Of Birth', 'required');
        $this->form_validation->set_rules('mobile_no', 'Mobile_no', 'required');
        $this->form_validation->set_rules('previous_qualification', 'Previous Qualification', 'required');
        // $this->form_validation->set_rules('guardian_name', 'Guardian Name', 'required');
        // $this->form_validation->set_rules('guardian_mobile_no', 'Guardian Mobile_no', 'required');
        // $this->form_validation->set_rules('guardian_relation', 'Guardian Relation', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('department_id', 'Dept Name', 'required');
        $this->form_validation->set_rules('branch_id', 'Branch Name ', 'required');
        $this->form_validation->set_rules('category', 'Category', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('reg_no', 'reg_no', 'required');
        $this->form_validation->set_rules('roll_no', 'roll_no', 'required');
        $this->form_validation->set_rules('year_id', 'year_id', 'required');

        $this->form_validation->set_rules('startDate', 'Admission Date', 'required');
        $this->form_validation->set_rules('endDate', 'Admission Date', 'required');
        //$this->form_validation->set_rules('guardian_id', 'guardian_id', 'required');


        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('failure', 'data cannot be added');

            $this->load->view('admission/create_admission', $page_data);
        } else {

            // data entery in user table as student
            $std_array['name'] = $this->input->post('first_name') . " " . $this->input->post('last_name');
            $std_array['email'] = $this->input->post('email');
            $std_array['password'] = $this->input->post('password');
            $std_array['mobile_no'] = $this->input->post('mobile_no');
            $std_array['address'] = $this->input->post('address');
            $std_array['D_O_B'] = $this->input->post('D_O_B');
            $std_array['role_id'] = 3;
            // $std_array['created_at'] = date('Y-m-d');
            $std_id = $this->User_model->create($std_array);
            //     print_r($std_id);
            // die;

            // data entery in guardian table
            $g_id = $this->input->post('guardian_id');
            if ($g_id) {
                $guar_id = $g_id;
            } else {
                $guar_array['guardian_name'] = $this->input->post('guardian_name');
                $guar_array['guardian_mobile_no'] = $this->input->post('guardian_mobile_no');
                $guar_array['guardian_relation'] = $this->input->post('guardian_relation');
                $guar_id = $this->guardian_model->create_guardian($guar_array);
            }
            //    print_r($guar_id);
            //     die;
            // $dob = new DateTime('D_O_B');
            // $today   = new DateTime('today');
          
            // $year = $dob->diff($today)->y;
           
            // echo $year;
            // data entery in admission table 

            $adm_array['first_name'] = $this->input->post('first_name');
            $adm_array['last_name'] = $this->input->post('last_name');
            $adm_array['std_id'] = $std_id;
            $adm_array['gender'] = $this->input->post('gender');
            $adm_array['reg_no'] = $this->input->post('reg_no');
            $adm_array['roll_no'] = $this->input->post('roll_no');
            $adm_array['D_O_B'] =$this->input->post('D_O_B');
            $adm_array['password'] = $this->input->post('password');
            $adm_array['address'] = $this->input->post('address');
            $adm_array['email'] = $this->input->post('email');
            $adm_array['mobile_no'] = $this->input->post('mobile_no');
            $adm_array['year'] = $this->input->post('year_id');
            $adm_array['admission_date'] = $this->input->post('startDate') . " To " . $this->input->post('endDate');
            $adm_array['previous_qualification'] = $this->input->post('previous_qualification');
            $adm_array['department_id'] = $this->input->post('department_id');
            $adm_array['guardian_id'] = $guar_id;

            $adm_array['branch_id'] = $this->input->post('branch_id');
            $adm_array['category_id'] = $this->input->post('category');
            $adm_array['created_at'] = date('Y-m-d');
            // print_r($adm_array);
            // exit();

            $this->admission_model->admissionUser($adm_array);
            $this->session->set_flashdata('success', 'data added successfully');
            redirect(base_url('admission_controller/list_admission'));
        }


    }
    public function list_admission()
    {
        $this->load->model('admission_model');
        $admission = $this->admission_model->all();
        // print_r($admission);
        // exit();
        $data = array();
        $data['admission'] = $admission;

        $this->load->view('admission/list_admission', $data);

    }
    function edit_admission($admissionId)
    {


        $this->load->model('admission_model');
        $this->load->model('department_model');
        $this->load->model('branch_model');
        $this->load->model('User_model');

    

        $admission = $this->admission_model->getAdmission($admissionId);
        $page_data['admission'] = $admission;
        $get_branch = $this->admission_model->get_branch();
        $page_data['get_branch'] = $get_branch;
        $get_department = $this->admission_model->get_department();
        $page_data['get_department'] = $get_department;
        $get_year = $this->admission_model->get_year();
        $page_data['get_year'] = $get_year;
        $get_user = $this->admission_model->get_user();
        $page_data['get_user'] = $get_user;
        // print_r($page_data);
        // exit();       

        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        //$this->form_validation->set_rules('gender', 'Student Gender', 'required');
        $this->form_validation->set_rules('D_O_B', 'Date Of Birth', 'required');
        $this->form_validation->set_rules('mobile_no', 'Mobile_no', 'required');
        $this->form_validation->set_rules('previous_qualification', 'Previous Qualification', 'required');
        // $this->form_validation->set_rules('guardian_name', 'Guardian Name', 'required');
        // $this->form_validation->set_rules('guardian_mobile_no', 'Guardian Mobile_no', 'required');
        // $this->form_validation->set_rules('guardian_relation', 'Guardian Relation', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('department_id', 'Dept Name', 'required');
        $this->form_validation->set_rules('branch_id', 'Branch Name ', 'required');
        //$this->form_validation->set_rules('category', 'Category', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('reg_no', 'reg_no', 'required');
        $this->form_validation->set_rules('roll_no', 'roll_no', 'required');
        $this->form_validation->set_rules('year_id', 'year_id', 'required');

        // $this->form_validation->set_rules('startDate', 'Admission Date', 'required');
        // $this->form_validation->set_rules('endDate', 'Admission Date', 'required');
        //$this->form_validation->set_rules('guardian_id', 'guardian_id', 'required');
        if ($this->form_validation->run() == false) {

            // echo "if";
            // exit;
            $this->session->set_flashdata('failure', 'data cannot be added');


            $page_data['get_branch'] = $this->admission_model->get_branch();
            $page_data['get_department'] = $this->admission_model->get_department();
            $page_data['get_year'] = $this->admission_model->get_year();

            $this->load->view('admission/edit_admission', $page_data);
            } else {


            // data entery in user table as student
            $std_array['name'] = $this->input->post('first_name') . " " . $this->input->post('last_name');
            $std_array['email'] = $this->input->post('email');
            $std_array['password'] = $this->input->post('password');
            $std_array['mobile_no'] = $this->input->post('mobile_no');
            $std_array['address'] = $this->input->post('address');
            $std_array['D_O_B'] = $this->input->post('D_O_B');
          
            // $std_array['created_at'] = date('Y-m-d');
            // print_r( $std_array);
            // exit();
            $this->load->model('User_model');
         $this->User_model->updateUser($admissionId, $std_array);
            // echo "bhb";
            // exit();
            // data entery in admission table 

            $adm_array['first_name'] = $this->input->post('first_name');
            $adm_array['last_name'] = $this->input->post('last_name');
           // $adm_array['gender'] = $this->input->post('gender');
            $adm_array['reg_no'] = $this->input->post('reg_no');
            $adm_array['roll_no'] = $this->input->post('roll_no');
            $adm_array['D_O_B'] = $this->input->post('D_O_B');
            $adm_array['password'] = $this->input->post('password');
            $adm_array['address'] = $this->input->post('address');
            $adm_array['email'] = $this->input->post('email');
            $adm_array['mobile_no'] = $this->input->post('mobile_no');
            $adm_array['year'] = $this->input->post('year_id');
           // $adm_array['admission_date'] = $this->input->post('startDate') . " To " . $this->input->post('endDate');
            $adm_array['previous_qualification'] = $this->input->post('previous_qualification');
            $adm_array['department_id'] = $this->input->post('department_id');

            $adm_array['branch_id'] = $this->input->post('branch_id');
            //$adm_array['category_id'] = $this->input->post('category');
            // $adm_array['created_at'] = date('Y-m-d');
           

            $this->admission_model->updateadmission($adm_array, $admissionId);
            $this->session->set_flashdata('success', 'data added successfully');
            redirect(base_url('admission_controller/list_admission'));
        }
    }

    function delete_admission($admissionId)
    {
        $this->load->model('admission_model');
        $user = $this->admission_model->getAdmission($admissionId);
        if (empty($user)) {
            $this->session->set_flashdata('failure', 'record not found in database');
            redirect(base_url() . 'admission_controller/list_admission');
        }
        $this->admission_model->delete_admission($admissionId);
        $this->session->set_flashdata('success', 'record deleted successfullyfrom database');
        redirect(base_url() . 'admission_controller/list_admission');
    }
}








?>