<!DOCTYPE html>

<?php
    include('shared/header.php');

    $ret = getUser($_SESSION['username']);
    $user_id = $ret['user_id'];

    // LOGOUT
    if(isset($_GET['logout'])){
        session_destroy();
        header('Location: index.php');
    }   
    // IF CANCELLED EVENT 
    if(isset($_POST['cancel'])){
        $return = cancelEvent($_POST['booking_id'],$_POST['event_id'],$user_id);

        echo ($return)? "<center style='color:red' >Event cancelled!</center>" :  "<center style='color:red' >Event not cancelled!</center>";
    }   
    // IF BOOKED EVENT
    if(isset($_POST['book'])){
        $return = bookEvent($_POST['event_id'],$user_id);

        echo ($return)? "<center style='color:green'>Event booked!</center>" :  "<center style='color:green'>Event not booked!</center>";
    }
?>

    <div class="container">
        <!-- DISPLAY NAME -->
        <div class="d-flex justify-content-center"> <h2><?php echo $ret['name']; ?></h2></div>
        <div class="d-flex justify-content-center"> 
            <form method="get">
                <button type="submit" class="btn btn-danger" name="logout">Logout</button>
            </form>
        </div>
        <hr>
        <div class="d-flex justify-content-center"> 
                <button class="btn btn-secondary" 
                        onClick="eventShow()"
                >
                Display Events
                </button>
                
                &nbsp;&nbsp;&nbsp;&nbsp;
                
                <button class="btn btn-secondary" 
                        onClick="bookedShow()"
                >
                Booked Events
                </button>
        </div>
        <hr>

        <!-- DISPLAY ALL EVENTS -->
        <div id="all-events" style="display: none">
            <div class="card-group" >     
                <?php
                    $response = getAllEvents();
                    $isBooked = 0;
                    while($row = mysqli_fetch_assoc($response)){
                        $res2 = getUserBookedEvents($user_id);
                        while( ($booked = mysqli_fetch_assoc($res2)) && !$isBooked ) {
                            if($row['event_id'] === $booked['event_id']){
                                $isBooked = 1;
                            }else{
                                $isBooked = 0;
                            }
                        }
                        if(!$isBooked){
                ?>
                
                    <div class="card">
                        <div class="d-flex justify-content-center"> 
                            <img src='<?php echo "uploads/".$row['event_image']?>' 
                                    class="img-fluid img-thumbnail"
                                    style="height:200px"
                                    alt="<?php echo "uploads/".$row['event_image']?>"
                            >
                        </div>   

                        <div class="card-body">
                        <h5 class="card-title"> <?php echo $row['event_name']; ?> </h5>
                            <form method="POST"> 
                                <input type="hidden" 
                                        name="event_id" 
                                        value="<?php echo $row['event_id']?>"
                                >
                                <button type="submit" 
                                        class="btn btn-success" 
                                        name="book"
                                >
                                Book
                                </button>
                            </form>
                       
                        </div>
                    </div>  
                    <?php  } $isBooked = 0; ?>                  
                <?php } ?>
            </div>  
        </div>  

        <!-- DISPLAY ALL BOOKED EVENTS -->
        <div id="booked-events" style="display: none"> 
            <div class="card-group"> 
                <?php
                    $response2 = getUserBookedEvents($user_id);

                    if(mysqli_num_rows($response2) === 0){
                        echo "<center><h4>No Booked Events!</h4></center>";
                    }

                    while($row = mysqli_fetch_assoc($response2)){
              
                ?>
                    <div class="card">
                        <div class="d-flex justify-content-center">
                            <img 
                                src='<?php echo "uploads/".$row['event_image']?>' 
                                class="img-fluid img-thumbnail"
                                style="height:200px"
                            >
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['event_name']; ?></h5> 
                            
                            <form method="POST"> 
                                <input type="hidden" 
                                        value="<?php echo $row['event_id']?>" 
                                        name="event_id"
                                >
                                <input type="hidden" 
                                        value="<?php echo $row['booking_id']?>" 
                                        name="booking_id">

                                <button type="submit" 
                                        class="btn btn-warning" 
                                        name="cancel"
                                >
                                Cancel
                                </button>
                            </form>

                        </div>  
                    </div>
                <?php } ?>
            </div>
        </div>
            
    </div>
</body>
<!-- HIDE OR SHOW EVENTS -->
<script>
    function eventShow() {
        var x = document.getElementById("all-events");
        var y = document.getElementById("booked-events");
        if (y.style.display !== "none") {
            y.style.display = "none";
        }       

        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }

    function bookedShow() {
        var x = document.getElementById("booked-events");
        var y = document.getElementById("all-events");

        if (y.style.display !== "none") {
            y.style.display = "none";
        } 

        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
    
</script>


</html>