<!DOCTYPE html>
<html>
	
	<head>
		<title>
			Hello Cakephp Test
		</title>
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	</head>
	<body>
		<form>
			<table>
				<tr><td>ID</td><td>Ticket Number</td><td>Price</td><td>Action</td></tr>
				<tr>
					<td><input type="text" name="id" id="idid"></td>
					<td><input type="text" name="ticketid" id="ticketid"></td>
					<td><input type="text" name="price" id="priceid"></td>
					<td>
						<button type="button" id="saveit">Save</button>
					</td>
				</tr>
				<tr id="appends"></tr>
			</table>
		</form>
		<script>
			$("#saveit").click(function(){
			
				var idid=$('#idid').val();
				var ticketid=$("#ticketid").val();
				var priceid=$("#priceid").val();
				var data='<tr class="parenttr">'
						+'<td><label class="ididlebel">'+idid+'</label><input type="text" style="display:none" name="ididclass" value="'+idid+'"></td>'
						+'<td><label class="ticketclasslabel">'+ticketid+'</label><input style="display:none" type="text" name="ticketidclass" value="'+ticketid+'"></td>'
						+'<td><label class="pricelabel">'+priceid+'</label><input type="text" style="display:none" name="priceidclass" value="'+priceid+'"></td>'
						+'<td><button type="button" class="save" style="display:none">Save</button><button type="button" class="edit">Edit</button><button type="button" class="deletebtn">Delete</button></td>'
						+'</tr>';
				$("#appends").after(data);
				
				/*
				
				$.each(val1,function(index,value){
						alert(value);
					})
					
				$.ajax({
				url : "<?php echo Router::url(array('controller'=>'events','action'=>'getticket')); ?>",
					type: "POST",
					data : {
						idid:idid,
						ticketid:ticketid,
						priceid:priceid
					},
					success: function(data, textStatus, jqXHR)
					{
						alert(data);
					},
					error: function (jqXHR, textStatus, errorThrown)
					{

					}
				});*/
			});	
		</script>
		<script>
			$(".deletebtn").live('click',function(){
				$('.parenttr').remove();
			});
			$(".edit").live('click',function(){
				//$(".ididlebel").hide();
				var dd=$(this).parent().parent().attr('class');
				//alert(dd);
				//$(this).find('input').show();
				
				$(this).parent().parent().find('input').show();
				
				$(this).parent().parent().find('.ididlebel').hide();
				$(this).parent().parent().find('.ticketclasslabel').hide();
				$(this).parent().parent().find('.pricelabel').hide();
				$(this).parent().parent().find('.edit').hide();
				$(this).parent().parent().find('.deletebtn').hide();
				$(this).parent().parent().find('.save').show();
				
			});
			$(".save").live('click',function(){
				var dd=$(this).parent().parent().attr('class');
				$(this).parent().parent().find('input').each(function(i,item){
					//var datas=$(this).parent().parent().find('input').val()
					 //var inputData=$(item).val();
					 if(i==0){
					 $(this).parent().parent().find('.ididlebel').show();
					 $(this).parent().parent().find('.ididlebel').html(' ');
					 $(this).parent().parent().find('.ididlebel').html($(item).val());
					 }
					 if(i==1){
					 $(this).parent().parent().find('.ticketclasslabel').html(' ');
					 $(this).parent().parent().find('.ticketclasslabel').show();
					 $(this).parent().parent().find('.ticketclasslabel').html($(item).val());
					 }
					 if(i==2){
					  $(this).parent().parent().find('.pricelabel').html(' ');
					  $(this).parent().parent().find('.pricelabel').show();
					  $(this).parent().parent().find('.pricelabel').html($(item).val());
					 }
					 $(this).parent().parent().find('input').hide();
					 //$(this).parent().parent().find('.ididlebel').html(inputData);
				});
				
			});
		</script>
	</body>
</html>