// Sidebar toggle for mobile ve masaüstü (menüyü sakla)
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  if (sidebar) {
    sidebar.classList.toggle("collapsed");
  }
}

// DOM yüklendiğinde butonlara event ekle
window.addEventListener("DOMContentLoaded", function () {
  const collapseBtn = document.getElementById("sidebar-collapse-btn");
  if (collapseBtn) {
    collapseBtn.addEventListener("click", function (e) {
      e.preventDefault();
      toggleSidebar();
    });
  }
  const sidebarToggle = document.querySelector(".sidebar-toggle");
  if (sidebarToggle) {
    sidebarToggle.addEventListener("click", function (e) {
      e.preventDefault();
      toggleSidebar();
    });
  }

  // Menüde sadece bir submenu açık olsun
  document.querySelectorAll(".menu-toggle").forEach((toggle) => {
    toggle.addEventListener("click", function (e) {
      e.preventDefault();
      // Tüm submenu'leri kapat ve tüm menu-toggle'lardan active class'ını kaldır
      document.querySelectorAll(".submenu").forEach((sm) => {
        sm.style.display = "none";
      });
      document.querySelectorAll(".menu-toggle").forEach((mt) => {
        mt.classList.remove("active");
        const icon = mt.querySelector(".toggle-icon");
        if (icon) {
          icon.classList.remove("fa-chevron-up");
          icon.classList.add("fa-chevron-down");
        }
      });
      // Sadece tıklanan submenu'yi aç/kapa (data-target ile id eşleşmesi)
      const submenuId = this.getAttribute("data-target");
      const submenu = document.getElementById(submenuId);
      if (!submenu) return;
      if (submenu.style.display === "block") {
        submenu.style.display = "none";
        this.classList.remove("active");
      } else {
        submenu.style.display = "block";
        this.classList.add("active");
        const toggleIcon = this.querySelector(".toggle-icon");
        if (toggleIcon) {
          toggleIcon.classList.remove("fa-chevron-down");
          toggleIcon.classList.add("fa-chevron-up");
        }
      }
    });
  });

  // Sayfa yüklendiğinde sadece aktif olan submenu açık kalsın, diğerleri kapalı olsun
  setTimeout(function () {
    let anyOpen = false;
    document.querySelectorAll(".menu-toggle").forEach((mt) => {
      const submenu = mt.nextElementSibling;
      if (mt.classList.contains("active") && submenu) {
        submenu.style.display = "block";
        anyOpen = true;
      } else if (submenu) {
        submenu.style.display = "none";
      }
    });
    // Eğer hiçbiri aktif değilse, hepsi kapalı kalsın
  }, 0);
  // Sil butonları için event ekle
  document.querySelectorAll(".delete-btn").forEach(button => {
    button.addEventListener("click", function () {
      const id = this.dataset.id;
      if (confirm("Silmek istediğinizden emin misiniz?")) {
        fetch("musteriler.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `action=delete&id=${encodeURIComponent(id)}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            this.closest("tr").remove();
            alert("Müşteri başarıyla silindi.");
          } else {
            alert("Silme işlemi başarısız: " + (data.message || "Bilinmeyen hata"));
          }
        })
        .catch(() => alert("Silme işlemi sırasında bir hata oluştu."));
      }
    });
  });
});

// Close sidebar when clicking outside on mobile
document.addEventListener("click", function (event) {
  const sidebar = document.getElementById("sidebar");
  const sidebarToggle = document.querySelector(".sidebar-toggle");

  // Check if elements exist to prevent errors if they are not in the DOM (e.g. during testing)
  if (window.innerWidth <= 768) {
    if (
      sidebar &&
      sidebarToggle &&
      !sidebar.contains(event.target) &&
      !sidebarToggle.contains(event.target)
    ) {
      sidebar.classList.remove("show");
    }
  }
});

/**
 * Menüdeki tüm aktif sınıfları ve açık alt menüleri temizler.
 * Toggle ikonlarını başlangıç durumuna getirir.
 */
function resetMenuState() {
  // Tüm menu-item ve submenu-item'lardan 'active' sınıfını kaldır
  document.querySelectorAll(".menu-item, .submenu-item").forEach((item) => {
    item.classList.remove("active");
  });

  // Tüm alt menüleri gizle
  document.querySelectorAll(".submenu").forEach((submenu) => {
    submenu.style.display = "none";
    // İlişkili menu-toggle'ın ikonunu aşağı çevir ve kendi aktifliğini kaldır
    const parentToggle = submenu.previousElementSibling;
    if (parentToggle && parentToggle.classList.contains("menu-toggle")) {
      parentToggle.classList.remove("active"); // Üst başlığın kendi aktifliğini de kaldır
      const toggleIcon = parentToggle.querySelector(".toggle-icon");
      if (toggleIcon) {
        toggleIcon.classList.remove("fa-chevron-up");
        toggleIcon.classList.add("fa-chevron-down");
      }
    }
  });
}

