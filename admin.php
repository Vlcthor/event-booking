<!DOCTYPE html>

<?php
    include('shared/header.php');
    $admin_ret = getUser($_SESSION['username']);

    // ADMIN LOGOUT
    if(isset($_GET['logout'])){
        session_destroy();
        header('Location: index.php');
    }

    // DELETE EVENT
    if(isset($_GET['delete'])){
        $ret_status = deleteEvent($_GET['event_id']);

        if($ret_status){
            unlink( "uploads/".$_GET['event_image'] );
            echo "<center>Event deleted!</center>";
        }else{
            echo "<center>Event not deleted!</center>";
        }
    }
    // UPDATE EVENT
    if(isset($_POST['update_event'])){

        $FILE = $_FILES['new_image'];
        $target = "uploads/".basename($FILE['name']);
        $temp_name = $FILE['tmp_name'];
        $filename = $FILE['name'];

        if(!$filename){
            $ret = updateEvent($_POST['event_id'],$_POST['event_name'],$_POST['event_image']);

            echo ($ret)? "<center style='color:green'>Event successfully updated!</center>"
                : "<center style='color:red'>Event not updated!</center>";            
        }else{
            $ret = updateEvent($_POST['event_id'],$_POST['event_name'],$filename);
            
            if($ret){
                if(move_uploaded_file($temp_name,$target)){
                    unlink( "uploads/".$_POST['event_image'] );
                    echo "<center><h5 style='color:green'>Event successfully updated!</h5></center>";
                }
            }else{
                echo "<center style='color:red'>Event not updated!</center>";
            }
        }
    }
