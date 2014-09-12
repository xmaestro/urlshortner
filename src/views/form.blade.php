@section('body')
@parent

<?php if(!empty($message)){ ?>
<div class="alert <?php if($success===true){ ?>alert-success<?php }else{?>alert-danger<?php } ?> alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
<?php echo $message; ?></div>
<?php } ?>

{{ Form::open(array()) }}

<div class="col-lg-14">
<div class="input-group">

{{ Form::text('url','',array('class'=>'form-control input-lg','placeholder'=>'Enter URL')) }}

<?php
if(!empty($messages)){
foreach ($messages->get('url') as $message){

	echo $message;

}}

?>

<span class="input-group-btn">
{{ Form::submit('Submit',array('class'=>'btn btn-default input-lg')) }}
</span>


{{ Form::close() }}

</div>
</div>

@stop