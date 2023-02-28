<?php
    include ('shared/header.php');
    $_SESSION["username"] = "";

    if(isset($_POST['submit'])){
        $ret = login($_POST['username']);
        
        if($ret){
            if($ret['user_type'] !== ""){
                $_SESSION["username"] = $_POST['username'];
            }
            if( password_verify($_POST['password'],$ret['password']) ){
                if($ret['user_type'] === 'Admin'){
                    header('Location: admin.php');
                }else if( $ret['user_type'] === 'User' ){
                    header('Location: user.php');
                }
            }else{
                echo "<center><h5 style='color:red'>Passwords do not match!</h5></center>";
            }
        }else{
            echo "<center><h5 style='color:red'>User not found!</h5></center>";
        }
    }

?>

    <div class="container" >
        <br>
        <div>
            <div> <h1> Event Booking </h1> </div>
        </div>

            <form method="post">
            <div class="form-group">
                <label for="exampleInputEmail1">Username</label>
                <input 
                       type="text" 
                       name="username" 
                       class="form-control" 
                       id="exampleInputEmail1" 
                       aria-describedby="usernameHelp" 
                       placeholder="Enter username"
                >
            </div>

            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input 
                       type="password"  
                       name="password" 
                       class="form-control" 
                       id="exampleInputPassword1" 
                       placeholder="Password"
                >
            </div>
            <br>
            <div class="form-group">
                <button 
                        type="submit" 
                        name="submit" 
                        class="btn btn-success"
                >
                Submit
                </button>
            </div>
            </form>
        </div>
    </body>
</html>