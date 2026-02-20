/**
 * LADA Drift - Галерея автомобилей
 * Фильтрация, табы и модальное окно с фото
 */
'use strict';

const FleetGallery = {
    elements: {},

    init() {
        this.cacheElements();
        this.bindEvents();
    },

    cacheElements() {
        this.elements = {
            fleetGrid: document.getElementById('fleetGrid'),
            filterBtns: document.querySelectorAll('.filter-btn'),
            fleetCards: document.querySelectorAll('.fleet-card'),
            galleryModal: document.getElementById('galleryModal'),
            galleryImage: document.getElementById('galleryImage'),
            closeGallery: document.getElementById('closeGallery'),
            prevImage: document.getElementById('prevImage'),
            nextImage: document.getElementById('nextImage'),
            galleryBtns: document.querySelectorAll('.fleet-card__gallery-btn'),
            tabBtns: document.querySelectorAll('.fleet-card__tab')
        };
    },

    bindEvents() {
        // Фильтрация
        this.elements.filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const filterRaw = (btn.dataset.filter || btn.textContent || '').trim();
                const filter = this.normalizeFilter(filterRaw);
                this.filterCards(filter);
                this.elements.filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });

        // Табы
        this.elements.tabBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.switchTab(e.target);
            });
        });

        // Открытие галереи
        this.elements.galleryBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.openGallery(btn);
            });
        });

        // Закрытие
        if (this.elements.closeGallery) {
            this.elements.closeGallery.addEventListener('click', () => this.closeGallery());
        }

        // Клик вне окна
        if (this.elements.galleryModal) {
            this.elements.galleryModal.addEventListener('click', (e) => {
                if (e.target === this.elements.galleryModal) this.closeGallery();
            });
        }

        // Escape
        document.addEventListener('keydown', (e) => {
            if (this.elements.galleryModal && this.elements.galleryModal.classList.contains('active') && e.key === 'Escape') {
                this.closeGallery();
            }
        });

        // Скрыть стрелки навигации (если не нужны)
        if (this.elements.prevImage) this.elements.prevImage.style.display = 'none';
        if (this.elements.nextImage) this.elements.nextImage.style.display = 'none';
    },

    filterCards(filter) {
        this.elements.fleetCards.forEach(card => {
            const categoryRaw = (card.dataset.category || card.dataset.filter || '').trim();
            const category = this.normalizeFilter(categoryRaw);

            if (filter === 'all' || category === filter) {
                card.style.display = 'block';
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                requestAnimationFrame(() => {
                    card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                });
            } else {
                card.style.display = 'none';
            }
        });
    },

    normalizeFilter(value) {
        const v = (value || '').toLowerCase();

        if (!v) return '';
        if (v === 'all' || v.includes('все')) return 'all';
        if (v === 'classic' || v.includes('класс')) return 'classic';
        if (v === 'modern' || v.includes('соврем')) return 'modern';
        if (v === 'unique' || v.includes('уник')) return 'unique';

        return v;
    },

    switchTab(tabBtn) {
        const card = tabBtn.closest('.fleet-card');
        const tabName = tabBtn.dataset.tab;

        card.querySelectorAll('.fleet-card__tab').forEach(tab => tab.classList.remove('active'));
        tabBtn.classList.add('active');

        card.querySelectorAll('.fleet-card__tab-content').forEach(content => content.classList.remove('active'));

        const targetContent = card.querySelector(`.fleet-card__tab-content[data-content="${tabName}"]`);
        if (targetContent) targetContent.classList.add('active');
    },

    openGallery(btn) {
        const card = btn.closest('.fleet-card');
        const img = card.querySelector('img');
        if (!img) return;

        this.elements.galleryImage.src = img.src;
        this.elements.galleryImage.alt = img.alt;
        this.elements.galleryModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    },

    closeGallery() {
        this.elements.galleryModal.classList.remove('active');
        document.body.style.overflow = '';
    }
};

document.addEventListener('DOMContentLoaded', () => {
    FleetGallery.init();
});