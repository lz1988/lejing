<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0" />
<title>乐境微信管理系统</title>
<link rel="stylesheet" href="__PUBLIC__/Home/css/style.css">
<script src="__PUBLIC__/Home/js/jquery.min.js"></script>
</head>

<body class="reg_body">
<header class="h_reg">
	欢迎你：<?php echo ($_SESSION['username']); ?> &nbsp;&nbsp; <a href="__APP__/index/logout">退出</a>
</header>
<section class="main_reg">
	
	<table border="1" width="100%">
	<tr>
	<td width="10%">分店</td>
	<td><?php echo ($shoparr["shop_name"]); ?></td>
	<td><?php echo ($date); ?></td>
	</tr>
	
	<tr>
	<td>序号</td>
	<td>项目</td>
	<td>本期金额</td>
 	</tr>
	<tr>
	<td>1</td>
	<td align="left">一、营业收入</td>
	<td align="right"><?php echo (round($yingyerice,2)); ?></td>
	</tr>
	<tr>
	<td>2</td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;减：产品成本及加工</td>
	<td align="right"><?php echo (round($chanpinchengben,2)); ?></td>
	</tr>
	<tr>
	<td>3</td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;减：营业税费</td>
	<td align="right"><?php echo (round($yingyeshuifei,2)); ?></td>
	</tr>
	<tr>
	<td>4</td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;减：销售费用</td>
	<td align="right"><?php echo (round($xiaoshoufeiyong,2)); ?></td>
	
	</tr>
	<tr>
	<td>5</td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;其中：店铺租金</td>
	<td align="right"><?php echo (round($sum_sell_fee,2)); ?></td>
	
	</tr>
	<tr>
	<td>6</td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;其中：人员薪资</td>
	<td align="right"><?php echo (round($sum_employee_pay,2)); ?></td>
	
	</tr>
	<tr>
	<td>7</td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;其中：物流快递费</td>
	<td align="right"><?php echo (round($kuaidiwuliufei,2)); ?></td>
	</tr>
	<tr>
	<td>8</td>
	<td  align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;减：管理费用</td>
	<td align="right"><?php echo (round($sum_manage_fee,2)); ?></td>
	</tr>
	<tr>
	<td>9</td>
	<td  align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;减：财务费用</td>
	<td align="right"><?php echo (round($caiwufeiyong,2)); ?></td>
	</tr>
	<tr>
	<td>10</td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;其中：刷卡手续费</td>
	<td align="right"><?php echo (round($caiwufeiyong,2)); ?></td>
	</tr>
	<tr>
	<td>11</td>
	<td  align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;加：公允价值变动收益（损失以“-”号填列）
</td>
	<td align="right"><?php echo (round($shouyi,2)); ?></td>
	</tr>
	<tr>
	<td>12</td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;投资收益（损失以“-”号填列）
</td>
	<td align="right"><?php echo (round($touzishouyi,2)); ?></td>
	</tr>
	<tr>
	<td>13</td>
	<td align="left">二、营业利润（亏损以“-”号填列）
</td>
	<td align="right"><?php echo (round($yingyelirun,2)); ?></td>
	</tr>
	<tr>
	<td>14</td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;加：营业外收入
</td>
	<td align="right"><?php echo (round($yingyewaishouru,2)); ?></td>
	</tr>
	<tr>
	<td>15</td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;减：营业外支出                        

</td>
	<td align="right"><?php echo (round($yingyewaizhichu,2)); ?></td>
	</tr>
	<tr>
	<td>16</td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;其中：非流动资产处置损失
</td>
	<td align="right"><?php echo (round($feiliudongzichan,2)); ?></td>
	</tr>
	<tr>
	<td>17</td>
	<td align="left">三、利润总额（亏损总额以“-”号填列）    </td>
	<td align="right"><?php echo (round($liyunzonge,2)); ?></td>
	</tr>
	<tr>
	<td>18</td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;减：所得税费用</td>
	<td align="right"><?php echo (round($suodeshuifeiyong,2)); ?></td>
	</tr>
	<tr>
	<td>19</td>
	<td align="left">四、净利润（净亏损以“-”号填列）</td>
	<td align="right"><?php echo (round($jinglirun,2)); ?></td>
	</tr>
	<tr>
	<td>20</td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;加：公司对加盟商补贴     </td>
	<td align="right"><?php echo (round($jiamengbutie,2)); ?></td>
	</tr>
	<tr>
	<td>21</td>
	<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;加：加盟预算费用返还（负“-”号表示加收）</td>
	<td align="right"><?php echo (round($jiamengfanhuan,2)); ?></td>
	</tr>
	<tr>
	<td>22</td>
	<td align="left">五、净收益（负收益以“-”号填列）  </td>
	<td align="right"><?php echo (round($jingshouyi,2)); ?></td>
	</tr>
	<tr>
	<td>23</td>
	<td align="left">（一）加盟商投资比例（%）</td>
	<td align="right"><?php echo ($bili); ?>%</td>
	</tr>
	<tr>
	<td>24</td>
	<td align="left">（二）加盟商分享收益（元）</td>
	<td align="right"><?php echo (round($fenxiangshouyi,2)); ?></td>
	</tr>
	</table>
</section>
</body>
</html>