/**
 * Belirtilen bir menü öğesini aktif yapar ve bağlı olduğu tüm üst alt menüleri açar.
 * @param {HTMLElement} targetElement - Aktif yapılacak menü öğesi (<a> etiketi veya <div>.menu-toggle).
 */
function activateMenuItemAndParents(targetElement) {
  // Önce tüm aktiflikleri ve açık menüleri temizle
  // Ancak bu fonksiyon genellikle sadece bir öğeyi aktif ederken çağrılmalı.
  // Eğer tüm menü state'ini sıfırlamak istiyorsak, bu fonksiyon dışında çağrılmalı.
  // Burada sadece targetElement'ın ve parent'larının aktifliğini yönetiyoruz.

  // Tıklanan öğeyi veya mevcut sayfanın öğesini aktif yap
  if (targetElement) {
    targetElement.classList.add("active");

    // Eğer bir alt menü öğesiyse veya alt menü başlığı ise
    let currentParent = targetElement.closest(".submenu");
    while (currentParent) {
      currentParent.style.display = "block"; // Alt menüyü göster
      const parentToggle = currentParent.previousElementSibling;
      if (parentToggle && parentToggle.classList.contains("menu-toggle")) {
        parentToggle.classList.add("active"); // Üst başlığı da aktif göster
        const toggleIcon = parentToggle.querySelector(".toggle-icon");
        if (toggleIcon) {
          toggleIcon.classList.remove("fa-chevron-down");
          toggleIcon.classList.add("fa-chevron-up");
        }
      }
      currentParent = currentParent.parentElement.closest(".submenu"); // Bir üst alt menüye geç
    }
  }
}

// Tüm menü öğeleri için tıklama dinleyicisi
document.querySelectorAll(".menu-item, .submenu-item").forEach((item) => {
  item.addEventListener("click", function (e) {
    // Eğer tıklanan öğe bir alt menü başlığı (menu-toggle) ise
    if (this.classList.contains("menu-toggle")) {
      e.preventDefault(); // Bağlantı davranışını engelle (sayfa geçişi yapma)

      const submenu = this.nextElementSibling;
      const toggleIcon = this.querySelector(".toggle-icon");

      // Sadece bu alt menüyü aç/kapa
      if (submenu.style.display === "block") {
        submenu.style.display = "none";
        this.classList.remove("active"); // Kendini deaktif yap
        if (toggleIcon) {
          toggleIcon.classList.remove("fa-chevron-up");
          toggleIcon.classList.add("fa-chevron-down");
        }
      } else {
        // Diğer açık alt menüleri kapat (yalnızca menu-toggle olanları)
        document.querySelectorAll(".submenu").forEach((otherSubmenu) => {
          if (otherSubmenu !== submenu) {
            otherSubmenu.style.display = "none";
            const otherToggle = otherSubmenu.previousElementSibling;
            if (otherToggle && otherToggle.classList.contains("menu-toggle")) {
              otherToggle.classList.remove("active");
              const otherToggleIcon = otherToggle.querySelector(".toggle-icon");
              if (otherToggleIcon) {
                otherToggleIcon.classList.remove("fa-chevron-up");
                otherToggleIcon.classList.add("fa-chevron-down");
              }
            }
          }
        });

        submenu.style.display = "block"; // Bu alt menüyü aç
        this.classList.add("active"); // Kendini aktif yap
        if (toggleIcon) {
          toggleIcon.classList.remove("fa-chevron-down");
          toggleIcon.classList.add("fa-chevron-up");
        }
      }
    } else {
      // Normal bir bağlantı menü öğesi veya alt menü öğesi ise (sayfa değiştirecek)
      // Sayfa geçişi yapacağımız için e.preventDefault() kullanmıyoruz.
      // Ama aktifliği ayarlamak için önce resetleyip sonra aktifleştiriyoruz.
      resetMenuState(); // Sayfa değişimi öncesi tüm menüyü sıfırla
      // activateMenuItemAndParents(this); // Bu satırı kaldırdık, çünkü sayfa zaten yeniden yüklenecek ve DOMContentLoaded halledecek.

      // Close sidebar on mobile after menu selection, but ideally this should happen on page load of the new page
      if (window.innerWidth <= 768) {
        const sidebar = document.getElementById("sidebar");
        if (sidebar) {
          sidebar.classList.remove("show");
        }
      }
    }
  });
});

