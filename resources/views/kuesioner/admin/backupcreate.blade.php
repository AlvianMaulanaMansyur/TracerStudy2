<button type="button" id="add-page"
class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded mt-4">Tambah
Halaman</button>
<button type="submit"
class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded mt-4">Simpan
Kuesioner</button>

@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Buat Kuesioner Baru</h1>

        <form id="kuesionerForm" novalidate>
            @csrf

            <div class="flex">
                <!-- Buttons Section -->
                <div class="w-1/4 pr-4 sticky top-0 bg-white z-10">
                    <div class="sticky top-0 bg-white z-10 p-4">
                        <h3 class="text-lg font-semibold mb-2">Tambahkan Pertanyaan</h3>
                        <div class="question-types">
                            <div class="question-type" draggable="true" id="add-text-question">Tambah Teks</div>
                            <div class="question-type" draggable="true" id="add-checkbox-question">Tambah Checkbox</div>
                            <div class="question-type" draggable="true" id="add-radio-question">Tambah Radio</div>
                            <div class="question-type" draggable="true" id="add-radio-question">Tambah Radio</div>
                        </div>
                        <button type="button" id="add-page"
                            class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded mt-4">Tambah
                            Halaman</button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded mt-4">Simpan
                            Kuesioner</button>
                    </div>
                </div>

                <!-- Questions Section -->
                <div id="questions-section" class="w-3/4">
                    <div class="mb-4">
                        <label class="block font-semibold mb-3 md:mb-3 pr-4 text-lg" for="judul_kuesioner">
                            Judul Kuesioner
                        </label>
                        <input type="text" name="judul_kuesioner" id="judul_kuesioner"
                            class="h-10 border rounded-sm border-gray-300 w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-black"
                            required>
                    </div>

                    <div id="page-buttons" class="mb-4 flex sticky top-0 bg-white z-10 p-4"></div>

                    <div id="page-template" class="hidden">
                        <div class="page-block mb-4">
                            <h2 class="text-lg font-semibold mb-5">Halaman <span class="page-number"></span></h2>
                            <div class="questions-container w-full min-h-96"></div>
                            <button type="button"
                                class="btn btn-danger remove-page bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">Hapus
                                Halaman</button>
                            <hr class="my-4">
                        </div>
                    </div>

                    <div id="question-template" class="hidden question-container p-4 mt-4">
                        <div class="flex w-full">
                            <div class="mb-6">
                                <div class="flex md:w-1/3 mb-3">
                                    <div>
                                        {{-- <label class="block font-semibold mb-1 md:mb-0 pr-4" for="inline-full-name">
                                            Pertanyaan
                                        </label> --}}
                                    </div>

                                </div>
                                <div class="flex">

                                    <div class="mr-3 font-semibold">
                                        Q{NO}
                                    </div>
                                    <div>
                                        <input
                                            class="h-10 border-b rounded-sm border-gray-300 w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-black"
                                            id="inline-full-name" type="text" value="Jane Doe"
                                            name="questions[][teks_pertanyaan]" placeholder="Masukkan pertanyaan">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="md:w-3/3 pt-3">
                                    <select name="questions[][tipe_pertanyaan]"
                                        class="h-10 rounded-sm border-gray-300 w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500 question-type hidden"
                                        required>
                                        <option value="text">Teks</option>
                                        <option value="checkbox">Pilihan Ganda</option>
                                        <option value="radio">Pilihan Radio</option>
                                    </select>
                                </div>
                            </div>
                            <div class="buttons-container hidden">
                                <button type="button"
                                    class="btn btn-danger remove-question bg-red-500 px-2 hover:bg-red-600 text-white font-semibold rounded-full">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <button type="button"
                                    class="btn btn-edit bg-yellow-500 px-2 hover:bg-yellow-600 text-white font-semibold rounded-full">
                                    Edit
                                </button>
                                <button type="button"
                                    class="btn btn-save hidden bg-green-500 px-2 hover:bg-green-600 text-white font-semibold rounded-full">
                                    Save
                                </button>
                                <button class="btn-up">↑</button> <!-- Up button -->
                                <button class="btn-down">↓</button> <!-- Down button -->
                            </div>
                        </div>

                        <div class="options-group mb-4">
                            <div class="option-group">
                                <input type="text" name="questions[][opsi_jawaban][]"
                                    class="h-10 border rounded-sm border-gray-300 w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-black"
                                    placeholder="Masukkan opsi jawaban">
                            </div>

                            <div>
                                <button type="button"
                                    class="mt-2 btn btn-secondary add-option bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded hidden">Tambah
                                    Opsi</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <script type="module">
        $(document).ready(function() {
            const $questionSection = $('#questions-section');
            const $questionTemplate = $('#question-template').clone().removeClass('hidden');
            const $pageTemplate = $('#page-template').removeClass('hidden');

            // Hapus elemen template dari DOM
            $pageTemplate.remove();

            let questionCount = 0;
            let $currentEditingQuestion = null; // Variabel untuk menyimpan pertanyaan yang sedang diedit

            let $currentPage; // Variable to store the currently active page

            // Function to update the current page reference based on scroll position
            function updateCurrentPage() {
                $('.page-block').each(function() {
                    const $this = $(this);
                    const offsetTop = $this.offset().top;
                    const scrollTop = $(window).scrollTop();
                    const windowHeight = $(window).height();

                    // Check if the page block is in the viewport
                    if (offsetTop >= scrollTop && offsetTop < scrollTop + windowHeight) {
                        $currentPage = $this; // Update the current page reference
                    }
                });
            }

            // Update the current page on scroll
            $(window).on('scroll', function() {
                updateCurrentPage();
            });

            // Event for drag-and-drop on question types
            $('.question-types .question-type').on('dragstart', function(event) {
                event.originalEvent.dataTransfer.setData('text/plain', $(this).attr('id'));
            });

            $(document).on('dragover', '.page-block', function(event) {
                event.preventDefault(); // Prevent default to allow drop
                $('.questions-container').addClass('highlight'); // Add highlight class for visual feedback
            });

            $(document).on('dragleave', '.page-block', function(event) {
                $('.questions-container').removeClass('highlight'); // Remove highlight when dragging leaves
            });

            // Handle the drop event for adding new questions
            $(document).on('drop', '.page-block', function(event) {
                event.preventDefault();

                const id = event.originalEvent.dataTransfer.getData('text/plain');
                const type = id.replace('add-', '').replace('-question', '');

                // Check if the dragged item is a question type
                if (id.startsWith('add-')) {
                    // Get the current questions container
                    if ($currentEditingQuestion) {
                        const currentPertanyaan = $currentEditingQuestion.find(
                            'input[name="questions[][teks_pertanyaan]"]').val().trim();

                        // Jika pertanyaan yang sedang diedit kosong, tampilkan alert dan batalkan penambahan
                        if (!currentPertanyaan) {
                            alert('Silakan isi pertanyaan yang sedang diedit terlebih dahulu!');

                            return; // Batalkan penambahan pertanyaan baru
                        }
                    }
                    const $currentPage = $(this); // Get the current questions container
                    addQuestion($currentPage, type); // Call the function to add a question
                }

                $('.questions-container').removeClass('highlight'); // Remove highlight after drop
            });

            // Fungsi untuk menambahkan pertanyaan baru
            function addQuestion(pageContainer, type = '') {

                if ($currentEditingQuestion) {
                    const currentPertanyaan = $currentEditingQuestion.find(
                        'input[name="questions[][teks_pertanyaan]"]').val().trim();

                    // Jika pertanyaan yang sedang diedit kosong, tampilkan alert dan batalkan penambahan
                    if (!currentPertanyaan) {
                        alert('Silakan isi pertanyaan yang sedang diedit terlebih dahulu!');
                        $currentEditingQuestion.find('input[name="questions[][teks_pertanyaan]"]').focus();
                        return; // Batalkan penambahan pertanyaan baru
                    }
                }
                const $newQuestion = $questionTemplate.clone();
                questionCount++; // Increment counter
                $newQuestion.attr('id', 'question-' + questionCount);
                $newQuestion.find('input[name="questions[][teks_pertanyaan]"]').val('');
                $newQuestion.find('select[name="questions[][tipe_pertanyaan]"]').val(type);
                $newQuestion.find('.options-group').toggleClass('hidden', type !== 'checkbox' && type !== 'radio');

//                 // Make the new question draggable
//                 $newQuestion.attr('draggable', true);

//                // Event for dragging the question
//                let draggedId = '';

// // Event for dragging the question
// $newQuestion.on('dragstart', function(event) {
//     draggedId = $(this).attr('id'); // Store the ID of the dragged question
//     event.originalEvent.dataTransfer.setData('text/plain', draggedId);
//     $(this).addClass('dragging'); 
//     console.log("Dragged ID (dragstart):", draggedId); // Log the ID
// });

// // Allow drop on the question container
// $newQuestion.on('dragover', function(event) {
//     event.preventDefault(); // Prevent default to allow drop

//     console.log("Dragged ID (dragover):", draggedId); // Log the ID

//     // Check if draggedId is valid
//     if (draggedId) {
//         const $draggedQuestion = $('#' + draggedId);

//         console.log('dragged question length',$draggedQuestion.length);
//         console.log('dragged question [0]', $draggedQuestion[0]);
//         console.log('this[0]', $(this)[0]);

//         // Check if the dragged question is not the same as the current question
//         if ($draggedQuestion.length && $draggedQuestion[0] !== $(this)[0]) {
//             console.log('halo');
//             // Animate the dragged question to follow the mouse cursor
//             $draggedQuestion.css({ position: 'absolute', top: event.clientY - ($draggedQuestion.outerHeight() / 2) });
//         }
//     } else {
//         console.error("Dragged ID is empty or invalid.");
//     }
// });
//                 // Handle the drop event for reordering
//                 $newQuestion.on('drop', function(event) {
//                     event.preventDefault();

//                     if ($currentEditingQuestion) {
//                         const currentPertanyaan = $currentEditingQuestion.find(
//                             'input[name="questions[][teks_pertanyaan]"]').val().trim();

//                         // Jika pertanyaan yang sedang diedit kosong, tampilkan alert dan batalkan penambahan
//                         if (!currentPertanyaan) {
//                             alert('Silakan isi pertanyaan yang sedang diedit terlebih dahulu!');

//                             return; // Batalkan penambahan pertanyaan baru
//                         }
//                     }

//                     const id = event.originalEvent.dataTransfer.getData('text/plain');
//                     if (id.startsWith('add-')) {
//                         console.log('halo');
//                     } else {
//                         const id = event.originalEvent.dataTransfer.getData('text/plain');
//                         const $draggedQuestion = $('#' + id);
//                         if ($draggedQuestion[0] !== $(this)[0]) {
//         // Insert the dragged question before the current question
//         $(this).before($draggedQuestion);

//         // Reset the position after dropping
//         $draggedQuestion.css({ position: '', top: '' });
//     }
//                     }

//                 });

// Add event handlers for up and down buttons
$newQuestion.find('.btn-up').on('click', function() {
    const $this = $(this).closest('.question-container');
    const $prev = $this.prev('.question-container');
    if ($prev.length) {
        // Animate the current question moving up
        const originalPosition = $this.position().top;
        $this.insertBefore($prev); // Move the question before the previous one
        const newPosition = $this.position().top;

        // Animate the movement
        $this.css({ position: 'absolute', top: originalPosition }).animate({ top: newPosition }, 300, function() {
            $(this).css({ position: '', top: '' }); // Reset position after animation
        });
    }
});

$newQuestion.find('.btn-down').on('click', function() {
    const $this = $(this).closest('.question-container');
    const $next = $this.next('.question-container');
    if ($next.length) {
        // Animate the current question moving down
        const originalPosition = $this.position().top;
        $this.insertAfter($next); // Move the question after the next one
        const newPosition = $this.position().top;

        // Animate the movement
        $this.css({ position: 'absolute', top: originalPosition }).animate({ top: newPosition }, 300, function() {
            $(this).css({ position: '', top: '' }); // Reset position after animation
        });
    }
});

                // Event untuk mengedit pertanyaan
                $newQuestion.find('.btn-edit').on('click', function() {
                    const $questionContainer = $(this).closest('.question-container');
                    console.log("Edit button clicked");
                    console.log("Question Container:", $questionContainer);
                    console.log("current edit", $currentEditingQuestion);

                    // Jika ada pertanyaan yang sedang diedit, simpan pertanyaan tersebut
                    if ($currentEditingQuestion && $currentEditingQuestion !== $questionContainer) {
                        console.log("Saving current editing question:", $currentEditingQuestion);
                        // Cek apakah ada pertanyaan yang sedang diedit
                        if ($currentEditingQuestion) {
                            const currentPertanyaan = $currentEditingQuestion.find(
                                'input[name="questions[][teks_pertanyaan]"]').val().trim();

                            // Jika pertanyaan yang sedang diedit kosong, tampilkan alert dan batalkan pengeditan
                            if (!currentPertanyaan) {
                                alert('Silakan isi pertanyaan yang sedang diedit terlebih dahulu!');
                                $currentEditingQuestion.find('input[name="questions[][teks_pertanyaan]"]')
                                    .focus();

                                return; // Batalkan pengeditan
                            }

                            // Jika ada pertanyaan yang sedang diedit, simpan pertanyaan tersebut
                            console.log("Saving current editing question:", $currentEditingQuestion);
                            $currentEditingQuestion.find('.btn-save')
                                .click(); // Simulasikan klik tombol save
                        }
                    }

                    // Mengaktifkan input pertanyaan
                    $questionContainer.find('input[name="questions[][teks_pertanyaan]"]').prop('disabled',
                        false);
                    $questionContainer.find('.option-group input').prop('disabled', false);

                    // Menampilkan pilihan tipe pertanyaan dan opsi jawaban
                    $questionContainer.find('.question-type').show(); // Menampilkan pilihan tipe pertanyaan
                    $questionContainer.find('.add-option').show(); // Menampilkan opsi jawaban
                    
                    $questionContainer.addClass('editing'); 

                    $(this).hide(); // Sembunyikan tombol edit
                    $questionContainer.find('.btn-save').show(); // Tampilkan tombol save

                    // Simpan referensi ke pertanyaan yang sedang diedit
                    $currentEditingQuestion = $questionContainer; // Set referensi
                    console.log("Current Editing Question set to:", $currentEditingQuestion);
                });

                // Event untuk menyimpan pertanyaan
                $newQuestion.find('.btn-save').on('click', function() {
                    const $questionContainer = $(this).closest('.question-container');
                    const pertanyaan = $questionContainer.find('input[name="questions[][teks_pertanyaan]"]')
                        .val().trim();

                    // Cek apakah pertanyaan kosong
                    if (pertanyaan) {
                        // Menonaktifkan input pertanyaan
                        $questionContainer.find('input[name="questions[][teks_pertanyaan]"]').prop(
                            'disabled', true);

                        // Menyembunyikan pilihan tipe pertanyaan dan opsi jawaban
                        $questionContainer.find('.question-type')
                            .hide(); // Menyembunyikan pilihan tipe pertanyaan
                        $questionContainer.find('.add-option').hide(); // Menyembunyikan opsi jawaban

                        // Menonaktifkan semua input di dalam .option-group
                        $questionContainer.find('.option-group input').prop('disabled', true);
                       // Remove the editing class
                        $questionContainer.removeClass('editing');

                        $(this).hide(); // Sembunyikan tombol save
                        $questionContainer.find('.btn-edit').show(); // Tampilkan kembali tombol edit

                        // Reset referensi pertanyaan yang sedang diedit
                        $currentEditingQuestion = null;
                    } else {
                        alert('Pertanyaan tidak boleh kosong!');
                        $questionContainer.find('input[name="questions[][teks_pertanyaan]"]').focus();
                        return;
                    }
                });

                // Event untuk mengubah tipe pertanyaan
                $newQuestion.find('.question-type').on('change', function() {
                    const $optionsGroup = $newQuestion.find('.options-group');
                    const selectedType = $(this).val();
                    $optionsGroup.toggleClass('hidden', !['checkbox', 'radio'].includes(selectedType));
                });

                $newQuestion.find('.add-option').on('click', function() {
                    const $input = $('<input>', {
                        type: 'text',
                        name: 'questions[][opsi_jawaban][]',
                        class: 'h-10 mt-2 border rounded-sm border-gray-300 w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-black',
                        placeholder: 'Masukkan opsi jawaban',
                    });
                    // Ensure you are appending to the correct container
                    $newQuestion.find('.option-group').append($input); // This should be correct
                });

                // Event untuk menghapus pertanyaan
                $newQuestion.find('.remove-question').on('click', function() {
                    $newQuestion.remove();
                });

                // Tambahkan pertanyaan baru ke dalam kontainer
                pageContainer.find('.questions-container').append($newQuestion);

                
                // Mengaktifkan input pertanyaan dan fokus pada input
                const $inputPertanyaan = $newQuestion.find('input[name="questions[][teks_pertanyaan]"]');
                $inputPertanyaan.prop('disabled', false); // Aktifkan input
                $inputPertanyaan.focus(); // Fokus pada input

                // Simulasikan klik tombol edit untuk masuk ke mode edit
                const $editButton = $newQuestion.find('.btn-edit');
                $editButton.trigger('click'); // Memicu event klik pada tombol edit
            }

            // Fungsi untuk menambahkan halaman baru
            function addPage() {

                if ($currentEditingQuestion) {
                    const currentPertanyaan = $currentEditingQuestion.find(
                        'input[name="questions[][teks_pertanyaan]"]').val().trim();

                    // Jika pertanyaan yang sedang diedit kosong, tampilkan alert dan batalkan penambahan
                    if (!currentPertanyaan) {
                        alert('Silakan isi pertanyaan yang sedang diedit terlebih dahulu!');
                        return; // Batalkan penambahan pertanyaan baru
                    }
                }
                const $newPage = $pageTemplate.clone();
                const pageNumber = $('.page-block').length + 1;

                $newPage.find('.page-number').text(pageNumber);

                $newPage.find('.remove-page').on('click', function() {
                    const $currentPage = $(this).closest('.page-block'); // Mendapatkan halaman saat ini
                    const $previousTemplate = $currentPage.parent().prev(
                        '#page-template'); // Mencari template sebelumnya
                    const $previousPage = $previousTemplate.find(
                        '.page-block'); // Mencari page-block di dalam template sebelumnya
                    const $parentTemplate = $currentPage.parent();

                    if ($previousPage.length) {
                        // Tampilkan halaman sebelumnya
                        $previousPage.show(); // Menampilkan halaman sebelumnya
                        $parentTemplate.remove(); // Menghilangkan template halaman saat ini
                        updatePageButtons()
                        console.log("Halaman sebelumnya ditemukan:", $previousPage);
                    } else {
                        // Tidak ada halaman sebelumnya, lakukan tindakan alternatif
                        console.log("Tidak ada halaman sebelumnya.");
                    }

                    // Menghapus halaman saat ini
                    $currentPage.remove(); // Menghapus halaman yang sedang aktif
                });

                // $('.page-block:visible');
                $newPage.appendTo($questionSection);
                // addQuestion($newPage, 'text');
                updatePageButtons();
                $newPage.show();
            }

            // Fungsi untuk memperbarui nomor halaman
            function updatePageNumbers() {
                $('.page-block').each(function(index) {
                    $(this).find('.page-number').text(index + 1);
                });
                updatePageButtons();
            }

            // Fungsi untuk memperbarui tombol navigasi halaman
            function updatePageButtons() {
                $('#page-buttons').empty();
                $('.page-block').each(function() {
                    const pageNumber = $(this).find('.page-number').text();
                    if (pageNumber) {
                        const $button = $('<button>', {
                            type: 'button',
                            class: 'page-button bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded mr-2',
                            text: `Halaman ${pageNumber}`,
                        });

                        $button.on('click', function() {
                            const pageNumber = $(this).text().match(/\d+/)[
                            0]; // Extract the page number
                            const $targetPage = $(
                            `.page-block:contains(${pageNumber})`); // Find the corresponding page block

                            // Scroll to the target page
                            $('html, body').animate({
                                scrollTop: $targetPage.offset().top -
                                    100 // Scroll to the top of the target page
                            }, 500); // Duration of the scroll animation in milliseconds

                            // Show the target page and hide others
                            // $('.page-block').hide();
                            $targetPage.show();
                        });

                        $('#page-buttons').append($button);
                    }
                });

                if ($('.page-block').length <= 1) {
                    $('.remove-page').hide();
                } else {
                    $('.remove-page').show();
                }
            }

            // Tambahkan halaman pertama saat halaman dimuat
            addPage();

            // Event untuk tombol tambah pertanyaan
            $('#add-text-question').on('click', function() {
                // const $currentPage = $('.page-block:visible');
                console.log('current page', $currentPage);
                addQuestion($currentPage, 'text');
            });

            $('#add-checkbox-question').on('click', function() {
                // const $currentPage = $('.page-block:visible');
                addQuestion($currentPage, 'checkbox');
            });

            $('#add-radio-question').on('click', function() {
                // const $currentPage = $('.page-block:visible');
                addQuestion($currentPage, 'radio');
            });

            $('#add-page').on('click', function() {
                addPage();
            });

            $('#kuesionerForm').on('submit', function(event) {
                event.preventDefault();

                let formData = {
                    judul_kuesioner: $('#judul_kuesioner').val(),
                    questions: [],
                };

                $('.page-block').each(function() {
                    const $page = $(this);
                    const pageNumber = $page.find('.page-number').text();
                    const $questions = $page.find('input[name="questions[][teks_pertanyaan]"]');
                    const $types = $page.find('select[name="questions[][tipe_pertanyaan]"]');
                    const $optionsGroups = $page.find('.options-group');

                    $questions.each(function(index) {
                        const teksPertanyaan = $(this).val().trim();
                        if (teksPertanyaan) {
                            const question = {
                                teks_pertanyaan: teksPertanyaan,
                                tipe_pertanyaan: $types.eq(index).val(),
                                opsi_jawaban: [],
                                halaman: pageNumber
                            };

                            $optionsGroups.eq(index).find(
                                'input[name="questions[][opsi_jawaban][]"]').each(
                                function() {
                                    const opsiJawaban = $(this).val().trim();
                                    if (opsiJawaban) {
                                        question.opsi_jawaban.push(opsiJawaban);
                                    }
                                });

                            formData.questions.push(question);
                        }
                    });
                });

                $.ajax({
                    url: '/api/kuesioner',
                    method: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: JSON.stringify(formData),
                    success: function(data) {
                        if (data.message) {
                            alert(data.message);
                            $('#kuesionerForm')[0].reset();
                        } else {
                            console.error('Terjadi kesalahan:', data);
                        }
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    },
                });
            });
        });
    </script>
@endsection
