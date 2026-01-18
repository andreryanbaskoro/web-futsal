{{-- Flash Messages dengan Inline CSS --}}
<div style="margin-bottom: 24px;">

    {{-- Success --}}
    @if (session('success'))
    <div id="successAlert" style="
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        width: 100%;
        max-width: 480px;
        border-radius: 6px;
        border-left: 4px solid #10b981;
        background-color: #f0fdf4;
        padding: 16px;
        color: #15803d;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        margin-bottom: 16px;
        animation: slideIn 0.3s ease-out;
    ">
        <div style="flex: 1; font-size: 14px; line-height: 1.5;">
            <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
            {{ session('success') }}
        </div>
        <button
            onclick="closeAlert('successAlert')"
            style="
                margin-left: 16px;
                background: none;
                border: none;
                color: #15803d;
                cursor: pointer;
                font-size: 16px;
                padding: 0;
                transition: color 0.2s;
            "
            onmouseover="this.style.color='#166534'"
            onmouseout="this.style.color='#15803d'">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    {{-- Error (Multiple) --}}
    @if ($errors->any())
    <div id="errorAlert" style="
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 480px;
        border-radius: 6px;
        border-left: 4px solid #ef4444;
        background-color: #fef2f2;
        padding: 16px;
        color: #991b1b;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        margin-bottom: 16px;
        animation: slideIn 0.3s ease-out;
    ">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
            <span style="font-size: 14px; font-weight: 600;">
                <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>
                Terjadi beberapa kesalahan:
            </span>
            <button
                onclick="closeAlert('errorAlert')"
                style="
                    background: none;
                    border: none;
                    color: #991b1b;
                    cursor: pointer;
                    font-size: 16px;
                    padding: 0;
                    transition: color 0.2s;
                "
                onmouseover="this.style.color='#7f1d1d'"
                onmouseout="this.style.color='#991b1b'">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul style="margin: 0; padding-left: 20px; font-size: 14px; line-height: 1.6;">
            @foreach ($errors->all() as $error)
            <li style="margin-bottom: 4px;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Error (Single) --}}
    @if (session('error'))
    <div id="singleErrorAlert" style="
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        width: 100%;
        max-width: 480px;
        border-radius: 6px;
        border-left: 4px solid #ef4444;
        background-color: #fef2f2;
        padding: 16px;
        color: #991b1b;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        margin-bottom: 16px;
        animation: slideIn 0.3s ease-out;
    ">
        <div style="flex: 1; font-size: 14px; line-height: 1.5;">
            <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>
            {{ session('error') }}
        </div>
        <button
            onclick="closeAlert('singleErrorAlert')"
            style="
                margin-left: 16px;
                background: none;
                border: none;
                color: #991b1b;
                cursor: pointer;
                font-size: 16px;
                padding: 0;
                transition: color 0.2s;
            "
            onmouseover="this.style.color='#7f1d1d'"
            onmouseout="this.style.color='#991b1b'">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    {{-- Warning --}}
    @if (session('warning'))
    <div id="warningAlert" style="
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        width: 100%;
        max-width: 480px;
        border-radius: 6px;
        border-left: 4px solid #f59e0b;
        background-color: #fffbeb;
        padding: 16px;
        color: #92400e;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        margin-bottom: 16px;
        animation: slideIn 0.3s ease-out;
    ">
        <div style="flex: 1; font-size: 14px; line-height: 1.5;">
            <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
            {{ session('warning') }}
        </div>
        <button
            onclick="closeAlert('warningAlert')"
            style="
                margin-left: 16px;
                background: none;
                border: none;
                color: #92400e;
                cursor: pointer;
                font-size: 16px;
                padding: 0;
                transition: color 0.2s;
            "
            onmouseover="this.style.color='#78350f'"
            onmouseout="this.style.color='#92400e'">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    {{-- Info --}}
    @if (session('info'))
    <div id="infoAlert" style="
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        width: 100%;
        max-width: 480px;
        border-radius: 6px;
        border-left: 4px solid #3b82f6;
        background-color: #eff6ff;
        padding: 16px;
        color: #1e40af;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        margin-bottom: 16px;
        animation: slideIn 0.3s ease-out;
    ">
        <div style="flex: 1; font-size: 14px; line-height: 1.5;">
            <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
            {{ session('info') }}
        </div>
        <button
            onclick="closeAlert('infoAlert')"
            style="
                margin-left: 16px;
                background: none;
                border: none;
                color: #1e40af;
                cursor: pointer;
                font-size: 16px;
                padding: 0;
                transition: color 0.2s;
            "
            onmouseover="this.style.color='#1e3a8a'"
            onmouseout="this.style.color='#1e40af'">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

</div>

{{-- Keyframe Animation & JavaScript --}}
<style>
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }

        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }
</style>

<script>
    // Auto close alerts after 60 seconds
    setTimeout(function() {
        ['successAlert', 'errorAlert', 'singleErrorAlert', 'warningAlert', 'infoAlert'].forEach(function(id) {
            var alert = document.getElementById(id);
            if (alert) {
                closeAlert(id);
            }
        });
    }, 60000);

    // Close alert function with animation
    function closeAlert(id) {
        var alert = document.getElementById(id);
        if (alert) {
            alert.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(function() {
                alert.style.display = 'none';
            }, 300);
        }
    }
</script>