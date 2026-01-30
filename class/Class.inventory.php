<?php
class FDSInventory
{
	public function GetLeadTime($lead_time,$db)
	{
		$query = "SELECT * FROM dbc_inventory_leadtime";
		$results = mysqli_query($db, $query);    
		if ( $results->num_rows > 0 ) 
		{
		    while($ROW = mysqli_fetch_array($results))  
			{
				$name = $ROW[$lead_time];
			}
			return $name;
		} else {
			return 0;
		}
		mysqli_close($db);
	}
	public function GetDailyInventory($kwiri,$eVal,$days_count,$min_leadtime,$max_leadtime,$db)
	{
		$sqlQueryRecords = "SELECT * FROM dbc_inventory_records";
		$invResults = mysqli_query($db, $sqlQueryRecords);    
	    if ( $invResults->num_rows > 0 ) 
	    {	    	
	    	while($INVENTORYROW = mysqli_fetch_array($invResults))  
			{
		    	$max_average = array();$average=0;$rol=0;
				for($x = 1; $x <= $days_count; $x++)
				{
					if($INVENTORYROW['day_'.$x] > 0)
					{
						$average += $INVENTORYROW['day_'.$x];
						$max = $INVENTORYROW['day_'.$x];					
					}
					$max_average[] = $max;
									$average_dr = round($average / $days_count);			
				$max_dr = max($max_average);			
				$safety_stocks = $max_dr * $max_leadtime - $average_dr * $min_leadtime;			
				$rol = $average_dr * $min_leadtime + $safety_stocks;

				}				
			}				
	    } else {
	    	$rol = 0;
	    }
	    if($eVal == 'rol')
	    {
	    	return $rol;
	    }
	    mysqli_close($db);
	}
}
