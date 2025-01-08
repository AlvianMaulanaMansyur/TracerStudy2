<div id="default-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex justify-center items-center w-full h-full animated ">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Edit Alumni
                </h3>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 flex space-x-4">
                <div class="w-1/3 flex justify-center items-center">
                    <img src="" alt="Foto Profil" class="rounded w-32 h-32" id="profileImage">
                </div>
                <form id="editForm">
                    @csrf
                    @method('PUT')
                    <div class="form-group ">
                        <label for="nim">NIM</label>
                        <input type="text " class="form-control cursor-not-allowed" id="nim" name="nim"
                            readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama_alumni">Nama Alumni</label>
                        <input type="text" class="form-control" id="nama_alumni" name="nama_alumni">
                    </div>
                    <div class="form-group">
                        <label for="angkatan">Angkatan</label>
                        <input type="text" class="form-control" id="angkatan" name="angkatan">
                    </div>
                    <div class="form-group">
                        <label for="gelombang_wisuda">Gelombang Wisuda</label>
                        <input type="text" class="form-control" id="gelombang_wisuda" name="gelombang_wisuda">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="email" name="email">
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div
                class="flex justify-end items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button type="button" id="closeModal"
                    class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-transparent rounded-lg border border-blue-500 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    Tutup
                </button>
                <button type="button" id="update"
                    class="ms-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>




<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk menampilkan modal dan mengisi field dengan data yang ingin diedit
        window.showModal = function(nim, nama, angkatan, foto_profil, email, gelombang_wisuda) {
            document.getElementById('nim').value = nim;
            document.getElementById('nama_alumni').value = nama;
            document.getElementById('angkatan').value = angkatan;
            document.getElementById('profileImage').src = foto_profil;
            document.getElementById('email').value = email;
            document.getElementById('gelombang_wisuda').value = gelombang_wisuda;
            // document.getElementById('alamat').value = alamat;
            // document.getElementById('no_telepon').value = no_telepon;


            // Menampilkan modal
            document.getElementById('default-modal').classList.remove('hidden');
        };

        // Menutup modal
        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('default-modal').classList.add('hidden');
        });

        // Fungsi untuk memperbarui data
        document.getElementById('update').addEventListener('click', function(e) {
            e.preventDefault();
            var form = $('#editForm');
            var url = '{{ route('admin.update-alumni') }} ';

            $.ajax({
                type: "PUT",
                url: url,
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        // Menutup modal setelah update berhasil
                        document.getElementById('default-modal').classList.add('hidden');

                        // Menampilkan alert
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Data Berhasil Diperbarui",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                            willClose: () => {
                                location.reload();
                            }
                        });

                        // Merefresh halaman
                        // location.reload();
                    } else if (response.error) {
                        Swal.fire({
                            title: "Gagal!",
                            text: "Data Gagal Diperbarui",
                            icon: "error",
                            showConfirmButton: false,
                            timer: 1900,
                            timerProgressBar: true,
                            willClose: () => {
                                location.reload();
                            }
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: "Gagal!",
                        text: "Data Gagal Diperbarui",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        willClose: () => {
                            location.reload();
                        }
                    });
                }
            });
        });
    });
</script>
