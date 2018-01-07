<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Lọc comment</title>
	<link href="./bootstrap.min.css" rel="stylesheet" />
	<link href="./dist/jquery.bootgrid.css" rel="stylesheet" />
	<script src="./js/modernizr-2.8.1.js"></script>
	<style>
	@-webkit-viewport { width: device-width; }
	@-moz-viewport { width: device-width; }
	@-ms-viewport { width: device-width; }
	@-o-viewport { width: device-width; }
	@viewport { width: device-width; }

	body { padding-top: 70px; }

	.column .text { color: #f00 !important; }
	.cell { font-weight: bold; }
</style>
</head>
<body>
	<header id="header" class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<span class="navbar-brand" data-i18n="title">Mini tool lọc comment trong một post</span>
			</div>
		</div>
	</header>
	<?php 
	$id='';
	if(isset($_POST['idpost']) and $_POST['idpost']!='')
		$id=$_POST['idpost'];
	if(isset($_POST['idfb']) and $_POST['idfb']!='')
		$idfb=$_POST['idfb'];
	else $idfb='';
	if(isset($_POST['limit']) and $_POST['limit']!='')
		$limit=$_POST['limit'];
	else $limit='';
	if(isset($_POST['token']) and $_POST['token']!='')
		$token=$_POST['token'];
	else $token='';
	?>
	<form action="" method="POST">
		<div class="col-xs-12"  style="margin-bottom: 5px">
			<input type="text" id="token" name="token" class="form-control" placeholder="token page" value="<?=$token?>">
		</div>
		<div class="col-sm-3">
			<input type="text" class="form-control" name="idpost" placeholder="nhập id bài viết" value="<?=$id?>">
		</div>
		<div class="col-sm-3">
			<input type="text" class="form-control" name="idfb" placeholder="id fanpage" value="<?=$idfb?>">
		</div>
		<div class="col-sm-2">
			<input type="text" class="form-control"  name="limit" placeholder="giới hạn cần lấy, mặc định 500" value="<?=$limit?>">
		</div>
		<div class="col-sm-1">
			<input type="submit" class="btn btn-success" value="Xúc thôi">
		</div>
	</form>
	<?php 
	if($_POST['token']!='')
		$tk = '&access_token='.$_POST['token'];
	else $tk ='';
	if(isset($_POST['idpost']) and $_POST['idpost']!='')
	{
		$id=$_POST['idpost'];
		if($idfb!='')
			$id=$idfb.'_'.$id;
		if(isset($_POST['limit']) and $_POST['limit']!='')
			$limit=$_POST['limit'];
		else $limit=500;
		$url = 'https://graph.facebook.com/v2.9/'.$id.'/comments?order=chronological&limit='.$limit.$tk;
		//echo $url;
		$chusn = curl_init();
		curl_setopt($chusn, CURLOPT_URL, $url);
		curl_setopt($chusn, CURLOPT_RETURNTRANSFER, 1);
		$resultusn = curl_exec($chusn);
		curl_close($chusn);
		$rsusn = json_decode($resultusn,true);
		$data = $rsusn['data'];		
		?>

		<div class="container-fluid">
			<div class="row">

				<div class="col-md-12">
					<!--div class="table-responsive"-->
					<table id="grid" class="table table-condensed table-hover table-striped"  data-row-select="true" data-keep-selection="true">
						<thead>
							<tr>
								<th data-column-id="Nick"  data-formatter="link" data-align="left" data-width="150">Nick</th>
								<th data-column-id="Uid"    data-align="left" data-width="150">Uid</th>
								<th data-column-id="id"   data-identifier="true   data-align="left" data-width="150">id</th>
								<th data-column-id="comment" data-align="left" data-header-align="left" data-width="75%">Comment</th>
								<th data-column-id="Time"  data-order="ASC" data-identifier="true"  data-align="left" data-width="200" >Time</th>
								<th data-column-id="commands"   data-formatter="commands" data-width="200" >Option</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($data as $item){
								?>
								<tr><td><?=$item['from']['name']?></td>
									<td><a href="http://fb.com/<?=$item['from']['id']?>"><?=$item['from']['id']?></a></td>
									<td><?php echo $item['id']?></td>
									<td><?php echo $item['message']?></td>
									<td><?php $time= str_replace(array('T','+0000'),array(' ',''), $item['created_time']);echo date('d/m/Y H:i:s',strtotime($time));?></td>
									<td></td>
									<tr/>
									<?php }?>
								</tbody>
							</table>
							<!--/div-->
						</div>
					</div>
				</div>

				<div class="modal fade" id="modalcomment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="exampleModalLabel">Trả lời comment</h4>
							</div>
							<div class="modal-body">
								<form>
									<input type="hidden" class="form-control" id="idcomment">
									<div class="form-group">
										<label for="message-text" class="control-label">Nội dung:</label>
										<textarea class="form-control" id="text"></textarea>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
								<button type="button" onclick="sendmessenger()" class="btn btn-primary">Gửi</button>
							</div>
						</div>
					</div>
				</div>
				<script src="./lib/jquery-1.11.1.min.js"></script>
				<script src="./js/bootstrap.js"></script>
				<script src="./dist/jquery.bootgrid.js"></script>
				<script src="./dist/jquery.bootgrid.fa.js"></script>
				<script>
					$(function()
					{
						function init()
						{
							$("#grid").bootgrid({
								formatters: {
									"link": function(column, row)
									{
										return "<a target=\"_blank\" href=\"https://facebook.com/"+row.Uid+"\">" + row.Nick + "</a>";
									},
									"commands": function(column, row)
									{
										return "<button type=\"button\" class=\"btn btn-primary btn-modalcomment\" data-toggle=\"modal\" data-target=\"#modalcomment\" data-idcomment=\""+row.id+"\">Trả lời</button>";
									}


								},
								rowCount: [-1, 10, 50, 100],

							}).on("loaded.rs.jquery.bootgrid", function () {

								$(this).find(".btn-modalcomment").click(function (e) {
									var id = $(this).attr("data-idcomment");
									console.log(id);
									$('#idcomment').val(id);
									$($(this).attr("data-target")).modal("show");
									return false;
								});
							})

						}
						init();

						$("#clear").on("click", function ()
						{
							$("#grid").bootgrid("clear");
						});

						$("#removeSelected").on("click", function ()
						{
							$("#grid").bootgrid("remove");
						});

						$("#destroy").on("click", function ()
						{
							$("#grid").bootgrid("destroy");
						});

						$("#init").on("click", init);

						$("#clearSearch").on("click", function ()
						{
							$("#grid").bootgrid("search");
						});

						$("#clearSort").on("click", function ()
						{
							$("#grid").bootgrid("sort");
						});

						$("#getCurrentPage").on("click", function ()
						{
							alert($("#grid").bootgrid("getCurrentPage"));
						});

						$("#getRowCount").on("click", function ()
						{
							alert($("#grid").bootgrid("getRowCount"));
						});

						$("#getTotalPageCount").on("click", function ()
						{
							alert($("#grid").bootgrid("getTotalPageCount"));
						});

						$("#getTotalRowCount").on("click", function ()
						{
							alert($("#grid").bootgrid("getTotalRowCount"));
						});

						$("#getSearchPhrase").on("click", function ()
						{
							alert($("#grid").bootgrid("getSearchPhrase"));
						});

						$("#getSortDictionary").on("click", function ()
						{
							alert($("#grid").bootgrid("getSortDictionary"));
						});

						$("#getSelectedRows").on("click", function ()
						{
							alert($("#grid").bootgrid("getSelectedRows"));
						});


					});


					function sendmessenger(){
						var id = $('#idcomment').val();
						var text = $('#text').val();			
						var data =  {
							message: text,
							access_token: '<?=$_POST['token']?>'
						}			
						$.ajax({

							url: 'https://graph.facebook.com/v2.9/'+id+'/comments',
							type: 'POST',
							dataType: 'json',
							data: data
						})
						.done(function(response) {
							$('#modalcomment').modal('hide');
							$('#text').val('');
							if(response.id){
								alert('Tin đã được gửi đi');
							}else{
								alert('Đã xảy ra lỗi vui lòng thử lại!');
							}
						})
					}
				</script>
				<?php } ?>
			</body>
			</html>
