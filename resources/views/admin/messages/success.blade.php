

@if (Session::has('flash_message'))
<div class="margin-10 alert alert-success alert-message">
    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
    {{ Session::get('flash_message')}}<br/>
</div>
@endif
