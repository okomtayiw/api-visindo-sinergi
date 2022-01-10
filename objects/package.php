<?php
class Package{
 
    // database connection and table name
    private $conn;
    private $table_name = "tbl_package";
 
    // object properties
    public $idpackage;
    public $namepackage;
    public $abonemen;
    public $description;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 

    function create(){
    
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    name_package = :namepackage,
                    abonemen = :abonemen,
                    description = :description";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->namepackage=htmlspecialchars(strip_tags($this->namepackage));
        $this->abonemen=htmlspecialchars(strip_tags($this->abonemen));
        $this->description=htmlspecialchars(strip_tags($this->description));
    
        // bind the values
        $stmt->bindParam(':namepackage', $this->namepackage);
        $stmt->bindParam(':abonemen', $this->abonemen);
        $stmt->bindParam(':description', $this->description);
    
    
    
        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }


    function getPackage(){
        $query = "SELECT * FROM tbl_package ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll();
    }
 

}

?>