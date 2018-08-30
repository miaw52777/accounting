	<section id="main_month" class="wrapper" name="main_month">
				<div class="inner">
					<div class="content">
					
					<div class="row" id="monthSwitch" style="text-align: center;">
						<div class="col-4">
						<input type="button" class="button primary small" value="<" onclick="changeMonth('-');" id="SubMonth"></input>
						</div>
						
						<div class="col-4">
						<p id="selectMonth"><? echo $curMonth; ?></p>
						</div>
						
						<div class="col-4">
						<input type="button" class="button primary small" value=">" onclick="changeMonth('+');" id="AddMonth"></input>
						</div>
					</div>
					
					
					<div class="table-wrapper">						
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
									<td><font color="green">NT$ <? echo $total_income_nt; ?></font></td>
									<td><font color="red">NT$ <? echo $total_outlay_nt; ?></font></td>
									<td><font color="blue">NT$ <? echo ($total_income_nt-$total_outlay_nt); ?></font></td>
								</tr>				
							</tbody>									
						</table>
					</div>
					
					
					<div id="line_chart_month" style="width: 100%; height: 500px"></div>
					<?
						if(!is_mobile())
						{
							$height_str = 'height:800px;';
						}
						echo 
					 '<iframe src="calendar_view.php?user_id='.$user_id.'&show_time='.$show_time.'&start_time='.$start_time.'&end_time='.$end_time.'" style="width:100%; '.$height_str.'overflow:hidden; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;">
					</iframe>';
							
							
						?>			
					</div>
				</div>
			</section>
	