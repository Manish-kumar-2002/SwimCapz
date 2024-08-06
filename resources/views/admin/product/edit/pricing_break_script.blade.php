<script>
function addButtion() {
    // Create a unique ID for the new row
    var uniqueID = Date.now();
    
    new_row = "<tr rowsadd='1' class='rows-add' data-id='" + uniqueID + "'><form><input type='hidden' name='prod_id' value='{{$data->id}}' id='prodid" + uniqueID + "' /><td class='padding-pricing-td'><input type='number' name='quantity' id='quantity" + uniqueID + "' /></td><td class='padding-pricing-td'>$ <input type='number' name='color1' id='color1" + uniqueID + "' ></td><td class='padding-pricing-td'>$ <input type='number' name='color2' id='color2" + uniqueID + "'></td><td class='padding-pricing-td'>$ <input type='number' name='color3' id='color3" + uniqueID + "'></td><td class='padding-pricing-td'>$ <input type='number' name='color4' id='color4" + uniqueID + "'></td><td class='padding-pricing-td'><button onclick='savePriceBreak(this,"+ uniqueID +")' class='btn btn-success btn-sm save-button' data-id='" + uniqueID + "'><i class='fa fa-save'></i></button> <button class='btn btn-sm btn-danger ibtnDel' data-id='" + uniqueID + "'><i class='fa fa-trash'></i></button></td></form></tr>";
    $('.pricing-table-add-row').append(new_row);
};

$("table.pricing-table").on("click", ".ibtnDel", function (event) {
    var rowID = $(this).data('id');
    var row = $(this).closest("tr");
    
    // If it's an existing row, send an AJAX request to delete it
    if (row.hasClass("existing-row")) {
        $.ajax({
            type: "POST",
            url: "delete_data.php", // Replace with your server-side script URL for deletion
            data: {
                id: rowID
            },
            success: function(response) {
                // Handle the server's response here, e.g., show a success message
                console.log("Row deleted successfully:", response);
                row.remove(); // Remove the row from the table
            },
            error: function(xhr, status, error) {
                // Handle any errors that occur during the AJAX request
                console.error("Error:", error);
            }
        });
    } else {
        // If it's a new row, simply remove it from the table
        row.remove();
    }
});
var buttonElement ='';
function savePriceBreak(button,rowID) {
    var buttonElement = $(button);
    // Get the values from the input fields
    var quantity = $('#quantity' + rowID).val();
    var color1 = $('#color1' + rowID).val();
    var color2 = $('#color2' + rowID).val();
    var color3 = $('#color3' + rowID).val();
    var color4 = $('#color4' + rowID).val();
    var prod_id = $('#prodid' + rowID).val();

    var requestData = {
        quantity: quantity,
        color_1: color1,
        color_2: color2,
        color_3: color3,
        color_4: color4,
        prod_id: prod_id,
    };

    if (buttonElement.closest('tr').attr('data-editing') === 'true') {
        requestData.id = rowID;
    }

    // Send an AJAX request to save the data (insert or update)
    $.ajax({
        type: "POST",
        url: "{{ route('admin-pricing-break-store') }}", // Replace with your server-side script URL for saving data
        data: requestData,
        success: function(response) {
            // Handle the server's response here
            console.log("Server Response:", response);

            if (response.status === 'success') {
                // Update the row ID if it's an insert operation
                    // Assuming your response contains the newly generated ID
                    var newRowID = response.records.id;
                    // Update the data-id attribute of the button
                    buttonElement.attr('data-id', newRowID);
                    buttonElement.attr('onclick', 'pricingBreakEdit(this, ' + newRowID + ')');
                    // Update the id attributes of the input fields with the new rowID
                    $('#quantity' + rowID).attr('id', 'quantity' + newRowID);
                    $('#color1' + rowID).attr('id', 'color1' + newRowID);
                    $('#color2' + rowID).attr('id', 'color2' + newRowID);
                    $('#color3' + rowID).attr('id', 'color3' + newRowID);
                    $('#color4' + rowID).attr('id', 'color4' + newRowID);
                    $('#prodid' + rowID).attr('id', 'prodid' + newRowID);
                    // Update the data-id attribute of the <tr> element
                    buttonElement.closest('tr').attr('data-id', newRowID);
                // Set the input field values based on the response
                $('#quantity' + newRowID).val(response.records.quantity);
                $('#color1' + newRowID).val(response.records.color_1);
                $('#color2' + newRowID).val(response.records.color_2);
                $('#color3' + newRowID).val(response.records.color_3);
                $('#color4' + newRowID).val(response.records.color_4);
                $('#prodid' + newRowID).val(response.records.prod_id);

                // Disable input fields after a successful response
                $('#quantity' + newRowID).prop('disabled', true);
                $('#color1' + newRowID).prop('disabled', true);
                $('#color2' + newRowID).prop('disabled', true);
                $('#color3' + newRowID).prop('disabled', true);
                $('#color4' + newRowID).prop('disabled', true);
                // Change button classes to edit-button
                buttonElement.html('<i class="fa fa-edit"></i>').removeClass('save-button').addClass('edit-button');
                // Update the trash button
                var trashButton = buttonElement.closest('tr').find('.ibtnDel');
                var url ='{{ route('admin-pricing-break-delete', [':id']) }}';
                url = url.replace(':id', newRowID);
                trashButton.attr('data-href',url);
                trashButton.attr('data-id', newRowID); // Set the data-id attribute
                trashButton.attr('data-toggle', 'modal'); // Set the data-toggle attribute
                trashButton.attr('data-target', '#pricing-break-delete'); // Set the data-target attribute
                trashButton.removeClass('ibtnDel');
                
                // Show a success toastr notification
                toastr.success('Data saved successfully');
            } else {
                // Show an error toastr notification
                toastr.error(response.message);
            }
        },
        error: function(xhr, status, error) {
            // Handle any errors that occur during the AJAX request
            if (xhr.status === 422) {
                // Handle the 422 validation error
                var errors = xhr.responseJSON.errors;
                var errorMessage = "Validation Error:<br>";
                for (var key in errors) {
                    if (errors.hasOwnProperty(key)) {
                        errorMessage += errors[key][0] + "<br>";
                    }
                }
                toastr.error(errorMessage);
            } else {
                // Handle other errors
                toastr.error('Error saving data');
            }
        }
    });
}


function pricingBreakEdit(button, rowID) {
    $(button).closest('tr').attr('data-editing', 'true');
    // Enable input fields
    $('#quantity' + rowID).prop('disabled', false);
    $('#color1' + rowID).prop('disabled', false);
    $('#color2' + rowID).prop('disabled', false);
    $('#color3' + rowID).prop('disabled', false);
    $('#color4' + rowID).prop('disabled', false);
    $(button).data('id', rowID); // Use .data() to set data-id attribute
    $(button).html('<i class="fa fa-save"></i>');
    $(button).removeAttr('onclick');
    $(button).attr('onclick', 'savePriceBreak(this, ' + rowID + ')');
    $(button).removeClass('edit-button').addClass('save-button');
}
</script>