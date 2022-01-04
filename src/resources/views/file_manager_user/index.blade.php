 <div class="dbody">
    <div class="dbody-inner">

        
        <div class="dashHead">
            <div class="dashHead-left">
                <h4 class="dashTitle">{{$title}}</h4>

                <!-- <p>Showing 1 to 10 of 150 entries</p> -->
            </div>
            
        </div>
        <div class="dashHead">

            <div class="dashHead-right">
                <div class="dashHead-action" style='border: 1px solid;
    padding: 10px;
    background: #007100;
    color: white;
    border-radius: 10px;
    font-weight: bold;'>

    Total Space : {{$stats->total_storage}} <br>
    Total Consumed :  {{$stats->used}}


            </div>
        </div>


         <form class="bulkActionForAllForm" method="POST"   style="margin-top: 20px">


            <div class="dashHead-action">
                
                    <a class="buttons dbtn-secondary" href="{{route($model.'.create')}}"><i class="fas fa-plus-circle"></i>add</a>
               
            </div>
        </div>

    <div class="listing">

                {{ csrf_field() }}

        <table id="closed_orders_datatables">
            <thead>
                <tr class="list-row">
                    <th>Id</th>


                    <th>User</th>

                    <th>Storage</th>
                    <th>{{('created_at')}}</th>
                    <th>{{('updated_at')}}</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>

    </div>
     </form>
</div>
</div>
 
 

<script type="text/javascript">
window.onload = function () {
    var table = $('#closed_orders_datatables').DataTable({
        processing: true,
        serverSide: true,
        // "scrollY": 350,
        order: [[0, "desc" ]],
        "ajax":{
            "url": '{!! route($model.'.datatables') !!}',
            "dataType": "json",
            "type": "POST",
            "data":{ _token: "{{csrf_token()}}"}
        },

        columns: [
            { data: 'id', name: 'id', orderable:false ,searchable:false},
            { data: 'user', name: 'user', orderable:false ,searchable:false},

            { data: 'storage', name: 'storage', orderable:true ,searchable:false},
         //  { data: 'deleted_at', name: 'deleted_at', orderable:true ,searchable:false},

           // { data: 'status', name: 'status', orderable:false ,searchable:false},
            { data: 'created_at', name: 'created_at', orderable:false ,searchable:false},
             { data: 'updated_at', name: 'updated_at', orderable:false ,searchable:false},
            { data: 'action', name: 'action', orderable:false, searchable:false  },
        ],
        "columnDefs": [
            { "searchable": false, "targets": 0,"visible": false }
        ],
        createdRow: function ( row, data, index ) {
            $(row).addClass('list-row');
            setTimeout(function(){
               //  var markup = '<tr class="listing-space"><td></td></tr>';
                // $(row).after(markup);
            }, 100);
        }
    });
};
</script>

 
