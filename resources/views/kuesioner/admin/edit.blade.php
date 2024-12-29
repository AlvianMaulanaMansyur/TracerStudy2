@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Kuesioner</h1>

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
                        <label class="block font-semibold mb-3 md:mb-3 pr-4 text-lg" for="judul_kuesioner">Judul Kuesioner</label>
                        <input type="text" name="judul_kuesioner" id="judul_kuesioner" class="h-10 border rounded-sm border-gray-300 w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-black" value="{{ $kuesioner->judul_kuesioner }}" required>
                    </div>

                    <div id="kuesioner" data-id="{{ $kuesioner->id_kuesioner }}"></div>

                    <div id="page-buttons" class="mb-4 flex sticky top-0 bg-white z-10 p-4"></div>

                    <div id="page-template" class="hidden">
                        <div class="page-block mb-4">
                            <h2 class="text-lg font-semibold mb-5">Halaman <span class="page-number"></span></h2>
                            <div class="questions-container w-full min-h-96"></div>
                            <button type="button" class="btn btn-danger remove-page bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">Hapus Halaman</button>
                            <hr class="my-4">
                        </div>
                    </div>

                    <div id="question-template" class="hidden question-container p-4 mt-4">
                        <div class="flex w-full">
                            <div class="mb-6">
                                <div class="flex md:w-1/3 mb-3">
                                    <div>
                                        {{-- <label class="block font-semibold mb-1 md:mb-0 pr-4" for="inline-full-name">Pertanyaan</label> --}}
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="mr-3 font-semibold question-number"></div>
                                    <div>
                                        <input class="h-10 border-b rounded-sm border-gray-300 w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-black" id="inline-full-name" type="text" name="questions[][teks_pertanyaan]" placeholder="Masukkan pertanyaan" required>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="md:w-3/3 pt-3">
                                    <select name="questions[][tipe_pertanyaan]" class="h-10 rounded-sm border-gray-300 w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500 question-type" required>
                                        <option value="text">Teks</option>
                                        <option value="checkbox">Pilihan Ganda</option>
                                        <option value="radio">Pilihan Radio</option>
                                    </select>
                                </div>
                            </div>
                            <div class="buttons-container hidden">
                                <button type="button" class="btn btn-danger remove-question bg-red-500 px-2 hover:bg-red-600 text-white font-semibold rounded-full">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="options-group mb-4">
                            <div class="option-group"></div>
                            <div>
                                <button type="button" class="mt-2 btn btn-secondary add-option bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded hidden">Tambah Opsi</button>
                            </div>
                        </div>

                        {{-- <div class="mb-4">
                            <label class="block font-semibold mb-1" for="halaman">Halaman</label>
                            <input type="number" name="questions[][halaman]" class="h-10 border rounded-sm border-gray-300 w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-black" placeholder="Masukkan nomor halaman" required>
                        </div> --}}
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


           // Mengambil data pertanyaan dari database
    const existingQuestions = @json($kuesioner->pertanyaan->map(function($pertanyaan) {
        return json_decode($pertanyaan->data_pertanyaan);
    }));


    // Kelompokkan pertanyaan berdasarkan halaman
    const questionsByPage = {};

    existingQuestions.forEach(question => {
        const page = question.halaman;
        // Jika halaman belum ada, buat array baru untuk halaman tersebut
        if (!questionsByPage[page]) {
            questionsByPage[page] = [];
        }

        // Tambahkan pertanyaan ke halaman yang sesuai
        questionsByPage[page].push({
            tipe_pertanyaan: question.tipe_pertanyaan,
            pertanyaan: question.pertanyaan,
            opsi_jawaban: question.opsi_jawaban
        });

    });

    // Tambahkan halaman dan pertanyaan ke dalam form
    Object.keys(questionsByPage).forEach(page => {
        // Tambahkan halaman baru
        addPage(questionsByPage[page]);// Pastikan fungsi addPage() sudah ada dan berfungsi

        // Ambil halaman yang baru ditambahkan
        const $currentPage = $('.page-block').last(); // Ambil halaman terakhir yang ditambahkan

        // Tambahkan pertanyaan ke halaman yang sesuai
        questionsByPage[page].forEach(question => {
            addQuestion($currentPage.find('.questions-container'), question.tipe_pertanyaan, {
                halaman: page,
                pertanyaan: question.pertanyaan,
                opsi_jawaban: question.opsi_jawaban
            });
        });
    });
             
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


