document.querySelectorAll('.sidebar .menu-button').forEach(function(btn) {
  btn.addEventListener('click', function() {
    // Menü butonlarındaki active sınıfını temizle
    document.querySelectorAll('.sidebar .menu-button').forEach(function(b) { 
      b.classList.remove('active'); 
    });
    // Tıklanan butonu aktif yap
    btn.classList.add('active');

    var tab = btn.getAttribute('data-tab');
    
    // Tüm tabları gizle ve active sınıfını kaldır
    document.querySelectorAll('.tab-section').forEach(function(sec) { 
      sec.classList.remove('active'); 
      sec.style.display = 'none';
    });
    
    // Seçilen tab'ı göster ve active yap
    var activeTab = document.getElementById(tab);
    activeTab.classList.add('active');
    activeTab.style.display = 'block';

    // Eğer sekme içinde grafik varsa, grafikleri başlat (ufak gecikmeyle)
    if (activeTab.querySelector('.chart-container')) {
      setTimeout(() => {
        initCharts();
      }, 100);
    }
  });
});


// Çıkış yap
document.getElementById('logoutBtn').addEventListener('click', function(e) {
  e.preventDefault();
  window.location.href = '/public/authentication/login.php';
});

// Profil modal aç
document.getElementById('profileBtn').addEventListener('click', function(e) {
  e.preventDefault();
  var modal = new bootstrap.Modal(document.getElementById('profileModal'));
  modal.show();
});

// Ayarlar modal aç
document.getElementById('settingsBtn').addEventListener('click', function(e) {
  e.preventDefault();
  var modal = new bootstrap.Modal(document.getElementById('settingsModal'));
  modal.show();
});

// Profil kaydetme
document.getElementById('profileForm').addEventListener('submit', function(e) {
  e.preventDefault();
  localStorage.setItem('mutabik_profile_name', document.getElementById('profileName').value);
  localStorage.setItem('mutabik_profile_surname', document.getElementById('profileSurname').value);
  localStorage.setItem('mutabik_profile_email', document.getElementById('profileEmail').value);
  localStorage.setItem('mutabik_profile_phone', document.getElementById('profilePhone').value);
  localStorage.setItem('mutabik_profile_username', document.getElementById('profileUsername').value);
  localStorage.setItem('mutabik_profile_address', document.getElementById('profileAddress').value);
  localStorage.setItem('mutabik_profile_birth', document.getElementById('profileBirth').value);
  localStorage.setItem('mutabik_profile_linkedin', document.getElementById('profileLinkedin').value);
  localStorage.setItem('mutabik_profile_instagram', document.getElementById('profileInstagram').value);
  if (document.getElementById('profilePassword').value) {
    localStorage.setItem('mutabik_profile_password', document.getElementById('profilePassword').value);
  }
  bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
});

// Ayarlar kaydetme
document.getElementById('settingsForm').addEventListener('submit', function(e) {
  e.preventDefault();
  localStorage.setItem('mutabik_lang', document.getElementById('langSelect').value);
  localStorage.setItem('mutabik_currency', document.getElementById('currencySelect').value);
  localStorage.setItem('mutabik_notif', document.getElementById('notifSelect').value);
  localStorage.setItem('mutabik_row_count', document.getElementById('rowCount').value);
  localStorage.setItem('mutabik_default_tab', document.getElementById('defaultTab').value);
  localStorage.setItem('mutabik_security_level', document.getElementById('securityLevel').value);
  if (document.getElementById('settingsPassword').value) {
    localStorage.setItem('mutabik_settings_password', document.getElementById('settingsPassword').value);
  }
  applySettings();
  bootstrap.Modal.getInstance(document.getElementById('settingsModal')).hide();
});

function applySettings() {
  if(localStorage.getItem('mutabik_darkmode') === '1') {
    document.body.classList.add('dark-mode');
    document.getElementById('darkModeSwitch').checked = true;
  } else {
    document.body.classList.remove('dark-mode');
    document.getElementById('darkModeSwitch').checked = false;
  }
}

window.addEventListener('DOMContentLoaded', function() {
  if(localStorage.getItem('mutabik_lang')) document.getElementById('langSelect').value = localStorage.getItem('mutabik_lang');
  if(localStorage.getItem('mutabik_currency')) document.getElementById('currencySelect').value = localStorage.getItem('mutabik_currency');
  if(localStorage.getItem('mutabik_notif')) document.getElementById('notifSelect').value = localStorage.getItem('mutabik_notif');
  if(localStorage.getItem('mutabik_darkmode')) document.getElementById('darkModeSwitch').checked = localStorage.getItem('mutabik_darkmode') === '1';
  applySettings();
  initCharts();
});

// Grafikler
function initCharts() {
  const cariCtx = document.getElementById('cariChart');
  if (cariCtx) {
    new Chart(cariCtx, {
      type: 'doughnut',
      data: {
        labels: ['Aktif Cari', 'Pasif Cari'],
        datasets: [{
          data: [38, 4],
          backgroundColor: ['#667eea', '#f093fb'],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              padding: 20,
              usePointStyle: true
            }
          }
        }
      }
    });
  }

  const faturaCtx = document.getElementById('faturaChart');
  if (faturaCtx) {
    new Chart(faturaCtx, {
      type: 'bar',
      data: {
        labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran'],
        datasets: [{
          label: 'Fatura Tutarı',
          data: [12000, 8500, 15000, 9800, 12500, 14200],
          backgroundColor: '#f093fb',
          borderRadius: 8
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return '₺' + value.toLocaleString();
              }
            }
          }
        }
      }
    });
  }

  const stokCtx = document.getElementById('stokChart');
  if (stokCtx) {
    new Chart(stokCtx, {
      type: 'line',
      data: {
        labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran'],
        datasets: [{
          label: 'Stok Miktarı',
          data: [100, 85, 120, 95, 110, 120],
          borderColor: '#43e97b',
          backgroundColor: 'rgba(67, 233, 123, 0.1)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  }
}
