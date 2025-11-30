export function initializeRegisterForm() {
    const roleSelection = document.getElementById('roleSelection');
    const registerForm = document.getElementById('registerForm');
    const roleInput = document.getElementById('roleInput');
    const container = document.getElementById('roleSpecificFields');
    const backButton = document.getElementById('backToRoleSelection');
    const roleCards = document.querySelectorAll('.role-card');

    if (!roleSelection || !registerForm || !roleInput || !container) {
        return;
    }

    const oldValues = window.oldFormValues || {};

    function showForm(role) {
        roleInput.value = role;
        roleSelection.style.display = 'none';
        registerForm.style.display = 'block';

        updateFormFields(role);
    }

    function showRoleSelection() {
        roleSelection.style.display = 'block';
        registerForm.style.display = 'none';
        roleInput.value = '';
        container.innerHTML = '';

        roleCards.forEach(card => {
            card.classList.remove('border-[#f56e9d]', 'bg-pink-50');
            card.classList.add('border-gray-300');
        });
    }

    function updateFormFields(role) {
        if (!role) {
            container.innerHTML = '';
            return;
        }

        let fields = '';

        if (role === 'pasien') {
            fields = getPasienFields(oldValues);
        } else if (role === 'dokter') {
            fields = getDokterFields(oldValues);
        } else if (['pendaftaran', 'apoteker', 'lab', 'kasir_klinik', 'kasir_apotek', 'kasir_lab'].includes(role)) {
            fields = getStafFields(role, oldValues);
        }

        container.innerHTML = fields;
    }

    roleCards.forEach(card => {
        card.addEventListener('click', function() {
            const role = this.dataset.role;

            roleCards.forEach(c => {
                c.classList.remove('border-[#f56e9d]', 'bg-pink-50');
                c.classList.add('border-gray-300');
            });

            this.classList.remove('border-gray-300');
            this.classList.add('border-[#f56e9d]', 'bg-pink-50');

            showForm(role);
        });
    });

    backButton.addEventListener('click', showRoleSelection);

    if (oldValues.role) {
        const selectedCard = document.querySelector(`.role-card[data-role="${oldValues.role}"]`);
        if (selectedCard) {
            selectedCard.classList.remove('border-gray-300');
            selectedCard.classList.add('border-[#f56e9d]', 'bg-pink-50');
        }
        showForm(oldValues.role);
    }
}

function getPasienFields(oldValues) {
    return `
        <div class="border-t pt-4 mb-4">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Data Pasien</h3>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_lengkap" value="${oldValues.nama_lengkap || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        NIK <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nik" value="${oldValues.nik || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="16" pattern="[0-9]{16}">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        Tanggal Lahir <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_lahir" value="${oldValues.tanggal_lahir || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    Jenis Kelamin <span class="text-red-500">*</span>
                </label>
                <select name="jenis_kelamin" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required>
                    <option value="">Pilih</option>
                    <option value="Laki-Laki" ${oldValues.jenis_kelamin === 'Laki-Laki' ? 'selected' : ''}>Laki-Laki</option>
                    <option value="Perempuan" ${oldValues.jenis_kelamin === 'Perempuan' ? 'selected' : ''}>Perempuan</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    Alamat <span class="text-red-500">*</span>
                </label>
                <textarea name="alamat" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" rows="3" required>${oldValues.alamat || ''}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Provinsi</label>
                    <input type="text" name="provinsi" value="${oldValues.provinsi || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="100">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Kota/Kabupaten</label>
                    <input type="text" name="kota_kabupaten" value="${oldValues.kota_kabupaten || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="100">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Kecamatan</label>
                    <input type="text" name="kecamatan" value="${oldValues.kecamatan || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="100">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Kewarganegaraan</label>
                    <input type="text" name="kewarganegaraan" value="${oldValues.kewarganegaraan || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="50">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        No. Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="no_telepon" value="${oldValues.no_telepon || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="15">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Golongan Darah</label>
                <select name="golongan_darah" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent">
                    <option value="">Pilih</option>
                    <option value="A" ${oldValues.golongan_darah === 'A' ? 'selected' : ''}>A</option>
                    <option value="B" ${oldValues.golongan_darah === 'B' ? 'selected' : ''}>B</option>
                    <option value="AB" ${oldValues.golongan_darah === 'AB' ? 'selected' : ''}>AB</option>
                    <option value="O" ${oldValues.golongan_darah === 'O' ? 'selected' : ''}>O</option>
                    <option value="A+" ${oldValues.golongan_darah === 'A+' ? 'selected' : ''}>A+</option>
                    <option value="A-" ${oldValues.golongan_darah === 'A-' ? 'selected' : ''}>A-</option>
                    <option value="B+" ${oldValues.golongan_darah === 'B+' ? 'selected' : ''}>B+</option>
                    <option value="B-" ${oldValues.golongan_darah === 'B-' ? 'selected' : ''}>B-</option>
                    <option value="AB+" ${oldValues.golongan_darah === 'AB+' ? 'selected' : ''}>AB+</option>
                    <option value="AB-" ${oldValues.golongan_darah === 'AB-' ? 'selected' : ''}>AB-</option>
                    <option value="O+" ${oldValues.golongan_darah === 'O+' ? 'selected' : ''}>O+</option>
                    <option value="O-" ${oldValues.golongan_darah === 'O-' ? 'selected' : ''}>O-</option>
                </select>
            </div>
        </div>
    `;
}

