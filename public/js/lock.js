document.addEventListener("DOMContentLoaded", function () {
    const lockStatusElement = document.querySelector(".lock-status");
    const lockIconElement = document.querySelector(".lock-icon i");
    const lockButtonElement = document.getElementById("lockButton");

    function fetchAndUpdateLockStatus() {
        fetch('/latest-lock')
            .then(response => response.json())
            .then(data => {
                if (data && data.status !== null) {
                    updateLockDisplay(data.status);
                } else {
                    toggleLockData(false);
                }
            })
            .catch(error => {
                console.error('Error fetching wheel lock status:', error);
            });
    }

    function updateLockDisplay(status) {
        lockStatusElement.textContent = `Status: ${status}`;
        if (status === 'Unlocked') {
            lockIconElement.setAttribute('data-feather', 'unlock');
            lockButtonElement.classList.remove('off');
            lockButtonElement.classList.add('on');
            lockButtonElement.setAttribute('data-status', 'on');
            lockButtonElement.textContent = 'ON';
        } else {
            lockIconElement.setAttribute('data-feather', 'lock');
            lockButtonElement.classList.remove('on');
            lockButtonElement.classList.add('off');
            lockButtonElement.setAttribute('data-status', 'off');
            lockButtonElement.textContent = 'OFF';
        }
        feather.replace();
    }

    function toggleLockData(isAvailable) {
        lockStatusElement.textContent = isAvailable ? 'Status: Loading...' : 'Status: -';
        lockIconElement.setAttribute('data-feather', 'refresh-cw');
        lockButtonElement.style.display = 'none'; // Menyembunyikan tombol saat data tidak tersedia
        feather.replace();
    }

    // Polling setiap 30 detik untuk memperbarui status
    setInterval(fetchAndUpdateLockStatus, 30000);
    // Memanggil fungsi pertama kali saat halaman dimuat
    fetchAndUpdateLockStatus();
});