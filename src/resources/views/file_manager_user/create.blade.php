 
    <div class="dbody">
        <div class="dbody-inner">

           
           <div class="dashHead">
            <div class="dashHead-left">
                <h4 class="dashTitle">{{$title}}</h4>
                <!-- <p>Showing 1 to 10 of 150 entries</p> -->
            </div>
          
        </div>

            <div class="dashBoard-tiles">
                <div class="dashBoard-tile">
                    <div class="dashBoard-title">
                        <!-- <h4>Input</h4> -->
                    </div>
                    <div class="dForm">
                        <form class="validate form-horizontal form-form"  role="form" id="create_user" method="POST" action="{{ route($model.'.store') }}" enctype = "multipart/form-data">

                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col">
                                    <div class="dForm-group">
                                        <label class="dForm-label">User <span class="require_field">*</span></label>
                                      
                                        
                                        {!! Form::hidden("user",null,['id'=>'user'])!!}

                                        {!! Form::text('user_title',null,[  "autocomplete"=>"off",'class'=>'dForm-control searchable-fld user_title','placeholder'=>'Please Select','id'=>'user_title','required'=>true]) !!}
                                        <div class="shosuggestion user-title-section">
                                        </div>
 
                                    </div>
                                </div>
                                

                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="dForm-group">
                                        <label class="dForm-label">Storage <span class="require_field">*</span></label>

                                         {!! Form::text('storage', null, ['maxlength'=>'255','placeholder' => 'Storage', 'required'=>true, 'class'=>'dForm-control']) !!}
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
                                        <button class="buttons dbtn-secondary" type="submit" >
                                            Save
                                            <span class="dlogin-loader loader buttons secondary" style="display: none;">
                                                <span class="line"></span>
                                                  <span class="line"></span>
                                                  <span class="line"></span>
                                                  <span class="line"></span>
                                            </span>
                                        </button>

                                        <a class="buttons dbtn-primary" href="{{route($model.'.index')}}">Cancel</a>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


<style type="text/css">
    .can-select{
        cursor:pointer;
        padding:5px;
        border-bottom:1px solid
    }
</style>


<script type="text/javascript">
     //jQuery.noConflict();
</script>
<script src="{{asset('public/asset/scripts/filter-multi-select-bundle.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">



$(function () {

    $('#recipient_select').filterMultiSelect({
      selectAllText: 'Select All',
      placeholderText: 'Please select',
      filterText: 'search',
      caseSensitive: false

    });
});




$(".user_title").on("keyup",function(){
    $(".user-title-section").addClass("active");
    //console.log($(this).val(),"sds")
    $.get("{{route('file-manager.getAllUserForEmail',['role'=>'BACKEND'])}}",{querystr:$(this).val()},function(data){
        html="";
        $("#user").val('');
        for(let i in data){
           html+=  "<div class='can-select user_title_row' data-id='"+i+"' value='"+data[i]+"'>"+data[i]+"</div>";
         }
         $(".user-title-section").html(html);
    })
})

$(".user_title").on("blur",function(){
    if($("#user").val() ==''){
       $(".user_title").val('');
    }
});

$(document).on("click",".user_title_row",function(){
    $(".user_title").val($(this).attr("value"))
    $(".user_title").attr('data-id',$(this).attr("data-id"))
    $("#user").val($(this).attr("data-id"));
    $(".user-title-section").html("");
    $(".user-title-section").removeClass("active");

});

$(document).click(function() {
    $(".user-title-section").removeClass("active");
});
</script>


