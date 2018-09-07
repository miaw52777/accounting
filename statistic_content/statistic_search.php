<?
	$rule = '';	
	
	
	/*if($_GET['overhead_type_radio_p'] == 'on') $mode = "pnt";
	else if($_GET['overhead_type_radio_all'] == 'on') $mode = "nt";
	*/
	
	
	if($is_statistic == 'T') $rule .= getOverheadRecord_Select_Rule('IS_STATISTIC','T');
	
	if(isset($_GET['start_date']))
	{
		$start_time = $_GET['start_date'];
	    $end_time = $_GET['end_date'];
	}
		
	$overhead_category = $_GET['overhead_category'];
	$overhead_type = $_GET['overhead_type'];
	$overhead_method = $_GET['overhead_method'];
	
	$overhead_Item = $_GET['overhead_Item'];
	$memo = $_GET['memo'];
	
	
	if($overhead_type != '' ) $rule .= getOverheadRecord_Select_Rule('OVERHEAD_TYPE',$overhead_type);
	if($overhead_method != '' ) $rule .= getOverheadRecord_Select_Rule('METHOD',$overhead_method);
	if($overhead_category != '' ) $rule .= getOverheadRecord_Select_Rule('OVERHEAD_CATEGORY',$overhead_category);
	if($overhead_Item != '' ) $rule .= getOverheadRecord_Select_Rule('ITEM',$overhead_Item);
	if($memo != '' ) $rule .= getOverheadRecord_Select_Rule('MEMO',$memo);

