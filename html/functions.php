<?php
function check_login($con)
{
    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
        $id = mysqli_real_escape_string($con, $id); 
        
        $query = "SELECT * FROM parents WHERE user_id = '$id' LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) === 0) {
          
            $query = "SELECT * FROM babysitter WHERE user_id = '$id' LIMIT 1";
            $result = mysqli_query($con, $query);
        }

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result); 
        } else {
            
            header("Location: login_in_babysitter.php");
            die;
        }
    }

   
    header("Location: login_in_parent.php");
    die;
}



    




function random_num($length)
{
    $text = "";
    if ($length < 5) {
        $length = 5;
    }
    $len = rand(4, $length);
    for ($i = 0; $i < $len; $i++) {
        $text .= rand(0, 9);
    }
    return $text;
}
?>
