/**
 * =========================================================
 * MANAJEMEN PROYEK
 * =========================================================
 */


/**
 * Preview gambar proyek
 */
function previewImage(input) {

    const previewContainer =
        document.getElementById('preview-container');

    const imgPreview =
        document.getElementById('img-preview');

    const placeholderContainer =
        document.getElementById('placeholder-container');

    const fileNameText =
        document.getElementById('file-name');


    if (!input.files || !input.files[0]) {
        return;
    }


    const file = input.files[0];


    // Validasi tipe file
    if (!file.type.startsWith('image/')) {

        input.value = '';

        previewContainer.classList.add('hidden');
        placeholderContainer.classList.remove('hidden');

        fileNameText.textContent =
            'PNG atau JPG, maksimal 2MB';

        fileNameText.classList.remove(
            'font-semibold',
            'text-amber-600'
        );


        if (typeof Swal !== 'undefined') {

            Swal.fire({
                icon: 'warning',
                title: 'File tidak valid',
                text: 'Silakan pilih file gambar.',
                confirmButtonColor: '#0f172a'
            });

        }

        return;
    }


    // Validasi ukuran maksimal 2MB
    if (file.size > 2 * 1024 * 1024) {

        input.value = '';

        previewContainer.classList.add('hidden');
        placeholderContainer.classList.remove('hidden');

        fileNameText.textContent =
            'PNG atau JPG, maksimal 2MB';

        fileNameText.classList.remove(
            'font-semibold',
            'text-amber-600'
        );


        if (typeof Swal !== 'undefined') {

            Swal.fire({
                icon: 'warning',
                title: 'Ukuran file terlalu besar',
                text: 'Ukuran gambar maksimal 2MB.',
                confirmButtonColor: '#0f172a'
            });

        }

        return;
    }


    const reader = new FileReader();


    reader.onload = function (event) {

        imgPreview.src =
            event.target.result;

        previewContainer.classList.remove('hidden');

        placeholderContainer.classList.add('hidden');

    };


    reader.readAsDataURL(file);


    fileNameText.textContent =
        'Terpilih: ' + file.name;

    fileNameText.classList.add(
        'font-semibold',
        'text-amber-600'
    );
}


/**
 * Toggle modal
 */
function toggleModal(modalId) {

    const modal =
        document.getElementById(modalId);


    if (!modal) {
        return;
    }


    const isOpening =
        modal.classList.contains('hidden');


    if (isOpening) {

        modal.classList.remove('hidden');

        modal.classList.add('flex');

        modal.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.classList.add(
            'overflow-hidden'
        );


        setTimeout(function () {

            const firstInput =
                modal.querySelector(
                    'input:not([type="file"]), select'
                );

            if (firstInput) {
                firstInput.focus();
            }

        }, 100);


    } else {

        modal.classList.add('hidden');

        modal.classList.remove('flex');

        modal.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.classList.remove(
            'overflow-hidden'
        );

    }
}


/**
 * Tutup modal dengan tombol Escape
 */
document.addEventListener(
    'keydown',
    function (event) {

        if (event.key !== 'Escape') {
            return;
        }


        const modal =
            document.getElementById(
                'modalTambahProyek'
            );


        if (
            modal &&
            !modal.classList.contains('hidden')
        ) {

            toggleModal(
                'modalTambahProyek'
            );

        }

    }
);


/**
 * =========================================================
 * LARAVEL SESSION & VALIDATION
 * =========================================================
 */

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const config =
            window.proyekConfig || {};


        /**
         * Buka kembali modal jika validasi gagal
         */
        if (config.hasValidationErrors) {

            toggleModal(
                'modalTambahProyek'
            );

        }


        /**
         * SweetAlert Success
         */
        if (
            config.successMessage &&
            typeof Swal !== 'undefined'
        ) {

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: config.successMessage,
                confirmButtonColor: '#0f172a',
                timer: 2500,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl'
                }
            });

        }


        /**
         * SweetAlert Error
         */
        if (
            config.errorMessage &&
            typeof Swal !== 'undefined'
        ) {

            Swal.fire({
                icon: 'error',
                title: 'Tidak dapat diproses',
                text: config.errorMessage,
                confirmButtonColor: '#0f172a',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl'
                }
            });

        }

    }
);