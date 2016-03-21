<?php

/** 
  * Timetable Model class
  *
  * Created by Spencer 03/10/2016 12:51:48 PM PST
  */

class Timetable extends CI_Model {
    protected $xday = null; // xml document root (ie timetable)
    protected $xperiod = null; // period xml
    protected $xclass = null; // class xml
    // Day xml
    protected $days = array();
    // Period xml
    protected $periods = array();
    // Class xml
    protected $courses = array();
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        // Load days array
        $this->xday = simplexml_load_file(DATAPATH . 'day.xml');
        foreach($this->xday->days->dayoftheweek as $day){
            $temp_days = array();
            foreach($day->info as $info){
                // do stuff to info
                $tempi = new InfoClass();
                $tempi->building = (string) $info->building;
                $tempi->room = (string) $info->room;
                $tempi->instructor = (string) $info->instructor;
                $tempi->stime = (string) $info->stime;
                $tempi->etime = (string) $info->etime;
                $tempi->course = (string) $info->class;
                
                $temp_days[] = $tempi;
            }
            $this->days[(string) $day['day']] = $temp_days;
        }
        
        // Load periods array
        $this->xperiod = simplexml_load_file(DATAPATH . 'period.xml');
        foreach($this->xperiod->periods->timeblock as $timeblock){
            // $temp_periods = array();
            $temp_periods = array();
            foreach($timeblock->info as $info){
                $tempi = new InfoClass();
                $tempi->building = (string) $info->building;
                $tempi->room = (string) $info->room;
                $tempi->instructor = (string) $info->instructor;
                $tempi->etime = (string) $info->etime;
                $tempi->day = (string) $info->day;
                $tempi->course = (string) $info->class;
   
//                $this->periods[(string) $timeblock['time']] = $tempi;
                $temp_periods[] = $tempi;
            }
            $this->periods[(string) $timeblock['stime']] = $temp_periods;
        } 
        
        // Load courses array
        $this->xclass = simplexml_load_file(DATAPATH . 'class.xml');
        foreach($this->xclass->courses->course as $course){
            //$this->courses[] = (string) $course['id'];
            $temp_courses = array();
            foreach($course->info as $info){
                $tempi = new InfoClass();
                $tempi->building = (string) $info->building;
                $tempi->room = (string) $info->room;
                $tempi->instructor = (string) $info->instructor;
                $tempi->stime = (string) $info->stime;
                $tempi->etime = (string) $info->etime;
                $tempi->day = (string) $info->day;
                
//                $this->courses[(string) $course['id']] = $tempi;
                 $temp_courses[] = $tempi;
            }
            $this->courses[(string) $course['id']] = $temp_courses;
        }
    }
    
    function getDaysArray(){
        return $this->days;
    }
    
    function getDaysInfoArray($day){
        if(isset($this->days[$day]))
            return $this->days[$day];
        else
            return null;
    }
    
    function getCoursesArray(){
        return $this->courses;
    }
    
    function getCoursesInfoArray($id){
        if(isset($this->courses[$id]))
            return $this->courses[$id];
        else
            return null;
    }
    function getPeriodsArray(){
        return $this->periods;
    }
    
    function getPeriodsInfoArray($time){
        if(isset($this->periods[$time]))
            return $this->periods[$time];
        else
            return null;
    }
    
    // This will be called when we search.
    // We need to have it so that there are 2 drop downs
        // One for the course we want
        // One for the list type we want
    // Day Facet
    public function findByDay($day){
        $periods_info_array = $this->getDaysInfoArray($day);
        $temp = array();
        foreach($periods_info_array as $info){
                $temp[] = $info;
        }
        return $temp;
    }
    // Period Facet
    public function findByTime($time){
        $periods_info_array = $this->getPeriodsInfoArray($time);
        $temp = array();
        foreach($periods_info_array as $info){
                $temp[] = $info;
        }
        return $temp;
        
    }
    // Class Facet
    public function findByClass($id){
        $course_info_array = $this->getCoursesInfoArray($id);
        $temp = array();
        foreach($course_info_array as $info){
                $temp[] = $info;
        }
        return $temp;
    }
}
class InfoClass {
    public $building;
    public $room;
    public $instructor;
    public $stime;
    public $etime;
    public $day;
    public $course;
}
