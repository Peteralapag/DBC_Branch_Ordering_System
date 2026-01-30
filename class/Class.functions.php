<?php
class FDSFunctions
{

	public function fordigosclusteronly($branch, $db)
	{
	    $stmt = $db->prepare("SELECT 1 FROM tbl_branch WHERE location = 'DIGOS CLUSTER' AND branch = ?");
	    $stmt->bind_param("s", $branch);
	    $stmt->execute();
	    $stmt->store_result();
	
	    if ($stmt->num_rows > 0) {
	        return 1;
	    } else {
	        return 0;
	    }
	}
		
	
	public function getPendingToReceive($branch,$db)
	{
		if($this->fordigosclusteronly($branch, $db) !== 1)
		{
			$val = 0;
			$query = "SELECT COUNT(*) AS total FROM dbc_order_request WHERE branch='$branch' AND status='In-Transit' AND YEAR(delivery_date) >= 2025";
			$result = mysqli_query($db, $query);
			if($result->num_rows > 0){
				while ($rows = $result->fetch_assoc()){
					$val = $rows['total'];
				}
				return $val;
			}
			return $val;
		}
	}
	
	public function branchorderopengetmodulevalue($module,$branch,$controlno,$itemcode,$db)
	{
		
	    
	    $allowedColumns = ['quantity', 'inv_ending'];
	    if (!in_array($module, $allowedColumns)) {
	        return false;
	    }
	    
	    $query = "SELECT `$module` FROM dbc_branch_order WHERE branch = ? AND control_no = ? AND item_code = ?";

	    // Prepare the statement
	    if ($stmt = $db->prepare($query)) {
	        
	        // Bind the branch, controlno, and itemcode parameters as strings
	        $stmt->bind_param("sss", $branch, $controlno, $itemcode);
	        
	        // Execute the statement
	        $stmt->execute();
	        
	        // Get the result
	        $result = $stmt->get_result();
	        
	        // Fetch the data (if there's a result)
	        $data = $result->fetch_assoc();
	        
	        // Close the statement
	        $stmt->close();
	        
	        // Return the value of the requested column
	        return $data[$module] ?? null; // Return null if no result is found
	    } else {
	        // If the query preparation failed, return false
	        return false;
	    }	    
	    
	    
	}



	public function dateLockChecking($transdate,$db)
	{
		$QUERY = "SELECT * FROM dbc_datelock_checker WHERE report_date='$transdate' AND status='1'";
		$RESULTS = mysqli_query($db, $QUERY);    
		if ( $RESULTS->num_rows > 0 ) 
		{
			return 1;
		} else {
			return 0;
		}
	}
	
	public function branchOrderNULLCheking($controlno,$db)
	{
		$QUERY = "SELECT * FROM dbc_branch_order WHERE control_no='$controlno' AND (branch_received IS NULL OR branch_received = '')";
		$RESULTS = mysqli_query($db, $QUERY);    
		if ( $RESULTS->num_rows > 0 ) 
		{
			return 1;
		} else {
			return 0;
		}
	}

	public function getSubmittedOrder($branch,$db)
	{
		$QUERY = "SELECT COUNT(*) AS status_count FROM dbc_order_request WHERE branch = '$branch' AND status = 'Submitted'";
		$RESULTS = mysqli_query($db, $QUERY);    
		if ($RESULTS) {
	   		$row = mysqli_fetch_assoc($RESULTS);
			return $row['status_count'];
		} else {
			return 0;
		}
	}
	public function getForApproval($branch,$db)
	{
		$QUERY = "SELECT COUNT(*) AS status_count FROM dbc_order_request WHERE branch = '$branch' AND status = 'Approval'";
		$RESULTS = mysqli_query($db, $QUERY);    
		if ($RESULTS) {
	   		$row = mysqli_fetch_assoc($RESULTS);
			return $row['status_count'];
		} else {
			return 0;
		}
	}
	public function getOpenOrder($branch,$db)
	{
		$QUERY = "SELECT COUNT(*) AS status_count FROM dbc_order_request WHERE branch = '$branch' AND status = 'In-Transit' AND delivery_date < CURDATE()";
		$RESULTS = mysqli_query($db, $QUERY);    
		if ($RESULTS) {
	   		$row = mysqli_fetch_assoc($RESULTS);
			return $row['status_count'];
		} else {
			return 0;
		}
	}
	public function getOrderCreator($creator,$control_no,$db)
	{
		$QUERY = "SELECT * FROM dbc_order_request WHERE control_no='$control_no' AND created_by='$creator'";
		$RESULTS = mysqli_query($db, $QUERY);    
		if ( $RESULTS->num_rows > 0 ) 
		{
			return 1;
		} else {
			return 0;
		}
	}
	public function GetItemStock($itemcode,$db)
	{
		$QUERY = "SELECT * FROM dbc_inventory_stock WHERE item_code='$itemcode'";
		$RESULTS = mysqli_query($db, $QUERY);    
		if ( $RESULTS->num_rows > 0 ) 
		{
			while($ROW = mysqli_fetch_array($RESULTS))  
			{
				$return = $ROW['stock_in_hand'];
			}
			return $return;
		} else {
			return 0;
		}
	}
	public function LoadBranch($cluster,$db)
	{
		$QUERY = "SELECT * FROM tbl_branch WHERE location='$cluster'";
		$RESULTS = mysqli_query($db, $QUERY);    
		if ( $RESULTS->num_rows > 0 ) 
		{
			$return = '<option value=""> --- SELECT BRANCH --- </option>';
			while($ROW = mysqli_fetch_array($RESULTS))  
			{
				$branch = $ROW['branch'];
				$return .= '<option value="'.$branch.'">'.$branch.'</option>';
			}
			return $return;
		} else {
			return '<option value="">--- No Branch ---</option>';
		}
	}
	public function GetOrderStatus($control_no,$column,$db)
	{
		$query = "SELECT * FROM dbc_order_request WHERE control_no='$control_no'";
		$results = mysqli_query($db, $query);    
		if ( $results->num_rows > 0 ) 
		{
		    while($ROW = mysqli_fetch_array($results))  
			{
				$retVal = $ROW[$column];
			}
			return $retVal;
		} 
	}

