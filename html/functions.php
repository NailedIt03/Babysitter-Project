<?php
function check_login($con)
{
    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
        $id = mysqli_real_escape_string($con, $id); 
        
        // Check if user is a parent
        $query = "SELECT * FROM parents WHERE user_id = '$id' LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) === 0) {
            // If not found in parents, check babysitter
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

// Generate a random number
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

// Function to send babysitter request
function send_babysitter_request($con, $parent_id, $babysitter_id, $child_name)
{
    // Check if request already exists
    $check_query = $con->prepare("SELECT * FROM babysitter_requests WHERE parent_id = ? AND babysitter_id = ? AND status = 'pending'");
    $check_query->bind_param("ii", $parent_id, $babysitter_id);
    $check_query->execute();
    $check_result = $check_query->get_result();

    if ($check_result->num_rows == 0) {
        // Insert new request
        $insert_query = $con->prepare("INSERT INTO babysitter_requests (parent_id, babysitter_id, child_name) VALUES (?, ?, ?)");
        $insert_query->bind_param("iis", $parent_id, $babysitter_id, $child_name);
        if ($insert_query->execute()) {
            return "Request sent successfully.";
        } else {
            return "Error sending request.";
        }
    } else {
        return "Request already sent.";
    }
}

// Function to get pending babysitter requests
function get_babysitter_requests($con, $babysitter_id)
{
    $query = $con->prepare("SELECT br.id, p.user_name AS parent_name, br.child_name 
                            FROM babysitter_requests br
                            JOIN parents p ON br.parent_id = p.id
                            WHERE br.babysitter_id = ? AND br.status = 'pending'");
    $query->bind_param("i", $babysitter_id);
    $query->execute();
    return $query->get_result();
}

// Function to accept or reject babysitter request
function handle_babysitter_request($con, $request_id, $action, $babysitter_id)
{
    if ($action === "accept") {
        // Get child name from the request
        $result = $con->prepare("SELECT child_name, parent_id FROM babysitter_requests WHERE id = ?");
        $result->bind_param("i", $request_id);
        $result->execute();
        $request = $result->get_result()->fetch_assoc();
        $child_name = $request['child_name'];

        // Append the child name to the babysitter's assigned_child column
        $update_child = $con->prepare("UPDATE babysitter SET assigned_child = 
                                       IFNULL(CONCAT(assigned_child, ', ', ?), ?) 
                                       WHERE id = ?");
        $update_child->bind_param("ssi", $child_name, $child_name, $babysitter_id);
        $update_child->execute();

        // Update request status
        $update_query = $con->prepare("UPDATE babysitter_requests SET status = 'accepted' WHERE id = ?");
        $update_query->bind_param("i", $request_id);
        $update_query->execute();

        return "Request accepted!";
    } elseif ($action === "reject") {
        $update_query = $con->prepare("UPDATE babysitter_requests SET status = 'rejected' WHERE id = ?");
        $update_query->bind_param("i", $request_id);
        $update_query->execute();

        return "Request rejected!";
    }
}

?>
