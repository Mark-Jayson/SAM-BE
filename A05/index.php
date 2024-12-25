
<?php
include("connect.php");
include("classes.php");

$quarters = array();

$getIslandQuery = "
    SELECT 
        ip.islandOfPersonalityID,
        ip.name AS island_name, 
        ip.longDescription AS long_desc, 
        ic.islandContentID,
        ic.content AS content, 
        ic.image AS island_image,
        ip.image AS personality_image,
        ic.color AS color
    FROM islandsofpersonality ip
    JOIN islandcontents ic ON ip.islandOfPersonalityID = ic.islandOfPersonalityID
    ORDER BY ip.islandOfPersonalityID, ic.islandContentID
";

$getIslandResult = executeQuery($getIslandQuery);

if (!$getIslandResult) {
    die('Query failed: ' . mysqli_error($connection));
}

while ($row = mysqli_fetch_assoc($getIslandResult)) {
    $a = new Island(
        $row['island_name'],
        $row['long_desc'],
        $row['content'],
        $row['island_image'],
        $row['personality_image'],
        $row['islandContentID'],
        $row['islandOfPersonalityID'],
        $row['color']
    );

    if (!isset($quarters[$row['islandOfPersonalityID']])) {
        $quarters[$row['islandOfPersonalityID']] = array();
    }
    array_push($quarters[$row['islandOfPersonalityID']], $a);
}
?>



<!DOCTYPE html>
<html>

