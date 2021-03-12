<?php
  // db connection
  include 'config/database.php';

  $airports_query = "SELECT * FROM airport";
  $stmt = $connection->prepare($airports_query);
  $stmt->execute();
  // store a list of all the airports on database
  $airports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
<form action="./result.php" method="GET">
  <div>
    <label for="departure">Departure airport</label>
    <select name="departure" id="departure">
      <?php
        foreach ($airports as $airport):
      ?>
        <option value="<?= $airport['code'] ?>"><?= $airport['name'] ?></option>
      <?php
        endforeach;
      ?>
    </select>
  </div>
  <div>
    <label for="arrival">Arrival airport</label>
    <select name="arrival" id="arrival">
      <?php
        foreach ($airports as $airport):
      ?>
        <option value="<?= $airport['code'] ?>"><?= $airport['name'] ?></option>
      <?php
        endforeach;
      ?>
    </select>
  </div>
  <input type="submit" value="Search">
</form>
</body>
</html>