	public function GetChecked($control_no,$column,$db)
	{
		$query = "SELECT * FROM dbc_order_request WHERE control_no='$control_no'";
		$results = mysqli_query($db, $query);    
		if ( $results->num_rows > 0 ) 
		{
		    while($ROW = mysqli_fetch_array($results))  
			{
				$retVal = $ROW[$column];
			}
			return $retVal;
		}
	}
	public function GetControlNo($branch,$db)
	{
		$query = "SELECT * FROM dbc_order_request WHERE branch='$branch'";
		$results = mysqli_query($db, $query);    
		if ( $results->num_rows > 0 ) 
		{
			$return = "";
		    while($ROW = mysqli_fetch_array($results))  
			{
				$controlno = $ROW['control_no'];
				$form_type = $ROW['form_type'];
				$return .= '<option value="'.$controlno.'">'.$controlno.' - '.$form_type.'</option>';
			}
			return $return;
		} else {
			return '<option value="">--- Recipient Not Configured ---</option>';
		}
	}
	public function GetRequestType($form_type)
	{
		$rt = array(
	        "MRS" => "MRS",
            "POF" => "POF"
        );
        $return = "";
        foreach ( $rt as $key => $value )
        {
        	$selected = "";
        	if($value == $form_type)
        	{
        		$selected = "selected";
        	}
            $return .= '<option '.$selected.' value="'.$value.'">'.$key.'</option>';
        }
        return $return;
	}
	public function getOrderRemarks($mrs_no,$db)
	{
		$QUERY = "SELECT * FROM dbc_branch_order_remarks WHERE control_no='$mrs_no'";
		$RESULTS = mysqli_query($db, $QUERY);    
		if ( $RESULTS->num_rows > 0 ) 
		{
			while($ROW = mysqli_fetch_array($RESULTS))  
			{
				return $ROW['remarks'];
			}
		} else {
			return "-|-";
		}
	}
	public function shortenText($text, $maxLength)
	{
		if (strlen($text) <= $maxLength)
		{
			return $text;
		} else {
		    $shortenedText = substr($text, 0, $maxLength);
		    $shortenedText .= '...';
		    return $shortenedText;
		}
	}
	public function GetRequestStatus($status)
	{
		$stats = array(
	        "Open" => "Open",
            "Closed" => "Closed",
            "Cancel" => "Cancel"
        );
        $return = "";
        foreach ( $stats as $key => $value )
        {
        	$selected = "";
        	if($value == $status)
        	{
        		$selected = "selected";
        	}
            $return .= '<option '.$selected.' value="'.$value.'">'.$key.'</option>';                        
        }
        return $return;
	}
	public function GetPriority($priority)
	{
        $priority = array(
	        "Normal" => "Normal",
            "Urgent" => "Urgent"                        
        );
        foreach ( $priority as $key => $value )
        {
        	$selected = "";
        	if($value == $transtype)
        	{
        		$selected = "selected";
        	}
            $return .= '<option '.$selected.' value="'.$value.'">'.$key.'</option>';                        
        }
        return $return;
	}
	public function LoadRecipient($form_type,$db)
	{
		$query = "SELECT * FROM dbc_recipient WHERE form_type='$form_type'";
		$results = mysqli_query($db, $query);    
		if ( $results->num_rows > 0 ) 
		{
//			$return = '<option value="">--- SELECT RECIPIENT ---</option>';
		    while($ROW = mysqli_fetch_array($results))  
			{
				$homopient = $ROW['recipient'];
				$selected = '';
				if($homopient == $recipient)
				{
					$selected = "selected";
				}
				$return .= '<option '.$selected.' value="'.$homopient.'">'.$homopient.'</option>';
			}
			return $return;
		} else {
			return '<option value="">--- Recipient Not Configured ---</option>';
		} 
	}	
	public function GetRecipient($recipient,$form_type,$db)
	{
		$query = "SELECT * FROM dbc_recipient WHERE form_type='$form_type'";
		$results = mysqli_query($db, $query);    
		if ( $results->num_rows > 0 ) 
		{
//			$return = '<option value="">--- SELECT RECIPIENT ---</option>';
		    while($ROW = mysqli_fetch_array($results))  
			{
				$homopient = $ROW['recipient'];
				$selected = '';
				if($homopient == $recipient)
				{
					$selected = "selected";
				}
				$return .= '<option '.$selected.' value="'.$homopient.'">'.$homopient.'</option>';
			}
			return $return;
		} else {
			return '<option value="">--- Recipient Not Configured ---</option>';
		} 
	}
	public function GetMRSNumber($db)
	{
		$query = "SELECT * FROM dbc_form_numbering WHERE id=1";
		$results = mysqli_query($db, $query);    
		if ( $results->num_rows > 0 ) 
		{
		    while($ROW = mysqli_fetch_array($results))  
			{
				$numbering = $ROW['mrs_number'];
			}
			return $numbering;
		} else {
			return 'Numbering is not configured';
		} 
	}
	public function GetTransType($transtype)
	{
        $transaction = array(
	        "Receiving" => "Receiving",
            "Return" => "Return"                        
        );
        foreach ( $transaction as $key => $value )
        {
        	$selected = "";
        	if($value == $transtype)
        	{
        		$selected = "selected";
        	}
            $return .= '<option '.$selected.' value="'.$value.'">'.$key.'</option>';                        
        }
        return $return;
	}

