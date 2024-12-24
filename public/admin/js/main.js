// Fungsi untuk menampilkan data di modal
function showModal(nim, nama, angkatan) {
    document.getElementById('nim').value = nim;
    document.getElementById('nama_alumni').value = nama;
    document.getElementById('angkatan').value = angkatan;
}

// Pastikan script dijalankan setelah DOM siap
$(document).ready(function() {
    // Event listener untuk tombol "Save changes"
    document.getElementById('update').addEventListener('click', function() {
        var formData = new FormData(document.getElementById('editForm')); // Mengambil data dari form modal

        // Tambahkan csrf token
        formData.append('_token', $('input[name=_token]').val());

        $.ajax({
            url: '/update-alumni', // Sesuaikan URL jika perlu
            type: 'PUT', // Ganti menjadi POST jika Anda menggunakan POST route
            data: formData, // Mengirim data form
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
                if (response.success) {
                    alert('Data updated successfully');
                    $('#editModal').modal('hide'); // Menutup modal setelah berhasil
                    location.reload(); // Reload halaman untuk melihat perubahan
                } else {
                    console.log(response);
                    alert('Failed to update data');
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                alert('Error updating data: ' + xhr.responseText);
            }
        });
    });
});

// $(document).ready(function() {
//     $('#saveChangesButton').click(function() {
//         console.log('Save changes button clicked');

//         var formData = {
//             _token: $('input[name=_token]').val(),
//             nim: $('#nim').val(),
//             nama_alumni: $('#nama_alumni').val(),
//             angkatan: $('#angkatan').val(),
//         };

//         console.log('Form Data:', formData);

//         $.ajax({
//             url: "/update-alumni", // Sesuaikan dengan URL yang benar jika perlu
//             type: 'POST',
//             data: formData,
//             success: function(response) {
//                 console.log('Response:', response);
//                 if(response.success) {
//                     alert(response.success);
//                     location.reload();
//                 } else if(response.error) {
//                     alert(response.error);
//                 }
//             },
//             error: function(xhr) {
//                 console.error('Error:', xhr.responseText);
//             }
//         });
//     });
// });


