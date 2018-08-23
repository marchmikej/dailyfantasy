@extends('template')

@section('content')
<div class="content">
<div class="container-fluid">
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Upload MLB File</h4>
            </div>   
            <form action="/mlb/rotogrinders/upload" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">    
                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">File</label>       
                        <div class="col-sm-3">         
                               <input type="file" name="fileToUpload" id="fileToUpload">
                        </div>
                    </div>
                </fieldset>          
                <div id="loaderParent"> 
                    <button id="filesubmit" type="submit" class="btn btn-fill btn-info">Submit</button>
                </div>
            </form>           
        </div>
            @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    </div>
</div>
</div>
</div>