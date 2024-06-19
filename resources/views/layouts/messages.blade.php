@if ($errors->any())
<ul>
    @foreach ($errors->all() as $error)
    <li><div class="alert alert-dismissible fade show alert-danger"><div href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</div> {!! $error !!}</div></li>
    @endforeach
</ul>
@endif

@if (! Schema::hasTable('users') && !file_exists(storage_path('installed')))
<div class="alert alert-dismissible fade show alert-info" role="alert">
    <div href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</div>
    It's looks like, you are not install the application yet, please <a href="{{ url('/install') }}">install</a> it first.
</div>
@endif

@if (session('info'))
<div class="alert alert-dismissible fade show alert-info" role="alert">
    <div href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</div>
    {!! session('info') !!}
</div>
@endif
@if (session('status'))
<div class="alert alert-dismissible fade show alert-success" role="alert">
    <div href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</div>
    &nbsp;{!! session('status') !!}
</div>
@endif
@if (session('success'))
<div class="alert alert-dismissible fade show alert-success" role="alert">
    <div href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</div>
    {!! session('success') !!}
</div>
@endif
@if (session('danger'))
<div class="alert alert-dismissible fade show alert-danger" role="alert">
    <div href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</div>
    {!! session('danger') !!}
</div>
@endif
@if (session('error'))
<div class="alert alert-dismissible fade show alert-danger" role="alert">
    <div href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</div>
    {!! session('error') !!}
</div>
@endif
@if (session('warning'))
<div class="alert alert-dismissible fade show alert-warning" role="alert">
    <div href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</div>
    {!! session('warning') !!}
</div>
@endif
@if (session('message'))
<div class="alert alert-dismissible fade show alert-info" role="alert">
    <div href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</div>
    {{ session('message') }}
</div>
@endif