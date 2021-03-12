Technologies used:
- php 8.0
- OS: Linux pop.os
- XAMPP Apache + mySql


I designed a database called "flight_scanner".
It was made up of 2 entities:
- 'airport' with the following columns
  id -> BIGINT unsigned as Primary Key with auto-increment
  name -> VARCHAR (150)
  code -> INT
  lat -> FLOAT(15,12)
  lng -> FLOAT(15, 12)


- 'flight' with the following columns
  id -> BIGINT unsigned as Primary Key with auto-increment
  code_departure -> INT
  code_arrival -> INT
  price -> FLOAT(6, 2)
