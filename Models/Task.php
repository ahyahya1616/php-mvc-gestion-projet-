<?php

class Task
{
    private $id;
    private $project_id;
    private $name;
    private $description;
    private $start_date;
    private $planned_end_date;
    private $actual_end_date;
    private $status;
    private $priority;
    private $assignee_id;
    private $project_name;
    private $member_name;

    
    public function __construct($id = null, $project_id = null, $name = null, $description = null,
     $start_date = null, $planned_end_date = null, $actual_end_date = null, $status = null,
      $priority = null, $assignee_id = null)
    {
        $this->id = $id;
        $this->project_id = $project_id;
        $this->name = $name;
        $this->description = $description;
        $this->start_date = $start_date;
        $this->planned_end_date = $planned_end_date;
        $this->actual_end_date = $actual_end_date;
        $this->status = $status;
        $this->priority = $priority;
        $this->assignee_id = $assignee_id;
        
    }


    public function setMemberName($member_name)
    {
        $this->member_name = $member_name;
    }
    
    public function getMemberName()
    {
        return $this->member_name;
    }
    public function setProjectName($project_name)
{
    $this->project_name = $project_name;
}

public function getProjectName()
{
    return $this->project_name;
}

    public function getId() {
         return $this->id; 
        }
    public function setId($id) {
         $this->id = $id; 
        }

    public function getProjectId() { 
        return $this->project_id; 
    }
    public function setProjectId($project_id) { 
        $this->project_id = $project_id; 
    }

    public function getName() { 
        return $this->name; 
    }
    public function setName($name) {
         $this->name = $name; 
        }

    public function getDescription() {
         return $this->description;
         }
    public function setDescription($description) {
         $this->description = $description;
         }

    public function getStartDate() {
         return $this->start_date;
         }
    public function setStartDate($start_date) {
         $this->start_date = $start_date;
         }

    public function getPlannedEndDate() {
         return $this->planned_end_date; 
        }
    public function setPlannedEndDate($planned_end_date) { 
        $this->planned_end_date = $planned_end_date; 
    }

    public function getActualEndDate() { 
        return $this->actual_end_date;
     }
    public function setActualEndDate($actual_end_date) {
         $this->actual_end_date = $actual_end_date;
         }

    public function getStatus() { return $this->status; }
    public function setStatus($status) { $this->status = $status; }

    public function getPriority() { return $this->priority; }
    public function setPriority($priority) { $this->priority = $priority; }

    public function getAssigneeId() { return $this->assignee_id; }
    public function setAssigneeId($assignee_id) { $this->assignee_id = $assignee_id; }

}
