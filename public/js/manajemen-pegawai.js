/**
 * =========================================================
 * HELPER
 * =========================================================
 */

// Cek apakah jabatan dengan ID tertentu boleh memiliki akun login.
// jabatanCanLogin didefinisikan di Blade: { "1": false, "2": true, ... }
function canJabatanLogin(jabatanId) {
    if (!jabatanId) return false;
    if (typeof jabatanCanLogin === 'undefined') return false;

    return Boolean(jabatanCanLogin[String(jabatanId)]);
}


/**
 * =========================================================
 * TAB NAVIGATION
 * =========================================================
 */
function switchTab(tab) {
    const contentPegawai = document.getElementById('contentPegawai');
    const contentJabatan = document.getElementById('contentJabatan');
    const tabPegawai = document.getElementById('tabPegawai');
    const tabJabatan = document.getElementById('tabJabatan');

    if (!contentPegawai || !contentJabatan || !tabPegawai || !tabJabatan) {
        return;
    }

    const activeClasses = ['border-slate-900', 'font-semibold', 'text-slate-900'];
    const inactiveClasses = ['border-transparent', 'font-medium', 'text-slate-500'];

    const setActive = (el) => {
        el.classList.add(...activeClasses);
        el.classList.remove(...inactiveClasses);
    };

    const setInactive = (el) => {
        el.classList.add(...inactiveClasses);
        el.classList.remove(...activeClasses);
    };

    if (tab === 'pegawai') {
        contentPegawai.classList.remove('hidden');
        contentJabatan.classList.add('hidden');
        setActive(tabPegawai);
        setInactive(tabJabatan);
    } else {
        contentPegawai.classList.add('hidden');
        contentJabatan.classList.remove('hidden');
        setActive(tabJabatan);
        setInactive(tabPegawai);
    }
}


/**
 * =========================================================
 * FILTER PEGAWAI
 * =========================================================
 */
function filterPegawaiTable() {
    const searchInput =
        document.getElementById('searchPegawai')?.value.toLowerCase().trim() || '';

    const filterStatus =
        document.getElementById('filterStatus')?.value.toLowerCase().trim() || '';

    const filterJabatan =
        document.getElementById('filterJabatan')?.value.toLowerCase().trim() || '';

    const rows = document.querySelectorAll('.pegawai-row');
    const emptyState = document.getElementById('searchEmptyState');

    let visibleRows = 0;

    rows.forEach((row) => {
        const nama = (row.dataset.nama || '').toLowerCase();
        const telp = (row.dataset.telp || '').toLowerCase();
        const jabatan = (row.dataset.jabatan || '').toLowerCase();
        const status = (row.dataset.status || '').toLowerCase();

        const matchesSearch =
            nama.includes(searchInput) ||
            telp.includes(searchInput) ||
            jabatan.includes(searchInput);

        const matchesStatus = !filterStatus || status === filterStatus;
        const matchesJabatan = !filterJabatan || jabatan === filterJabatan;

        const visible = matchesSearch && matchesStatus && matchesJabatan;

        row.style.display = visible ? '' : 'none';

        if (visible) {
            visibleRows++;
        }
    });

    if (emptyState) {
        if (rows.length > 0 && visibleRows === 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }
    }
}


/**
 * =========================================================
 * MODAL MANAGEMENT
 * =========================================================
 */
function toggleModal(id, show) {
    const modal = document.getElementById(id);

    if (!modal) {
        return;
    }

    if (show) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');

        // Saat modal tambah pegawai dibuka, sinkronkan field akun
        // dengan jabatan yang sedang terpilih (mis. setelah validasi gagal).
        if (id === 'modalTambahPegawai') {
            const select = document.getElementById('pegawai_jabatan');
            if (select) checkJabatan(select);
        }
    } else {
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        const openedModals = document.querySelectorAll('[id^="modal"].flex');

        if (openedModals.length === 0) {
            document.body.classList.remove('overflow-hidden');
        }
    }
}


