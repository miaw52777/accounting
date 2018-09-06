<?
	$rule = '';	
	
	
	if($_POST['overhead_type_radio_p'] == 'on') $mode = "pnt";
	else if($_POST['overhead_type_radio_all'] == 'on') $mode = "nt";
	
	if($_POST['is_statistic'] == 'on') $rule .= getOverheadRecord_Select_Rule('IS_STATISTIC','T');
	
	if(isset($_POST['start_date']))
	{
		$start_time = $_POST['start_date'];
	    $end_time = $_POST['end_date'];
	}
		
	$overhead_category = $_POST['overhead_category'];
	$overhead_type = $_POST['overhead_type'];
	$overhead_method = $_POST['overhead_method'];
	
	$overhead_Item = $_POST['overhead_Item'];
	$memo = $_POST['memo'];
	
	
	if($overhead_type != '' ) $rule .= getOverheadRecord_Select_Rule('OVERHEAD_TYPE',$overhead_type);
	if($overhead_method != '' ) $rule .= getOverheadRecord_Select_Rule('METHOD',$overhead_method);
	if($overhead_category != '' ) $rule .= getOverheadRecord_Select_Rule('OVERHEAD_CATEGORY',$overhead_category);
	if($overhead_Item != '' ) $rule .= getOverheadRecord_Select_Rule('ITEM',$overhead_Item);
	if($memo != '' ) $rule .= getOverheadRecord_Select_Rule('MEMO',$memo);

?>
	<section id="main" class="wrapper" name="main">
				<div class="inner">
					<div class="content">	
					<form id='searchOverheadForm' action="?slide=0" method="Post">
					
						<div class="row gtr-uniform">
							<div class="col-4 col-12-small">
								<input type="radio" id="overhead_type_radio_p" name="overhead_type_radio_p" onclick="radioOverheadtypeSelect('personal');" checked >
								<label for="overhead_type_radio_p">個人開銷</label>
							
								<input type="radio" id="overhead_type_radio_all" name="overhead_type_radio_all" onclick="radioOverheadtypeSelect('overall');">
								<label for="overhead_type_radio_all">全部開銷</label>
							</div>
							<div class="col-6 col-12-xsmall">
								<input type="checkbox" id="is_statistic" name="is_statistic" :IS_STATISTIC>
								<label for="is_statistic">濾除不納入統計</label>						
							</div>
						</div>	
						
						<br>
						<div class="row gtr-uniform">
							<div class="col-6 col-12-xsmall">
								<h3>日期範圍 : 
									<? 
										if(is_mobile())
										{
											echo '<br>';
										}
									?>
									<input type="date" name="start_date" id="end_date" value="<? echo $start_time; ?>" placeholder="Name" />~
									<input type="date" name="end_date" id="end_date" value="<? echo $end_time; ?>" placeholder="Name" />													
								</h3>								
							</div>							
							
						</div>		
						<br>
						<div class="row gtr-uniform">
							<div class="col-4">
									<select name="overhead_category" id = "overhead_category">
										<option value="" >-方式-</option>　
										<option value="支出" style="color:red">支出</option>
									　<option value="收入" style="color:green"> 收入</option>					
									</select>										
							</div>							
							<div class="col-4">
									<select name="overhead_type" id = "overhead_type">
										<option value="" >-類型-</option>　
										<?
											$overhead_type = getOverhead_Item_List_Type($user_id);
											while($temp=mysqli_fetch_assoc($overhead_type['DATA']))
											{					
												echo '<option value="'.$temp['type'].'">'.$temp['type'].'</option>';
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
												echo '<option value="'.$temp['name'].'">'.$temp['name'].'</option>';
											}
										
										?>										
									</select>										
							</div>	
						</div>		
						<br>
						<div class="row gtr-uniform">
							<div class="col-6 col-12-xsmall">
								<input name="overhead_Item" id="overhead_Item" type="text" size="10" placeholder="項目"/>
							</div>
							<div class="col-6 col-12-xsmall">
								<input name="memo" id="memo" type="text" size="10" placeholder="備註"/>				
							</div>	
						</div>
						<br>
						<div class="row gtr-uniform">
							<div class="col-12 col-12-xsmall">
								<input type="submit" value="submit" id="submit" class="primary" />	
							</div>
						</div>
						
					</form>
					
					
					
					<?						
						if(isset($_POST['start_date']))
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
										
									$recordTmpHtml = "<tr>
														<td><font color=\":COLOR\">:OVERHEAD_CATEGORY</font></td>
														<td>:OVERHEAD_ITEM</td>
														<td>:NT</td>
													</tr>
												   ";				
									
									if($temp['is_statistic'] == 'F') $item = $temp['overhead_item'].' <img src="./image/non_statistic.png" witdth="15" height="15" alt="不納入統計" title="不納入統計"></image>'; 
									else $item = $temp['overhead_item'];
									
									$sourceStr = array(":OVERHEAD_CATEGORY", ":OVERHEAD_ITEM",":NT",":COLOR");
									$replaceStr   = array($temp['overhead_category'],$item,$nt,$color);
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
	
	
	