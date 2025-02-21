<?php

class Project
{
    private $id;
    private $name;
    private $description;
    private $start_date;
    private $end_date;
    private $status;
    private $manager_id;
    private $manager_name;

    public function __construct($id = null, $name = null, $description = null, $start_date = null, $end_date = null, $status = null, $manager_id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->status = $status;
        $this->manager_id = $manager_id;
      
    }

    public function setManagerName($manager_name)
{
    $this->manager_name = $manager_name;
}

public function getManagerName()
{
    return $this->manager_name;
}

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }

    public function getStartDate() { return $this->start_date; }
    public function setStartDate($start_date) { $this->start_date = $start_date; }

    public function getEndDate() { return $this->end_date; }
    public function setEndDate($end_date) { $this->end_date = $end_date; }

    public function getStatus() { return $this->status; }
    public function setStatus($status) { $this->status = $status; }

    public function getManagerId() { return $this->manager_id; }
    public function setManagerId($manager_id) { $this->manager_id = $manager_id; }

    
}
