<?php 

namespace Models;

class Role{
    private $id;
    private $description;

    public function __construct(){}

    function setId($id){$this->id=$id;}
    function setDescription($description){$this->description = $description;}
    function getId(){return $this->id;}
    function getDescription(){return $this->description;}
}

?>