	public function GetDeliverytatust($status)
	{
        $delstatus = array(
	        "Full" => "Full",
            "Partial" => "Partial"                        
        );
        foreach ( $delstatus as $key => $value )
        {
            $return .= '<option value="'.$value.'">'.$key.'</option>';                        
        }
        return $return;
	}
	public function GetOrderStatust($status)
	{
        $ordstatus = array(
	        "Open" => "Open",
            "Closed" => "Closed"                        
        );
        foreach ( $ordstatus as $key => $value )
        {
            $return .= '<option value="'.$value.'">'.$key.'</option>';                        
        }
        return $return;
	}
	public function GetSupplierName($supplier_id,$db)
	{
		$query = "SELECT * FROM dbc_supplier WHERE id='$supplier_id'";
		$results = mysqli_query($db, $query);    
		if ( $results->num_rows > 0 ) 
		{
		    while($ROW = mysqli_fetch_array($results))  
			{
				$name = $ROW['name'];
			}
			return $name;
		} else {
			return 'Supplier not exists';
		} 
	}
	public function GetSupplier($supplier_id,$db)
	{
		$query = "SELECT * FROM dbc_supplier";
		$results = mysqli_query($db, $query);    
		if ( $results->num_rows > 0 ) 
		{
			$return = '<option value="">--- SELECT SUPPLIER ---</option>';
		    while($ROW = mysqli_fetch_array($results))  
			{
				$name = $ROW['name'];
				$value = $ROW['id'];
				$selected = '';
				if($value == $supplier_id)
				{
					$selected = "selected";
				}
				$return .= '<option '.$selected.' value="'.$value.'">'.$name.'</option>';
			}
			return $return;
		} else {
			return '<option value="">--- NO SUPPLIER ---</option>';
		} 
	}
	public function GetUOM($uom,$db)
	{
		$query = "SELECT * FROM dbc_units_measures";
		$results = mysqli_query($db, $query);    
		if ( $results->num_rows > 0 ) 
		{
			$return = '<option value="">- UOM -</option>';
		    while($ROW = mysqli_fetch_array($results))  
			{
				$units = $ROW['unit_name'];
				$selected = '';
				if($units == $uom)
				{
					$selected = "selected";
				}
				$return .= '<option '.$selected.' value="'.$units.'">'.$units.'</option>';
			}
			return $return;
		} else {
			return '<option value="">--- NO UOM ---</option>';
		} 
	}
	public function GetItemCategory($cat,$db)
	{
		$query = "SELECT * FROM dbc_item_category WHERE active=1";
		$results = mysqli_query($db, $query);    
		if ( $results->num_rows > 0 ) 
		{
			$return = '<option value="">--- SELECT CATEGORY ---</option>';
		    while($ROW = mysqli_fetch_array($results))  
			{
				$category = $ROW['category'];
				$selected = '';
				if($category == $cat)
				{
					$selected = "selected";
				}
				$return .= '<option '.$selected.' value="'.$category.'">'.$category.'</option>';
			}
			return $return;
		} else {
			return '<option value="">--- NO CATEGORY ---</option>';
		} 
	}
	public function GetRowLimit()
	{
        $limit = array(            
            "50" => "50",            
            "100" => "100",
			"250" => "250",
			"500" => "500",
			"All" => ""
        );
        foreach ( $limit as $key => $value )
        {
            $return .= '<option value="'.$value.'">'.$key.'</option>';                        
        }
        return $return;
	}
}