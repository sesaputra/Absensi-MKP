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
    // HELPER — ERROR HANDLER
    // =====================================================

    function tampilkanError(message) {
        if (typeof window.Swal !== 'undefined') {
            window.Swal.fire({
                icon: 'error',
                title: 'Tidak dapat memproses',
                text: message,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#0f172a'
            });

            return;
        }

        window.alert(message);
    }


    // =====================================================
    // MODAL — HELPER
    // =====================================================

    function bukaModal(modalId) {
        const modal = document.getElementById(modalId);

        if (!modal) {
            console.error(`Modal "${modalId}" tidak ditemukan.`);
            return;
        }

        // Tutup modal lain yang sedang terbuka
        document.querySelectorAll('[data-modal="true"]')
            .forEach(modalLain => {
                if (modalLain.id !== modalId) {
                    modalLain.classList.add('hidden');
                    modalLain.classList.remove('flex');
                    modalLain.setAttribute('aria-hidden', 'true');
                }
            });

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');

        document.body.classList.add('overflow-hidden');
    }


    function tutupModal(modalId) {
        const modal = document.getElementById(modalId);

        if (!modal) {
            return;
        }

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.setAttribute('aria-hidden', 'true');

        // Cek apakah masih ada modal lain yang terbuka
        const masihAdaModal = document.querySelector(
            '[data-modal="true"]:not(.hidden)'
        );

        if (!masihAdaModal) {
            document.body.classList.remove('overflow-hidden');
        }
    }


    // =====================================================
    // MODAL — TERMIN KLIEN
    // =====================================================

    window.bukaModalPemasukan = function () {
        bukaModal('modalCatatPemasukan');
    };

    window.tutupModalPemasukan = function () {
        tutupModal('modalCatatPemasukan');
    };


    // =====================================================
    // MODAL — BIAYA LAPANGAN
    // =====================================================

    window.bukaModalPengeluaran = function () {
        bukaModal('modalCatatPengeluaran');
    };

    window.tutupModalPengeluaran = function () {
        tutupModal('modalCatatPengeluaran');
    };


    // =====================================================
    // MODAL — KASBON
    // =====================================================

    window.bukaModalKasbon = function () {
        bukaModal('modalCatatKasbon');
    };

    window.tutupModalKasbon = function () {
        tutupModal('modalCatatKasbon');
    };


    // =====================================================
    // MODAL — BAYAR UPAH
    // =====================================================

    window.bukaModalGaji = function () {
        bukaModal('modalGaji');
    };

    window.tutupModalGaji = function () {
        tutupModal('modalGaji');
    };


    // =====================================================
    // MODAL — EDIT TRANSAKSI
    // =====================================================

