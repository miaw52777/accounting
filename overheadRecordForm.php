<?php 
include_once("function/conn.php");
include_once("function/CommFunc.php");
include_once("function/OverheadFunc.php"); 

function generateOverheadForm($action, $paramArr)
{
	$TITLE = $paramArr['TITLE'];
	$OVERHEAD_DATE = $paramArr['OVERHEAD_DATE'];
	$OVERHEAD_TIME = $paramArr['OVERHEAD_TIME'];
	$STATISTIC_TIME = $paramArr['STATISTIC_TIME'];
	$NT = $paramArr['NT'];
	$PNT = $paramArr['PNT'];
	$OVERHEAD_CATEGORY_OUTLAY = $paramArr['OVERHEAD_CATEGORY_OUTLAY'];
	$OVERHEAD_CATEGORY_INCOME = $paramArr['OVERHEAD_CATEGORY_INCOME'];
	$OVERHEAD_CATEGORY_XFER = $paramArr['OVERHEAD_CATEGORY_XFER'];
	$IS_STATISTIC = $paramArr['IS_STATISTIC'];
	$IS_NECESSARY = $paramArr['IS_NECESSARY'];
	$MEMO = $paramArr['MEMO'];
	$overhead_method = $paramArr['OVERHEAD_METHOD'];
	$overhead_xfer_to = $paramArr['OVERHEAD_XFER_TO'];
	$overhead_name_select = $paramArr['OVERHEAD_NAME'];
	$overhead_type_select = $paramArr['OVERHEAD_TYPE'];
	$GUID = $paramArr['GUID'];	
	$ITEM = $paramArr['ITEM'];	
	$user_id = $paramArr['USER_ID'];	
	$page = $paramArr['PAGE'];	
	
	$htmlTemplate = '';
	if($TITLE != "") $htmlTemplate = '<header class="special"> <h2>:TITLE</h2></header>';
	$htmlTemplate .= '
				<form id=\'overheadForm\'>
					<div>									
						<img src="./image/overhead_time.png" id="img_overheadtime_title" height="30" width="30" alt="消費Date/time" title="消費Date/time : :OVERHEAD_DATE :OVERHEAD_TIME" onclick="show_overheadtime_text();"> </img>		
						<input type="date" id="overhead-date" name="overhead-date" value=":OVERHEAD_DATE" style="display:none" onchange="overhead_time_change();"/>				
						<input type="time" id="overhead-time" name="overhead-time" value=":OVERHEAD_TIME" style="display:none" onchange="overhead_time_change();"/>
						<img src="./image/checkout_day.png" id="img_checkoutday_title" height="30" width="30" alt="結帳日" title="結帳日 : :STATISTIC_TIME" onclick="show_checkoutday_text();"> </img>
						<input type="date" id="statistict_time" name="statistict_time" value=":STATISTIC_TIME" style="display:none" onchange="statistict_time_change(this.value);"/>
				';
			if($action == 'NEW')	
			{
				$htmlTemplate .= '<img src="./image/new.png" id="img_overhead_add" height="30" width="30" alt="新增" title="新增" onclick="addOverhead();"> </img>';
			}
			else 
			{
				$htmlTemplate .= '<img src="./image/edit.png" id="img_overhead_add" height="30" width="30" alt="修改" title="修改" onclick="performUpdateOverhead(\':GUID\');"> </img>';
			}
			
			$htmlTemplate .= '</div>';
					
					$htmlTemplate .= '
					<div class="row gtr-uniform">
						 <div class="col-4">							
								 <select name="overhead_type" id="overhead_type" onchange="overhead_type_change(this.value, \':OPTION_STR\');"> 
								 :OVERHEADTYPE_HTML
								</select>							
						</div>	
						<div class="col-4 col-12-xsmall">					 							 
								 <select id="sel_overhead_Item" onchange = "sel_overhead_Item_change(this.value,\':OPTION_STR\');">
								  <option value="">-Select-</option>	
									:OVERHEAD_ITEM_HTML									 
								</select>  
						</div>
						<div class="col-4 col-12-xsmall">
								<input name="overhead_Item" id="overhead_Item" type="text" size="10" placeholder="* 項目" Autofocus="on" required value=":ITEM" onchange = "overhead_Item_text_change(\':OPTION_STR\')"/>
						 
						</div>						
					</div>	
					
					<br>
					
					<div class="row gtr-uniform">
							<div class="col-6 col-12-xsmall">						 
								<input name="overheadDollar" id="overheadDollar" type="text" size="10" onchange="checkIsNum(this);" placeholder="* 開銷總額 NT$" required value= ":NT"/>
							</div> 
							<div class="col-6 col-12-xsmall">	
								<input name="PersonalDollar" id="PersonalDollar" type="text" size="10" placeholder="個人開銷金額 PNT$" onchange="checkIsNum(this);" value= ":PNT"/>
								 <br>
							 </div>
					 </div>
					 ';
					 
				 
			 
					 // 食衣住行...
					$rule = getOverhead_Item_List_Select_Rule("VALID","T");
					$overhead_item_list = getOverhead_Item_List($user_id,$rule);
					$overhead_item_Arr_Str = "";		
					$overheadTypeHtml = "";
					$overhead_type_tmpArr = array();	
					if($overhead_item_list['REC_CNT'] ==0)
					{
						$overhead_item_list_default = array("食","衣","住","行","育","樂","醫","他");
						for($i=0;$i<count($overhead_item_list_default);$i++)
						{
							$tmp_type = $overhead_item_list_default[$i];	
							$overhead_item_Arr_Str .= $tmp_type.'@@F;';		
							$overheadTypeHtml .=  '<option value="'.$tmp_type.'" >'.$tmp_type.'</option>';
							array_push($overhead_type_tmpArr,$tmp_type);
						}
					}
					else
					{
						mysqli_data_seek($overhead_item_list['DATA'],0); // 移回第一筆資料	
						while($temp=mysqli_fetch_assoc($overhead_item_list['DATA']))
						{			
							$overhead_item_Arr_Str .= $temp['type'].'@'.$temp['name'].'@'.$temp['is_necessary'].';';
							if(!in_array($temp['type'],$overhead_type_tmpArr))
							{
								if($temp['type'] == $overhead_type_select) $is_select = 'selected';
								else $is_select = '';
								
								$overheadTypeHtml .=  '<option value="'.$temp['type'].'" '.$is_select.' >'.$temp['type'].'</option>'.CHR(13).CHR(10);
								array_push($overhead_type_tmpArr,$temp['type']);
							}
						}		 
					}
					
					$overheadItemHtml = "";
						// 食衣住行的項目...							
						if($overhead_item_list['REC_CNT']>0)
						{
							mysqli_data_seek($overhead_item_list['DATA'],0); // 移回第一筆資料	
							while($temp=mysqli_fetch_assoc($overhead_item_list['DATA']))
							{					
								if($overhead_type_select == "") $overhead_type_select = $overhead_type_tmpArr[0];
								if($overhead_type_select == $temp['type'])
								{
									if($temp['name'] == $overhead_name_select) $is_select = 'selected';
									else $is_select = '';
									$overheadItemHtml .=  '<option value="'.$temp['name'].'" '.$is_select.'>'.$temp['name'].'</option>'.CHR(13).CHR(10);
								}
							}
						}
						
						$sel_category = "";		
						$show_xfer_option = "";
						if($OVERHEAD_CATEGORY_INCOME != '')
						{
							$sel_category = "收入";
							$show_xfer_option = "none";
						}
						else if($OVERHEAD_CATEGORY_OUTLAY != '')
						{
							$sel_category = "支出";
							$show_xfer_option = "none";
						}
						else 
						{
							$sel_category = "轉帳";
							$show_xfer_option = "";
						}
						
					// 收入/支出	
					$htmlTemplate .= '	 			
					 <div class="row gtr-uniform">
					 <div class="col-4">
					 <select name="overhead_category" id = "overhead_category" onchange="overhead_category_change();">
						　<option value="支出" style="color:blue" :OVERHEAD_CATEGORY_OUTLAY >支出</option>
						　<option value="收入" style="color:green" :OVERHEAD_CATEGORY_INCOME > 收入</option>
						<option value="轉帳" style="color:purple" :OVERHEAD_CATEGORY_XFER > 轉帳</option>		
					</select>
					</div>					 
					<div class="col-4">
					 <select name="overhead_Method" id="overhead_Method" onchange="overhead_method_change();"> 
						:OVERHEAD_CATEGORY_OPTION_HTML
					 </select>	
					 </div>
					 <div class="col-4">
					 <select name="overhead_xfer_to" id="overhead_xfer_to" onchange="overhead_method_change();" style="display:'.$show_xfer_option.'"> 
						:OVERHEAD_XFER_OPTION_HTML
					 </select>	
					 </div>
					 ';
					 
						// 現金...
						$rule = getOverhead_Account_Select_Rule("VALID","T");
						$returnMsg = getOverhead_Account($user_id,'nt',$rule);	
						$overhead_category_option_Str = "";
						$overhead_category_option_Html = "";
						$overhead_Xfer_option_Html = "";
						if(!$returnMsg['RESULT'])						
						{							
							echo $returnMsg['MSG'];						
						}						
						else						
						{							
							if($returnMsg['REC_CNT'] == 0)
							{
								$overhead_category_option_Str .= "支出,收入,轉帳@現金@@;"; //支出@現金@結帳日@繳費日
								$overhead_category_option_Html .=  '<option value="現金">現金</option>';								
							}
							else
							{
								while($temp=mysqli_fetch_assoc($returnMsg['DATA']))
								{
									if($temp['name'] == $overhead_method) $is_select = "selected";
									else $is_select = "";
									
									if($temp['name'] == $overhead_xfer_to) $is_select_xfer = "selected";
									else $is_select_xfer = "";
																	
									
									$tmp_overhead_category = $temp['overhead_category'];
									if(!strpos($tmp_overhead_category, '轉帳') !== false)
									{
										//不存在，判斷是否為銀行，如果是自動新增
										if($temp['type'] == "銀行")
										{
											$tmp_overhead_category .= ',轉帳';
										}
									}
									
									//echo $temp['name'].'  '.$tmp_overhead_category.'  '.$sel_category.'<br>';
									
									if (strpos($tmp_overhead_category, $sel_category) !== false) 
									{
										$overhead_category_option_Html .=  '<option value="'.$temp['name'].'" '.$is_select.'>'.$temp['name'].'</option>'.CHR(13).CHR(10);
										$overhead_Xfer_option_Html .=  '<option value="'.$temp['name'].'" '.$is_select_xfer.'>'.$temp['name'].'</option>'.CHR(13).CHR(10);
									}
									
								 
									
									
									$overhead_category_option_Str .= $tmp_overhead_category."@".$temp['name']."@".$temp['checkoutday']."@".$temp['paymentday'].";"; //支出@現金@結帳日@繳費日
									
								}
							}
						}
					
					 
					 $htmlTemplate .= '
					 
					<div class="col-6 col-12-small">
						<input type="checkbox" id="is_statistic" name="checkbox" :IS_STATISTIC>
						<label for="is_statistic">此筆不納入統計</label>
					</div>
					<div class="col-6 col-12-small" hidden >
						<input type="checkbox" id="is_necessary" name="checkbox" :IS_NECESSARY>
						<label for="is_necessary" >是否為必要花費</label>
					</div>
					 
					 
						 <div class="col-12">
												<textarea name="textarea" id="memo" placeholder="備註" rows="6">:MEMO</textarea>
						</div>
						
					</div>
					
					<input name="page" id="page" type="hidden" value=":PAGE"/>
					<input name="overhead_category_method_str" id="overhead_category_method_str" type="hidden" value=":OVERHEAD_OPTION_STR"/>
					
				</form>
				';
				if($action == 'NEW')	
				{			
					$htmlTemplate .= '<script> overhead_method_change(); </script>	';
				}

	$sourceStr = array(":TITLE", ":OVERHEAD_DATE",':OVERHEAD_TIME',':STATISTIC_TIME',":NT",":PNT",":OVERHEAD_CATEGORY_OUTLAY"
						,":OVERHEAD_CATEGORY_INCOME",":IS_STATISTIC",":IS_NECESSARY",":MEMO",":GUID",":ITEM",":OPTION_STR"
						,":PAGE",":OVERHEAD_OPTION_STR",":OVERHEAD_CATEGORY_XFER"
						,":OVERHEAD_ITEM_HTML",":OVERHEADTYPE_HTML",":OVERHEAD_CATEGORY_OPTION_HTML"
						,":OVERHEAD_XFER_OPTION_HTML");
	$replaceStr = array($TITLE,$OVERHEAD_DATE,$OVERHEAD_TIME,$STATISTIC_TIME,$NT,$PNT,$OVERHEAD_CATEGORY_OUTLAY
						,$OVERHEAD_CATEGORY_INCOME,$IS_STATISTIC,$IS_NECESSARY,$MEMO,$GUID,$ITEM,$overhead_item_Arr_Str
						,$page,$overhead_category_option_Str,$OVERHEAD_CATEGORY_XFER
						,$overheadItemHtml,$overheadTypeHtml,$overhead_category_option_Html
						,$overhead_Xfer_option_Html);	
				
	$htmlTemplate = str_replace($sourceStr,$replaceStr,$htmlTemplate);
	return $htmlTemplate;
	
}
?>