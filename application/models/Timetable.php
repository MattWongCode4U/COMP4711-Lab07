<?php

/** 
  * Timetable Model class
  *
  * Created by Spencer 03/10/2016 12:51:48 PM PST
  */

// DATAPATH = '../data' // ?

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
        $this->xday = simplexml_load_file(DATAPATH . 'days.xml');
        foreach($this->xday->timetable->days->dayoftheweek as $day){
            foreach($day->info as $info){
                // do stuff to info
                $tempi = new InfoClass();
                $tempi->building = (string) $info->building;
                $tempi->room = (string) $info->room;
                $tempi->instructor = (string) $info->instructor;
                $tempi->stime = (string) $info->stime;
                $tempi->etime = (string) $info->etime;
                $tempi->course = (string) $info->class;

                $this->days[(string) $day['day']] = (string) $tempi;
            }
        }
        
        // Load periods array
        $this->xperiod = simplexml_load_file(DATAPATH . 'periods.xml');
        foreach($this->xperiod->timetable->periods->timeblock as $timeblock){
            foreach($timeblock->info as $info){
                $tempi = new InfoClass();
                $tempi->building = (string) $info->building;
                $tempi->room = (string) $info->room;
                $tempi->instructor = (string) $info->instructor;
                $tempi->etime = (string) $info->etime;
                $tempi->day = (string) $info->day;
                $tempi->course = (string) $info->class;
                
                $this->periods[(string) $timeblock['time']] = (string) $tempi;
            }
        } 
        
        // Load courses array
        $this->xclass = simplexml_load_file(DATAPATH . 'class.xml');
        foreach($this->xclass->timetable->courses->course as $course){
            foreach($course->info as $info){
                $tempi = new InfoClass();
                $tempi->building = (string) $info->building;
                $tempi->room = (string) $info->room;
                $tempi->instructor = (string) $info->instructor;
                $tempi->stime = (string) $info->stime;
                $tempi->etime = (string) $info->etime;
                $tempi->day = (string) $info->day;
                
                $this->courses[(string) $course['id']] = (string) $tempi;
            }
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
    
    
    
    // Day Facet
    public function findByDay($day, $id){
        
    }
    // Period Facet
    public function findByTime($time, $id){
        
    }
    // Class Facet
    public function findByClass($class){
        
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
