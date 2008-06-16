<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Admin extends Controller {
		
		function Admin()
		{
			parent::Controller();
			//$this->output->enable_profiler(true);
			
			
			$this->load->library('administration');
			$this->load->model('member_model');
			$this->load->library('validation');			
			$this->template['module']	= 'member';
			$this->template['admin']		= true;
			$this->_init();
		}
		
		function index()
		{
			redirect('admin/member/listall');
		}
		
		function _init() 
		{
			/*
			* default values
			*/
			if (!isset($this->system->login_signup_enabled)) 
			{
				 $this->system->login_signup_enabled = 1;
			}
			
		}
		
		function listall() 
		{
			$debut = $this->uri->segment(4);
			$limit = $this->uri->segment(5);
			$this->template['members'] = $this->member_model->get_list();
			$this->load->library('pagination');

			$config['base_url'] = base_url() . 'member/listall/';
			$config['total_rows'] = $this->member_model->member_total;
			$config['per_page'] = '20'; 

			$this->pagination->initialize($config); 

			$this->template['pager'] = $this->pagination->create_links();
			
			$this->layout->load($this->template, 'admin');
			return;
		}
		
		
		function settings()
		{
		
		}
		
		function save()
		{



		}
		
		function add()
		{
			$rules['username'] = "trim|required|min_length[4]|max_length[12]|xss_clean|callback__verify_username";
			$rules['password'] = "trim|matches[passconf]|required";
			$rules['passconf'] = "trim";
			$rules['email'] = "trim|required|valid_email|callback__verify_mail";	

			
			$this->validation->set_rules($rules);	

			$fields['email'] = __("email");	
			$fields['password'] = __("password");	
			$fields['passconf'] = __("password confirmation");	

			$this->validation->set_fields($fields);	
			$this->validation->set_error_delimiters('<p style="color:#900">', '</p>');

			//$this->validation->set_message('min_length', __('The %s field is required'));
			$this->validation->set_message('required', __('The %s field is required'));
			$this->validation->set_message('matches', __('The %s field does not match the %s field'));
			$this->validation->set_message('valid_email', 'The email address you entered is not valid.');			
							

						
			if ($this->validation->run() == FALSE)
			{
				$this->layout->load($this->template, 'add');
			}
			else
			{
				$id = $this->user->register(
					$this->input->post('username'),
					$this->input->post('password'),
					$this->input->post('email')
				);
				
				$this->session->set_flashdata('notification', __("User registered"));
				redirect('admin/member/listall');
			}

		}

		function _verify_username($data)
		{

			$username = $this->input->post('username');
			
			//check if email belongs to someone else
			if ($this->member_model->exists(array('username' => $username)))
			{
				$this->validation->set_message('_verify_username', __("The username is already in use"));
				return FALSE;
			}

		}		
		function profile($username = null) 
		{
			if ( is_null($username) )
			{
				$username = $this->session->userdata("username");
			}
			
			if ($this->member_model->exists("username = '$username'"))
			{
				echo "exist";
			}
			else 
			{
				$this->session->set_flashdata("notification", __("This member doesn't exist"));
				redirect("admin/member/listall");
			}
			
		}

		function delete($username = null)
		{
			if (is_null($username))
			{
				$this->session->set_flashdata("notification", __("Username and status required"));
				redirect("admin/member/listall");
			}
			
			if ($username == $this->session->userdata("username"))
			{
				$this->session->set_flashdata("notification", __("You cannot remove yourself"));
				redirect("admin/member/listall");
			
			}
			
			$this->db->delete('users', array('username' => $username));
			$this->session->set_flashdata("notification", __("User deleted"));
			redirect("admin/member/listall");
			
		}
		
		function status($username = null, $fromstatus = null)
		{
			if (is_null($username) || is_null($fromstatus))
			{
				$this->session->set_flashdata("notification", __("Username and status required"));
				redirect("admin/member/listall");
			}
			
			if ($fromstatus == 'active') 
			{
				$data['status'] = 'disabled';
			}
			else
			{
				$data['status'] = 'active';
			}
			$this->user->update($username, $data);
			$this->session->set_flashdata("notification", __("User status updated"));
			redirect("admin/member/listall");
			
			
		}
		function edit($username = null) 
		{
			$rules['password'] = "trim|matches[passconf]";
			$rules['passconf'] = "trim";
			$rules['email'] = "trim|required|valid_email|callback__verify_mail";	

			
			$this->validation->set_rules($rules);	

			$fields['email'] = __("email");	
			$fields['password'] = __("password");	
			$fields['passconf'] = __("password confirmation");	

			$this->validation->set_fields($fields);	
			$this->validation->set_error_delimiters('<p style="color:#900">', '</p>');

			$this->validation->set_message('required', __('The %s field is required'));
			$this->validation->set_message('matches', __('The %s field does not match the %s field'));
			$this->validation->set_message('valid_email', 'The email address you entered is not valid.');			
			if ( is_null($username) )
			{
				$username = $this->input->post("username");
			}
							
			if ($this->template['member'] = $this->member_model->get_user($username))
			{
				
						
				if ($this->validation->run() == FALSE)
				{
					$this->layout->load($this->template, 'edit');
				}
				else
				{
					$data = array(
						'status' => $this->input->post('status'),
						'email' => $this->input->post('email')
						);
					
					if ($this->input->post('password'))
					{
						$data['password'] = $this->input->post('password');
					}

					$this->user->update($username, $data);
					$this->session->set_flashdata('notification', __("User saved"));
					redirect('admin/member/listall');
				}
			}
			else 
			{
				$this->session->set_flashdata("notification", __("This member doesn't exist"));
				redirect("admin/member/listall");
			}				

		}


	// ------------------------------------------------------------------------
	
	/**
	 * Registration validation callback
	 *
	 * @access	private
	 * @param	string
	 * @return	bool
	 */
		function _verify_mail($data)
		{

			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$username = $this->input->post('username');
			
			//check if email belongs to someone else
			if ($this->member_model->exists(array('email' => $email, 'username !=' => $username)))
			{
				$this->validation->set_message('_verify_mail', __("The email is already in use"));
				return FALSE;
			}

		}		
		
		
	}
	
?>