<?php defined('BASEPATH') or exit('No direct script access allowed');
class Samples_controller extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$isLogin = $this->session->userdata('LoggedIn');
		if (!$isLogin) {
			$this->session->sess_destroy();
			redirect('portal');
		} else {
			$this->load->model('Model_folder/Samples_model', 'm');
		}
	}

	public function index()
	{
		$data['Root'] = "Samples Root";
		$data['Title'] = "Samples Title";
		$data['Breadcrumb'] = array('Samples');
		$data['Template'] = "templates/private";
		$data['Components'] = array(
			'main' => "/v_private",
			'header' => $data['Template'] . "/components/v_header",
			'sidebar' => $data['Template'] . "/components/v_sidebar",
			'navbar' => $data['Template'] . "/components/v_navbar",
			'footer' => $data['Template'] . "/components/v_footer",
			'content' => "samples_view/v_samples"
		);
		$this->load->view('v_main', $data);
	}

	public function list_data()
	{
		header('Content-Type: application/json');
		echo $this->m->get_list_data();
	}

	public function simpan()
	{
		// Code here
	}

	public function get_data()
	{
		$result = $this->m->get_data();
		echo json_encode($result);
	}

	public function hapus()
	{
		$data = array(
			'deleted' => TRUE,
			'updated_by' => $this->session->userdata('nama'),
			'updated_date' => date('Y-m-d H:i:s')
		);
		$this->m->hapus($data);
		$pesan = array(
			'warning' => 'Berhasil!',
			'kode' => 'success',
			'pesan' => 'Data berhasil di hapus!'
		);
		echo json_encode($pesan);
	}

	public function options()
	{
		$searchTerm = $this->input->post('searchTerm');
		$response = $this->m->options($searchTerm);
		echo json_encode($response);
	}
}
