@extends('layouts.admin')

@section('content')
    <div class="">
        <h1 class="text-2xl font-bold mb-4">Buat Kuesioner Baru</h1>

        <form id="kuesionerForm" novalidate>
            @csrf

            <div class="flex">
                <!-- Buttons Section -->
                <div class="w-1/4">
                    <div class="sticky top-15 w-52 p-4 z-10">
                        <h3 class="text-lg font-semibold mb-2">Tambahkan Pertanyaan</h3>
                        <div class="question-types">
                            <div class="space-y-2">
                                <div class="question-type flex items-center justify-between px-4 py-2 bg-blue-100 text-blue-700 rounded-lg shadow hover:bg-blue-200 cursor-pointer transition-transform transform hover:scale-105" 
                                     draggable="true" id="add-text-question">
                                    <span>Tambah Teks</span>
                                    <i class="fas fa-align-left"></i>
                                </div>
                                <div class="question-type flex items-center justify-between px-4 py-2 bg-green-100 text-green-700 rounded-lg shadow hover:bg-green-200 cursor-pointer transition-transform transform hover:scale-105" 
                                     draggable="true" id="add-checkbox-question">
                                    <span>Tambah Checkbox</span>
                                    <i class="fas fa-check-square"></i>
                                </div>
                                <div class="question-type flex items-center justify-between px-4 py-2 bg-purple-100 text-purple-700 rounded-lg shadow hover:bg-purple-200 cursor-pointer transition-transform transform hover:scale-105" 
                                     draggable="true" id="add-radio-question">
                                    <span>Tambah Radio</span>
                                    <i class="fas fa-dot-circle"></i>
                                </div>
                                <div class="question-type flex items-center justify-between px-4 py-2 bg-red-100 text-red-700 rounded-lg shadow hover:bg-red-200 cursor-pointer transition-transform transform hover:scale-105" 
                                     draggable="true" id="add-dropdown-question">
                                    <span>Tambah Dropdown</span>
                                    <i class="fa-solid fa-caret-down"></i>
                                </div>

                                <div class="question-type flex items-center justify-between px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg shadow hover:bg-yellow-200 cursor-pointer transition-transform transform hover:scale-105" 
                                     draggable="true" id="add-rating-question">
                                    <span>Tambah Dropdown</span>
                                    <i class="fa-regular fa-star"></i>
                                </div>
                            </div>
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

                    <div id="page-buttons" class="mb-4 flex sticky overflow-y-auto max-h-40 bg-gray-100 max-w-3xl py-2 px-1 text-sm"></div>

                    <div id="page-template" class="hidden">
                      
                        <div class="page-block mb-4">
                            <div class="page-title">
                                <input type="text" placeholder="Masukkan Judul Halaman" class="w-full h-10 border rounded-sm border-gray-300 px-4">
                            </div>
                            <div class="page-description">
                                <textarea placeholder="Masukkan Deskripsi Halaman" class="w-full h-20 border rounded-sm border-gray-300 px-4"></textarea>
                            </div>
                            <h2 class="text-lg font-semibold mb-3">Page <span class="page-number"></span></h2>
                            <div class="questions-container w-full min-h-96">
                                <div class="drop-area absolute inset-0 flex items-center justify-center">
                                    <span class="drop-text text-gray-500">Klik Pertanyaan atau Seret dan Lepaskan ke Sini</span>
                                </div>
                            </div>
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
                                       
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="mr-3 font-semibold question-number"></div>
                                    <div>
                                        <input
                                            class="h-10 border-b rounded-sm border-gray-300 w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-black"
                                            id="inline-full-name" type="text" name="questions[][teks_pertanyaan]" placeholder="Masukkan pertanyaan" required>
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
                                        <option value="dropdown">Pilihan Dropdown</option>
                                        <option value="rating">Pilihan Rating</option>
                                    </select>
                                </div>
                            </div>
                            <div class="buttons-container hidden">
                                <button type="button"
                                    class="btn btn-danger remove-question bg-red-500 px-2 hover:bg-red-600 text-white font-semibold rounded-full">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <button type="button" class="select-page-button bg-blue-500 text-white px-4 py-2 rounded-lg mt-2">
                                    Atur Logika Pertanyaan
                                </button>

                            </div>
                        </div>

                        <div class="options-group mb-4">
                            <div class="option-group"></div>
                            <div>
                                <button type="button"
                                    class="mt-2 btn btn-secondary add-option bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded hidden">Tambah Opsi</button>
                            </div>
                            <div id="pageSelectModal" class="modal-logic hidden">
                                <div class="modal-content">
                                    <span class="close-button">&times;</span>
                                    <h2>Atur Halaman Tujuan</h2>
                                    <div class="dynamic-options-container"></div>
                                    <button type="button" class="savePageSelection" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script type="module">
        $(document).ready(function() {
            console.log('jQuery version:', jQuery.fn.jquery); // Ini akan menampilkan versi jQuery di konsol
            const $questionSection = $('#questions-section');
            const $questionTemplate = $('#question-template').clone().removeClass('hidden');
            const $pageTemplate = $('#page-template').removeClass('hidden');

            // Hapus elemen template dari DOM
            $pageTemplate.remove();

            let questionCount = 0;
            let $currentEditingQuestion = null; // Variabel untuk menyimpan pertanyaan yang sedang diedit

            let $currentPage = $('.page-block').first();
            let savedChoices = {};
            let dataLogika = [];

            console.log($currentPage);

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
                $newQuestion.find('.options-group').toggleClass('hidden', type !== 'checkbox' && type !== 'radio' && type !== 'dropdown' && type !== 'rating');

                // Update the question number display
    $newQuestion.find('.question-number').text(`Q${questionCount}`);

function updateQuestionNumbers() {
    $('.question-container').each(function(index) {
        $(this).find('.question-number').text(`Q${index}`); // Update the question number
    });
}

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

// Event handler untuk $newQuestion
$newQuestion.on('click', handleQuestionClick);

function handleQuestionClick(event) {
    const $target = $(event.target);
    const $questionContainer = $(this);
    if (!$questionContainer.hasClass('editing')) {
        editQuestion($questionContainer);
    }
}

function editQuestion($questionContainer) {
    console.log("Edit button clicked");
    console.log("Question Container:", $questionContainer);
    console.log("current edit", $currentEditingQuestion);

    if ($currentEditingQuestion && $currentEditingQuestion !== $questionContainer) {
        console.log("Saving current editing question:", $currentEditingQuestion);
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
    $questionContainer.find('.remove-option-button').show();

    
    // Menampilkan pilihan tipe pertanyaan dan opsi jawaban
    $questionContainer.find('.question-type').show();
    $questionContainer.find('.add-option').show();
    
    $questionContainer.addClass('editing'); 

    // Simpan referensi ke pertanyaan yang sedang diedit
    $currentEditingQuestion = $questionContainer;
    console.log("Current Editing Question set to:", $currentEditingQuestion);
}

// Fungsi untuk menyimpan pertanyaan
function saveQuestion($questionContainer) {
    const pertanyaan = $questionContainer.find('input[name="questions[][teks_pertanyaan]"]').val().trim();

    // Cek apakah pertanyaan kosong
    if (pertanyaan) {
        // Menyembunyikan pilihan tipe pertanyaan dan opsi jawaban
        $questionContainer.find('.question-type').hide();
        $questionContainer.find('.add-option').hide();
        $questionContainer.find('.remove-option-button').hide();

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
    if (['checkbox', 'radio', 'dropdown'].includes(selectedType)) {
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

            const $removeOptionButton = $('<button>', {
                    type: 'button',
                    class: 'remove-option-button text-red-500 ml-2',
                    text: 'Hapus',
                });

                $optionContainer.append($textInput, $removeOptionButton);

                // Event listener untuk tombol "Hapus Opsi"
                $removeOptionButton.on('click', function () {
                    $optionContainer.remove();
                });
            $optionsGroup.find('.option-group').append($optionContainer);
        });
    } else if(['dropdown'].includes(selectedType)){
        $optionsGroup.removeClass('hidden');

        // Tampilkan tombol "Tambah Opsi"
        $optionsGroup.find('.add-option').removeClass('hidden');

        // Tambahkan opsi berdasarkan nilai yang disimpan
        existingOptions.forEach(function(optionValue) {
            const $optionContainer = $('<div class="option-container flex items-center mt-2"></div>');
          
            const $textInput = $('<input>', {
                type: 'text',
                name: 'questions[][opsi_jawaban][]',
                class: 'h-10 border rounded-sm border-gray-300 w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-black',
                placeholder: 'Masukkan opsi jawaban',
                value: optionValue // Set nilai yang disimpan
            });

            // Tambahkan input radio/checkbox dan input teks ke dalam kontainer opsi
            $optionContainer.append($textInput);

            const $removeOptionButton = $('<button>', {
                    type: 'button',
                    class: 'remove-option-button text-red-500 ml-2',
                    text: 'Hapus',
                });

                $optionContainer.append($textInput, $removeOptionButton);

                // Event listener untuk tombol "Hapus Opsi"
                $removeOptionButton.on('click', function () {
                    $optionContainer.remove();
                });
            $optionsGroup.find('.option-group').append($optionContainer);
        });
    } else {
        // Jika tipe yang dipilih adalah teks, sembunyikan opsi dan tombol "Tambah Opsi"
        $optionsGroup.addClass('hidden'); // Sembunyikan grup opsi
        $optionsGroup.find('.add-option').addClass('hidden'); // Sembunyikan tombol "Tambah Opsi"
    }
});

if (type === 'radio' || type === 'checkbox' || type === 'dropdown') {
    const $select = $newQuestion.find('.select-page-button');
    $select.on('click', function () {
        const $modal = $(this).closest('.question-container').find('.modal-logic');
        if (!$modal.length) {
            console.error('Modal tidak ditemukan!');
            return;
        }

        const $dynamicOptionsContainer = $modal.find('.dynamic-options-container');
        $dynamicOptionsContainer.empty();

        $newQuestion.find('.option-container').each(function () {
            const $textInput = $(this).find('input[type="text"]');
            const optionValue = $textInput.val();

            if (optionValue) {
                const $optionContainer = $('<div class="option-question-container mb-4"></div>');

                const $label = $('<label>').text(`Pertanyaan untuk "${optionValue}":`);
                const $addQuestionButton = $('<button>', {
                    type: 'button',
                    class: 'add-question-button bg-blue-500 text-white px-4 py-2 rounded-lg mb-2',
                    text: 'Tambah Pertanyaan Baru',
                });

                const $questionsContainer = $('<div class="questions-container"></div>');

                $optionContainer.append($label, $questionsContainer, $addQuestionButton);
                $dynamicOptionsContainer.append($optionContainer);

                const addNewQuestion = () => {
                    const $questionContainer = $('<div class="logic-question-container mb-2"></div>');

                    const $typeSelect = $('<select>', {
                        class: 'question-type-select w-full border border-gray-300 rounded-lg mb-2',
                    });

                    const questionTypes = ['text', 'radio', 'checkbox'];
                    questionTypes.forEach(type => {
                        $typeSelect.append($('<option>', {
                            value: type,
                            text: type.charAt(0).toUpperCase() + type.slice(1),
                        }));
                    });

                    const $questionInput = $('<input>', {
                        type: 'text',
                        class: 'additional-question-input w-full border border-gray-300 rounded-lg mb-2',
                        placeholder: 'Masukkan pertanyaan tambahan...',
                    });

                    const $addOptionButton = $('<button>', {
                        type: 'button',
                        class: 'add-option-button bg-green-500 text-white px-4 py-2 rounded-lg mt-2',
                        text: 'Tambah Opsi',
                    });

                    const $optionsContainer = $('<div class="options-container mb-2"></div>');

                    const $removeQuestionButton = $('<button>', {
                        type: 'button',
                        class: 'remove-question-button text-red-500 ml-2',
                        text: 'Hapus Pertanyaan',
                    });

                    $questionContainer.append($typeSelect, $questionInput, $optionsContainer, $addOptionButton, $removeQuestionButton);
                    $questionsContainer.append($questionContainer);

                    $addOptionButton.on('click', function () {
                        const $optionContainer = $('<div class="option-container flex items-center mt-2"></div>');
                        const $optionInput = $('<input>', {
                            type: 'text',
                            class: 'new-option-input w-full border border-gray-300 rounded-lg py-2 px-4',
                            placeholder: 'Masukkan opsi jawaban...',
                        });

                        const $removeOptionButton = $('<button>', {
                            type: 'button',
                            class: 'remove-option-button text-red-500 ml-2',
                            text: 'Hapus',
                        });

                        $optionContainer.append($optionInput, $removeOptionButton);
                        $optionsContainer.append($optionContainer);

                        $removeOptionButton.on('click', function () {
                            $optionContainer.remove();
                        });
                    });

                    $removeQuestionButton.on('click', function () {
                        $questionContainer.remove();
                    });
                };

                addNewQuestion();

                $addQuestionButton.on('click ', addNewQuestion);
            }
        });

        // Tambahkan event listener untuk tombol "Simpan"
        $modal.find('.savePageSelection').on('click', function () {
            const dataLogika = [];
            const optionContainers = document.querySelectorAll('.option-question-container');

            optionContainers.forEach(optionContainer => {
                const label = optionContainer.querySelector('label').textContent;
                const optionName = label.match(/"([^"]+)"/)[1]; // Ambil teks dalam tanda kutip

                const questions = [];
                const questionContainers = optionContainer.querySelectorAll('.logic-question-container');

                questionContainers.forEach(questionContainer => {
                    const questionType = questionContainer.querySelector('.question-type-select').value;
                    const questionText = questionContainer.querySelector('.additional-question-input').value;

                    const options = [];
                    const optionInputs = questionContainer.querySelectorAll('.options-container .new-option-input');
                    optionInputs.forEach(optionInput => {
                        const optionValue = optionInput.value;
                        if (optionValue) {
                            options.push(optionValue);
                        }
                    });

                    // Tambahkan pertanyaan ke array pertanyaan
                    questions.push({
                        type: questionType,
                        text: questionText,
                        options: options
                    });
                });

                // Tambahkan opsi utama dan pertanyaan terkait ke data
                dataLogika.push({
                    option: optionName,
                    questions: questions
                });
            });

            console.log('Data yang disimpan:', dataLogika);
            $modal.addClass('hidden'); // Tutup modal
        });

        $modal.removeClass('hidden');
        $modal.find('.close-button').off('click').on('click', function () {
            $modal.addClass('hidden');
        });
    });
}

