<?php

$a = json_decode('[{"Name":"Lpg"},{"Name":"Gas oil"},{"Name":"Naturel Gas"},{"Name":"Motorin"},{"Name":"No4 Fuel-Oil"},{"Name":"No5 Fuel-Oil Heavy"},{"Name":"No6 Fuel-Oil"},{"Name":"No8 Fuel-Oil"},{"Name":"No5 Fuel-Oil Light"},{"Name":"No2 Fuel-Oil"},{"Name":"No1 Fuel-Oil"}]', true);

//Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvbG9naW4iLCJpYXQiOjE2MTU4OTMxNjIsImV4cCI6MTYxNTg5Njc2MiwibmJmIjoxNjE1ODkzMTYyLCJqdGkiOiJpYzNjblFBZjJMd0FlNDFIIiwic3ViIjoxLCJwcnYiOiJmNmI3MTU0OWRiOGMyYzQyYjc1ODI3YWE0NGYwMmI3ZWU1MjlkMjRkIn0.c8iTG8-Aw4nbwhxRG0koJbZwTyXQ6Ia7eiAGhUuOlIs
//$a = array("Name" => "Peter", "Age" => "41", "Country" => "USA");
print_r(array_values($a));
