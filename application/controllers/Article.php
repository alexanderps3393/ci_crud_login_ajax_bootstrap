<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Article extends CI_Controller{

  public function __construct()
  {
      parent::__construct();
      $this->load->model('Article_model');
      $this->load->model('Author_model');
  }

  public function index()
  {
      $this->load->helper('url');
      $data['list'] = $this->Author_model->get_rows();
			$data['author_id'] = $this->session->userdata('author_id');
			$data['name'] = $this->session->userdata('name');
			$data['role'] = $this->session->userdata('role');
      $this->load->view('article/index', $data);
  }

  public function ajax_list()
  {
      $list = $this->Article_model->get_datatables();
      $data = array();
      $no = $_POST['start'];
      foreach ($list as $article) {
          $no++;
          $row = array();
          $row[] = '<input type="checkbox" class="data-check" value="'.$article->id_article.'" onclick="showBottomDelete()"/>';
          $row[] = $article->title;
          $row[] = $article->content;
          $row[] = $article->name;
          //add html for action
          $row[] = $this->session->userdata('role') === 'admin' ? '<a class="btn btn-sm btn-primary" href="javascript:void()" title="Edit" onclick="editArticle('."'".$article->id_article."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                <a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="deleteArticle('."'".$article->id_article."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>' :
								'<a class="btn btn-sm btn-primary" href="javascript:void()" title="Edit" onclick="editArticle('."'".$article->id_article."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
			          ';
          $data[] = $row;
      }
      $output = array(
                      "draw" => $_POST['draw'],
                      "recordsTotal" => $this->Article_model->count_all(),
                      "recordsFiltered" => $this->Article_model->count_filtered(),
                      "data" => $data,
              );
      //output to json format
      echo json_encode($output);
  }

  public function ajax_edit($id)
  {
      $data = $this->Article_model->get_by_id($id);
      echo json_encode($data);
  }

  public function ajax_add()
  {
      $this->_validate();
      $data = array(
              'title' => $this->input->post('title'),
              'content' => $this->input->post('content'),
              'author_id' => $this->input->post('author_id')
          );
      $this->Article_model->save($data);
      echo json_encode(array("status" => TRUE));
  }

  public function ajax_update()
  {
      $this->_validate();
      $data = array(
              'title' => $this->input->post('title'),
              'content' => $this->input->post('content'),
              'author_id' => $this->input->post('author_id')

          );
      $this->Article_model->update(array('id_article' => $this->input->post('id_article')), $data);
      echo json_encode(array("status" => TRUE));
  }

  public function ajax_delete($id)
  {
      $this->Article_model->delete_by_id($id);
      echo json_encode(array("status" => TRUE));
  }

  public function ajax_list_delete()
   {
       $list_id = $this->input->post('id');
       foreach ($list_id as $id) {
           $this->Article_model->delete_by_id($id);
       }
       echo json_encode(array("status" => TRUE));
   }

  private function _validate()
  {
      $data = array();
      $data['error_string'] = array();
      $data['inputerror'] = array();
      $data['status'] = TRUE;

      if($this->input->post('title') == '')
      {
          $data['inputerror'][] = 'title';
          $data['error_string'][] = 'Title is required';
          $data['status'] = FALSE;
      }

      if($this->input->post('content') == '')
      {
          $data['inputerror'][] = 'content';
          $data['error_string'][] = 'Content is required';
          $data['status'] = FALSE;
      }

      if($this->input->post('author_id') == '')
      {
          $data['inputerror'][] = 'author_id';
          $data['error_string'][] = 'Author is required';
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
