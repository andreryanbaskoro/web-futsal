@push('styles')
<style>
    .profile-card {
        background: #ffffff;
        border-radius: 16px;
        padding-bottom: 28px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .06);
        border: 1px solid #f1f5f9;
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 24px;
    }

    .profile-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        flex-shrink: 0;
    }

    .profile-header h3 {
        font-size: 1.15rem;
        font-weight: 600;
        margin: 0;
    }

    .profile-header p {
        font-size: .85rem;
        color: #64748b;
        margin: 0;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-top: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-label {
        font-size: .8rem;
        font-weight: 600;
        color: #475569;
    }

    .form-control {
        padding: 11px 14px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        font-size: .9rem;
        transition: all .2s ease;
    }

    .form-control:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, .15);
        outline: none;
    }

    .profile-meta {
        margin-top: 24px;
        padding: 14px 16px;
        background: #f8fafc;
        border-radius: 12px;
        display: flex;
        gap: 24px;
        font-size: .85rem;
        color: #334155;
    }

    .profile-meta i {
        margin-right: 6px;
        color: #22c55e;
    }

    .profile-action {
        display: flex;
        justify-content: flex-end;
        margin-top: 26px;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 12px;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
        font-size: .85rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all .2s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(34, 197, 94, .35);
    }

    .btn-primary {
        position: relative;
        overflow: hidden;
    }

    .btn-loading {
        display: none;
        align-items: center;
        gap: 6px;
    }

    .btn-text {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        pointer-events: none;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .profile-meta {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>
@endpush

<div class="profile-card">
    <div class="profile-header">
        <div class="profile-icon profile-icon--success">
            <i class="fas fa-user"></i>
        </div>

        <div>
            <h3>Profil Saya</h3>
            <p>Kelola informasi akun pelanggan</p>
        </div>
    </div>

    <form x-ref="profileForm"
        action="{{ route('pelanggan.profile.update') }}"
        method="POST">

        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control"
                    value="{{ old('nama', auth()->user()->nama) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                    value="{{ old('email', auth()->user()->email) }}" required>
            </div>
        </div>

        <div class="form-group" style="margin-top:16px">
            <label class="form-label">Nomor HP</label>
            <input type="text" name="no_hp" class="form-control"
                value="{{ old('no_hp', auth()->user()->no_hp) }}">
        </div>

        <div class="profile-meta">
            <span><i class="fas fa-id-badge"></i> {{ ucfirst(auth()->user()->peran) }}</span>
            <span><i class="fas fa-circle-check"></i> {{ auth()->user()->status }}</span>
        </div>

        <div class="profile-action">
            <button
                type="button"
                class="btn-primary"
                id="btn-save-profile"
                onclick="confirmUpdateProfile()">

                <span class="btn-text">
                    <i class="fas fa-save"></i>
                    Simpan Perubahan
                </span>

                <span class="btn-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    Menyimpan...
                </span>
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
    function confirmUpdateProfile() {
        const form = document.querySelector('form[x-ref="profileForm"]');
        const btn = document.getElementById('btn-save-profile');
        const text = btn.querySelector('.btn-text');
        const loading = btn.querySelector('.btn-loading');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: 'Simpan perubahan?',
            text: 'Informasi profil akan diperbarui.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#64748b'
        }).then((result) => {
            if (result.isConfirmed) {
                // 🔒 Lock button
                btn.disabled = true;
                text.style.display = 'none';
                loading.style.display = 'inline-flex';

                // 🚀 Submit form
                form.submit();
            }
        });
    }
</script>
@endpush