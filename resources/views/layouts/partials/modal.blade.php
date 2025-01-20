<div id="default-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex justify-center items-center w-full h-full animated">
    <div class="relative p-4 w-full max-w-5xl max-h-full">
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
                <form id="editForm" class="flex flex-wrap gap-4 w-full">
                    @csrf
                    @method('PUT')
                    <!-- NIM -->
                    <div class="form-group w-1/4">
                        <label for="nim">NIM</label>
                        <input type="text" readonly class="form-control rounded cursor-not-allowed" id="nim" name="nim" readonly>
                    </div>
                    <!-- Nama Alumni -->
                    <div class="form-group w-1/4">
                        <label for="nama_alumni">Nama Alumni</label>
                        <input type="text" readonly class="form-control rounded cursor-not-allowed" id="nama_alumni" name="nama_alumni" readonly>
                    </div>
                    <!-- Angkatan -->
                    <div class="form-group w-1/4 hidden">
                        <label for="angkatan">Angkatan</label>
                        <input type="text" readonly class="form-control rounded cursor-not-allowed" id="angkatan" name="angkatan" readonly>
                    </div>
                    <!-- Jenjang -->
                    <div class="form-group w-1/4 ">
                        <label for="jenjang">Jenjang</label>
                        <input type="text" readonly class="form-control rounded cursor-not-allowed" id="jenjang" name="jenjang" readonly>
                    </div>
                    <!-- Email -->
                    <div class="form-group w-1/4">
                        <label for="email">Email</label>
                        <input type="text" readonly class="form-control rounded cursor-not-allowed" id="email" name="email" readonly>
                    </div>
                    <!-- Alamat -->
                    <div class="form-group w-1/4">
                        <label for="nik">nik</label>
                        <input type="text" readonly class="form-control rounded" id="nik" name="nik">
                    </div>
                    <!-- Telepon -->
                    <div class="form-group w-1/4">
                        <label for="prodi">prodi</label>
                        <input type="text" readonly class="form-control rounded" id="prodi" name="prodi">
                    </div>
                    <div class="form-group w-1/4">
                        <label for="no_telepon">No Telepon</label>
                        <input type="text" readonly class="form-control rounded" id="no_telepon" name="no_telepon">
                    </div>

                </form>
            </div>
            <!-- Modal footer -->
            <div class="flex justify-end items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button type="button" id="closeModal"
                    class="ms-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>





<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk menampilkan modal dan mengisi field dengan data yang ingin diedit
        window.showModal = function(nim, nama, angkatan, foto_profil, email, jenjang, nik, nama_prodi, no_telepon, ) {
            document.getElementById('nim').value = nim;
            document.getElementById('nama_alumni').value = nama;
            document.getElementById('angkatan').value = angkatan;
            document.getElementById('profileImage').src = foto_profil;
            document.getElementById('email').value = email;
            document.getElementById('jenjang').value = jenjang;
            document.getElementById('nik').value = nik;
            document.getElementById('prodi').value = nama_prodi;
            document.getElementById('no_telepon').value = no_telepon;
            // document.getElementById('tahun_lulus').value = tahun_lulus;

            // console.log(tahun_lulus);

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
