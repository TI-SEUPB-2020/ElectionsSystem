<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="stylesheet.css">
  <title>Vota por tu capitán</title>
</head>
<body style="font-family:Verdana;color:#aaaaaa;">
  <div style="background-color:#e5e5e5;padding:15px;text-align:center;">
  <h1>Elecciones de Capitán 2020 - SEUPB 2020</h1>
</div>

<div style="overflow:auto">
  <div class="col1" style="padding-top: 25px;">
    <button onclick="window.location.href = '/electionsTrial/indexmenu.php';" class="buttonVote buttonVote1">Volver a Elegir Carrera</button>
  </div>

  <div class="main">

    <?php
      include 'db_connection.php';

  	  $conn = OpenCon();

      $school = $_GET['school'];

      switch ($school) {
        case "DTI":
          $validDegrees = array("Ingenieria Electromecanica","Ingenieria de Sistemas Computacionales","Ingenieria de Sistemas Electronicos y de Telecomunicaciones");
          $degree = join("','", $validDegrees);
          break;
        case "MEE":
          $validDegrees = array();
          break;
        case "EIE":
          $validDegrees = array();
          break;
        default:
          echo "Your school is not valid!";
      }

      echo "<form method='post'>";
      echo "<h2>Candidatos de ". $school . " - ¡A votar! </h2>";
      echo "Tu Código: <input type='number' name='code' id='code' value='";
      print $_POST["code"];
      echo "' placeholder='Ej.: 10101'>";

      echo " <button type='submit' name='CheckBtn' class='buttonVote buttonVote1'>Verificar</button><br>";

      if(isset($_POST["CheckBtn"])){
        $code = $_POST["code"];

        $sql3 = "SELECT code FROM Students WHERE code = $code AND voted = 'false' AND degree IN ('$degree')";
        $result3 = $conn->query($sql3);

        $sql4 = "SELECT fullname FROM Students WHERE code = $code AND voted = 'false' AND degree IN ('$degree')";
        $result4 = $conn->query($sql4);

        if ($result3 == null) {
          echo "Por favor introduzca su codigo";
        } else {
          if ($result3->num_rows > 0) {
      			// output data of each row
      			while($row = $result3->fetch_assoc()) {
              echo "Habilitado para Votar <br>";
              echo "Código: " . $row["code"] . "<br>";
              if ($result4->num_rows > 0){
                while($row = $result4->fetch_assoc()){
                  echo "Nombre: " . $row["fullname"];
                }
              }

                $sql2 = "SELECT fullname FROM Candidates WHERE school = '$school'";
                $result2 = $conn->query($sql2);

                $cont = 0;

                if ($result2->num_rows > 0) {
                  // output data of each row
                  echo "<table class='table'>";
                  while($row = $result2->fetch_assoc()) {
                    if ($cont == 0){
                      echo "<tr><th><input type='radio' name='candidate' value='" . $row["fullname"] . "'> " . $row["fullname"] . "</th>";
                      $cont = 1;
                    } else if ($cont == 1){
                      echo "<th><input type='radio' name='candidate' value='" . $row["fullname"] . "'> " . $row["fullname"] . "</th></tr>";
                      $cont = 0;
                    }
                  }
                } else {
                  echo "0 results";
                }
                echo "</table>";
                echo "<button type='submit' name='VoteBtn' class='buttonVote buttonVote1'>Votar</button><br>";
      			}
      		} else {
      			echo "0 results";
      		}
        }

      } else if (isset($_POST['VoteBtn'])){
          $std0 = $_POST["code"];
          $std = (int)$std0;
        if(!isset($_POST['candidate'])) {
          echo "Selecciona una opción.";
          echo $std;
        } else {
          $cand = $_POST['candidate'];
          $sqlvote = "UPDATE Candidates SET votes = votes + 1 WHERE fullname = '$cand'";
          $sqlvoteCheck = "UPDATE Students SET voted = 1 WHERE code = $std";

          if ($conn->query($sqlvote) === TRUE) {
            echo "New record created successfully";
            if($conn->query($sqlvoteCheck) === TRUE){
              echo "Votaste por " . $cand . " codigo " . $std;
            }
          } else {
            echo "Error: " . $sqlvote . "<br>" . $conn->error;
          }
        }

      }
      echo "</form>";

      CloseCon($conn);
     ?>
  </div>

  <div class="col2 col2Vote">
    <img src="/electionsTrial/images/icons01.png" alt="Girl in a jacket" height="200px" width="200px">
  </div>
</div>

<div class="footer">By Secretaría de Tecnología e Innovación - ISC 2020</div>

</body>
</html>
