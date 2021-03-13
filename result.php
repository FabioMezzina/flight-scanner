<?php
// db connection
include 'config/database.php';

try {
  // get departure and arrival airports code from query-string
  $departure = (int) $_GET['departure'];
  $arrival = (int) $_GET['arrival'];
  
  // error check if the user input for departure and arrival airport is the same
  if($departure == $arrival) {
    echo '<h2>Your departure is equal to your destnation, you does not need a flight :)</h2>';
  } else {
    // initialize flight solutions array
  $solutions = [];

  /**
   * get all flights from $departure code airport
   * db query that gets every flight departing from $departure (direct flights to $arrival WILL BE in the results of this query)
   */
  $query_string_find_dep = "SELECT * FROM flight WHERE code_departure = $departure";
  $stmt = $connection->prepare($query_string_find_dep);
  $stmt->execute();
  // store departure flights into array
  $departure_flights = $stmt->fetchAll(PDO::FETCH_ASSOC);

  /**
   * get all flights to $arrival code airport
   * db query that gets every flight arriving at $arrival (direct flights from $departure WILL NOT BE in the results of this query to avoid data redundancy)
   */  
  $query_string_find_arr = "SELECT * FROM flight WHERE code_arrival = $arrival AND code_departure <> $departure";
  $stmt = $connection->prepare($query_string_find_arr);
  $stmt->execute();
  // store arrival flights into array
  $arrival_flights = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
  /**
   * populate the solutions array
   */
  foreach ($departure_flights as $dep_flight) {
    // check if the current flight is a direct flight to arrival airport
    if ($dep_flight['code_arrival'] == $arrival) {
      $solutions[] = [
        'direct' => true,
        'id_dep' => $dep_flight['id'],
        'id_arr' => null,
        'price' => $dep_flight['price'],
      ];
    } else {
      // if not a direct flight, check if a flight arriving at destination departing from the arrival airport of the first flight exists
      foreach ($arrival_flights as $arr_flight) {
        if ($arr_flight['code_departure'] == $dep_flight['code_arrival']) {
          $solutions[] = [
            'direct' => false,
            'id_dep' => $dep_flight['id'],
            'id_arr' => $arr_flight['id'],
            'price' => $dep_flight['price'] + $arr_flight['price'],
          ];
        }
      }
    }
  }

  /**
   * search algorithm for lowest price
   */

  // check if a flight solution exists
  if(!empty($solutions)) {

    // initialize lowest price solutions array
    $lowest_price_array = [
      ['direct' => false,
      'id_dep' => null,
      'id_arr' => null,
      'price' => INF,]
    ];
    // check if the current flight solution has a lower price. If so, it becomes the first and only element
    foreach ($solutions as $key => $solution) {
      if($solution['price'] < $lowest_price_array[0]['price']) {
        $lowest_price_array = [$solution];
        // if it has an equal price, push it into the lowest price solutions array
      } else if($solution['price'] == $lowest_price_array[0]['price']) {
        $lowest_price_array[] = $solution;
      }
    }

    // print the lowest price solutions array
    echo '<h2>The best flight solutions for you:</h2>';
    foreach ($lowest_price_array as $key => $result) {
      echo '<h3>Solution nr. '. ($key + 1) .'</h3>';
      if($result['direct']) {
        echo '<h4>Flight code: ' . $result['id_dep'] .'</h4>';
      } else {
        echo '<h4>First flight code: ' . $result['id_dep'] .'</h4>';
        echo '<h4>Second flight code: ' . $result['id_arr'] .'</h4>';
      }
      echo '<p>Total price: ' . $result['price'] . ' €</p>';
      echo '<hr>';
    }
    } else {
      echo '<h2>We are sorry, there are no flights for your request :(</h2>';
    }
  }
}
// errors handling
catch(PDOException $exception) {
  die('ERROR: ' . $exception->getMessage());
}

?>