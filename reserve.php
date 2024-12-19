<?php  

include 'components/connect.php';  

session_start();  

if (isset($_SESSION['user_id'])) {  
    $user_id = $_SESSION['user_id'];  
} else {  
    $user_id = '';  
    header('location:home.php');  
}  



if (isset($_POST['reserve'])) {  
    $reservation_date = $_POST['reservation_date'];  
    $reservation_time = $_POST['reservation_time'];  

    // Validate inputs  
    if (empty($reservation_date) || empty($reservation_time)) {  
        $messages[] = 'Please fill in all fields.';   
    } else {  
        $reservation_datetime = $reservation_date . ' ' . $reservation_time;  

        $check_availability = $conn->prepare("SELECT * FROM `reservations` WHERE reservation_datetime = ?");  
        $check_availability->execute([$reservation_datetime]);  

        if ($check_availability->rowCount() > 0) {  
            $messages[] = 'Time is not available, please choose another';  
        } else {  
            $insert_reservation = $conn->prepare("INSERT INTO `reservations` (user_id, reservation_datetime) VALUES (?, ?)");  
            if ($insert_reservation->execute([$user_id, $reservation_datetime])) {  
                $messages[] = 'Reservation successfully made!';  
            } else {  
                $messages[] = 'Failed to make the reservation. Please try again.'; 
            }  
        }  
    }  
}  

?>  

<!DOCTYPE html>  
<html lang="en">  
<head>  
   <meta charset="UTF-8">  
   <meta http-equiv="X-UA-Compatible" content="IE=edge">  
   <meta name="viewport" content="width=device-width, initial-scale=1.0">  
   <title>Reserve</title>  
   <!-- font awesome cdn link  -->  
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">  
   <!-- custom css file link  -->  
   <link rel="stylesheet" href="css/style.css">  
   <style>  
      .reservation {  
         max-width: 70%;  
         margin: 2rem auto;  
         background: #fff;  
         padding: 3rem;  
         border-radius: 15px;  
         box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);  
         width: 100%;  
      }  
      .reservation h1.title {  
         text-align: center;  
         font-size: 2.5rem;  
         margin-bottom: 0rem;  
         color: #333;  
      }  
      .reservation-form {  
         display: flex;  
         flex-direction: column;  
         align-items: center;  
         gap: 0rem;  
      }  
      .form-group {  
         margin-top: 2rem;  
         display: flex;  
         align-items: center;  
         gap: 1rem;   
         width: 100%;  
         max-width: 550px;  
      }  
      .reservation-form label {  
         font-size: 2rem;  
         color: #555;  
         width: auto;  
         text-align: left;  
      }  
      .reservation-form input[type="date"],  
      .reservation-form input[type="time"] {  
         padding: 1.5rem;  
         font-size: 1.5rem;  
         border: 1px solid #ccc;  
         border-radius: 5px;  
         flex: 1;   
      }  
      .reservation-form .btn {  
         background: #333;  
         color: #fff;  
         border: none;  
         padding: 1rem 2rem;  
         font-size: 1.3rem;  
         cursor: pointer;  
         border-radius: 8px;  
         text-transform: uppercase;  
         transition: background 0.3s;  
      }  
      .reservation-form .btn:hover {  
         background: #555;  
      }  
   </style>  
</head>  
<body>  
   
<!-- header section starts  -->  
<?php include 'components/user_header.php'; ?>  
<!-- header section ends -->  

<div class="heading">  
   <h3>Reserve</h3>  
   <p><a href="home.php">home</a> <span> / reserve</span></p>  
</div>  

<section class="reservation">  
   <h1 class="title">Make a Reservation</h1>  

   <?php  
   if (!empty($messages)) {  
       foreach ($messages as $message) {  
           echo '  
           <div class="message">  
               <span>' . $message . '</span>  
               <i class="fas fa-times" onclick="this.parentElement.remove();"></i>  
           </div>  
           ';  
       }  
   }  
   ?>  

   <form action="" method="POST" class="reservation-form">  
      <div class="form-group">  
         <label for="reservation_date">Date:</label>  
         <input type="date" name="reservation_date" id="reservation_date" required>  
      </div>  

      <div class="form-group">  
         <label for="reservation_time">Time:</label>  
         <input type="time" name="reservation_time" id="reservation_time" required>  
      </div>  

      <input type="submit" name="reserve" value="Reserve Now" class="btn">  
   </form>  
</section>  

<!-- custom js file link  -->  
<script src="js/script.js"></script>  

</body>  
</html>