<head>
  <title>Jayson's Island of Personality</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="icon.png">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Lato', sans-serif;
      color: #777;
      line-height: 1.8;
    }

    

    hr {
        border: none;
        border-top: 2px solid #000; 
        width: 80%; 
        margin: 20px auto; 
        margin-bottom: 50px;
    }
    
    .hover-effect {
      position: relative;
      width: 400px;
      height: 200px;
      background-color: #ccc;
      overflow: hidden;
      border-radius: 10px;
      margin: 10px;
    }

    .hover-effect img{
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .overlay {
      position: absolute;
      bottom: -100%;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: bottom 0.3s ease;
    }

    .text {
      color: #fff;
      font-size: 30px;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .hover-effect:hover .overlay {
      bottom: 0;
    }

    .hover-effect:hover .text {
      opacity: 1;
    }


    .parallax,
    .parallax-contact,
    .parallax-personalities {
      background-attachment: fixed;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      min-height: 100vh;
    }

    .parallax {
      background-image: url("Assets/Banner.jpg");
      height: 700px;
      z-index: 1;
    }
    .parallax::before {
      content:  "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: inherit;
      background:  linear-gradient( to right,
                rgba(0, 0, 0, 0.1), /* Semi-transparent red */
                rgba(0, 0, 0, 0.7));
      pointer-events: none;
      z-index: 2;
    }
    .main-logo {
      width: 170px;
      z-index: 11;
    }

    .logo {
      color: white;
      position: relative;
      font-family: Poppins;
      margin-left: 700px;
      z-index: 11;
    }

    .parallax-personalities {
      margin: auto;
      background-size: 70%;
      min-height: 400px;
      border-bottom: 200px;
      height: auto;
      width: 70vw;
      position: relative; 
      background-image: url('your-image.jpg');
      background-repeat: no-repeat;
      background-position: center;
    }

    .parallax-personalities::before {
      content:  "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      pointer-events: none;
      z-index: 1;
    }

    .label{
      position: relative;
      z-index: 2;
      color: white;
      text-align: center;
      padding: 20px;
      font-family: Poppins;
      font-weight: 700;
    }

    

    .wide-text {
      letter-spacing: 10px;
    }

    @media only screen and (max-device-width: 1600px) {
      .parallax {
        overflow: hidden;
        min-height: 400px;
      }
    }

    @media (max-width: 768px) {
      .parallax {
        height: 500px;
      }

      .hover-effect {
        width: 80%;
        height: 200px;
      }

      .parallax-personalities {
        height: 400px;
        background-size: 120vw;
        width: 100vw;
      }

      .wide-text {
        font-size: 2rem;
      }

      .logo {
        font-family: Poppins;
        margin: auto;
      }
    }

    .skill-bar {
      height: 24px;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light fixed-top p-4 bg-white" id="mainNav">
    <div class="container">
      <a class="navbar-brand" href="#home">HOME</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="#about"><i class="fa  me-2"></i>ABOUT</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#Leisure"><i class="fa  me-2"></i>LEISURE</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#Hobbies"><i class="fa  me-2"></i>HOBBIES</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#Organization Works"><i class="fa  me-2"></i>ORG WORKS</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#Events"><i class="fa  me-2"></i>EVENTS</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#contact"><i class="fa  me-2"></i>CONTACT</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- First Parallax Image -->
  <div class="parallax d-flex align-items-center justify-content-center" id="home">
    <div class="text-center">
      <h1 class="display-4 logo">Jayson's Personality<img src="Assets/logo.png" class="main-logo"
          alt="Photo of Me"></h1>
    </div>
  </div>

  <!-- About Section -->
  <div class="container py-5" id="about">
    <h3 class="text-center mb-4">ABOUT ME</h3>
    <p class="mb-4 d-flex justify-content-center text-center">Hi I'm Mark Jayson Agao, I am a 4th Year BS Information Technology Student in PUP Sto. Tomas Branch. Moreover, I am known for the different facets of my personalities as seen by my closed friends and relatives in different fields. I hope this website show that aspect of my personality and make you know more about me. </p>



  </div>



  <!-- Personalities Section -->
                    <?php
                        foreach ($quarters as $personalityID => $islands) {
                            $firstIsland = $islands[0];
                            echo $firstIsland->generatePersonalityHeader(); 
                            foreach ($islands as $island) {
                                echo $island->generateContentCard();
                            }
                            echo Island::closePersonalitySection();
                        }
                        ?>

      <div class="modal bg-dark" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0">
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
              <h1 class="display-4 text-white" id="modalTitle"></h1>
              <img id="modalImage" class="img-fluid">
              <p id="modalCaption" class="text-white mt-3"></p>
            </div>
          </div>
        </div>
      </div>




      <!-- Contact Section -->
      <hr>
      <div class="container py-5" id="contact">
        <h3 class="text-center">CONTACTS</h3>

        <div class="row">
          <div class="col-md-4">
          </div>
          <div class="col-md-8">
            <div class="fs-5 mb-4">
              <p><i class="fa fa-map-marker fa-fw me-3"></i>Lipa City, Batangas, Philippines</p>
              <p><i class="fa fa-phone fa-fw me-3"></i>Phone: 09664124895</p>
              <p><i class="fa fa-envelope fa-fw me-3"></i>Email: markjaysonagao10@gmail.com</p>
            </div>
          </div> 
        </div>
      </div>

      <!-- Footer -->
      <footer class="bg-dark text-white text-center py-5">
        <a href="#home" class="btn btn-light mb-4">
          <i class="fa fa-arrow-up me-2"></i>To the top
        </a>
        <div class="fs-3 mb-4">

          <a href="https://www.facebook.com/mjathegreatest" class="text-white text-decoration-none"><i class="fa fa-facebook-official mx-2 hover-opacity" ></i></a>
          <a href="https://www.instagram.com/markjayson_creates/" class="text-white text-decoration-none"><i class="fa fa-instagram mx-2 hover-opacity"></i></a>
          <a href="https://www.linkedin.com/in/mark-jayson-agao/" class="text-white text-decoration-none"><i class="fa fa-linkedin mx-2 hover-opacity"></i></a>
        </div>
        <p>Powered by <a href="https://getbootstrap.com" class="text-success text-decoration-none">Bootstrap</a></p>
      </footer>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
      <script>
        // Modal Image Gallery
        function showModal(divElement) {
          const modal = new bootstrap.Modal(document.getElementById('imageModal'));

          // Find the image inside the clicked div
          const imgElement = divElement.querySelector("img");

          // Update modal content using the div and img attributes
          document.getElementById('modalTitle').innerHTML = divElement.id; // Use the parent div's ID as the title
          document.getElementById('modalImage').src = imgElement.src; 
          document.getElementById('modalCaption').innerHTML = imgElement.getAttribute("alt"); 

          // Show the modal
          modal.show();
        }


      
        window.onscroll = function () {
          const navbar = document.getElementById('mainNav');
          if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            navbar.classList.add('bg-white', 'shadow');
          } else {
            navbar.classList.remove('bg-white', 'shadow');
          }
        };

        document.addEventListener("DOMContentLoaded", function () {
          const hoverDivs = document.querySelectorAll(".hover-effect");
          hoverDivs.forEach(function (hoverDiv) {
            const overlayText = hoverDiv.querySelector(".text");
            overlayText.textContent = hoverDiv.id; 
          });
        });

      </script>

</body>

</html>