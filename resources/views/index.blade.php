
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Service Managemet</title>
  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css' />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
</head>

<body>
  <div class="container">
    <div class="row my-5">
      <div class="col-lg-12">
        <h2>Service Management</h2>
        <div class="card shadow">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="text-light">Service Management</h3>
            <div>
              <button class="btn btn-light" id="importExcel"><i class="bi-file-earmark-spreadsheet me-2"></i>Import Excel</button>
              <button class="btn btn-light" id="exportExcel"><i class="bi-file-earmark-spreadsheet me-2"></i>Export Excel</button>
              <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addServiceModal"><i class="bi-plus-circle me-2"></i>Add New Services</button>
            </div>
          </div>
          <div class="card-body" id="show_all_service">
            <h1 class="text-center text-secondary my-5">Loading...</h1>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- new service modal --}}
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Services</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="add_service_form" enctype="multipart/form-data">
        @csrf
        <div class="modal-body p-4 bg-light">
          <div class="row">
            <div class="col-lg">
              <label for="service_name">Service Name</label>
              <input type="text" name="service_name" class="form-control" placeholder="Service Name" required>
            </div>
            <div class="col-lg">
              <label for="description">Description</label>
              <input type="text" name="description" class="form-control" placeholder="description" required>
            </div>
          </div>
          <div class="my-2">
            <label for="price">Price</label>
            <input type="price" name="price" class="form-control" placeholder="price" required>
          </div>
          <div class="my-2">
            <label for="service_image">Service Image </label>
            <input type="file" name="service_image" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="add_service_btn" class="btn btn-primary">Add Service</button>
        </div>
      </form>
    </div>
  </div>
</div>
 
{{-- edit service modal --}}
<div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Service</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="#" method="POST" id="edit_service_form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="emp_id" id="emp_id">
        <input type="hidden" name="emp_service_image" id="emp_service_image">
        <div class="modal-body p-4 bg-light">
          <div class="row">
            <div class="col-lg">
              <label for="service_name">Service Name</label>
              <input type="text" name="service_name" id="service_name" class="form-control" placeholder="Service Name" required>
            </div>
            <div class="col-lg">
              <label for="description">Description</label>
              <input type="text" name="description" id="description" class="form-control" placeholder="Description" required>
            </div>
          </div>
          <div class="my-2">
            <label for="price">Price</label>
            <input type="price" name="price" id="price" class="form-control" placeholder="Price" required>
          </div>
          <div class="my-2">
            <label for="service_image">Service Image</label>
            <input type="file" name="service_image" class="form-control">
          </div>
          <div class="mt-2" id="service_image"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="edit_service_btn" class="btn btn-success">Update Service</button>
        </div>
      </form>
    </div>
  </div>
</div>
 
  <script src='https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js'></script>
  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $(function() {
 
      // add new employee ajax request
      $("#add_service_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#add_service_btn").text('Adding...');
        $.ajax({
          url: '{{ route('store') }}',
          method: 'post',
          data: fd,
          cache: false,
          contentType: false,
          processData: false,
          dataType: 'json',
          success: function(response) {
            if (response.status == 200) {
              Swal.fire(
                'Added!',
                'Service Added Successfully!',
                'success'
              )
              fetchAllServices();
            }
            $("#add_service_btn").text('Add Service');
            $("#add_service_form")[0].reset();
            $("#addServiceModal").modal('hide');
          }
        });
      });
 
      // edit service ajax request
      $(document).on('click', '.editIcon', function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        $.ajax({
          url: '{{ route('edit') }}',
          method: 'get',
          data: {
            id: id,
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            $("#service_name").val(response.service_name);
            $("#description").val(response.description);
            $("#price").val(response.price);
            $("#service_image").html(
              `<img src="storage/images/${response.service_image}" width="100" class="img-fluid img-thumbnail">`);
            $("#emp_id").val(response.id);
            $("#emp_service_image").val(response.service_image);
          }
        });
      });
 
      // update service ajax request
      $("#edit_service_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#edit_service_btn").text('Updating...');
        $.ajax({
          url: '{{ route('update') }}',
          method: 'post',
          data: fd,
          cache: false,
          contentType: false,
          processData: false,
          dataType: 'json',
          success: function(response) {
            if (response.status == 200) {
              Swal.fire(
                'Updated!',
                'Service Updated Successfully!',
                'success'
              )
              fetchAllServices();
            }
            $("#edit_service_btn").text('Update Service');
            $("#edit_service_form")[0].reset();
            $("#editServiceModal").modal('hide');
          }
        });
      });
 
      // delete service ajax request
      $(document).on('click', '.deleteIcon', function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        let csrf = '{{ csrf_token() }}';
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
              url: '{{ route('delete') }}',
              method: 'delete',
              data: {
                id: id,
                _token: csrf
              },
              success: function(response) {
                console.log(response);
                Swal.fire(
                  'Deleted!',
                  'Your file has been deleted.',
                  'success'
                )
                fetchAllServices();
              }
            });
          }
        })
      });
 
      // fetch all services ajax request
      fetchAllServices();
 
     // Import Excel file
     $("#importExcel").on('click', function() {
        const input = $('<input type="file" accept=".xlsx, .xls"/>');
        input.on('change', function(event) {
          const file = event.target.files[0];
          const reader = new FileReader();
          reader.onload = function(e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });
            const sheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[sheetName];
            const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
            importData(jsonData);
          };
          reader.readAsArrayBuffer(file);
        });
        input.trigger('click');
      });

      function importData(data) {
        const headers = data[0];
        const rows = data.slice(1);
        rows.forEach(row => {
          const serviceData = {};
          headers.forEach((header, index) => {
            serviceData[header.toLowerCase()] = row[index];
          });
          $.ajax({
            url: '{{ route('store') }}',
            method: 'post',
            data: {
              _token: '{{ csrf_token() }}',
              ...serviceData
            },
            success: function(response) {
              if (response.status == 200) {
                Swal.fire('Imported!', 'Services Imported Successfully!', 'success');
                fetchAllServices();
              }
            }
          });
        });
      }

      // Export Excel file
  $("#exportExcel").on('click', function() {
    $.ajax({
      url: '{{ route('export') }}',
      method: 'get',
      success: function(response) {
        const workbook = XLSX.utils.book_new();
        const worksheet = XLSX.utils.json_to_sheet(response);
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Services');
        XLSX.writeFile(workbook, 'services.xlsx');
      }
    });
  });

  // Existing fetchAllServices function
  function fetchAllServices() {
    $.ajax({
      url: '{{ route('fetchAll') }}',
      method: 'get',
      success: function(response) {
        $("#show_all_service").html(response);
        $("table").DataTable({
          order: [0, 'desc']
        });
      }
    });
  }
});
  </script>
</body>
</html>