// NOT YET CLEANED
?>

    <div class="container">
        <div class="d-flex justify-content-center"> <h2><?php echo $admin_ret['name']; ?></h2></div>
        <div class="d-flex justify-content-center"> 
            <form method="get">
                <button type="submit" 
                        class="btn btn-danger" 
                        name="logout"
                >
                Logout
                </button>
            </form>
        </div>
        <hr>
        <div class="d-flex justify-content-center"> 
            <!-- <form method="get"> -->
                <button type="submit" 
                        class="btn btn-primary" 
                        onClick="openEvent()"
                >
                Create Event
                </button>
            <!-- </form> -->
            &nbsp;&nbsp;&nbsp;&nbsp;
            <button type="submit" 
                    class="btn btn-primary" 
                    onClick="createUser()"
            >
            Create User
            </button>
        </div>
        <!-- CREATE EVENT -->
        <div id="create-event" >
        <?php
                if(isset($_POST['submit_event'])){

                    $FILE = $_FILES['event_image'];
                    $target = "uploads/".basename($FILE['name']);               
                    $return_value = createEvent($_POST['event_name'],$FILE['name']);

                    if($return_value){
                        if( move_uploaded_file( $FILE['tmp_name'], $target) ){
                            echo "<center>Event created!</center>";
                        }
                    }else{
                        echo "<center>Event not created!</center>";
                    }
                }
        ?>  
                <form method="POST" enctype="multipart/form-data">
                <h2 class="header">Create Event</h2> <br>
                <div class="form-group">
                    <label for="exampleInputEmail1">Event Name</label>
                    <input type="text" 
                        class="form-control" 
                        id="exampleInputEmail1" 
                        name="event_name" 
                        placeholder="Enter event name" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="event_image">Event Image</label>
                    <input type="file" 
                        class="form-control" 
                        id="exampleInputPassword1" 
                        name="event_image" 
                        placeholder="Please choose a file" 
                        required
                    >
                </div>
                <br>
                <button type="submit" name="submit_event" class="btn btn-success">Submit</button>
                </form>          
            </div>

            <div id="create-user" style="display: none">
            <?php
                    // CREATE USER
                    if(isset($_POST['submit_user'])){
                        $ret = createUser($_POST['email'],$_POST['name'],$_POST['username'],$_POST['password']);

                        echo ($ret)? "<center>User successfully created!</center>" : "<center>User not created!</center>";
                    }
                ?>
                    
                        <form method="POST">
                            <h2 class="header">Create User</h2> <br>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input 
                                    type="username" 
                                    class="form-control" 
                                    id="exampleInputEmail1" 
                                    name="username" 
                                    placeholder="Enter username" 
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="exampleInputEmail1" 
                                    name="password" 
                                    placeholder="Enter password" 
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" 
                                    class="form-control" 
                                    id="exampleInputEmail1" 
                                    name="name" 
                                    placeholder="Enter full name" 
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Email</label>
                                <input type="text" 
                                    class="form-control" 
                                    id="exampleInputEmail1" 
                                    name="email" 
                                    placeholder="Enter email" 
                                    required
                                >
                            </div>
                            <br>
                            <button type="submit" name="submit_user" class="btn btn-success">Create</button>
                        </form>  
                    </div>

                <hr>

        <!-- DISPLAY ALL EVENTS -->
        <button type="submit" class="btn btn-secondary" onClick="displayEvents()">Display Events</button>
        <br>
        <br>
        <div id="display-events" display="none">
            <div class="card-group">
    <?php 
        $response = getAllEvents();
        while($row = mysqli_fetch_assoc($response)){
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
                        <form method="get">
                            <input 
                                type="hidden" 
                                name="event_id" 
                                value="<?php echo $row['event_id']?>"
                            >
                            
                            <input 
                                type="hidden" 
                                name="event_image" 
                                value="<?php echo $row['event_image']?>"
                            >                                    
                            <button 
                                type="submit" 
                                name="update" 
                                class="btn btn-secondary"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-plus" viewBox="0 0 16 16">
                            <path d="M8.5 6a.5.5 0 0 0-1 0v1.5H6a.5.5 0 0 0 0 1h1.5V10a.5.5 0 0 0 1 0V8.5H10a.5.5 0 0 0 0-1H8.5V6z"/>
                            <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
                            </svg>
                            </button>

                            <button 
                                type="submit" 
                                name="delete" 
                                class="btn btn-danger"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                            </button>
                         </form>
                    </div>
                </div>
    <?php }  ?>
            </div>
        </div>
        <br>
        <div id="update-events" class="card-group">
    <?php if(isset($_GET['update'])) { 
        $event = getEvent($_GET['event_id']);      
    ?>
        
            <div class="card">
            <script> document.getElementById("display-events").style.display="none"; 
                    document.getElementById("create-event").style.display="none";
            </script>
                <form method="post" enctype="multipart/form-data"> 
                    <h5>Event Image</h5>
                    
                    <img
                        class="img-fluid img-thumbnail"
                        style="height:200px" 
                        src='<?php echo "uploads/".$event['event_image']?>' 
                    >
    
                    <div class="form-group">
                    <input 
                        type="hidden" 
                        class="form-control" 
                        name="event_id" 
                        value="<?php echo $event['event_id']?>"
                    >
                    <label for="event-name">Event Name</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        name="event_name" 
                        value="<?php echo $event['event_name']?>"
                    >
                    </div>   
                    
                    <div class="form-group">
                    <label for="event-image">Current Event Image</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        name="event_image" 
                        value="<?php echo $event['event_image']?>" 
                        readonly
                    >
                    </div>
                    
                    <div class="form-group">
                    <label for="event-image">New Event Image</label>
                    <input 
                        type="file" 
                        class="form-control" 
                        name="new_image"
                    >
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success"  name="update_event">Submit</button>
                
                </form>
            </div>
         </div>
    <?php } ?>
    </div>
</body>

<!-- HIDE OR SHOW EVENTS -->
<script>

    function createUser() {
        var x = document.getElementById("create-user");
        var y = document.getElementById("create-event");

        if (y.style.display !== "none") {
            y.style.display = "none";
        } 

        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }

    function openEvent() {
        var x = document.getElementById("create-event");
        var y = document.getElementById("create-user");

        if (y.style.display !== "none") {
            y.style.display = "none";
        } 

        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }

    function displayEvents() {
        var x = document.getElementById("display-events");
        var y = document.getElementById("update-events");

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