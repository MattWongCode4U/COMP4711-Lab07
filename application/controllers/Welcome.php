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
            $this->load->helper('directory');
            $days = $this->timetable->getDaysArray();
            $periods = $this->timetable->getPeriodsArray();
            $courses = $this->timetable->getCoursesArray();
            // Setup drop down's with the 'keys'
                // days = 'mon' 'tue' 'wed'...
                // periods = '8:30' '9:30' '10:30'
                // courses = 'BLAW3600' 'COMP4560' 'COMP4711'
            
            // Dropdown Setup
            // Course dropdown
            $temp_course = $this->dropDown($courses);
            $this->data['courseselection'] = $temp_course;
            // Time dropdown
            $temp_time = $this->dropDown($periods);
            $this->data['timeselection'] = $temp_time;
            // Day dropdown
            $temp_day = $this->dropDown($days);
            $this->data['dayselection'] = $temp_day;
            
            // Check dropdown selections
            // Only class selected
            if((isset($_POST['class'])&& $_POST['class'] != '-')&& (isset($_POST['time']) && $_POST['time'] == '-') && (isset($_POST['day']) && $_POST['day'] == '-')){
                $selected_course = $_POST['class'];
                $selected_time = "Any Time";
                $selected_day = "Any Day";
                $found = $this->timetable->findByClass($_POST['class']);
                $info = $this->tableInfo($found);
                $this->data['info'] = $info;
                
            // Only time selected    
            }else if((isset($_POST['class']) && $_POST['class'] == '-') && (isset($_POST['time']) && $_POST['time'] != '-') && (isset($_POST['day']) && $_POST['day'] == '-')){
                $selected_course = "All Courses";
                $selected_time = $_POST['time'];
                $selected_day = "Any Day";
                $found = $this->timetable->findByTime($selected_time);
                $info = $this->tableInfo($found);
                $this->data['info'] = $info;
            
            //  Only days selected
            }else if((isset($_POST['class'])&& $_POST['class'] == '-')&& (isset($_POST['time']) && $_POST['time'] == '-') && (isset($_POST['day']) && $_POST['day'] != '-')){
                $selected_course = "All Courses";
                $selected_time = "Any Time";
                $selected_day = $_POST['day'];
                $found = $this->timetable->findByDay($selected_day);
                $info = $this->tableInfo($found);
                $this->data['info'] = $info;
            
            //  Class and time selected
            }else if((isset($_POST['class'])&& $_POST['class'] != '-')&& (isset($_POST['time']) && $_POST['time'] != '-') && (isset($_POST['day']) && $_POST['day'] == '-')){
                $selected_course = $_POST['class'];
                $selected_time = $_POST['time'];
                $selected_day = "ExampleDay";
                
                $classesfound = $this->timetable->findByClass($_POST['class']);
                $subset = array();
                foreach($classesfound as $thing){
                    if($thing->stime == $selected_time){
                        $subset[] = $thing;
                    }
                }
                
                $info = $this->tableInfo($subset);
                $this->data['info'] = $info;
            
            //  Class and day selected
            }else if((isset($_POST['class'])&& $_POST['class'] != '-')&& (isset($_POST['time']) && $_POST['time'] == '-') && (isset($_POST['day']) && $_POST['day'] != '-')){
                $selected_course = $_POST['class'];
                $selected_time = "ExampleTime";
                $selected_day = $_POST['day'];
                
                $classesfound = $this->timetable->findByClass($_POST['class']);
                $subset = array();
                foreach($classesfound as $thing){
                    if($thing->day == $selected_day){
                        $subset[] = $thing;
                    }
                }
                
                $info = $this->tableInfo($subset);
                $this->data['info'] = $info;
                
            //  Time and day selected
            }else if((isset($_POST['class'])&& $_POST['class'] == '-')&& (isset($_POST['time']) && $_POST['time'] != '-') && (isset($_POST['day']) && $_POST['day'] != '-')){
                $selected_course = "ExampleCourseID";
                $selected_time = $_POST['time'];
                $selected_day = $_POST['day'];
                
                $timefound = $this->timetable->findByTime($_POST['time']);

                foreach($timefound as $thing){
                    if($thing->day == $selected_day){
                        $subset[] = $thing;
                    }
                }
                
                $info = $this->tableInfo($subset);
                $this->data['info'] = $info;
                
            //  Class, time and day selected
            }else if((isset($_POST['class'])&& $_POST['class'] != '-')&& (isset($_POST['time']) && $_POST['time'] != '-') && (isset($_POST['day']) && $_POST['day'] != '-')){
                $selected_course = $_POST['class'];
                $selected_time = $_POST['time'];
                $selected_day = $_POST['day'];
                
                $classesfound = $this->timetable->findByClass($_POST['class']);
                $subset = array();
                foreach($classesfound as $thing){
                    if($thing->stime == $selected_time){
                        $subset[] = $thing;
                    }
                }
                
                foreach($subset as $otherthing){
                    if($otherthing->day == $selected_day){
                        $supersubset[] = $otherthing;
                    }
                }
                
                $info = $this->tableInfo($supersubset);
                $this->data['info'] = $info;
                
            //  None selected
            }else { //First time before searching
                $selected_course = "ExampleCourseID";
                $selected_time = "ExampleTime";
                $selected_day = "ExampleDay";
                $this->data['info'] = $this->tableInfo($temp = array());
            } 
            
            // Sets the text to course, time and day selected by user
            $this->data['courseID'] = $selected_course;
            $this->data['selectedTime'] = $selected_time;
            $this->data['selectedDay'] = $selected_day;
            
            // Load in the files
            $this->load->view('header');
            $this->load->view('start_form');
            $this->parser->parse('course_dropdown', $this->data);
            $this->parser->parse('time_dropdown', $this->data);
            $this->parser->parse('day_dropdown', $this->data);
            $this->load->view('close_form');
            $this->parser->parse('classes', $this->data); // This is where the data from the dropdown select will be loaded
            $this->load->view('footer');
            //$this->load->view('welcome_message');
	}
        
        /**
         * Construct methods
         * 
         * Called when the controller is loaded
         * 
         * Loads the Timetable model
         */
        function __construct(){
            parent::__construct();
            $this->load->model('timetable');
        }
        
        /**
         * Table Info
         * 
         * Loads the data passed in into a table format.
         * 
         * @param type $data Data to parse into a table.
         * @return string Array to return to be parsed into the view.
         */
        function tableInfo($data){
                $info[] = array('detail' => '<th>Building</th><th>Room</th><th>Instructor</th><th>Start Time</th><th>End Time</th><th>Day</th>');
                foreach($data as $obj){
                    $info[] = array('detail' => '<tr>'.
                        '<td>' . (string) $obj->building . '</td>'.
                        '<td>' . (string) $obj->room . '</td>'.
                        '<td>' . (string) $obj->instructor . '</td>'.
                        '<td>' . (string) $obj->stime . '</td>'.
                        '<td>' . (string) $obj->etime . '</td>'.
                        '<td>' . (string) $obj->day . '</td>'.
                        //'<td>' . (string) $obj->course . '</td>'.
                        '</tr>');
                }
            return $info;
        }
        
        /**
         * Drop Down
         * 
         * Creates a dropdown list from data passed in.
         * 
         * @param type $data Data to get the array key from as a dropdown option.
         * @return string Array to return to be parsed inot the view.
         */
        function dropDown($data){
             $list[] = array('option' => '<option value=\'-\'>---</option>');
            while($key = current($data)){ // Gets the current value of the carot
                  $list[] = array('option' => '<option value=' .(string) key($data). '>' . (string) key($data) . '</option>');
                  next($data); // Moves the carot to the next position
            }
            reset($data); // Restarts the carot to the beginning of the array
            return $list;
        }
}
