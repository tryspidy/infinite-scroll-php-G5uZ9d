<?php

    $pageno = $_POST['pageno'];

    $no_of_records_per_page = 10;
    $offset = ($pageno-1) * $no_of_records_per_page;

    $conn=mysqli_connect("localhost","my_user","my_password","my_db");
    // Check connection
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        die();
    }

    $sql = "SELECT * FROM table LIMIT $offset, $no_of_records_per_page";
    $res_data = mysqli_query($conn,$sql);

    while($row = mysqli_fetch_array($res_data)){

        echo '<div>Demo'.$row["id"].'</div>';

    }

    mysqli_close($conn);

?>


<!DOCTYPE html>
<html>
<head>
    <title>Infinite Scroll Demo</title>

    <!-- JQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <!-- Inview Js (jquery.inview.js) -->
    <script src="jquery.inview.js"></script>

    <style>
        #response div{
            border: 1px solid lightgrey;
            height: 80px;
            margin-bottom: 5px;
            padding: 50px 0px 0px 0px;
            text-align: center;
        }
        #loader{
            display: block;
            margin: auto;
        }
    </style>
</head>
<body>

    <div id="response">

        <!-- response(next page's data) will get appended here -->

        <!--we need to populate some initial data-->
        <?php
            $conn=mysqli_connect("localhost","my_user","my_password","my_db");
            // Check connection
            if (mysqli_connect_errno()){
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                die();
            }
            $sql = "SELECT * FROM table LIMIT 5";
            $res_data = mysqli_query($conn,$sql);
            while($row = mysqli_fetch_assoc($res_data)){
                echo '<div>Demo'.$row["id"].'</div>';
            }
            mysqli_close($conn);
        ?>
    </div>

     <input type="hidden" id="pageno" value="1">
     <img id="loader" src="loader.svg">
     <script>
         $(document).ready(function(){
             $('#loader').on('inview', function(event, isInView) {
                 if (isInView) {
                     var nextPage = parseInt($('#pageno').val())+1;
                     $.ajax({
                         type: 'POST',
                         url: 'pagination.php',
                         data: { pageno: nextPage },
                         success: function(data){
                             if(data != ''){							 
                                 $('#response').append(data);
                                 $('#pageno').val(nextPage);
                             } else {								 
                                 $("#loader").hide();
                             }
                         }
                     });
                 }
             });
         });
     </script>
</body>
</html>