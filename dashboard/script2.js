// Sidebar toggle for mobile
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    
    if (window.innerWidth <= 768) {
        if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
            sidebar.classList.remove('show');
        }
    }
});

// Menu item click handler
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', function(e) {
        // Sadece `.menu-toggle` sınıfına sahip olmayanlar için `e.preventDefault()` çağrılmalı
        // Çünkü `.menu-toggle` zaten kendi preventDefault'unu çağırıyor
        if (!this.classList.contains('menu-toggle')) {
            e.preventDefault();
        }
        
        // Remove active class from all menu items
        document.querySelectorAll('.menu-item').forEach(menuItem => {
            menuItem.classList.remove('active');
        });
        
        // Add active class to clicked item
        this.classList.add('active');
        
        // Close sidebar on mobile after menu selection
        if (window.innerWidth <= 768) {
            document.getElementById('sidebar').classList.remove('show');
        }
    });
});

// Submenu toggle handler
document.querySelectorAll('.menu-toggle').forEach(toggle => {
    toggle.addEventListener('click', function(e) {
        e.preventDefault(); // Varsayılan bağlantı davranışını engeller
        const submenu = this.nextElementSibling;
        const toggleIcon = this.querySelector('.toggle-icon');

        // Toggle submenu visibility
        // Bu kısım, alt menünün mevcut görünürlüğüne göre açıp kapatır
        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';

        // Toggle arrow icon
        toggleIcon.classList.toggle('fa-chevron-down');
        toggleIcon.classList.toggle('fa-chevron-up');
    });
});

// Animate progress rings on page load
window.addEventListener('load', function() {
    const progressRings = document.querySelectorAll('.progress-ring .progress');
    progressRings.forEach(ring => {
        // Stildeki başlangıç değeri 251.2 olduğundan, geçişi sağlamak için başlangıçta sıfıra ayarlayıp sonra hedef değere geri dönüyoruz.
        // Eğer halka başlangıçta tam doluysa (stroke-dashoffset 0), o zaman başlangıç değeri 251.2 olmalıdır.
        // Eğer başlangıç değeri 0 ise ve tam dolu göstermek istiyorsak, 0'a çekmeliyiz.
        // Mevcut kodunuzda halkalar başlangıçta 0'a ayarlı ve sonra 0 olarak kalıyor.
        // Animasyon için bir hedef belirleyelim.
        const targetOffset = ring.style.strokeDashoffset === '0' ? '0' : '251.2'; // Eğer başlangıç 0 ise 0 kalsın, yoksa 251.2'ye gitsin

        ring.style.strokeDashoffset = '251.2'; // Başlangıç pozisyonu (tamamen gizli)

        setTimeout(() => {
            ring.style.strokeDashoffset = targetOffset; // Hedef pozisyona geçiş
        }, 100); // Küçük bir gecikme ile animasyonu başlat
    });
});
