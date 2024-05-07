<?php
include 'connect.php';

// Create an instance of the Connect class
$connection = new Connect();
if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
 }else{
    $user_id='';
    header('location:login.php');
 }
 $select_user = $connection->conn->prepare("SELECT * FROM languagelearners WHERE LearnerID = ? LIMIT 1"); 
 $select_user->bind_param("i", $user_id);
 $select_user->execute();
 $fetch_user = $select_user->get_result()->fetch_assoc();
 
 // Check if the query was successful
 if ($fetch_user) {
     $name = $fetch_user['FirstName'];
    // Default name if the query fails or no data is found
    $name = "Guest";
 }
if(isset($_GET['partnerID'])){
    $partnerID = $_GET['partnerID'];}

// Retrieve partner details from the database
$sql = "SELECT * FROM LanguagePartners WHERE PartnerID = ?";
$stmt = $connection->conn->prepare($sql);
$stmt->bind_param('s', $partnerID);
$stmt->execute();
$result = $stmt->get_result();

// $sqlSidebar = "SELECT Photo, CONCAT(FirstName, ' ', LastName) AS FullName FROM LanguageLearners WHERE User_Role = 'learner' LIMIT 1";
// $resultSidebar = $connection->conn->query($sqlSidebar);
// $rowSidebar = $resultSidebar->fetch_assoc();
// $learnerPhoto = $rowSidebar['Photo'];
// $learnerName = $rowSidebar['FullName'];

// Calculate average rating for the partner
// $averageRating = 0;
// $numRatings = 0;

// // Query to get all ratings for the partner
// $sqlRatings = "SELECT Rating FROM reviewsratings WHERE PartnerID = ?";
// $stmtRatings = $connection->conn->prepare($sqlRatings);
// $stmtRatings->bind_param('s', $partnerID);
// $stmtRatings->execute();
// $resultRatings = $stmtRatings->get_result();

// // Calculate average rating
// if ($resultRatings->num_rows > 0) {
//     while ($rowRating = $resultRatings->fetch_assoc()) {
//         $averageRating += $rowRating['Rating'];
//         $numRatings++;
//     }
//     $averageRating /= $numRatings; // Calculate the average
//     $sqlUpdateRating = "UPDATE languagepartners SET Rating = $averageRating WHERE PartnerID = ?";
//     $stmtUpdateRating = $connection->conn->prepare($sqlUpdateRating);
//     $stmtUpdateRating->bind_param('s', $partnerID);
//     $stmtUpdateRating->execute();
//     $stmtUpdateRating->close();
// }
// else{
//     $sqlUpdateRating = "UPDATE languagepartners SET Rating = 0 WHERE PartnerID = ?";
// $stmtUpdateRating = $connection->conn->prepare($sqlUpdateRating);
// $stmtUpdateRating->bind_param('s', $partnerID);
// $stmtUpdateRating->execute();
// $stmtUpdateRating->close();
    
// }


if($result->num_rows > 0) {
    $partner = $result->fetch_assoc();
} else {
    echo "Partner not found.";
}

$stmt->close();
$connection->conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="header">
<div class="flex">
   
   <a href="profileLearner.php" class="logo"> <img src = "images/logo.jpg" width="210" height="60" alt="logo"></a> 

   <div class="icons">
     
      <div id="toggle-btn" class="fas fa-sun"></div>
   </div>


</div>
</header>

<div class="side-bar">

<div id="close-btn">
   <i class="fas fa-times"></i>
</div>

<div class="profile">
<img src="images/<?= $fetch_user['Photo']; ?>" class="image" alt="">
   <h3 class="name"><?= $fetch_user['FirstName'] . ' ' . $fetch_user['LastName']; ?></h3>
   <p class="role">Learner</p>
</div>

<nav class="navbar">
<a href="profileLearner.php"><i class="fas fa-home"></i><span>home</span></a>
   <a href="SesssionsLearner.php"><i><img src="images/session.png" alt="sessions"></i><span>sessions</span></a>
   <a href="partners.php"><i class="fas fa-chalkboard-user"></i><span>partners</span></a>
   <a href="about_learner.php"><i class="fas fa-question"></i><span>about</span></a>
</nav>
<nav>
   <div style="text-align: center; margin-top: 20px; margin-bottom: 150px;">
   <a href="user_logout.php"  class="inline-btn" >Sign out</a>
</div>
</nav>

</div>

<section class="teacher-profile">
    <h1 class="heading">Partner Details</h1>
    <div class="details">
        <div class="tutor">
            <img src="images/<?php echo $partner['Photo']; ?>" alt="">
            <h3><?php echo $partner['FirstName'] . ' ' . $partner['LastName']; ?></h3>
            <span><?php echo $partner['Bio']; ?></span>
        </div>
        <div class="flex">
            <p>Proficiency in Language : <span>Professional</span></p>
            <p>Session Price : <span> 100$ per hour.</span></p> 
            <p> <?php echo $partner['Rating']; ?> • 6 reviews <a href="reviewsLearner.php?partnerID=<?php echo $partnerID; ?>" 
            class="inline-btn">View Reviews</a></p>
        </div>
    </div>
</section>

<footer class="footer">
&copy; copyright @ 2024 by <span>CHAT FLUENCY</span> | all rights reserved!
   <a href="contact_learner.php"><i class="fas fa-headset"></i><span> contact us</span></a>
</footer>

<script src="script.js"></script>
</body>
</html>


