<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
        protected $data = array();
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
            $this->load->helper('directory');
            $days = $this->timetable->getDaysArray();
            $periods = $this->timetable->getPeriodsArray();
            $courses = $this->timetable->getCoursesArray();
            // Setup drop down's with the 'keys'
                // days = 'mon' 'tue' 'wed'...
                // periods = '8:30' '9:30' '10:30'
                // courses = 'BLAW3600' 'COMP4560' 'COMP4711'
            
            // Parse out to the Search.php file in {dropdown}
                // format example: <option value="mon">mon</option>
            //$this->dropdown = 
            // Testing
            /*
            $this->data = array('selection' => array(array(
                                                    'option' => '<option value=mon>mon</option>'
                                                 ),
                                                 array(
                                                    'option' => '<option value=tue>tue</option>'
                                                 ),
                                                 array(
                                                    'option' => '<option value=wed>wed</option>'
                                                 )
                                                )
                        );
            print_r($this->data);
            echo "/n";
            */ // This is a test for how the data is loaded for the options
            while($key = current($courses)){
                  $temp_course[] = array('option' => '<option value=\"' .(string) key($courses). '\">' . (string) key($courses) . '</option>');
                  next($courses);
            }
            $this->data['selection'] = $temp_course;
            // Load the php files
                // header
                // search
                // footer
            $this->load->view('header');
            $this->parser->parse('dropdown', $this->data);
            $this->parser->parse('classes', $this->courses); // This is where the data from the dropdown select will be loaded
            $this->load->view('footer');
            //$this->load->view('welcome_message');
	}
        
        function __construct(){
            parent::__construct();
            $this->load->model('timetable');
        }
}
