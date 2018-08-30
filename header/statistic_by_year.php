﻿	<section id="main_year" name="main_year"  class="wrapper">
				<div class="inner">
					<div class="content">
					
					<div class="row" id="monthSwitch" style="text-align: center;">
						<div class="col-4">
						<input type="button" class="button primary small" value="<" onclick="changeYear('-');" id="SubYear"></input>
						</div>
						
						<div class="col-4">
						<p id="selectYear"><? echo $curYear; ?></p>
						</div>
						
						<div class="col-4">
						<input type="button" class="button primary small" value=">" onclick="changeYear('+');" id="AddYear"></input>
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
									<td><font color="green">NT$ <? echo $total_income_nt_Year; ?></font></td>
									<td><font color="red">NT$ <? echo $total_outlay_nt_Year; ?></font></td>
									<td><font color="blue">NT$ <? echo ($total_income_nt_Year-$total_outlay_nt_Year); ?></font></td>
								</tr>				
							</tbody>									
						</table>
					</div>
					
					
					<div id="line_chart_year" style="width: 100%; height: 500px"></div>
					<?
						$show_time = getToday();					
						if(!is_mobile())
						{
							$height_str = 'height:800px;';
						}
						echo 
					 '<iframe src="calendar_view.php?user_id='.$user_id.'&show_time='.$show_time.'&start_time='.$start_time_Year.'&end_time='.$end_time_Year.'" style="width:100%; '.$height_str.'overflow:hidden; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;">
					</iframe>';
							
							
						?>			
					</div>
				</div>
			</section>
	