/**
 * =========================================================
 * TAMBAH PEGAWAI - CHECK JABATAN
 * =========================================================
 * Begitu jabatan yang boleh login (mis. Pengawas) dipilih,
 * kolom email & password langsung muncul.
 */
function checkJabatan(select) {
    const accountFields = document.getElementById('accountFields');
    if (!accountFields || !select) return;

    const emailInput = document.getElementById('pegawai_email');
    const passwordInput = document.getElementById('pegawai_password');

    if (canJabatanLogin(select.value)) {
        accountFields.classList.remove('hidden');
        if (emailInput) emailInput.required = true;
        if (passwordInput) passwordInput.required = true;
    } else {
        accountFields.classList.add('hidden');
        if (emailInput) { emailInput.required = false; emailInput.value = ''; }
        if (passwordInput) { passwordInput.required = false; passwordInput.value = ''; }
    }
}


/**
 * =========================================================
 * EDIT JABATAN
 * =========================================================
 */
function openEditJabatanModal(button) {
    const data = button.dataset;
    const form = document.getElementById('formEditJabatan');

    if (!form) {
        console.error('Form edit jabatan tidak ditemukan.');
        return;
    }

    if (!data.updateUrl) {
        console.error('URL update jabatan tidak tersedia.');
        return;
    }

    form.action = data.updateUrl;

    const namaInput = document.getElementById('edit_nama_jabatan');
    const salaryInput = document.getElementById('edit_gaji_harian');
    const canLoginInput = document.getElementById('edit_can_login');

    if (namaInput) namaInput.value = data.name || '';
    if (salaryInput) salaryInput.value = data.salary || '';
    if (canLoginInput) canLoginInput.checked = data.canLogin === '1';

    toggleModal('modalEditJabatan', true);
}


/**
 * =========================================================
 * DETAIL PEGAWAI
 * =========================================================
 */
function openDetailPegawaiModal(button) {
    const data = button.dataset;

    const nama = data.nama || '-';
    const telp = data.telp || '-';
    const jabatan = data.jabatan || '-';
    const status = data.status || 'aktif';
    const email = data.email || '';
    const hasAccount = data.hasAccount === '1';

    const setText = (id, value) => {
        const element = document.getElementById(id);
        if (element) element.textContent = value;
    };

    setText('detailNama', nama);
    setText('detailJabatan', jabatan);
    setText('detailTelp', telp);
    setText('detailAvatar', nama.charAt(0).toUpperCase());

    // Status pegawai
    const statusPegawaiElement = document.getElementById('detailStatusPegawai');

    if (statusPegawaiElement) {
        if (status === 'aktif') {
            statusPegawaiElement.innerHTML = `
                <span class="inline-flex items-center gap-1.5 rounded-md border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    Aktif
                </span>
            `;
        } else {
            statusPegawaiElement.innerHTML = `
                <span class="inline-flex items-center gap-1.5 rounded-md border border-slate-200 bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500">
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                    Nonaktif
                </span>
            `;
        }
    }

    // Status akun
    const statusAkunElement = document.getElementById('detailStatusAkun');

    if (statusAkunElement) {
        if (hasAccount) {
            statusAkunElement.innerHTML = `
                <span class="inline-flex items-center gap-1.5 rounded-md border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    Memiliki Akun
                </span>
            `;
        } else {
            statusAkunElement.innerHTML = `
                <span class="inline-flex items-center gap-1.5 rounded-md border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-500">
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                    Tanpa Akun
                </span>
            `;
        }
    }

    // Email akun
    const emailWrapper = document.getElementById('detailEmailWrapper');
    const emailElement = document.getElementById('detailEmail');

    if (emailWrapper && emailElement) {
        if (hasAccount && email) {
            emailWrapper.classList.remove('hidden');
            emailWrapper.classList.add('flex');
            emailElement.textContent = email;
        } else {
            emailWrapper.classList.add('hidden');
            emailWrapper.classList.remove('flex');
            emailElement.textContent = '-';
        }
    }

    toggleModal('modalDetailPegawai', true);
}


/**
 * =========================================================
 * EDIT PEGAWAI
 * =========================================================
 */