function getDokterFields(oldValues) {
    return `
        <div class="border-t pt-4 mb-4">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Data Dokter</h3>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">NIP</label>
                <input type="text" name="nip" value="${oldValues.nip || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="30">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_lengkap" value="${oldValues.nama_lengkap || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        NIK <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nik" value="${oldValues.nik || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="16" pattern="[0-9]{16}">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        Tanggal Lahir <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_lahir" value="${oldValues.tanggal_lahir || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    Jenis Kelamin <span class="text-red-500">*</span>
                </label>
                <select name="jenis_kelamin" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required>
                    <option value="">Pilih</option>
                    <option value="Laki-Laki" ${oldValues.jenis_kelamin === 'Laki-Laki' ? 'selected' : ''}>Laki-Laki</option>
                    <option value="Perempuan" ${oldValues.jenis_kelamin === 'Perempuan' ? 'selected' : ''}>Perempuan</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    Alamat <span class="text-red-500">*</span>
                </label>
                <textarea name="alamat" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" rows="3" required>${oldValues.alamat || ''}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Provinsi</label>
                    <input type="text" name="provinsi" value="${oldValues.provinsi || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="100">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Kota/Kabupaten</label>
                    <input type="text" name="kota_kabupaten" value="${oldValues.kota_kabupaten || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="100">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Kecamatan</label>
                    <input type="text" name="kecamatan" value="${oldValues.kecamatan || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="100">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Kewarganegaraan</label>
                    <input type="text" name="kewarganegaraan" value="${oldValues.kewarganegaraan || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="50">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        No. Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="no_telepon" value="${oldValues.no_telepon || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="15">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Spesialisasi</label>
                    <input type="text" name="spesialisasi" value="${oldValues.spesialisasi || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="100">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">No. STR</label>
                    <input type="text" name="no_str" value="${oldValues.no_str || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="50">
                </div>
            </div>
        </div>
    `;
}

function getStafFields(role, oldValues) {
    const roleLabels = {
        'pendaftaran': 'Staf Pendaftaran',
        'apoteker': 'Apoteker',
        'lab': 'Staf Laboratorium',
        'kasir_klinik': 'Kasir Klinik',
        'kasir_apotek': 'Kasir Apotek',
        'kasir_lab': 'Kasir Lab'
    };

    return `
        <div class="border-t pt-4 mb-4">
            <h3 class="text-lg font-bold text-gray-700 mb-4">Data ${roleLabels[role]}</h3>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">NIP</label>
                <input type="text" name="nip" value="${oldValues.nip || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="30">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_lengkap" value="${oldValues.nama_lengkap || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="100">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        NIK <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nik" value="${oldValues.nik || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="16" pattern="[0-9]{16}">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        Tanggal Lahir <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_lahir" value="${oldValues.tanggal_lahir || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    Jenis Kelamin <span class="text-red-500">*</span>
                </label>
                <select name="jenis_kelamin" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required>
                    <option value="">Pilih</option>
                    <option value="Laki-Laki" ${oldValues.jenis_kelamin === 'Laki-Laki' ? 'selected' : ''}>Laki-Laki</option>
                    <option value="Perempuan" ${oldValues.jenis_kelamin === 'Perempuan' ? 'selected' : ''}>Perempuan</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    Alamat <span class="text-red-500">*</span>
                </label>
                <textarea name="alamat" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" rows="3" required>${oldValues.alamat || ''}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Provinsi</label>
                    <input type="text" name="provinsi" value="${oldValues.provinsi || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="100">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Kota/Kabupaten</label>
                    <input type="text" name="kota_kabupaten" value="${oldValues.kota_kabupaten || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="100">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Kecamatan</label>
                    <input type="text" name="kecamatan" value="${oldValues.kecamatan || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="100">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Kewarganegaraan</label>
                    <input type="text" name="kewarganegaraan" value="${oldValues.kewarganegaraan || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" maxlength="50">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        No. Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="no_telepon" value="${oldValues.no_telepon || ''}" class="shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#f56e9d] focus:border-transparent" required maxlength="15">
                </div>
            </div>
        </div>
    `;
}
