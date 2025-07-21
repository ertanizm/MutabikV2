</div> <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- MENÜ ACCORDION KODLARI ---
            const submenuTriggers = document.querySelectorAll('.has-submenu > a');
            submenuTriggers.forEach(trigger => {
                trigger.addEventListener('click', function(event) {
                    event.preventDefault();
                    const clickedParentLi = this.parentElement;
                    submenuTriggers.forEach(otherTrigger => {
                        const otherParentLi = otherTrigger.parentElement;
                        if (otherParentLi !== clickedParentLi) {
                            otherParentLi.classList.remove('open');
                        }
                    });
                    clickedParentLi.classList.toggle('open');
                });
            });


            // --- OTOMATİK KAPANAN BİLDİRİM KODU ---
            const alertMessage = document.querySelector('.alert');
            if (alertMessage) {
                setTimeout(() => {
                    alertMessage.style.opacity = '0';
                    setTimeout(() => {
                        alertMessage.style.display = 'none';
                    }, 500);
                }, 5000);
            }


            // --- PANEL MANTIĞI (YENİ GÜNCELLEMELERLE) ---
            const editPanelBtn = document.getElementById('editPanelBtn');
            const widgetMenu = document.getElementById('widgetMenu');
            const placeholder = document.getElementById('empty-placeholder');

            // 1. TERCİHLERİ KAYDETME FONKSİYONU
            function saveWidgetPreferences() {
                const visibleWidgets = [];
                // Görünür olan tüm opsiyonel widget'ları bul ve ID'lerini listeye ekle
                document.querySelectorAll('.dashboard-grid .widget:not(.widget-hidden)').forEach(widget => {
                    visibleWidgets.push(widget.id);
                });
                // Listeyi tarayıcının hafızasına (localStorage) kaydet
                localStorage.setItem('dashboardWidgets', JSON.stringify(visibleWidgets));
            }

            // 2. TERCİHLERİ YÜKLEME FONKSİYONU
            function loadWidgetPreferences() {
                // Hafızadan kayıtlı widget'ları çek
                const savedWidgets = JSON.parse(localStorage.getItem('dashboardWidgets'));
                
                // Eğer hafızada bir kayıt varsa
                if (savedWidgets && savedWidgets.length > 0) {
                    // Önce tüm opsiyonel widget'ları gizle
                    document.querySelectorAll('.dashboard-grid .widget').forEach(widget => {
                        widget.classList.add('widget-hidden');
                    });
                     // Menüdeki tüm checkbox'ların işaretini kaldır
                    document.querySelectorAll('.widget-menu input[type="checkbox"]').forEach(checkbox => {
                        checkbox.checked = false;
                    });

                    // Sadece hafızadaki widget'ları görünür yap ve checkbox'larını işaretle
                    savedWidgets.forEach(widgetId => {
                        const widgetElement = document.getElementById(widgetId);
                        const checkbox = document.querySelector(`.widget-menu input[data-widget="${widgetId}"]`);
                        if (widgetElement) {
                            widgetElement.classList.remove('widget-hidden');
                        }
                        if (checkbox) {
                            checkbox.checked = true;
                        }
                    });

                    // Eğer grafik görünürse, onu çiz
                    if (savedWidgets.includes('widget-grafik')) {
                        drawCashFlowChart();
                    }
                }
                checkPlaceholder(); // Boş alan mesajını son durumda göre ayarla
            }

            function checkPlaceholder() {
                const visibleWidgets = document.querySelectorAll('.dashboard-grid .widget:not(.widget-hidden)');
                if (placeholder) {
                    placeholder.style.display = visibleWidgets.length === 0 ? 'block' : 'none';
                }
            }

            if (editPanelBtn) {
                editPanelBtn.addEventListener('click', () => {
                    widgetMenu.classList.toggle('open');
                });
            }
            if (widgetMenu) {
                widgetMenu.addEventListener('change', (event) => {
                    if (event.target.type !== 'checkbox') return;
                    const widgetId = event.target.dataset.widget;
                    const widgetElement = document.getElementById(widgetId);
                    if (widgetElement) {
                        widgetElement.classList.toggle('widget-hidden', !event.target.checked);
                        if (widgetId === 'widget-grafik' && event.target.checked) {
                           drawCashFlowChart();
                        }
                        checkPlaceholder();
                        saveWidgetPreferences(); // HER DEĞİŞİKLİKTE TERCİHLERİ KAYDET
                    }
                });
            }
            
            // 3. SAYFA YÜKLENDİĞİNDE TERCİHLERİ YÜKLE
            loadWidgetPreferences(); 
        });

        // GRAFİK ÇİZME FONKSİYONU
        function drawCashFlowChart() {
            const cashFlowCanvas = document.getElementById('cashFlowChart');
            if (cashFlowCanvas && !cashFlowCanvas.classList.contains('widget-hidden')) {
                const ctx = cashFlowCanvas.getContext('2d');
                if (window.myChart instanceof Chart) { window.myChart.destroy(); }
                const labels = ["Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz"];
                const incomeData = [5200, 6100, 7500, 5800, 8200, 9100];
                const expenseData = [3100, 4000, 4500, 4800, 5100, 5900];
                window.myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Gelir', data: incomeData, backgroundColor: 'rgba(59, 130, 246, 0.7)'
                        }, {
                            label: 'Gider', data: expenseData, backgroundColor: 'rgba(239, 68, 68, 0.7)'
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }
        }
    </script>
</body>
</html>