// Sayfa yüklendiğinde mevcut URL'ye göre menü öğesini aktif yap
window.addEventListener("DOMContentLoaded", function () {
  const currentPathname = window.location.pathname.split("/").pop(); // Sadece dosya adını al (e.g., "musteriler.php")

  // Her sayfa yüklendiğinde menü durumunu tamamen sıfırla
  resetMenuState();

  let foundActive = false;

  // Tüm menü öğelerini (hem ana hem de alt menü) döngüye al
  document.querySelectorAll(".menu-item, .submenu-item").forEach((item) => {
    const href = item.getAttribute("href");
    // Eğer href varsa ve dosya adı mevcut URL'nin dosya adıyla eşleşiyorsa
    if (href && href.split("/").pop() === currentPathname) {
      // Bu öğe mevcut sayfayı temsil ediyor, onu ve üst menülerini aktif yap
      activateMenuItemAndParents(item);
      foundActive = true;
      // Mobil ise sidebar'ı kapat (sayfa yüklendiğinde)
      if (window.innerWidth <= 768) {
        const sidebar = document.getElementById("sidebar");
        if (sidebar) sidebar.classList.remove("show");
      }
      // Doğru öğeyi bulduğumuz için diğerlerine bakmaya gerek yok
      // (forEach içinde return; döngüyü tamamen kırmaz ama mevcut iterasyonu sonlandırır, bu yüzden 'foundActive' gerekli)
    }
  });

  // Eğer hiçbir öğe aktif bulunamazsa ve anasayfadaysak (veya direkt domaindeysek), dashboard'u aktif yap
  if (
    !foundActive &&
    (currentPathname === "" ||
      currentPathname === "dashboard2.php" ||
      currentPathname === "index.php")
  ) {
    const dashboardLink = document.querySelector('a[href="dashboard2.php"]');
    if (dashboardLink) {
      activateMenuItemAndParents(dashboardLink);
    }
  }
  document.querySelectorAll(".edit-btn").forEach((button) => {
    button.addEventListener("click", function () {
      // Modalı aç
      const modal = new bootstrap.Modal(
        document.getElementById("addCustomerModal")
      );

      // Verileri modal içine yerleştir
      document.getElementById("customerId").value = this.dataset.id;
      document.getElementById("customerName").value = this.dataset.isim;
      document.getElementById("customerTaxId").value = this.dataset.vergi_no;
      document.getElementById("customerEmail").value = this.dataset.email;
      document.getElementById("customerPhone").value = this.dataset.telefon;
      document.getElementById("customerAddress").value = this.dataset.adres;

      // İl ve ilçe varsa ekle (yoksa boş bırak)
      document.getElementById("customerCity").value = this.dataset.il;
      document.getElementById("customerDistrict").value = this.dataset.ilce;

      // Başlığı ve buton metnini değiştir
      document.getElementById("addCustomerModalLabel").textContent =
        "Müşteri Düzenle";
      document.getElementById("submitCustomerBtn").textContent =
        "Güncellemeyi Kaydet";

      modal.show();
    });
  });
  // Yeni müşteri ekle butonunu seçelim (datanın data-bs-target="#addCustomerModal" olduğunu varsayıyorum)
const newCustomerBtn = document.querySelector('button[data-bs-target="#addCustomerModal"]:not(.edit-btn)');

newCustomerBtn.addEventListener('click', () => {
  const form = document.getElementById('addCustomerForm');
  form.reset();

  // Gizli ID inputunu da sıfırla
  const idInput = document.getElementById('customerId');
  if(idInput) idInput.value = '';

  document.getElementById("addCustomerModalLabel").textContent = "Yeni Müşteri Ekle";
  document.getElementById("submitCustomerBtn").textContent = "Müşteriyi Kaydet";
});
});

// Profil dropdown ve widget aç/kapa
window.addEventListener("DOMContentLoaded", function () {
  const userInfo = document.getElementById("userInfo");
  const profileDropdown = document.getElementById("profileDropdown");
  const profileWidget = document.getElementById("profileWidget");
  const openProfileWidgetBtn = document.getElementById("openProfileWidget");

  if (userInfo && profileDropdown) {
    userInfo.addEventListener("click", function (e) {
      // Sadece dropdown'ı aç/kapa
      if (profileDropdown.style.display === "block") {
        profileDropdown.style.display = "none";
      } else {
        profileDropdown.style.display = "block";
        if (profileWidget) profileWidget.style.display = "none";
      }
      e.stopPropagation();
    });
  }
  if (openProfileWidgetBtn && profileWidget && profileDropdown) {
    openProfileWidgetBtn.addEventListener("click", function (e) {
      profileDropdown.style.display = "none";
      profileWidget.style.display = "block";
      e.stopPropagation();
    });
  }
  // Dışarı tıklanınca kapat
  document.addEventListener("click", function (e) {
    if (profileDropdown && !userInfo.contains(e.target)) {
      profileDropdown.style.display = "none";
    }
    if (
      profileWidget &&
      !profileWidget.contains(e.target) &&
      e.target !== openProfileWidgetBtn
    ) {
      profileWidget.style.display = "none";
    }
  });
});

// Animate progress rings on page load
window.addEventListener("load", function () {
  const progressRings = document.querySelectorAll(".progress-ring .progress");
  progressRings.forEach((ring) => {
    const targetOffset = ring.style.strokeDashoffset === "0" ? "0" : "251.2";
    ring.style.strokeDashoffset = "251.2"; // Start hidden
    setTimeout(() => {
      ring.style.strokeDashoffset = targetOffset; // Animate to target
    }, 100);
  });
});