?>
	<section id="main" class="wrapper" name="main">
				<div class="inner">
					<div class="content">	
						<div class="row gtr-uniform">
							<div class="col-6 col-12-xsmall">
								<h3>日期範圍 : 
									<? 
										if(is_mobile())
										{
											echo '<br>';
										}
									?>
									<input type="date" name="start_date" id="start_date" value="<? echo $start_time; ?>" />~
									<input type="date" name="end_date" id="end_date" value="<? echo $end_time; ?>" />													
								</h3>								
							</div>							
							
						</div>		
						<br>
						<div class="row gtr-uniform">
							<div class="col-4">
									<select name="overhead_category" id = "overhead_category">
										<option value="" >-方式-</option>　
										<?											
											$overhead_category_list = array(0=>array("value"=>"支出","color"=>"red"), 1=>array("value"=>"收入","color"=>"green"));
											
											for($i=0;$i<count($overhead_category_list);$i++)
											{											
												if($overhead_category == $overhead_category_list[$i]['value'])
												{
													$is_select = "selected";
												}
												else $is_select = "";
												
												echo '<option value="'.$overhead_category_list[$i]['value'].'" style="color:'.$overhead_category_list[$i]['color'].'" '.$is_select.'>'.$overhead_category_list[$i]['value'].'</option>';												
											}
										?>											
									</select>										
							</div>				
							
							<div class="col-4">
									<select name="overhead_type" id = "overhead_type">
										<option value="" >-類型-</option>　
										<?
											$overhead_type_result = getOverhead_Item_List_Type($user_id);
											while($temp=mysqli_fetch_assoc($overhead_type_result['DATA']))
											{				
												if($overhead_type == $temp['type']) $is_select = "selected";
												else $is_select = "";
												
												echo '<option value="'.$temp['type'].'" '.$is_select.'>'.$temp['type'].'</option>';
											}
										
										?>										
									</select>										
							</div>
							<div class="col-4">
									<select name="overhead_method" id = "overhead_method">
										<option value="" >-帳戶-</option>　
										<?
											$accout_list = getOverhead_Account_Name($user_id);
											while($temp=mysqli_fetch_assoc($accout_list['DATA']))
											{		
												if($overhead_method == $temp['name']) $is_select = "selected";
												else $is_select = "";
												
												echo '<option value="'.$temp['name'].'" '.$is_select.'>'.$temp['name'].'</option>';
											}
										
										?>										
									</select>										
							</div>	
						</div>		
						<br>
						<div class="row gtr-uniform">
							<div class="col-6 col-12-xsmall">
								<input name="overhead_Item" id="overhead_Item" type="text" size="10" placeholder="項目" value="<? echo $overhead_Item; ?>" />
							</div>
							<div class="col-6 col-12-xsmall">
								<input name="memo" id="memo" type="text" size="10" placeholder="備註" value="<? echo $memo; ?>"/>				
							</div>	
						</div>
						<br>
						<div class="row gtr-uniform">
							<div class="col-12 col-12-xsmall">
								<input type="submit" value="submit" id="submit" class="primary" />	
							</div>
						</div>
						
					
					
					
					
					<?						
						if(isset($_GET['start_date']))
						{
							$queryResult = getOverheadRawdata($user_id,$start_time,$end_time,$rule);
							//var_dump($queryResult['SQL']);

							/*********** Start to print search data **********************/
							if($queryResult['RESULT'])
							{		
								$total_income_nt = 0;
								$total_outlay_nt = 0;
								$total_income_nt_daily = 0;
								$total_outlay_nt_daily = 0;
								$tmpday="";
								$htmlResult = "";
										
								
								while($temp=mysqli_fetch_assoc($queryResult['DATA']))
								{		
									$nt = $temp[$mode];
									if($temp['overhead_category'] == "收入") $total_income_nt += $nt;
									if($temp['overhead_category'] == "支出") $total_outlay_nt += $nt;
									
									if(($tmpday == "") || ($tmpday != $temp['statistic_time']))
									{
										$sourceStr = array(":DATE", ":TOTAL_OUTLAY_NT",":RECORD",":TOTAL_INCOME_NT",":TOTAL_SUM_NT");
										$replaceStr   = array($tmpday,$total_outlay_nt_daily,$recordHtml,$total_income_nt_daily,$total_income_nt_daily-$total_outlay_nt_daily);
										$html = str_replace($sourceStr,$replaceStr,$html);	
										$htmlResult .= $html;
									
										$html = "<div class=\"table-wrapper\">
												<b>:DATE</b>
												<table>
													<thead>
														<tr>
															<th></th>
															<th></th>
															<th></th>
														</tr>
													</thead>
													<tbody>
														:RECORD					
													</tbody>
													<tfoot>
														<tr>
															<td colspan=\"2\"><font color=\"green\">收入 : NT$:TOTAL_INCOME_NT</font></td>
															<td><font color=\"blue\">支出 : NT$:TOTAL_OUTLAY_NT</font><BR>
																<font color=\"brown\">結算 : NT$:TOTAL_SUM_NT</font>
															</td>
															
														</tr>
													</tfoot>
												</table>
											</div>
											";
										$recordHtml = '';
										$total_income_nt_daily = 0;
										$total_outlay_nt_daily = 0;
										$tmpday = $temp['statistic_time'];
									}
									
									if($temp['overhead_category'] == "收入")
									{
										$total_income_nt_daily += $nt;
										$color = "green";
									}
									if($temp['overhead_category'] == "支出") 
									{
										$total_outlay_nt_daily += $nt;
										$color = "blue";
									}
										
									$recordTmpHtml = '<tr>
														<td><font color=":COLOR">:OVERHEAD_CATEGORY</font></td>
														<td>:OVERHEAD_ITEM</td>
														<td>:NT
														
														<img src="./image/delete.png" id="img_overhead_delete" alt="刪除" title="刪除" onclick="deleteOverhead(\':GUID\');" width="32"> </img>
														
														<img src="./image/edit.png" id="img_overhead_edit" alt="編輯" title="編輯" onclick="showOverhead(\':GUID\');" width="30"> </img>
														
														<iframe src="showEditOVerheadForm.php?guid=:GUID" style="width:100%; '.$height_str.'overflow:hidden; border:none; margin:0; padding:0; overflow:hidden; z-index:999999; display:none" id="editForm_:GUID">
														</iframe>
														</td>
													</tr>
												   ';		

 												
									
									if($temp['is_statistic'] == 'F') $item = $temp['overhead_item'].' <img src="./image/non_statistic.png" witdth="15" height="15" alt="不納入統計" title="不納入統計"></image>'; 
									else $item = $temp['overhead_item'];
									
									$sourceStr = array(":OVERHEAD_CATEGORY", ":OVERHEAD_ITEM",":NT",":COLOR",":GUID");
									$replaceStr   = array($temp['overhead_category'],$item,$nt,$color,$temp['guid']);
									$recordTmpHtml = str_replace($sourceStr,$replaceStr,$recordTmpHtml);
									$recordHtml .= $recordTmpHtml;			
									
								}
								
								$sourceStr = array(":DATE", ":TOTAL_OUTLAY_NT",":RECORD",":TOTAL_INCOME_NT",":TOTAL_SUM_NT");
								$replaceStr = array($tmpday,$total_outlay_nt_daily,$recordHtml,$total_income_nt_daily,$total_income_nt_daily-$total_outlay_nt_daily);
								$html = str_replace($sourceStr,$replaceStr,$html);	
								$htmlResult .= $html;
								
								
								echo "<div class=\"table-wrapper\">						
												<table>
													<thead>
														<tr>
															<th>總收入</th>
															<th>總支出</th>
															<th>結算</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td><font color=\"green\">".$total_income_nt."</font></td>
															<td><font color=\"blue\">".$total_outlay_nt."</font></td>
															<td><font color=\"brown\">".($total_income_nt-$total_outlay_nt)."</font></td>
														</tr>				
													</tbody>									
												</table>
											</div>";
								echo $htmlResult;						
							}
							else
							{
								echo 'Error : '.$queryResult['MSG'];
							}
							
						}
					
					?>
					
					
					</div>
				</div>
			</section>
	
	
	