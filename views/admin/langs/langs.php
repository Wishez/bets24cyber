<div class="row" style="margin-bottom: 20px;">
	<div class="col-md-12">
		<a href="/admin/langs/add" class="btn pull-right btn-success btn-labeled"><span class="btn-label icon fa fa-plus"></span>Add Lang</a>
	</div>
</div>

<div  class="panel panel-default">
	<div class="panel-heading">

		<span class="elipsis"><!-- panel title -->
			<strong>Languages List</strong>
		</span>

		<!-- right options -->
		<!-- <ul class="options pull-right list-inline">
			<li><a href="#" class="opt panel_colapse" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Colapse"></a></li>
		</ul> -->
		
		<!-- /right options -->

	</div>
	<!-- panel content -->
	<div class="panel-body">
		<table class="table table-striped table-bordered table-hover" id="sample_5">
			<thead>
				<tr>
					<th>
						 #
					</th>	
					<th>
						 Name
					</th>
					<th class="hidden-xs">
						 Local
					</th>
					<th class="hidden-xs">
						 Url
					</th>
					<th class="hidden-xs">
						 Controls
					</th>
				</tr>
			</thead>
			<tbody>
				<?foreach ($langs as $key =>  $object) {?>
				<tr id="<?=$object->id?>">
					<td>
						 <?=($key+1)?>
					</td>
					<td>
						 <?=$object->name?>
					</td>
					<td>
						 <?=$object->local?>
					</td>
					<td>
						 <?=$object->url?>
					</td>

					<td>
						 <div class="str_buts_2" style="width: 100%;display: inline-block;text-align: center;">
                                <a href="/admin/langs/edit?id=<?=$object->id?>" class="btn btn-warning btn-rounded"><i class="icon fa fa-pencil"></i>Edit</a>
                                <a href="javascript:void();" onclick="javascript: if(confirm('You are sure?')) { document.location='/admin/langs/delete?id=<?=$object->id?>';} else {return false;};" class="btn btn-danger btn-rounded"><i class="icon fa fa-times"></i>DELETE</a>

                            </div>
					</td>
				</tr>
				<?}?>
			</tbody>
		</table>
	</div>
	<!-- /panel footer -->
</div>