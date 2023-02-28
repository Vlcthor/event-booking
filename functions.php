<?php
// CONNECT TO DATABASE +
function connectDatabase(){
    $con = mysqli_connect("localhost","root","","event_booking");
    return $con;
}

// GET FUNCTIONS
function getUser($username){
    $res = mysqli_query(connectDatabase() , "SELECT * from user_detail where username='$username' ");   
    $row;

    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_assoc($res);
    }
    return $row;
}

function getEvent($event_id){
    $res = mysqli_query(connectDatabase(),"SELECT * from events where event_id = $event_id");   
    $row;

    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_assoc($res);
    }
    return $row;
}

function getAllEvents(){
    return mysqli_query(connectDatabase(),"SELECT * from events");   
}

function getUserBookedEvents($user_id){
    $query="SELECT events.event_id AS event_id,     bookings.booking_id AS booking_id,
                   events.event_image AS event_image, events.event_name AS event_name 
            FROM bookings 
            INNER JOIN events ON bookings.event_id = events.event_id 
            INNER JOIN user_detail ON bookings.users_id = user_detail.user_id  
            WHERE bookings.users_id = $user_id";

    return mysqli_query(connectDatabase(),$query);   
}
// END OF GET FUNCTIONS

//  UPDATE OR DELETE FUNCTIONS

function deleteEvent($event_id){
    return mysqli_query(connectDatabase(),"DELETE FROM events WHERE event_id = $event_id");   
}

function updateEvent($event_id,$event_name,$event_image){
    return mysqli_query(connectDatabase(),"UPDATE events SET event_name = '$event_name',event_image = '$event_image' WHERE event_id = $event_id");   
}

function cancelEvent($booking_id,$event_id,$user_id){
    return mysqli_query(connectDatabase(),"DELETE FROM bookings WHERE booking_id = $booking_id  AND event_id = $event_id AND users_id = $user_id");      
}

// USER AND ADMIN LOGIN +
function login($username){
    $res = mysqli_query(connectDatabase(),"SELECT user_type, password from user_detail where username='$username' ");
    $row;

    if(mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_assoc($res);
    }else{
        $row = 0;
    }
    return $row;
}

// CREATE FUNCTIONS

function createUser($email,$name,$username,$password){
    $HASHED_PASS = password_hash($password, PASSWORD_DEFAULT);
    $query= "INSERT INTO user_detail (username,password,name,email,user_type) VALUES('$username','$HASHED_PASS','$name','$email','User')";

    return mysqli_query(connectDatabase(),$query);   
}

function createEvent($event_name,$event_image){
    return mysqli_query(connectDatabase(),"INSERT INTO events (event_name,event_image) VALUES('$event_name','$event_image')");   
}

function bookEvent($event_id,$user_id){
    return mysqli_query(connectDatabase(),"INSERT INTO bookings(event_id,users_id) VALUES ($event_id,'$user_id')");   
}

?>