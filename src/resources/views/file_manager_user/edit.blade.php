@extends('layouts.dashboard')

@section('content')

    <div class="dbody">
        <div class="dbody-inner">
            @include('message')
           <div class="dashHead">
            <div class="dashHead-left">
                <h4 class="dashTitle">{{$title}}</h4>
                <!-- <p>Showing 1 to 10 of 150 entries</p> -->
            </div>
            <div class="dashHead-right">
                @include('includes.breadcrum')
            </div>
        </div>

            <div class="dashBoard-tiles">
                <div class="dashBoard-tile">
                    <div class="dashBoard-title">
                        <!-- <h4>Input</h4> -->
                    </div>
                    <div class="dForm">
                        {!! Form::model($row, ['method' => 'PATCH','route' => [$model.'.update', $row->id],'class'=>'validate form-form','id'=>'edit_user', 'enctype'=>'multipart/form-data']) !!}
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col">
                                    <div class="dForm-group">
                                        <label class="dForm-label">Storage <span class="require_field">*</span></label>

                                         {!! Form::text('storage', $row->storage_real, ['maxlength'=>'255','placeholder' => 'Storage', 'required'=>true, 'class'=>'dForm-control']) !!}
                                    </div>
                                </div>
                                
                                <div class="col">
                                    <div class="dForm-group">
                                        <label class="dForm-label">Storage unit <span class="require_field">*</span></label>
                                        {!! Form::select('storage_unit', ["KB"=>"KB","MB"=>"MB","GB"=>"GB"],null, [ 'required'=>true, 'class'=>'dForm-control ','id'=>'']) !!}
                                      
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col">
                                    <div class="dForm-actions form-buttons">
                                        <button class="buttons secondary" type="submit" name="button">
                                            Update
                                            <span class="dlogin-loader loader buttons secondary" style="display: none;">
                                               <span class="line"></span>
                                                 <span class="line"></span>
                                                 <span class="line"></span>
                                                 <span class="line"></span>
                                           </span>
                                        </button>
                                        <a class="buttons dbtn-primary" href="{{route($model.'.index')}}" name="button">Cancel</a>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('uniquepagescript')
<script type="text/javascript">
$(function () {
    $(".select_role").select2({
        placeholder: "Select Role"
    });
    $(".select_country").select2({
        placeholder: "Select Country"
    });
});
</script>
@endsection