// Event untuk menambahkan opsi
$newQuestion.find('.add-option').on('click', function() {
    const selectedType = $newQuestion.find('.question-type').val(); // Ambil tipe pertanyaan yang dipilih
    const $optionContainer = $('<div class="option-container flex items-center mt-2"></div>'); // Kontainer untuk opsi

    // Buat input teks untuk opsi jawaban
    const $textInput = $('<input>', {
        type: 'text',
        name: 'questions[][opsi_jawaban][]',
        class: 'h-10 border rounded-sm border-gray-300 w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-black',
        placeholder: 'Masukkan opsi jawaban',
    });
    // Buat input radio atau checkbox
    if (selectedType == 'radio' || selectedType == 'checkbox') { 
    const $input = $('<input>', {
        type: selectedType, // Tipe input berdasarkan pilihan
        name: 'questions[][opsi_jawaban][]', // Pastikan nama input sesuai
        class: 'mr-2', // Tambahkan margin kanan untuk jarak
    });

    $optionContainer.append($input).append($textInput);

    } else if (selectedType == 'dropdown') {

    $optionContainer.append($textInput);

    } else if (selectedType == 'rating') {
        console.log(selectedType);
        console.log('ratingigng');
        const $ratingContainer = $('<div class="rating-container"></div>');
            for (let i = 1; i <= 5; i++) { // Misalnya, 5 bintang
                const $star = $('<span class="star" data-value="' + i + '">&#9733;</span>'); // Bintang
                $ratingContainer.append($star);
            }
            console.log('rating container : ',$ratingContainer);
            $optionContainer.append($ratingContainer); // Kosongkan dan tambahkan bintang
    }
   
     // Tambahkan input radio/checkbox dan input teks ke dalam kontainer opsi
     const $removeOptionButton = $('<button>', {
                    type: 'button',
                    class: 'remove-option-button text-red-500 ml-2',
                    text: 'Hapus',
                });

                $optionContainer.append($textInput, $removeOptionButton);

                // Event listener untuk tombol "Hapus Opsi"
                $removeOptionButton.on('click', function () {
                    $optionContainer.remove();
                });


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
                pageContainer.find('.questions-container').append($newQuestion);

                // Sembunyikan teks drop jika ada pertanyaan
    const $dropText = pageContainer.find('.drop-text'); // Ambil elemen teks drop
    const $dropArea =  pageContainer.find('.drop-area');
    if (pageContainer.find('.questions-container').children().length > 0) {
        $dropText.hide(); // Sembunyikan teks drop
        $dropArea.hide(); 
    }


                updateQuestionNumbers(); // Update question numbers after moving

                // Mengaktifkan input pertanyaan dan fokus pada input
                const $inputPertanyaan = $newQuestion.find('input[name="questions[][teks_pertanyaan]"]');
                $inputPertanyaan.focus(); // Fokus pada input

                if (type == 'radio' ||type == 'checkbox' ||type == 'dropdown') {
                    $newQuestion.find('.add-option').trigger('click')
                }
                $newQuestion.find('.add-option').trigger('click')
                // Simulasikan klik tombol edit untuk masuk ke mode edit
                $newQuestion.trigger('click');
            }

            let pageCount = 0; // Counter untuk nomor halaman

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

    // Tambahkan ID unik ke halaman baru
    const uniqueId = `page-block-${pageNumber}`;
    $newPage.attr('id', uniqueId); // Set ID untuk page-block
    $newPage.find('.page-number').text(pageNumber);

    $newPage.find('.remove-page').on('click', function() {
        const $currentPage = $(this).closest('.page-block'); // Mendapatkan halaman saat ini
        const $previousTemplate = $currentPage.parent().prev('#page-template'); // Mencari template sebelumnya
        const $previousPage = $previousTemplate.find('.page-block'); // Mencari page-block di dalam template sebelumnya
        const $parentTemplate = $currentPage.parent();

        if ($previousPage.length) {
            // Tampilkan halaman sebelumnya
            $previousPage.show(); // Menampilkan halaman sebelumnya
            $parentTemplate.remove(); // Menghilangkan template halaman saat ini
            updatePageButtons();
            console.log("Halaman sebelumnya ditemukan:", $previousPage);
        } else {
            // Tidak ada halaman sebelumnya, lakukan tindakan alternatif
            console.log("Tidak ada halaman sebelumnya.");
        }

        // Menghapus halaman saat ini
        $currentPage.remove(); // Menghapus halaman yang sedang aktif
        updatePageButtons(); // Perbarui tombol setelah menghapus
    });

    $newPage.appendTo($questionSection);
    // addQuestion($newPage, 'text'); // Jika Anda memiliki logika untuk menambahkan pertanyaan
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
                class: 'page-button bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded mr-2 min-w-[100px]',
                text: `Page ${pageNumber}`,
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

            // Tambahkan halaman pertama saat halaman dimuat
            addPage();

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

            $('#add-dropdown-question').on('click', function() {
                addQuestion($currentPage, 'dropdown');
            });

            $('#add-rating-question').on('click', function() {
                addQuestion($currentPage, 'rating');
            });

            $('#add-page').on('click', function() {
                addPage();
            });

            updateCurrentPage();

            $('#kuesionerForm').on('submit', function(event) {
    event.preventDefault();
    function generateUniqueId() {
    // Mendapatkan timestamp dalam milidetik
    const timestamp = Date.now();

    // Membuat string acak 3-byte dalam bentuk heksadesimal
    const randomBytes = Array.from(crypto.getRandomValues(new Uint8Array(3)))
        .map(byte => byte.toString(16).padStart(2, '0'))
        .join('');

    // Menggabungkan prefix, timestamp, dan random string
    return `k${timestamp}${randomBytes}`;
}
    
    let formData = {
        judul_kuesioner: $('#judul_kuesioner').val(),
        kuesioner_id: generateUniqueId(),
        pages: [], // Menyimpan data halaman
        questions: [],
        logics: [],
    };

    const generatePageId = () => {
    return 'p' + Date.now().toString().slice(-6); // Menghasilkan ID dengan awalan 'p' dan 6 digit terakhir dari timestamp
    };
    const generateQuestionId = () => {
    return 'q' + Date.now().toString().slice(-7); // Menghasilkan ID dengan awalan 'p' dan 6 digit terakhir dari timestamp
    };
    const generateQuestionLogicId = () => {
    return 'ql' + Date.now().toString().slice(-6); // Menghasilkan ID dengan awalan 'p' dan 6 digit terakhir dari timestamp
    };
    let counter = 1;

    $('.page-block').each(function() {
        const $page = $(this);
        const pageNumber = $page.find('.page-number').text();
        console.log($page.find('.page-title'));
        const pageTitle = $page.find('.page-title input').val().trim(); // Ambil judul halaman
        const pageDescription = $page.find('.page-description textarea').val().trim(); // Ambil deskripsi halaman

        // Simpan data halaman
        formData.pages.push({
            halaman_id: generatePageId(),  // Anda bisa menggunakan pageNumber sebagai ID halaman
            judul_halaman: pageTitle,
            deskripsi_halaman: pageDescription,
        });

        const halamanId = formData.pages[formData.pages.length - 1].halaman_id

        const $questions = $page.find('input[name="questions[][teks_pertanyaan]"]');
        const $types = $page.find('select[name="questions[][tipe_pertanyaan]"]');
        const $optionsGroups = $page.find('.options-group');

        console.log('tipe :',$types);
        $questions.each(function(index) {
            const teksPertanyaan = $(this).val().trim();
            if (teksPertanyaan) {
                const question = {
                    kode_pertanyaan: generateQuestionId(),
                    teks_pertanyaan: teksPertanyaan,
                    tipe_pertanyaan: $types.eq(index).val(),
                    opsi_jawaban: [],
                    halaman_id:  halamanId,
                };

                // Ambil opsi jawaban
                $optionsGroups.eq(index).find('.option-group .option-container').each(function() {
                    const opsiJawaban = $(this).find('input[type="text"]').val().trim();
                    if (opsiJawaban) {
                        question.opsi_jawaban.push({ opsiJawaban });
                    }
                });

                // Ambil logika dan buat pertanyaan baru
                $optionsGroups.eq(index).find('.option-question-container').each(function() {
                    const optionName = $(this).find('label').text().match(/"([^"]+)"/)[1];
                    const questions1 = [];

                    $(this).find('.logic-question-container').each(function() {
                        const questionType = $(this).find('.question-type-select').val();
                        const questionText = $(this).find('.additional-question-input').val().trim();

                        const options = [];
                        $(this).find('.options-container .new-option-input').each(function() {
                            const optionValue = $(this).val().trim();
                            if (optionValue) {
                                options.push(optionValue);
                            }
                        });

                        counter += 10;
                        if (questionText) {
                            formData.logics.push({
                                id: generateQuestionLogicId() + counter, // Menghasilkan kode_pertanyaan
                                pertanyaan_id: question.kode_pertanyaan,
                                option_name: optionName,
                                tipe_pertanyaan: questionType,
                                teks_pertanyaan: questionText,
                                opsi_jawaban: options,
                            });
                        }
                    });

                    // Tambahkan pertanyaan baru ke formData.questions
                    // if (optionName) {
                    //     questions1.forEach(newQuestion => {
                    //         formData.questions.push(newQuestion);
                    //     });
                    // }
                });

                // Tambahkan pertanyaan utama ke formData.questions
                formData.questions.push(question);
                console.log(formData);
                console.log(formData.questions);
                console.log(formData.pages);

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
