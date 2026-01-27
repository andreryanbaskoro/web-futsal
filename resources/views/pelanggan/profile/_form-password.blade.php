@push('styles')
<style>
    .profile-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 28px;
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
        background: linear-gradient(135deg, #ef4444, #dc2626);
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

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-top: 16px;
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
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, .15);
        outline: none;
    }

    .profile-action {
        display: flex;
        justify-content: flex-end;
        margin-top: 26px;
    }

    .btn-danger {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 12px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        font-size: .85rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all .2s ease;
    }

    .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(239, 68, 68, .35);
    }

    .btn-text {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-loading {
        display: none;
        align-items: center;
        gap: 6px;
    }

    button:disabled {
        opacity: .7;
        cursor: not-allowed;
        pointer-events: none;
    }
</style>
@endpush

<div class="profile-card">
    <div class="profile-header">
        <div class="profile-icon profile-icon--danger">
            <i class="fas fa-key"></i>
        </div>

        <div>
            <h3>Ganti Password</h3>
            <p>Gunakan password yang kuat dan aman</p>
        </div>
    </div>

    <form x-ref="passwordForm"
        action="{{ route('pelanggan.profile.password') }}"
        method="POST">

        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Password Lama</label>
            <input type="password" name="password_lama" class="form-control" required>
            @error('password_lama') <small style="color:red">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Password Baru</label>
            <input type="password" name="password" class="form-control" required>
            @error('password') <small style="color:red">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="profile-action">
            <button type="button"
                class="btn-danger"
                id="btn-password"
                onclick="confirmUpdatePassword()">

                <span class="btn-text">
                    <i class="fas fa-key"></i>
                    Ubah Password
                </span>

                <span class="btn-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    Memproses...
                </span>
            </button>
        </div>
    </form>
</div>



@push('scripts')
<script>
    function confirmUpdatePassword() {
        const form = document.querySelector('form[x-ref="passwordForm"]');
        const btn = document.getElementById('btn-password');
        const text = btn.querySelector('.btn-text');
        const loading = btn.querySelector('.btn-loading');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: 'Ubah password?',
            text: 'Pastikan Anda mengingat password baru.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ubah',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.disabled = true;
                text.style.display = 'none';
                loading.style.display = 'inline-flex';

                form.submit();
            }
        });
    }
</script>
@endpush