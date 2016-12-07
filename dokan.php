<?php
echo "US Format: 10/02/2016 " .strtotime("10/02/2016") . "<br />";
echo "Date Output " .date("d/m/Y", strtotime("10/02/2016")) . "<br />";
echo "Non US Format: 10.02.2016 " .strtotime("10.02.2016") . "<br />";
echo "Date Output " .date("d/m/Y", strtotime("10.02.2016")) . "<br />";
