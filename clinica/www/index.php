<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>JS Bin</title>
  <script src='https://meet.jit.si/external_api.js'></script>
  <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
  <script src='js/video_convite.js'></script>
</head>
<body>
  <?php 

  $tk = $_GET['token'];

  echo "<h1 align='center'> Video </h1>
        <div align='center'>
            <input type='hidden' value='$tk' id='token'>
        </div>
          <script> entrarSala(); </script>
        <div align='center' id='meet'> 
            
        </div>";
  ?>
</body>
</html>