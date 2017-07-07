<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
	public function __construct()
  {
      parent::__construct();
      $this->load->model('Author_model');
  }

	public function index()
	{
		$this->load->helper('url');
		$data['list'] = $this->Author_model->get_list();
		$data['name'] = $this->session->userdata('name');
		$this->load->view('admin/index', $data);
	}
	public function ajax_list()
  {
      $list = $this->Author_model->get_datatables();
      $data = array();
      $no = $_POST['start'];
      foreach ($list as $user) {
          $no++;
          $row = array();
          $row[] = $_POST['start'] += 1;
          $row[] = $user->name;
          $row[] = $user->username;
					$row[] = $user->email;
					$row[] = $user->role;
					//add html for action
          $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void()" title="Edit" onclick="editUser('."'".$user->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                <a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="deleteUser('."'".$user->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>
			          ';
          $data[] = $row;
      }
      $output = array(
                      "draw" => $_POST['draw'],
                      "recordsTotal" => $this->Author_model->count_all(),
                      "recordsFiltered" => $this->Author_model->count_filtered(),
                      "data" => $data,
              );
      //output to json format
      echo json_encode($output);
  }

  public function ajax_edit($id)
  {
      $data = $this->Author_model->get_by_id($id);
      echo json_encode($data);
  }

  public function ajax_add()
  {
      $this->_validate();
      $data = array(
              'name' => $this->input->post('name'),
              'username' => $this->input->post('username'),
              'password' => md5($this->input->post('password')),
							'email' => $this->input->post('email'),
							'role' => $this->input->post('role'),
          );
      $this->Author_model->save($data);
      echo json_encode(array("status" => TRUE));
  }

  public function ajax_update()
  {
      $this->_validate();
      $data = array(
				'name' => $this->input->post('name'),
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password')),
				'email' => $this->input->post('email'),
				'role' => $this->input->post('role'),
      );
      $this->Author_model->update(array('id' => $this->input->post('id')), $data);
      echo json_encode(array("status" => TRUE));
  }

  public function ajax_delete($id)
  {
      $this->Author_model->delete_by_id($id);
      echo json_encode(array("status" => TRUE));
  }

  public function ajax_list_delete()
   {
       $list_id = $this->input->post('id');
       foreach ($list_id as $id) {
           $this->Author_model->delete_by_id($id);
       }
       echo json_encode(array("status" => TRUE));
   }

  private function _validate()
  {
      $data = array();
      $data['error_string'] = array();
      $data['inputerror'] = array();
      $data['status'] = TRUE;

      if($this->input->post('name') == '')
      {
          $data['inputerror'][] = 'name';
          $data['error_string'][] = 'Name is required';
          $data['status'] = FALSE;
      }

      if($this->input->post('username') == '')
      {
          $data['inputerror'][] = 'username';
          $data['error_string'][] = 'Username is required';
          $data['status'] = FALSE;
      }

			if($this->input->post('password') == '')
      {
          $data['inputerror'][] = 'password';
          $data['error_string'][] = 'Password is required';
          $data['status'] = FALSE;
      }

			if($this->input->post('email') == '')
      {
          $data['inputerror'][] = 'email';
          $data['error_string'][] = 'Email is required';
          $data['status'] = FALSE;
      }

      if($this->input->post('role') == '')
      {
          $data['inputerror'][] = 'role';
          $data['error_string'][] = 'Role is required';
          $data['status'] = FALSE;
      }


      if($data['status'] === FALSE)
      {
          echo json_encode($data);
          exit();
      }
  }

  private function _validate_string($string)
  {
      $allowed = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
      for ($i=0; $i<strlen($string); $i++)
      {
          if (strpos($allowed, substr($string,$i,1))===FALSE)
          {
              return FALSE;
          }
      }

     return TRUE;
  }

  private function _validate_number($string)
  {
      $allowed = "0123456789";
      for ($i=0; $i<strlen($string); $i++)
      {
          if (strpos($allowed, substr($string,$i,1))===FALSE)
          {
              return FALSE;
          }
      }

     return TRUE;
  }
}
