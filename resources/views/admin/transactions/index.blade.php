@extends('layouts.admin-master')

@section('title')
Transactions
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>Transactions</h1>
</div>

<div class="section-body">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <button class="btn btn-primary mb-5" id="get_data" onclick="get_data_transaction()">Get Data</button>
        <table id="table_transaction" class="display" style="width:100%">
          <thead>
            <tr>
                <th>Order ID</th>
                <th>Receiver Name</th>
                <th>Email</th>
                <th>Province</th>
                <th>City</th>
                <th>Sub District</th>
                <th>Zip Code</th>
                <th>Address</th>
                <th>Detail Address</th>
                <th>Action</th>
            </tr>
          </thead>
      </table>
      </div>
    </div>
  </div>
</div>

</section>
<!-- Modal Trnsaction View -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Transactions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="accordion">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Transaction
        </button>
      </h5>
    </div>
    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="card-body">
        <ul>
          <li>Order ID : <span id="detail_order_id"></span></li>
          <li>Receiver Name : <span id="detail_receiver_name"></span></li>
          <li>Email : <span id="detail_email"></span></li>
          <li>Province : <span id="detail_province"></span></li>
          <li>City : <span id="detail_city"></span></li>
          <li>Sub District : <span id="detail_subdistrict"></span></li>
          <li>Zip Code : <span id="detail_zip_code"></span></li>
          <li>Address : <span id="detail_address"></span></li>
          <li>Address Detail : <span id="detail_address_detail"></span></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Transaction Details
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
      <div class="card-body">
        <table id="table_transaction_detail" class="table table-responsive">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Code Product</th>
              <th>Qty</th>
              <th>Unit</th>
              <th>Price</th>
              <th>Promo Amount</th>
              <th>Price Promo</th>
              <th>Sub Total</th>
            </tr>
          </thead>
          <tbody id="data_transaction_detail">
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Trnsaction Update -->
<div class="modal fade" id="ModalViewUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Update Transactions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label>Receiver Name</label>
            <input type="text" class="form-control" id="upd_receiver_name">
          </div>
          <div class="form-group">
            <label>Address</label>
            <textarea id="upd_address" class="form-control" cols="30" rows="20"></textarea>
          </div>
          <div class="form-group">
            <label>Address Detail</label>
            <textarea id="upd_address_detail" class="form-control" cols="30" rows="20"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer" id="btn_footer_transaction_update">
      </div>
    </div>
  </div>