function openEditPegawaiModal(button) {
    const data = button.dataset;
    const form = document.getElementById('formEditPegawai');
    const modal = document.getElementById('modalEditPegawai');

    if (!form || !modal) {
        console.error('Form atau modal edit pegawai tidak ditemukan.');
        return;
    }

    if (!data.updateUrl) {
        console.error('URL update pegawai tidak tersedia.');
        return;
    }

    form.action = data.updateUrl;

    const namaInput = document.getElementById('edit_pegawai_nama');
    const telpInput = document.getElementById('edit_pegawai_telp');
    const jabatanInput = document.getElementById('edit_pegawai_jabatan');
    const statusInput = document.getElementById('edit_pegawai_status');
    const emailInput = document.getElementById('edit_pegawai_email');
    const passwordInput = document.getElementById('edit_pegawai_password');
    const accountFields = document.getElementById('editAccountFields');

    if (namaInput) namaInput.value = data.nama || '';
    if (telpInput) telpInput.value = data.telp || '';
    if (jabatanInput) jabatanInput.value = data.jabatanId || '';
    if (statusInput) statusInput.value = data.status || 'aktif';
    if (emailInput) emailInput.value = data.email || '';
    if (passwordInput) passwordInput.value = '';

    // Simpan status akun SEBELUM memanggil checkEditJabatan.
    if (accountFields) {
        accountFields.dataset.hasAccount = data.hasAccount === '1' ? '1' : '0';
    }

    if (jabatanInput) checkEditJabatan(jabatanInput);

    toggleModal('modalEditPegawai', true);
}


/**
 * =========================================================
 * EDIT PEGAWAI - CHECK AKUN DAN JABATAN
 * =========================================================
 * - Pegawai yang sudah punya akun: field email/password selalu tampil.
 * - Pegawai tanpa akun (mis. Tukang): field baru muncul begitu
 *   jabatan diganti ke jabatan yang boleh login (mis. Pengawas).
 */
function checkEditJabatan(select) {
    const accountFields = document.getElementById('editAccountFields');
    const emailInput = document.getElementById('edit_pegawai_email');
    const passwordInput = document.getElementById('edit_pegawai_password');

    if (!accountFields || !select) return;

    const canLogin = canJabatanLogin(select.value);
    const hasAccount = accountFields.dataset.hasAccount === '1';

    const show = hasAccount || canLogin;

    if (show) {
        accountFields.classList.remove('hidden');

        if (emailInput) emailInput.required = true;

        // Akun baru wajib password; akun lama boleh dikosongkan.
        if (passwordInput) passwordInput.required = !hasAccount;
    } else {
        accountFields.classList.add('hidden');
        if (emailInput) { emailInput.required = false; emailInput.value = ''; }
        if (passwordInput) { passwordInput.required = false; passwordInput.value = ''; }
    }
}


/**
 * =========================================================
 * TUTUP MODAL DENGAN TOMBOL ESCAPE
 * =========================================================
 */
document.addEventListener('keydown', function (event) {
    if (event.key !== 'Escape') {
        return;
    }

    document.querySelectorAll('[id^="modal"].flex').forEach((modal) => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    document.body.classList.remove('overflow-hidden');
});


/**
 * =========================================================
 * INISIALISASI
 * =========================================================
 */
document.addEventListener('DOMContentLoaded', function () {
    const addJabatan = document.getElementById('pegawai_jabatan');

    if (addJabatan && addJabatan.value) {
        checkJabatan(addJabatan);
    }

    const alert = document.getElementById('successAlert');

    if (alert) {
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }
});


/**
 * =========================================================
 * KONFIRMASI HAPUS
 * =========================================================
 */
function confirmDelete(formId, itemLabel) {
    if (typeof Swal === 'undefined') {
        const form = document.getElementById(formId);

        if (form && confirm(`Hapus data ${itemLabel}?`)) {
            form.submit();
        }

        return;
    }

    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: `Data ${itemLabel} akan dihapus secara permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(formId);

            if (form) {
                form.submit();
            }
        }
    });
}