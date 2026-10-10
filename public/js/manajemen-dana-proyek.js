document.addEventListener('DOMContentLoaded', () => {

    // =====================================================
    // KONFIGURASI
    // =====================================================

    const config = window.manajemenDanaProyekConfig || {};



    // =====================================================
    // HELPER — FORMAT RUPIAH
    // =====================================================

    function formatRupiah(angka) {

        const nilai = Number(angka) || 0;

        return new Intl.NumberFormat('id-ID').format(nilai);
    }



    // =====================================================
    // MODAL — HELPER
    // =====================================================

    function bukaModal(modalId) {

        const modal = document.getElementById(modalId);

        if (!modal) {

            console.error(
                `Modal "${modalId}" tidak ditemukan.`
            );

            return;
        }


        // Tutup modal lain yang sedang terbuka
        document
            .querySelectorAll('[data-modal="true"]')
            .forEach(modalLain => {

                if (modalLain.id !== modalId) {

                    modalLain.classList.add('hidden');
                    modalLain.classList.remove('flex');

                    modalLain.setAttribute(
                        'aria-hidden',
                        'true'
                    );
                }

            });


        modal.classList.remove('hidden');
        modal.classList.add('flex');

        modal.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.classList.add(
            'overflow-hidden'
        );
    }



    function tutupModal(modalId) {

        const modal =
            document.getElementById(modalId);

        if (!modal) {
            return;
        }


        modal.classList.add('hidden');

        modal.classList.remove('flex');

        modal.setAttribute(
            'aria-hidden',
            'true'
        );


        // Cek apakah masih ada modal lain yang terbuka
        const masihAdaModal =
            document.querySelector(
                '[data-modal="true"]:not(.hidden)'
            );


        if (!masihAdaModal) {

            document.body.classList.remove(
                'overflow-hidden'
            );
        }
    }



    // =====================================================
    // MODAL — TERMIN KLIEN
    // =====================================================

    window.bukaModalPemasukan = function () {

        bukaModal(
            'modalCatatPemasukan'
        );
    };


    window.tutupModalPemasukan = function () {

        tutupModal(
            'modalCatatPemasukan'
        );
    };



    // =====================================================
    // MODAL — BIAYA LAPANGAN
    // =====================================================

    window.bukaModalPengeluaran = function () {

        bukaModal(
            'modalCatatPengeluaran'
        );
    };


    window.tutupModalPengeluaran = function () {

        tutupModal(
            'modalCatatPengeluaran'
        );
    };



    // =====================================================
    // MODAL — KASBON
    // =====================================================

    window.bukaModalKasbon = function () {

        bukaModal(
            'modalCatatKasbon'
        );
    };


    window.tutupModalKasbon = function () {

        tutupModal(
            'modalCatatKasbon'
        );
    };



    // =====================================================
    // MODAL — BAYAR UPAH
    // =====================================================

    window.bukaModalGaji = function () {

        bukaModal(
            'modalGaji'
        );
    };


    window.tutupModalGaji = function () {

        tutupModal(
            'modalGaji'
        );
    };



    // =====================================================
    // TUTUP MODAL DENGAN ESC
    // =====================================================

    document.addEventListener(
        'keydown',
        event => {

            if (event.key !== 'Escape') {
                return;
            }


            const modalAktif =
                document.querySelector(
                    '[data-modal="true"]:not(.hidden)'
                );


            if (!modalAktif) {
                return;
            }


            tutupModal(
                modalAktif.id
            );
        }
    );



    // =====================================================
    // FILTER BUKU KAS
    // =====================================================

    const searchInput =
        document.getElementById(
            'cariTransaksi'
        );

    const filterInput =
        document.getElementById(
            'filterTipeTransaksi'
        );


    function filterBukuKas() {

        if (
            !searchInput ||
            !filterInput
        ) {
            return;
        }


        const keyword =
            searchInput.value
                .toLowerCase()
                .trim();


        const tipeFilter =
            filterInput.value
                .toLowerCase();


        const rows =
            document.querySelectorAll(
                '#tabelBukuKas .row-transaksi'
            );


        let adaHasil = false;


        rows.forEach(row => {

            const keteranganElement =
                row.querySelector(
                    '.cell-keterangan'
                );


            const keterangan =
                keteranganElement
                    ? keteranganElement.textContent
                        .toLowerCase()
                    : '';


            const tipe =
                row.getAttribute(
                    'data-tipe'
                ) || '';


            const cocokKeyword =
                keterangan.includes(
                    keyword
                );


            const cocokTipe =
                tipeFilter === '' ||
                tipe === tipeFilter;


            const tampil =
                cocokKeyword &&
                cocokTipe;


            row.style.display =
                tampil
                    ? ''
                    : 'none';


            if (tampil) {
                adaHasil = true;
            }

        });


        const pesanKosong =
            document.getElementById(
                'pesanTransaksiKosong'
            );


        if (pesanKosong) {

            pesanKosong.classList.toggle(
                'hidden',
                adaHasil
            );
        }
    }



    // =====================================================
    // EVENT FILTER
    // =====================================================

    if (searchInput) {

        searchInput.addEventListener(
            'input',
            filterBukuKas
        );
    }


    if (filterInput) {

        filterInput.addEventListener(
            'change',
            filterBukuKas
        );
    }


    // Tetap tersedia jika dibutuhkan oleh Blade
    window.filterBukuKas =
        filterBukuKas;



    // =====================================================
    // AJAX — PREVIEW GAJI
    // =====================================================

    const formCekGaji =
        document.getElementById(
            'formCekGaji'
        );


    if (formCekGaji) {

        formCekGaji.addEventListener(
            'submit',
            async event => {

                event.preventDefault();


                const btnCek =
                    document.getElementById(
                        'btnCekGaji'
                    );


                const areaHasil =
                    document.getElementById(
                        'areaHasilGaji'
                    );


                if (
                    !btnCek ||
                    !areaHasil
                ) {
                    return;
                }


                const textLama =
                    btnCek.innerHTML;


                // Disable tombol
                btnCek.disabled = true;

                btnCek.innerHTML =
                    'Menghitung...';


                // Sembunyikan hasil lama
                areaHasil.classList.add(
                    'hidden'
                );


                const formData =
                    new FormData(
                        formCekGaji
                    );


                try {

                    const response =
                        await fetch(
                            config.previewGajiUrl,
                            {
                                method: 'POST',

                                body: formData,

                                headers: {
                                    'X-Requested-With':
                                        'XMLHttpRequest',

                                    'Accept':
                                        'application/json'
                                }
                            }
                        );


                    const result =
                        await response.json();


                    // =============================================
                    // RESPONSE ERROR
                    // =============================================

                    if (
                        !response.ok ||
                        result.status !== 'success'
                    ) {

                        const message =
                            result.message ||
                            'Data gaji tidak dapat dihitung.';


                        tampilkanError(
                            message
                        );

                        return;
                    }


                    // =============================================
                    // DATA BERHASIL
                    // =============================================

                    const data =
                        result.data || {};


                    // =============================================
                    // NAMA PEGAWAI
                    // =============================================

                    const labelNamaPegawai =
                        document.getElementById(
                            'labelNamaPegawai'
                        );


                    if (
                        labelNamaPegawai &&
                        data.nama_pegawai
                    ) {

                        labelNamaPegawai.innerText =
                            data.nama_pegawai;
                    }


                    // =============================================
                    // KEHADIRAN
                    // =============================================

                    const txtHariFull =
                        document.getElementById(
                            'txtHariFull'
                        );


                    if (txtHariFull) {

                        txtHariFull.innerText =
                            data.hari_full ?? 0;
                    }


                    const txtHariSetengah =
                        document.getElementById(
                            'txtHariSetengah'
                        );


                    if (txtHariSetengah) {

                        txtHariSetengah.innerText =
                            data.hari_setengah ?? 0;
                    }


                    // =============================================
                    // UPAH KOTOR
                    // =============================================

                    const txtUpahKotor =
                        document.getElementById(
                            'txtUpahKotor'
                        );


                    if (txtUpahKotor) {

                        txtUpahKotor.innerText =
                            'Rp ' +
                            formatRupiah(
                                data.total_upah_kotor
                            );
                    }


                    // =============================================
                    // POTONGAN KASBON
                    // =============================================

                    const txtPotonganKasbon =
                        document.getElementById(
                            'txtPotonganKasbon'
                        );


                    if (txtPotonganKasbon) {

                        txtPotonganKasbon.innerText =
                            '- Rp ' +
                            formatRupiah(
                                data.total_potongan_kasbon
                            );
                    }


                    // =============================================
                    // GAJI BERSIH
                    // =============================================

                    const txtGajiBersih =
                        document.getElementById(
                            'txtGajiBersih'
                        );


                    if (txtGajiBersih) {

                        txtGajiBersih.innerText =
                            'Rp ' +
                            formatRupiah(
                                data.estimasi_gaji_bersih
                            );
                    }


                    // =============================================
                    // NOMINAL YANG DIBAYAR
                    // =============================================

                    const inputNominalDibayar =
                        document.getElementById(
                            'inputNominalDibayar'
                        );


                    if (inputNominalDibayar) {

                        inputNominalDibayar.value =
                            Math.max(
                                0,
                                Number(
                                    data.estimasi_gaji_bersih
                                ) || 0
                            );
                    }


                    // =============================================
                    // DATA FINAL PAYMENT
                    // =============================================

                    const finalPegawaiId =
                        document.getElementById(
                            'finalPegawaiId'
                        );


                    if (finalPegawaiId) {

                        finalPegawaiId.value =
                            formData.get(
                                'pegawai_id'
                            ) || '';
                    }


                    const finalStartDate =
                        document.getElementById(
                            'finalStartDate'
                        );


                    if (finalStartDate) {

                        finalStartDate.value =
                            formData.get(
                                'start_date'
                            ) || '';
                    }


                    const finalEndDate =
                        document.getElementById(
                            'finalEndDate'
                        );


                    if (finalEndDate) {

                        finalEndDate.value =
                            formData.get(
                                'end_date'
                            ) || '';
                    }


                    const finalEstimasiSistem =
                        document.getElementById(
                            'finalEstimasiSistem'
                        );


                    if (finalEstimasiSistem) {

                        finalEstimasiSistem.value =
                            data.estimasi_gaji_bersih ?? 0;
                    }


                    const finalIdAbsensi =
                        document.getElementById(
                            'finalIdAbsensi'
                        );


                    if (finalIdAbsensi) {

                        finalIdAbsensi.value =
                            data.id_absensi ?? '';
                    }


                    // =============================================
                    // TAMPILKAN HASIL
                    // =============================================

                    areaHasil.classList.remove(
                        'hidden'
                    );

                } catch (error) {

                    console.error(
                        'Preview gaji error:',
                        error
                    );


                    tampilkanError(
                        'Gagal mengambil data gaji. Silakan coba lagi.'
                    );

                } finally {

                    btnCek.innerHTML =
                        textLama;

                    btnCek.disabled =
                        false;
                }
            }
        );
    }

    // =====================================================
    // ERROR HANDLER
    // =====================================================

    function tampilkanError(message) {

        // Gunakan SweetAlert jika tersedia
        if (
            typeof window.Swal !== 'undefined'
        ) {

            window.Swal.fire({
                icon: 'error',
                title: 'Tidak dapat memproses',
                text: message,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#0f172a'
            });

            return;
        }
        // Fallback jika SweetAlert belum tersedia
        window.alert(message);
    }

    // =====================================================
// SWEETALERT — SESSION & VALIDASI LARAVEL
// =====================================================

if (typeof window.Swal !== 'undefined') {

    // Sukses (otomatis hilang dalam 2,5 detik)
    if (config.successMessage) {
        window.Swal.fire({
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

    // Error dari controller (session 'error')
    if (config.errorMessage) {
        window.Swal.fire({
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

    // Error validasi form
    if (
        config.hasValidationErrors &&
        Array.isArray(config.validationErrors) &&
        config.validationErrors.length
    ) {
        window.Swal.fire({
            icon: 'error',
            title: 'Validasi gagal',
            html: config.validationErrors
                .map(pesan => `<div>${pesan}</div>`)
                .join(''),
            confirmButtonColor: '#0f172a',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl'
            }
        });
    }
}

});