window.bukaModalEditTransaksi = function (button) {
    const modal = document.getElementById('modalEditTransaksi');
    const form = document.getElementById('formEditTransaksi');

    if (!modal || !form || !button) {
        return;
    }

    const data = button.dataset;

    const tipe = document.getElementById('editTipeTransaksi');
    const kategori = document.getElementById('editKategoriTransaksi');
    const wrapperKategori = document.getElementById('wrapperKategoriTransaksi');

    // Atur URL transaksi yang akan diperbarui.
    form.action = data.url;

    // Isi jenis transaksi.
    tipe.value = data.tipe || '';

    if (data.tipe === 'Pemasukan') {
        // Sembunyikan seluruh kolom kategori untuk pemasukan.
        wrapperKategori.classList.add('hidden');

        // Pemasukan selalu menggunakan kategori Termin Klien.
        kategori.value = 'Termin Klien';
        kategori.removeAttribute('required');
    } else {
        // Tampilkan kolom kategori untuk pengeluaran.
        wrapperKategori.classList.remove('hidden');

        kategori.value = data.kategori || '';
        kategori.setAttribute('required', 'required');
    }

    // Isi nominal.
    const nominal = document.getElementById('editNominalTransaksi');

nominal.value = data.nominal
    ? Math.round(Number(data.nominal.replace(',', '.'))).toString()
    : '';

    // Isi tanggal.
    document.getElementById('editTanggalTransaksi').value =
        data.tanggal || '';

    // Isi keterangan.
    document.getElementById('editKeteranganTransaksi').value =
        data.keterangan || '';

    // Kosongkan bukti transaksi sebelumnya.
    document.getElementById('editBuktiTransaksi').value = '';

    // Tampilkan modal.
    bukaModal('modalEditTransaksi');

    // Fokus pada field yang relevan.
    if (data.tipe === 'Pengeluaran') {
        kategori.focus();
    } else {
        document.getElementById('editNominalTransaksi').focus();
    }
};

    window.tutupModalEditTransaksi = function () {
        tutupModal('modalEditTransaksi');
    };


    // =====================================================
    // TUTUP MODAL DENGAN ESC
    // =====================================================

    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') {
            return;
        }

        const modalAktif = document.querySelector(
            '[data-modal="true"]:not(.hidden)'
        );

        if (!modalAktif) {
            return;
        }

        tutupModal(modalAktif.id);
    });


    // =====================================================
    // FILTER BUKU KAS
    // =====================================================

    const searchInput = document.getElementById('cariTransaksi');
    const filterInput = document.getElementById('filterTipeTransaksi');


    function filterBukuKas() {
        if (!searchInput || !filterInput) {
            return;
        }

        const keyword = searchInput.value.toLowerCase().trim();
        const tipeFilter = filterInput.value.toLowerCase();

        const rows = document.querySelectorAll(
            '#tabelBukuKas .row-transaksi'
        );

        let adaHasil = false;

        rows.forEach(row => {
            const keteranganElement = row.querySelector(
                '.cell-keterangan'
            );

            const keterangan = keteranganElement
                ? keteranganElement.textContent.toLowerCase()
                : '';

            const tipe = (
                row.getAttribute('data-tipe') || ''
            ).toLowerCase();

            const cocokKeyword = keterangan.includes(keyword);

            const cocokTipe =
                tipeFilter === '' || tipe === tipeFilter;

            const tampil = cocokKeyword && cocokTipe;

            row.style.display = tampil ? '' : 'none';

            if (tampil) {
                adaHasil = true;
            }
        });

        const pesanKosong = document.getElementById(
            'pesanTransaksiKosong'
        );

        if (pesanKosong) {
            pesanKosong.classList.toggle('hidden', adaHasil);
        }
    }


    // =====================================================
    // EVENT FILTER
    // =====================================================

    if (searchInput) {
        searchInput.addEventListener('input', filterBukuKas);
    }

    if (filterInput) {
        filterInput.addEventListener('change', filterBukuKas);
    }


    // =====================================================
    // BUKTI PEMBAYARAN — REFERENSI ELEMEN
    // =====================================================

    const formBayarGaji =
        document.getElementById('formBayarGaji');

    const inputBuktiFileGaji =
        document.getElementById('inputBuktiFileGaji');

    const namaBuktiFileGaji =
        document.getElementById('namaBuktiFileGaji');

    const teksNamaBuktiFileGaji =
        document.getElementById('teksNamaBuktiFileGaji');

    const pratinjauBuktiGaji =
        document.getElementById('pratinjauBuktiGaji');

    const gambarBuktiGaji =
        document.getElementById('gambarBuktiGaji');

    const btnKonfirmasiGaji =
        document.getElementById('btnKonfirmasiGaji');

    let urlPratinjauBuktiGaji = null;


    // =====================================================
    // RESET BUKTI PEMBAYARAN
    // =====================================================

    function resetBuktiPembayaran() {
        if (urlPratinjauBuktiGaji) {
            URL.revokeObjectURL(urlPratinjauBuktiGaji);
            urlPratinjauBuktiGaji = null;
        }

        if (inputBuktiFileGaji) {
            inputBuktiFileGaji.value = '';
        }

        if (teksNamaBuktiFileGaji) {
            teksNamaBuktiFileGaji.textContent = '';
        }

        if (namaBuktiFileGaji) {
            namaBuktiFileGaji.classList.add('hidden');
            namaBuktiFileGaji.classList.remove('flex');
        }

        if (gambarBuktiGaji) {
            gambarBuktiGaji.removeAttribute('src');
        }

        if (pratinjauBuktiGaji) {
            pratinjauBuktiGaji.classList.add('hidden');
        }
    }


    // =====================================================
    // AJAX — PREVIEW GAJI
    // =====================================================

    const formCekGaji = document.getElementById('formCekGaji');

    if (formCekGaji) {
        formCekGaji.addEventListener('submit', async event => {
            event.preventDefault();

            const btnCek = document.getElementById('btnCekGaji');

            const areaHasil = document.getElementById(
                'areaHasilGaji'
            );

            if (!btnCek || !areaHasil) {
                console.error(
                    'Tombol cek gaji atau area hasil gaji tidak ditemukan.'
                );
                return;
            }

            // Pastikan URL preview tersedia
            if (!config.previewGajiUrl) {
                tampilkanError(
                    'URL preview gaji belum dikonfigurasi. Periksa konfigurasi manajemenDanaProyekConfig.'
                );
                return;
            }

            const textLama = btnCek.innerHTML;

            btnCek.disabled = true;
            btnCek.innerHTML = 'Menghitung...';

            // Sembunyikan hasil lama
            areaHasil.classList.add('hidden');

            const formData = new FormData(formCekGaji);

            try {
                const response = await fetch(
                    config.previewGajiUrl,
                    {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    }
                );

                let result;

                try {
                    result = await response.json();
                } catch (parseError) {
                    throw new Error(
                        'Server mengirim response yang tidak valid. Periksa route dan controller preview gaji.'
                    );
                }

                // =============================================
                // RESPONSE ERROR
                // =============================================

                if (
                    !response.ok ||
                    result.success !== true ||
                    !result.data
                ) {
                    let message =
                        result.message ||
                        'Data gaji tidak dapat dihitung.';

                    if (
                        !result.message &&
                        result.errors
                    ) {
                        message = Object.values(result.errors)
                            .flat()
                            .join('\n');
                    }

                    tampilkanError(message);
                    return;
                }

                // =============================================
                // DATA BERHASIL
                // =============================================

                const data = result.data;

                // Kehadiran penuh
                const txtHariFull =
                    document.getElementById('txtHariFull');

                if (txtHariFull) {
                    txtHariFull.innerText = data.hari_full ?? 0;
                }

                // Kehadiran setengah hari
                const txtHariSetengah =
                    document.getElementById('txtHariSetengah');

                if (txtHariSetengah) {
                    txtHariSetengah.innerText =
                        data.hari_setengah ?? 0;
                }

                // Upah kotor
                const txtUpahKotor =
                    document.getElementById('txtUpahKotor');

                if (txtUpahKotor) {
                    txtUpahKotor.innerText =
                        'Rp ' + formatRupiah(data.upah_kotor);
                }

                // Potongan kasbon
                const txtPotonganKasbon =
                    document.getElementById('txtPotonganKasbon');

                if (txtPotonganKasbon) {
                    txtPotonganKasbon.innerText =
                        '- Rp ' + formatRupiah(data.potongan_kasbon);
                }

                // Gaji bersih
                const txtGajiBersih =
                    document.getElementById('txtGajiBersih');

                if (txtGajiBersih) {
                    txtGajiBersih.innerText =
                        'Rp ' + formatRupiah(data.upah_bersih);
                }

                // Nominal yang dibayar (default = gaji bersih)
                const inputNominalDibayar =
                    document.getElementById('inputNominalDibayar');

                if (inputNominalDibayar) {
                    inputNominalDibayar.value = Math.max(
                        0,
                        Number(data.upah_bersih) || 0
                    );
                }

                // Data final payment
                const finalPegawaiId =
                    document.getElementById('finalPegawaiId');

                if (finalPegawaiId) {
                    finalPegawaiId.value =
                        data.pegawai_id ??
                        formData.get('pegawai_id') ??
                        '';
                }

                const finalStartDate =
                    document.getElementById('finalStartDate');

                if (finalStartDate) {
                    finalStartDate.value =
                        formData.get('start_date') || '';
                }

                const finalEndDate =
                    document.getElementById('finalEndDate');

                if (finalEndDate) {
                    finalEndDate.value =
                        formData.get('end_date') || '';
                }

                const finalEstimasiSistem =
                    document.getElementById('finalEstimasiSistem');

                if (finalEstimasiSistem) {
                    finalEstimasiSistem.value =
                        Number(data.upah_bersih) || 0;
                }

                const finalIdAbsensi =
                    document.getElementById('finalIdAbsensi');

                if (finalIdAbsensi) {
                    finalIdAbsensi.value = JSON.stringify(
                        Array.isArray(data.attendance_ids)
                            ? data.attendance_ids
                            : []
                    );
                }

                // Reset bukti pembayaran untuk preview baru
                resetBuktiPembayaran();

                // Tampilkan hasil
                areaHasil.classList.remove('hidden');

            } catch (error) {
                console.error('Preview gaji error:', error);

                tampilkanError(
                    error.message ||
                    'Gagal mengambil data gaji. Silakan coba lagi.'
                );

            } finally {
                btnCek.innerHTML = textLama;
                btnCek.disabled = false;
            }
        });
    }


    // =====================================================
    // PILIH FILE BUKTI PEMBAYARAN
    // =====================================================

    if (inputBuktiFileGaji) {
        inputBuktiFileGaji.addEventListener('change', () => {
            const file = inputBuktiFileGaji.files?.[0];

            // Bersihkan pratinjau sebelumnya
            if (urlPratinjauBuktiGaji) {
                URL.revokeObjectURL(urlPratinjauBuktiGaji);
                urlPratinjauBuktiGaji = null;
            }

            if (gambarBuktiGaji) {
                gambarBuktiGaji.removeAttribute('src');
            }

            if (namaBuktiFileGaji) {
                namaBuktiFileGaji.classList.add('hidden');
                namaBuktiFileGaji.classList.remove('flex');
            }

            if (pratinjauBuktiGaji) {
                pratinjauBuktiGaji.classList.add('hidden');
            }

            if (!file) {
                return;
            }

            // Validasi ekstensi
            const ekstensiDiizinkan =
                /\.(jpg|jpeg|png|pdf)$/i.test(file.name);

            if (!ekstensiDiizinkan) {
                inputBuktiFileGaji.value = '';

                tampilkanError(
                    'Format bukti pembayaran harus JPG, JPEG, PNG, atau PDF.'
                );

                return;
            }

            // Batas 5 MB; sesuaikan dengan validasi controller
            const batasUkuran = 5 * 1024 * 1024;

            if (file.size > batasUkuran) {
                inputBuktiFileGaji.value = '';

                tampilkanError(
                    'Ukuran file bukti pembayaran maksimal 5 MB.'
                );

                return;
            }

            // Tampilkan nama file
            if (teksNamaBuktiFileGaji) {
                teksNamaBuktiFileGaji.textContent = file.name;
            }

            if (namaBuktiFileGaji) {
                namaBuktiFileGaji.classList.remove('hidden');
                namaBuktiFileGaji.classList.add('flex');
            }

            // Pratinjau hanya untuk gambar
            if (
                file.type.startsWith('image/') &&
                gambarBuktiGaji &&
                pratinjauBuktiGaji
            ) {
                urlPratinjauBuktiGaji = URL.createObjectURL(file);

                gambarBuktiGaji.src = urlPratinjauBuktiGaji;

                pratinjauBuktiGaji.classList.remove('hidden');
            }
        });
    }


    // =====================================================
    // SUBMIT PEMBAYARAN GAJI
    // =====================================================

    if (formBayarGaji && btnKonfirmasiGaji) {
        formBayarGaji.addEventListener('submit', event => {

            // Validasi input wajib sebelum form dikirim
            if (!formBayarGaji.reportValidity()) {
                event.preventDefault();
                return;
            }

            // Pastikan file bukti benar-benar dipilih
            if (
                !inputBuktiFileGaji ||
                !inputBuktiFileGaji.files ||
                inputBuktiFileGaji.files.length === 0
            ) {
                event.preventDefault();

                tampilkanError(
                    'Silakan unggah bukti pembayaran terlebih dahulu.'
                );

                return;
            }

            // Cegah pengiriman berulang
            if (btnKonfirmasiGaji.disabled) {
                event.preventDefault();
                return;
            }

            btnKonfirmasiGaji.disabled = true;
            btnKonfirmasiGaji.textContent =
                'Memproses pembayaran...';
        });
    }


    // =====================================================
    // SWEETALERT — SESSION & VALIDASI LARAVEL
    // =====================================================

    if (typeof window.Swal !== 'undefined') {

        // Sukses
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

        // Error dari controller
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
                    .map(pesan => {
                        const elemen = document.createElement('div');

                        elemen.textContent = pesan;

                        return elemen.outerHTML;
                    })
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