function addQuestion(pageContainer, type = '', existingData = null) {

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

    if (existingData) {
        $newQuestion.find('input[name="questions[][teks_pertanyaan]"]').val(existingData.pertanyaan);
        $newQuestion.find('select[name="questions[][tipe_pertanyaan]"]').val(type);
        $newQuestion.find('input[name="questions[][halaman]"]').val(existingData.halaman);

        if (type === 'checkbox' || type === 'radio') {
            existingData.opsi_jawaban.forEach(function(optionValue) {
                const $optionContainer = $('<div class="option-container flex items-center mt-2"></div>');
                const $input = $('<input>', {
                    type: type,
                    name: 'questions[][opsi_jawaban][]',
                    class: 'mr-2',
                });
                const $textInput = $('<input>', {
                    type: 'text',
                    name: 'questions[][opsi_jawaban][]',
                    class: 'h-10 border rounded-sm border-gray-300 w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-black',
                    placeholder: 'Masukkan opsi jawaban',
                    value: optionValue // Set nilai yang disimpan
                });

                $optionContainer.append($input).append($textInput);
                $newQuestion.find('.option-group').append($optionContainer);
            });
        }
    } else {
        $newQuestion.find('input[name="questions[][teks_pertanyaan]"]').val('');
        $newQuestion.find('select[name="questions[][tipe_pertanyaan]"]').val(type);
        $newQuestion.find('.options-group').toggleClass('hidden', type !== 'checkbox' && type !== 'radio');
    }

    $newQuestion.find('.question-number').text(`Q${questionCount}`);

    $('.questions-container').sortable({
        items: '.question-container', // Elemen yang dapat disortir
        axis: 'y', // Batasi gerakan hanya pada sumbu vertikal
        // containment: 'parent', // Batasi gerakan dalam kontainer induk
        update: function(event, ui) {
            // Panggil fungsi untuk memperbarui nomor pertanyaan setelah urutan diubah
            updateQuestionNumbers();
        },
        start: function(event, ui) {
            // Set z-index saat drag dimulai
            ui.item.css('z-index', 1000);
        },
        stop: function(event, ui) {
            // Reset z-index setelah drag selesai
            ui.item.css('z-index', '');

            // Reset posisi untuk memastikan tidak ada posisi yang tidak diinginkan
            ui.item.css({
                position: '',
                top: '',
                left: ''
            });
        }
    });

$newQuestion.on('click', handleQuestionClick);

function handleQuestionClick(event) {
    const $target = $(event.target);
    const $questionContainer = $(this);
    if (!$questionContainer.hasClass('editing')) {
        editQuestion($questionContainer);
    }
}


function editQuestion($questionContainer) {
    if ($currentEditingQuestion && $currentEditingQuestion !== $questionContainer) {
        const currentPertanyaan = $currentEditingQuestion.find(
            'input[name="questions[][teks_pertanyaan]"]').val().trim();

        // Jika pertanyaan yang sedang diedit kosong, tampilkan alert dan batalkan pengeditan
        if (!currentPertanyaan) {
            alert('Silakan isi pertanyaan yang sedang diedit terlebih dahulu!');
            $currentEditingQuestion.find('input[name="questions[][teks_pertanyaan]"]').focus();
            return; // Batalkan pengeditan
        }

        // Simulasikan klik tombol save untuk menyimpan pertanyaan yang sedang diedit
           saveQuestion($currentEditingQuestion);

    }

    // Mengaktifkan input untuk opsi jawaban
    $questionContainer.find('.option-group input').prop('disabled', false);

    // Menampilkan pilihan tipe pertanyaan dan opsi jawaban
    $questionContainer.find('.question-type').show();
    $questionContainer.find('.add-option').show();
    
    $questionContainer.addClass('editing'); 

    // Simpan referensi ke pertanyaan yang sedang diedit
    $currentEditingQuestion = $questionContainer;
}

// Fungsi untuk menyimpan pertanyaan
function saveQuestion($questionContainer) {
    const pertanyaan = $questionContainer.find('input[name="questions[][teks_pertanyaan]"]').val().trim();

    // Cek apakah pertanyaan kosong
    if (pertanyaan) {
        // Menyembunyikan pilihan tipe pertanyaan dan opsi jawaban
        $questionContainer.find('.question-type').hide();
        $questionContainer.find('.add-option').hide();
        $questionContainer.removeClass('editing');

        // Reset referensi pertanyaan yang sedang diedit
        $currentEditingQuestion = null;
    } else {
        alert('Pertanyaan tidak boleh kosong!');
        $questionContainer.find('input[name="questions[][teks_pertanyaan]"]').focus();
        return;
    }
}

// Event untuk mengubah tipe pertanyaan
$newQuestion.find('.question-type').on('change', function() {
    const $optionsGroup = $newQuestion.find('.options-group');
    const selectedType = $(this).val();

    // Simpan nilai opsi yang ada
    const existingOptions = [];
    $optionsGroup.find('.option-group .option-container').each(function() {
        const $textInput = $(this).find('input[type="text"]');
        existingOptions.push($textInput.val()); // Simpan nilai input teks
    });

    // Hapus semua opsi yang ada hanya jika tipe yang dipilih adalah checkbox atau radio
    if (['checkbox', 'radio'].includes(selectedType)) {
        $optionsGroup.find('.option-group').empty();
    }

    // Tampilkan grup opsi jika tipe yang dipilih adalah checkbox atau radio
    if (['checkbox', 'radio'].includes(selectedType)) {
        $optionsGroup.removeClass('hidden');

        // Tampilkan tombol "Tambah Opsi"
        $optionsGroup.find('.add-option').removeClass('hidden');

        // Tambahkan opsi berdasarkan nilai yang disimpan
        existingOptions.forEach(function(optionValue) {
            const $optionContainer = $('<div class="option-container flex items-center mt-2"></div>');
            const $input = $('<input>', {
                type: selectedType,
                name: 'questions[][opsi_jawaban][]',
                class: 'mr-2',
            });
            const $textInput = $('<input>', {
                type: 'text',
                name: 'questions[][opsi_jawaban][]',
                class: 'h-10 border rounded-sm border-gray-300 w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-black',
                placeholder: 'Masukkan opsi jawaban',
                value: optionValue // Set nilai yang disimpan
            });

            // Tambahkan input radio/checkbox dan input teks ke dalam kontainer opsi
            $optionContainer.append($input).append($textInput);
            $optionsGroup.find('.option-group').append($optionContainer);
        });
    } else {
        // Jika tipe yang dipilih adalah teks, sembunyikan opsi dan tombol "Tambah Opsi"
        $optionsGroup.addClass('hidden'); // Sembunyikan grup opsi
        $optionsGroup.find('.add-option').addClass('hidden'); // Sembunyikan tombol "Tambah Opsi"
    }
});
// Event untuk menambahkan opsi
$newQuestion.find('.add-option').on('click', function() {
    const selectedType = $newQuestion.find('.question-type').val(); // Ambil tipe pertanyaan yang dipilih
    const $optionContainer = $('<div class="option-container flex items-center mt-2"></div>'); // Kontainer untuk opsi

    // Buat input radio atau checkbox
    const $input = $('<input>', {
        type: selectedType, // Tipe input berdasarkan pilihan
        name: 'questions[][opsi_jawaban][]', // Pastikan nama input sesuai
        class: 'mr-2', // Tambahkan margin kanan untuk jarak
    });

    // Buat input teks untuk opsi jawaban
    const $textInput = $('<input>', {
        type: 'text',
        name: 'questions[][opsi_jawaban][]',
        class: 'h-10 border rounded-sm border-gray-300 w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-black',
        placeholder: 'Masukkan opsi jawaban',
    });

    // Tambahkan input radio/checkbox dan input teks ke dalam kontainer opsi
    $optionContainer.append($input).append($textInput);

    // Tambahkan kontainer opsi ke dalam grup opsi
    $newQuestion.find('.option-group').append($optionContainer);
});

 // Event untuk menghapus pertanyaan
 $newQuestion.find('.remove-question').on('click', function() {
                    $currentEditingQuestion = null;
                    $newQuestion.remove();
                    questionCount--;

                });

    // Tambahkan pertanyaan baru ke dalam kontainer

    updateQuestionNumbers(); // Update question numbers after moving

function updateQuestionNumbers() {
$('.question-container').each(function(index) {
    $(this).find('.question-number').text(`Q${index}`); // Update the question number
});
    if(existingData) {
        pageContainer.append($newQuestion);
        pageContainer.find('.question-type').hide();
    } else {
        pageContainer.find('.questions-container').append($newQuestion);
        $newQuestion.find('.add-option').trigger('click')
    }

   
}

}
    let pageCount = 0; // Counter untuk nomor halaman

