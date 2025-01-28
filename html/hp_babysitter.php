<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests Page</title>
    <link rel="stylesheet" href="../css/hp_babysitter.css">
</head>
<body>

    <div class="navbar">
        <div class="logo">
            <img src="../images/LOGO.png">
            </div>
        <div class="nav-links">
            <a href="#">HOME</a>
            <a href="calendar_babysitter.php">CALENDAR</a>
            <a href="#">REQUESTS</a>
            <a href="#" class="logout">LOG OUT</a>
        </div>
    </div>

    <div class="container">
        <?php
            $requests = [
                ["name" => "PARENT NAME", "message" => "Hello, I am PARENT NAME. Will you be able to take care of my kids on Wednesday?", "email" => "dajhdajhdj@mail.com", "phone" => "08129121921"],
                ["name" => "PARENT NAME", "message" => "Hello, I am PARENT NAME. Will you be able to take care of my kids on Wednesday?", "email" => "dajhdajhdj@mail.com", "phone" => "08129121921"],
                ["name" => "PARENT NAME", "message" => "Hello, I am PARENT NAME. Will you be able to take care of my kids on Wednesday?", "email" => "dajhdajhdj@mail.com", "phone" => "08129121921"]
            ];

            foreach ($requests as $request) {
                echo "<div class='request-card'>
                        <div class='profile-pic'></div>
                        <div class='request-content'>
                            <strong>{$request['name']}</strong>
                            <p>{$request['message']}</p>
                            <small>Here are my contacts: {$request['email']}, {$request['phone']}</small>
                        </div>
                    </div>";
            }
        ?>
    </div>

</body>
</html>
