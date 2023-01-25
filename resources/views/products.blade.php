<!DOCTYPE html>
<html>
<head>
    <title>Laravel 5 - Multiple delete records with checkbox example</title>


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-confirmation/1.0.5/bootstrap-confirmation.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>
<body>


<div class="container">
    <h3>Laravel 5 - Multiple delete records with checkbox example</h3>
    <button style="margin-bottom: 10px" class="btn btn-primary delete_all" data-url="{{ url('myproductsDeleteAll') }}">Delete All Selected</button>
    <table class="table table-bordered">
        <tr>
            <th width="50px"><input type="checkbox" id="master"></th>
            <th width="80px">No</th>
            <th>Product Name</th>
            <th>Product Details</th>
            <th width="100px">Action</th>
        </tr>
        @if($products->count())
            @foreach($products as $key => $product)
                <tr id="tr_{{$product->id}}">
                    <td><input type="checkbox" class="sub_chk" data-id="{{$product->id}}"></td>
                    <td>{{ ++$key }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->details }}</td>
                    <td>
                         <a href="{{ url('myproducts',$product->id) }}" class="btn btn-danger btn-sm"
                           data-tr="tr_{{$product->id}}"
                           data-toggle="confirmation"
                           data-btn-ok-label="Delete" data-btn-ok-icon="fa fa-remove"
                           data-btn-ok-class="btn btn-sm btn-danger"
                           data-btn-cancel-label="Cancel"
                           data-btn-cancel-icon="fa fa-chevron-circle-left"
                           data-btn-cancel-class="btn btn-sm btn-default"
                           data-title="Are you sure you want to delete ?"
                           data-placement="left" data-singleton="true">
                            Delete
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </table>
</div> <!-- container / end -->


</body>


<script type="text/javascript">
    $(document).ready(function () {


        $('#master').on('click', function(e) {
         if($(this).is(':checked',true))  
         {
            $(".sub_chk").prop('checked', true);  
         } else {  
            $(".sub_chk").prop('checked',false);  
         }  
        });


        $('.delete_all').on('click', function(e) {


            var allVals = [];  
            $(".sub_chk:checked").each(function() {  
                allVals.push($(this).attr('data-id'));
            });  


            if(allVals.length <=0)  
            {  
                alert("Please select row.");  
            }  else {  


                var check = confirm("Are you sure you want to delete this row?");  
                if(check == true){  


                    var join_selected_values = allVals.join(","); 


                    $.ajax({
                        url: $(this).data('url'),
                        type: 'DELETE',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: 'ids='+join_selected_values,
                        success: function (data) {
                            if (data['success']) {
                                $(".sub_chk:checked").each(function() {  
                                    $(this).parents("tr").remove();
                                });
                                alert(data['success']);
                            } else if (data['error']) {
                                alert(data['error']);
                            } else {
                                alert('Whoops Something went wrong!!');
                            }
                        },
                        error: function (data) {
                            alert(data.responseText);
                        }
                    });


                  $.each(allVals, function( index, value ) {
                      $('table tr').filter("[data-row-id='" + value + "']").remove();
                  });
                }  
            }  
        });


        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            onConfirm: function (event, element) {
                element.trigger('confirm');
            }
        });


        $(document).on('confirm', function (e) {
            var ele = e.target;
            e.preventDefault();


            $.ajax({
                url: ele.href,
                type: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    if (data['success']) {
                        $("#" + data['tr']).slideUp("slow");
                        alert(data['success']);
                    } else if (data['error']) {
                        alert(data['error']);
                    } else {
                        alert('Whoops Something went wrong!!');
                    }
                },
                error: function (data) {
                    alert(data.responseText);
                }
            });


            return false;
        });
    });
</script>


</html>