# City-Distance-Calculator

This script uses a city/state CSV file to calculate the distance between any 2 cities in the United State via Haversine formula.

The current setup is as a DIY example as a self-teaching exercise, though you can bypass this by just loading my prepared database located at Assets/locations.sql into a 'locations' database with 'geocode' table.
I used table 'geocode' with columns: state char(2) as primary key, city varchar(255), lati double(9,6), longi double(9,6).

If you choose to DIY, the CSV contains a lot of duplicates-
use "ALTER IGNORE TABLE geocode ADD UNIQUE_INDEX idx_geo (state, city);" to erase duplicates.
Done.

Once the database is running you can just run the index.php page and it will calculate all cities within 20 miles from the entered city.