function addPage(existingQuestions = []) {
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
    console.log('pageblok length', pageNumber);

    // Tambahkan ID unik ke halaman baru
    const uniqueId = `page-block-${pageNumber}`;
    console.log('uniq',uniqueId);
    $newPage.attr('id', uniqueId); // Set ID untuk page-block
    $newPage.find('.page-number').text(pageNumber);

    // Jika ada pertanyaan yang sudah ada untuk halaman ini, tambahkan ke halaman
    if(existingQuestions) {
    existingQuestions.forEach(question => {
        if (question.halaman == pageNumber) {
            addQuestion($newPage.find('.questions-container'), question.tipe_pertanyaan, {
                halaman: question.halaman,
                pertanyaan: question.pertanyaan,
                opsi_jawaban: question.opsi_jawaban
            });
        }
    });
}

    $newPage.find('.remove-page').on('click', function() {
        const $currentPage = $(this).closest('.page-block'); // Mendapatkan halaman saat ini
        $currentPage.remove(); // Menghapus halaman yang sedang aktif
        updatePageButtons(); // Perbarui tombol setelah menghapus
    });

    $newPage.appendTo($questionSection);
    updatePageButtons(); // Perbarui tombol navigasi setelah menambahkan halaman

    $newPage.show();
}

   // Fungsi untuk memperbarui tombol navigasi halaman
   function updatePageButtons() {
    $('#page-buttons').empty(); // Kosongkan tombol yang ada
    $('.page-block').each(function() {
        const pageNumber = $(this).find('.page-number').text().match(/\d+/)[0]; // Ekstrak nomor halaman
        if (pageNumber) {
            const $button = $('<button>', {
                type: 'button',
                class: 'page-button bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded mr-2',
                text: `Halaman ${pageNumber}`,
            });

            $button.on('click', function() {
                const pageId = `page-block-${pageNumber}`; // Buat ID berdasarkan nomor halaman
                const $targetPage = $(`#${pageId}`); // Temukan blok halaman berdasarkan ID

                // Scroll ke halaman target
                $('html, body').animate({
                    scrollTop: $targetPage.offset().top - 100// Scroll ke atas halaman target
                }, 500); // Durasi animasi scroll dalam milidetik
            });

            $('#page-buttons').append($button); // Tambahkan tombol ke kontainer
        }
    });

    // Menangani visibilitas tombol hapus
    if ($('.page-block').length <= 1) {
        $('.remove-page').hide(); // Sembunyikan tombol hapus jika hanya ada satu halaman
    } else {
        $('.remove-page').show(); // Tampilkan tombol hapus jika ada lebih dari satu halaman
    }
}

            // Event untuk tombol tambah pertanyaan
            $('#add-text-question').on('click', function() {
                addQuestion($currentPage, 'text');
            });

            $('#add-checkbox-question').on('click', function() {
                addQuestion($currentPage, 'checkbox');
            });

            $('#add-radio-question').on('click', function() {
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
                    opsi_jawaban: [], // Tetap kosong jika tidak ingin menghitung checkbox
                    halaman: pageNumber
                };

                // Mengumpulkan nilai dari input teks dalam options-group
                $optionsGroups.eq(index).find('input[type="text"]').each(function() {
                    const opsiJawaban = $(this).val().trim();
                    if (opsiJawaban) {
                        question.opsi_jawaban.push(opsiJawaban);
                    }
                });

                formData.questions.push(question);
            }
        });
    });

    console.log('Data yang akan dikirim:', formData); // Debugging

    const kuesionerId = {{ $kuesioner->id }};
    $.ajax({
        url: `/api/kuesioner/${encodeURIComponent(kuesionerId)}`,
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify(formData),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function(data) {
            if (data.message) {
                alert(data.message);
                // Reset form atau lakukan tindakan lain setelah berhasil
                $('#kuesionerForm')[0].reset();
            } else {
                console.error('Terjadi kesalahan:', data);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim data. Silakan coba lagi.');
        }
    });
});
        });
    </script>
@endsection