</div>
<script>
    $(document).ready(function(){
        $('#table_transaction').DataTable({
           processing: true,
           serverSide: true,
           ajax: "{{ route('admin.transaction-list') }}",
           columns: [
                    { data: 'id', name: 'id' },
                    { data: 'receiver_name', name: 'receiver_name' },
                    { data: 'email', name: 'email' },
                    { data: 'province_id', name: 'province_id' },
                    { data: 'city_id', name: 'city_id' },
                    { data: 'subdistrict_id', name: 'subdistrict_id' },
                    { data: 'zip_code', name: 'zip_code' },
                    { data: 'address', name: 'address' },
                    { data: 'address_detail', name: 'address_detail' },
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                 ]
        });
    });

    function get_data_transaction() {
      $.ajax({
        url: '{{ route('admin.transaction-Store') }}',
        type: 'GET',
        dataType: 'JSON',
        beforeSend:function() {
          $('#get_data').attr('disabled', 'disabled');
          $('#get_data').text('Loading...');
        },
        success:function(res) {
          $('#get_data').removeAttr('disabled', 'disabled');
          $('#get_data').text('Get Data');
          alert('Fetching Data Success !');
          location.href = "{{ route('admin.transaction') }}";
        }
      });
    }

    $('body').on('click', '.view', function () {
      var order_id = $(this).data('id');
      $.ajax({
        url: '{{ route('admin.transaction-Details') }}',
        type: 'GET',
        dataType: 'JSON',
        data: {order_id: order_id},
        success:function(res) {
          $('#detail_order_id').html('<b>'+res.transaction[0].id+'</b>');
          $('#detail_receiver_name').html('<b>'+res.transaction[0].receiver_name+'</b>');
          $('#detail_email').html('<b>'+res.transaction[0].email+'</b>');
          $('#detail_province').html('<b>'+res.transaction[0].province_id+'</b>');
          $('#detail_city').html('<b>'+res.transaction[0].city_id+'</b>');
          $('#detail_subdistrict').html('<b>'+res.transaction[0].subdistrict_id+'</b>');
          $('#detail_zip_code').html('<b>'+res.transaction[0].zip_code+'</b>');
          $('#detail_address').html('<b>'+res.transaction[0].address+'</b>');
          $('#detail_address_detail').html('<b>'+res.transaction[0].address_detail+'</b>');
          var htmlTransactionDetail = '';
          for(var i=0; i < res.transaction_detail.length; i++) {
            htmlTransactionDetail += '<tr>';
            htmlTransactionDetail += '<td>'+res.transaction_detail[i].product_name+'</td>';
            htmlTransactionDetail += '<td>'+res.transaction_detail[i].code_product+'</td>';
            htmlTransactionDetail += '<td>'+res.transaction_detail[i].qty+'</td>';
            htmlTransactionDetail += '<td>'+res.transaction_detail[i].unit+'</td>';
            htmlTransactionDetail += '<td>'+res.transaction_detail[i].price+'</td>';
            htmlTransactionDetail += '<td>'+res.transaction_detail[i].promo_amount+'</td>';
            htmlTransactionDetail += '<td>'+res.transaction_detail[i].price_promo+'</td>';
            htmlTransactionDetail += '<td>'+res.transaction_detail[i].sub_total+'</td>';
            htmlTransactionDetail += '</tr>';
          }
          $('#data_transaction_detail').html(htmlTransactionDetail);
          $('#table_transaction_detail').DataTable();
        }
      });
      
      $('#exampleModalCenter').modal('show');
   });

  $('body').on('click', '.delete', function () {
      var order_id = $(this).data('id');
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: '{{ route('admin.transaction-Delete') }}',
            type: 'GET',
            dataType: 'JSON',
            data: {order_id: order_id},
            success:function(res) {
              Swal.fire(
                'Deleted!',
                'Transaction has been deleted.',
                'success'
              )
              location.href = "{{ route('admin.transaction') }}";
            }
          });
        }
      })
   });

  $('body').on('click', '.update', function () {
      var order_id = $(this).data('id');
      $.ajax({
        url: '{{ route('admin.transaction-Details') }}',
        type: 'GET',
        dataType: 'JSON',
        data: {order_id: order_id},
        success:function(res) {
          $('#upd_receiver_name').val(res.transaction[0].receiver_name);
          $('#upd_address').val(res.transaction[0].address);
          $('#upd_address_detail').val(res.transaction[0].address_detail);
          $('#btn_footer_transaction_update').html(`<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="UpdateTransaction(`+order_id+`)">Save</button>`)
          $('#ModalViewUpdate').modal('show');
        }
      });
   });

  function UpdateTransaction(order_id) {
    var upd_receiver_name = $('#upd_receiver_name').val();
    var upd_address = $('#upd_address').val();
    var upd_address_detail = $('#upd_address_detail').val();

    if(upd_receiver_name == '' || upd_address == '' || upd_address_detail == '') {
      alert('field is required');
      return false;
    }
    var param = {
      order_id : order_id,
      receiver_name: upd_receiver_name,
      address: upd_address,
      address_detail: upd_address_detail
    }

    Swal.fire({
      title: 'Do you want to save the changes?',
      showDenyButton: true,
      showCancelButton: true,
      confirmButtonText: `Save`,
      denyButtonText: `Don't save`,
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        $.ajax({
          url: '{{ route('admin.transaction-Update') }}',
          type: 'GET',
          dataType: 'JSON',
          data: param,
          success:function(res) {
            Swal.fire('Saved!', '', 'success')
            location.href = "{{ route('admin.transaction') }}";
          }
        });
      } else if (result.isDenied) {
        Swal.fire('Changes are not saved', '', 'info')
      }
    })
    
  }
</script>
@endsection

