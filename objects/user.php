<?php
// 'user' object
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "tbl_users";
 
    // object properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
    public $numbercustomer;
    public $nohp;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
// create() method will be here
// create new user record
function create(){
 
    // insert query
     $query = "INSERT INTO " . $this->table_name . "
            SET
                first_name = :firstname,
                last_name = :lastname,
                email = :email,
                password = :password,
                number_customer = :numbercustomer,
                no_handphone = :nohp";
 
    // prepare the query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->firstname=htmlspecialchars(strip_tags($this->firstname));
    $this->lastname=htmlspecialchars(strip_tags($this->lastname));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->password=htmlspecialchars(strip_tags($this->password));
    $this->numbercustomer=htmlspecialchars(strip_tags($this->numbercustomer));
    $this->nohp=htmlspecialchars(strip_tags($this->nohp));
 
    // bind the values
    $stmt->bindParam(':firstname', $this->firstname);
    $stmt->bindParam(':lastname', $this->lastname);
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':numbercustomer', $this->numbercustomer);
    $stmt->bindParam(':nohp', $this->nohp);
 
    // hash the password before saving to database
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $password_hash);
 
    // execute the query, also check if query was successful
    if($stmt->execute()){
        return true;
    }
 
    return false;
}

function emailExists(){
 
    // query to check if email exists
    $query = "SELECT id, first_name, last_name, password
            FROM " . $this->table_name . "
            WHERE email = ?
            LIMIT 0,1";
 
    // prepare the query
    $stmt = $this->conn->prepare( $query );
 
    // sanitize
    $this->email=htmlspecialchars(strip_tags($this->email));
 
    // bind given email value
    $stmt->bindParam(1, $this->email);
 
    // execute the query
    $stmt->execute();
 
    // get number of rows
    $num = $stmt->rowCount();
 
    // if email exists, assign values to object properties for easy access and use for php sessions
    if($num>0){
 
        // get record details / values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        // assign values to object properties
        $this->id = $row['id'];
        $this->firstname = $row['first_name'];
        $this->lastname = $row['last_name'];
        $this->password = $row['password'];
 
        // return true because email exists in the database
        return true;
    }
 
    // return false if email does not exist in the database
    return false;
}
 
function numberExists(){
 
    // query to check if email exists
    $query = "SELECT id, first_name, last_name, password
            FROM " . $this->table_name . "
            WHERE number_customer = ?
            LIMIT 0,1";
 
    // prepare the query
    $stmt = $this->conn->prepare( $query );
 
    // sanitize
    $this->numbercustomer=htmlspecialchars(strip_tags($this->numbercustomer));
 
    // bind given email value
    $stmt->bindParam(1, $this->numbercustomer);
 
    // execute the query
    $stmt->execute();
 
    // get number of rows
    $num = $stmt->rowCount();
 
    // if email exists, assign values to object properties for easy access and use for php sessions
    if($num>0){
 
        // get record details / values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        // assign values to object properties
        $this->id = $row['id'];
        $this->firstname = $row['first_name'];
        $this->lastname = $row['last_name'];
        $this->password = $row['password'];
 
        // return true because email exists in the database
        return true;
    }
 
    // return false if email does not exist in the database
    return false;
}


// update a user record
function update(){
 
 
    $query = "UPDATE " . $this->table_name . "
            SET
                first_name = :firstname,
                last_name = :lastname,
                email = :email,
                no_handphone = :nohp
            WHERE id = :id";
 
    // prepare the query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->firstname=htmlspecialchars(strip_tags($this->firstname));
    $this->lastname=htmlspecialchars(strip_tags($this->lastname));
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->nohp=htmlspecialchars(strip_tags($this->nohp));
 
    // bind the values from the form
    $stmt->bindParam(':firstname', $this->firstname);
    $stmt->bindParam(':lastname', $this->lastname);
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':nohp', $this->nohp);
 
  
    // unique ID of record to be edited
    $stmt->bindParam(':id', $this->id);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}

 function getNumberCustomerExist($number){
        $query = "SELECT count(number_customer) as tot FROM tbl_customers WHERE number_customer = $number";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

function getUser($idUser){


    $query = "SELECT * FROM tbl_users
    LEFT OUTER JOIN tbl_customers ON tbl_customers.number_customer = tbl_users.number_customer
    LEFT OUTER JOIN tbl_package ON tbl_customers.id_package = tbl_package.id_package
    WHERE id =".$idUser;

    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    return $stmt->fetchAll();

  
}
 
}

?>