// This Sweetalert is apply for href attribute

$(function () {
  $(document).on("click", "#delete", function (e) {
    e.preventDefault();
    var link = $(this).attr("href");

    Swal.fire({
      title: "Are you sure?",
      text: "Delete This Data?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!",
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = link;
        Swal.fire("Deleted!", "Your file has been deleted.", "success");
      }
    });
  });
});

// This Sweetalert is apply for form and button
$(function () {
  $(document).on("click", "#delete-btn", function (e) {
    e.preventDefault(); // Prevent immediate form submission

    // Find the closest form to the clicked button
    var form = $(this).closest("form");

    Swal.fire({
      title: "Are you sure?",
      text: "Do you really want to delete this record?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!",
    }).then((result) => {
      if (result.isConfirmed) {
        // Submit the form if the user confirms
        form.submit();
        Swal.fire("Deleted!", "Your file has been deleted.", "success");
      }
    });
  });
});
