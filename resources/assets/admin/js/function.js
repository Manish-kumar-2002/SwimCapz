
$("#delete-variants").on("show.bs.modal", function (e) {
    $(this)
      .find(".delete-form")
      .attr("action", $(e.relatedTarget).data("href"));
  });

  $("#delete-variants .delete-form").on("submit", function (e) {
    if (admin_loader == 1) {
      $(".submit-loader").show();
    }
    $.ajax({
      method: "POST",
      url: $(this).prop("action"),
      data: new FormData(this),
      dataType: "JSON",
      contentType: false,
      cache: false,
      processData: false,
      success: function (data) {
        $("#delete-variants").modal("toggle");
        $(".alert-danger").hide();
        $(".alert-success").show();
        $(".alert-success p").html(data.message);
        $('#record_'+data.variant_id).remove();
        toastr.success(data.message);
        if (admin_loader == 1) {
          $(".submit-loader").hide();
        }
      },
    });
    return false;
  });


$("#confirm-variant-image-delete").on("show.bs.modal", function (e) {
  $(this)
    .find(".delete-form")
    .attr("action", $(e.relatedTarget).data("href"));
    var image = $(e.relatedTarget).data('image');
    $('#old-image').val(image);
    var id = $(e.relatedTarget).data('id');
    $('#old-id').val(id);
});



$("#confirm-variant-image-delete .delete-form").on("submit", function (e) {
  if (admin_loader == 1) {
    $(".submit-loader").show();
  }
  $.ajax({
    method: "POST",
    url: $(this).prop("action"),
    data: new FormData(this),
    dataType: "JSON",
    contentType: false,
    cache: false,
    processData: false,
    success: function (data) {
      var remove_image = $('#old-id').val();
      $('#images-'+remove_image).hide();
      $("#confirm-variant-image-delete").modal("toggle");
      $(".alert-danger").hide();
      $(".alert-success").show();
      $(".alert-success p").html(data.message);
      toastr.success(data.message);
      if (admin_loader == 1) {
        $(".submit-loader").hide();
      }
    },
  });
  return false;
});

// This code is for setting the action attribute of the delete form in the modal.
$("#pricing-break-delete").on("show.bs.modal", function (e) {
  $(this)
    .find(".delete-form")
    .attr("action", $(e.relatedTarget).data("href"));
});

// This code handles the form submission when the delete button in the modal is clicked.
$("#pricing-break-delete .delete-form").on("submit", function (e) {
  if (admin_loader == 1) {
    $(".submit-loader").show();
  }
  $.ajax({
    method: "POST",
    url: $(this).prop("action"),
    data: new FormData(this), // Sending form data
    dataType: "JSON",
    contentType: false,
    cache: false,
    processData: false,
    success: function (data) {
      $("#pricing-break-delete").modal("toggle"); // Close the modal
      $(".alert-danger").hide();
      $(".alert-success").show();
      $(".alert-success p").html(data.message);

      // Assuming `data.id` contains the value you want to match
      $('tr[data-id="' + data.id + '"]').remove(); // Remove the corresponding table row
      toastr.success(data.message);

      if (admin_loader == 1) {
        $(".submit-loader").hide();
      }
    },
  });
  return false; // Prevent the form from submitting normally
});
