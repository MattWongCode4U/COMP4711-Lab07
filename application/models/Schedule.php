<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Schedule extends CI_Model {
    protected $xday = null; // xml document root (ie timetable)
    protected $xperiod = null; // period xml
    protected $xclass = null; // class xml

    protected $building = "";
    protected $room = "";
    protected $instructor="";
    protected $stime= null;
    protected $etime="";
    protected $class= null;
    protected $day= null;
    
    public function _construct($filename = null){
        parent::_construct();
        if($filename == null){
            return;
        }       
        //$this->xday = simplexml_load_file(DATAPATH . $filename . XMLSUFFIX);
        elseif($filename == 'day'){
            $this->xday = simplexml_load_file(DATAPATH . 'day.xml');
            foreach($this->xday->days->dayoftheweek as $day){
                foreach($day->info as $info){
                    $this->infos[] = $this->get($info);
                }
            }
        }
        elseif($filename == 'period'){
            $this->xperiod = simplexml_load_file(DATAPATH . 'period.xml');
            foreach($this->xperiod->periods->timeblock as $timeblock){
                foreach($timeblock->info as $info){
                    $this->infos[] = $this->get($info);
                }
            }
        }
        $this->xclass = simplexml_load_file(DATAPATH . 'class.xml');
        foreach($this->xclass->courses->course as $course){
            foreach($course->info as $info){            
                $this->infos[] = $this->get($info);
            }
        }
    }
    
    function get($element){
        $tempi = new stdClass();
        
        $tempi->building = (string) $element->building;
        $tempi->room = (string) $element->room;
        $tempi->instructor = (string) $element->instructor;
        $tempi->stime = (isset($this->$element->day))? (string) $element->stime : null;
        $tempi->etime = (string) $element->etime;
        $tempi->course = (isset($this->$element->day))? (string) $element->class : null;
        $tempi->day = (isset($this->$element->day))?(string) $element->day : null;
        
        $tempi->schedule = array();
        foreach($element->sched as $one){
            $tempi->schedule[] = (string)$one;
        }
        
        return $tempi;
    }
    
    function getBuilding(){
        return $this->building;
    }
    function getRoom(){
        return $this->room;
    }
    function getInstructor(){
        return $this->instructor;
    }
    function getStime(){
        return $this->stime;
    }
    function getEtime(){
        return $this->etime;
    }
    function getCourse(){
        return $this->class;
    }
    function getDay(){
        return $this->day;
    }
}