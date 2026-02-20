'use strict';

// ============================================
// Глобальные переменные и константы
// ============================================

const LADA = {
    // Селекторы DOM элементов
    selectors: {
        header: '#header',
        burgerBtn: '#burgerBtn',
        mobileMenu: '#mobileMenu',
        mobileOverlay: '#mobileOverlay',
        closeMenuBtn: '#closeMenuBtn',
        scrollTopBtn: '#scrollTopBtn',
        callbackBtn: '#callbackBtn',
        callbackForm: '#callbackForm',
        closeCallbackBtn: '#closeCallbackBtn',
        callbackRequestForm: '#callbackRequestForm',
        subscribeForm: '#subscribeForm',
        modal: '#modal',
        modalOverlay: '#modalOverlay',
        modalClose: '#modalClose',
        modalBody: '#modalBody',
        toastContainer: '#toastContainer',
        faqItems: '.faq-item',
        animateOnScroll: '.animate',
        reviewsSwiper: '.reviewsSwiper'
    },
    
    // Настройки
    config: {
        scrollOffset: 100,           // Отступ для появления кнопки "наверх"
        headerScrollClass: 'scrolled', // Класс шапки при скролле
        animationThreshold: 0.2,     // Порог срабатывания анимации (20% элемента видно)
        toastDuration: 5000,         // Длительность показа уведомления (мс)
        phonePattern: /^\+7\s?\(?\d{3}\)?\s?\d{3}[-\s]?\d{2}[-\s]?\d{2}$/, // Паттерн телефона
        emailPattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/ // Паттерн email
    },
    
    // Хранилище DOM элементов
    elements: {},
    
    // Флаги состояния
    state: {
        menuOpen: false,
        callbackFormOpen: false,
        isScrolling: false
    }
};

// ============================================
// Инициализация при загрузке DOM
// ============================================

document.addEventListener('DOMContentLoaded', () => {
    // Кэширование DOM элементов
    LADA.cacheElements();
    
    // Инициализация компонентов
    LADA.initHeader();
    LADA.initMobileMenu();
    LADA.initScrollTop();
    LADA.initCallbackWidget();
    LADA.initFAQ();
    LADA.initReviewsSlider();
    LADA.initScrollAnimations();
    LADA.initForms();
    LADA.initModal();
    LADA.initPhoneMask();
    
});

// ============================================
// Кэширование DOM элементов
// ============================================

LADA.cacheElements = function() {
    for (const [key, selector] of Object.entries(this.selectors)) {
        if (selector.startsWith('.')) {
            // Для классов - получаем все элементы
            this.elements[key] = document.querySelectorAll(selector);
        } else {
            // Для ID - получаем один элемент
            this.elements[key] = document.querySelector(selector);
        }
    }
};


// ============================================
// Шапка сайта (Header)
// ============================================

LADA.initHeader = function() {
    const header = this.elements.header;
    
    if (!header) return;
    
    // Добавляем класс при скролле
    const handleScroll = () => {
        if (window.scrollY > this.config.scrollOffset) {
            header.classList.add(this.config.headerScrollClass);
        } else {
            header.classList.remove(this.config.headerScrollClass);
        }
    };
    
    // Throttle для оптимизации
    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                handleScroll();
                ticking = false;
            });
            ticking = true;
        }
    });
    
    // Проверяем начальное состояние
    handleScroll();
};

// ============================================
// Мобильное меню
// ============================================

LADA.initMobileMenu = function() {
    const { burgerBtn, mobileMenu, mobileOverlay, closeMenuBtn } = this.elements;
    
    if (!burgerBtn || !mobileMenu) return;
    
    // Открытие меню
    burgerBtn.addEventListener('click', () => {
        this.toggleMobileMenu(true);
    });
    
    // Закрытие по кнопке
    if (closeMenuBtn) {
        closeMenuBtn.addEventListener('click', () => {
            this.toggleMobileMenu(false);
        });
    }
    
    // Закрытие по клику на оверлей
    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', () => {
            this.toggleMobileMenu(false);
        });
    }
    
    // Закрытие по Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && this.state.menuOpen) {
            this.toggleMobileMenu(false);
        }
    });
    
    // Закрытие при клике на ссылку
    const menuLinks = mobileMenu.querySelectorAll('a');
    menuLinks.forEach(link => {
        link.addEventListener('click', () => {
            this.toggleMobileMenu(false);
        });
    });
};

LADA.toggleMobileMenu = function(open) {
    const { burgerBtn, mobileMenu } = this.elements;
    
    this.state.menuOpen = open;
    
    if (open) {
        mobileMenu.classList.add('active');
        burgerBtn.classList.add('active');
        document.body.classList.add('menu-open');
    } else {
        mobileMenu.classList.remove('active');
        burgerBtn.classList.remove('active');
        document.body.classList.remove('menu-open');
    }
};

// ============================================
// Кнопка "Наверх"
// ============================================

LADA.initScrollTop = function() {
    const scrollTopBtn = this.elements.scrollTopBtn;
    
    if (!scrollTopBtn) return;
    
    // Показ/скрытие кнопки
    const toggleButton = () => {
        if (window.scrollY > this.config.scrollOffset * 3) {
            scrollTopBtn.classList.add('visible');
        } else {
            scrollTopBtn.classList.remove('visible');
        }
    };
    
    window.addEventListener('scroll', LADA.throttle(toggleButton, 100));
    
    // Прокрутка наверх
    scrollTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
};

// ============================================
// Виджет обратного звонка
// ============================================

LADA.initCallbackWidget = function() {
    const { callbackBtn, callbackForm, closeCallbackBtn, callbackRequestForm } = this.elements;
    
    if (!callbackBtn || !callbackForm) return;
    
    // Открытие/закрытие формы
    callbackBtn.addEventListener('click', () => {
        this.state.callbackFormOpen = !this.state.callbackFormOpen;
        callbackForm.classList.toggle('active', this.state.callbackFormOpen);
    });
    
    // Закрытие формы
    if (closeCallbackBtn) {
        closeCallbackBtn.addEventListener('click', () => {
            this.state.callbackFormOpen = false;
            callbackForm.classList.remove('active');
        });
    }
    
    // Обработка отправки формы
    if (callbackRequestForm) {
        callbackRequestForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleCallbackSubmit(callbackRequestForm);
        });
    }
};

LADA.handleCallbackSubmit = async function(form) {
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    // Валидация
    if (!data.name || data.name.trim().length < 2) {
        this.showToast('Введите корректное имя', 'error');
        return;
    }
    
    if (!this.config.phonePattern.test(data.phone)) {
        this.showToast('Введите корректный номер телефона', 'error');
        return;
    }
    
    // Показываем загрузку
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Отправка...';
    submitBtn.disabled = true;
    
    try {
        // Отправка на сервер (симуляция)
        await this.simulateAPICall(data);
        
        this.showToast('Заявка отправлена! Мы перезвоним вам в ближайшее время.', 'success');
        form.reset();
        
        // Закрываем форму
        this.elements.callbackForm.classList.remove('active');
        this.state.callbackFormOpen = false;
        
    } catch (error) {
        this.showToast('Ошибка отправки. Попробуйте позже или позвоните нам.', 'error');
    } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
};

// ============================================
// FAQ Аккордеон
// ============================================

LADA.initFAQ = function() {
    const faqItems = this.elements.faqItems;
    
    if (!faqItems || faqItems.length === 0) return;
    
    faqItems.forEach(item => {
        const header = item.querySelector('.faq-item__header');
        
        if (!header) return;
        
        header.addEventListener('click', () => {
            // Закрываем другие элементы (опционально - для единственного открытого)
            // faqItems.forEach(otherItem => {
            //     if (otherItem !== item) {
            //         otherItem.classList.remove('active');
            //     }
            // });
            
            // Переключаем текущий элемент
            item.classList.toggle('active');
        });
    });
};

// ============================================
// Слайдер отзывов (Swiper)
// ============================================

LADA.initReviewsSlider = function() {
    const swiperContainer = document.querySelector('.reviewsSwiper');
    
    if (!swiperContainer || typeof Swiper === 'undefined') return;
    
    new Swiper('.reviewsSwiper', {
        // Основные настройки
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        centeredSlides: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true
        },
        
        // Пагинация
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            dynamicBullets: true
        },
        
        // Навигация
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        },
        
        // Адаптивность
        breakpoints: {
            640: {
                slidesPerView: 1,
                spaceBetween: 20
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 30,
                centeredSlides: false
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 30,
                centeredSlides: false
            }
        },
        
        // Эффекты
        effect: 'slide',
        speed: 600,
        
        // Доступность
        a11y: {
            prevSlideMessage: 'Предыдущий отзыв',
            nextSlideMessage: 'Следующий отзыв',
            paginationBulletMessage: 'Перейти к отзыву {{index}}'
        }
    });
};

// ============================================
// Анимации при скролле (Intersection Observer)
// ============================================

LADA.initScrollAnimations = function() {
    const elements = this.elements.animateOnScroll;
    
    if (!elements || elements.length === 0) return;
    
    // Проверяем поддержку Intersection Observer
    if (!('IntersectionObserver' in window)) {
        // Fallback: показываем все элементы сразу
        elements.forEach(el => el.classList.add('animate--active'));
        return;
    }
    
    // Создаём наблюдатель
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate--active');
                // Прекращаем наблюдение за элементом после анимации
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: this.config.animationThreshold,
        rootMargin: '0px 0px -50px 0px'
    });
    
    // Начинаем наблюдение
    elements.forEach(el => observer.observe(el));
};

// ============================================
// Валидация форм
// ============================================

LADA.initForms = function() {
    // Форма подписки
    const subscribeForm = this.elements.subscribeForm;
    
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleSubscribeSubmit(subscribeForm);
        });
    }
    
    // Добавляем валидацию в реальном времени для всех форм
    const allForms = document.querySelectorAll('form');
    allForms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                this.validateField(input);
            });
            
            input.addEventListener('input', () => {
                // Убираем ошибку при вводе
                input.classList.remove('error');
                const errorEl = input.parentElement.querySelector('.form-error');
                if (errorEl) errorEl.remove();
            });
        });
    });
};

LADA.validateField = function(input) {
    const value = input.value.trim();
    const type = input.type;
    const required = input.required;
    let isValid = true;
    let errorMessage = '';
    
    // Проверка обязательности
    if (required && !value) {
        isValid = false;
        errorMessage = 'Это поле обязательно для заполнения';
    }
    // Проверка email
    else if (type === 'email' && value && !this.config.emailPattern.test(value)) {
        isValid = false;
        errorMessage = 'Введите корректный email';
    }
    // Проверка телефона
    else if (type === 'tel' && value && !this.config.phonePattern.test(value)) {
        isValid = false;
        errorMessage = 'Введите телефон в формате +7 (XXX) XXX-XX-XX';
    }
    
    // Отображение ошибки
    if (!isValid) {
        input.classList.add('error');
        let errorEl = input.parentElement.querySelector('.form-error');
        
        if (!errorEl) {
            errorEl = document.createElement('span');
            errorEl.className = 'form-error';
            input.parentElement.appendChild(errorEl);
        }
        
        errorEl.textContent = errorMessage;
    } else {
        input.classList.remove('error');
        const errorEl = input.parentElement.querySelector('.form-error');
        if (errorEl) errorEl.remove();
    }
    
    return isValid;
};

LADA.handleSubscribeSubmit = async function(form) {
    const emailInput = form.querySelector('input[type="email"]');
    const email = emailInput.value.trim();
    
    if (!this.config.emailPattern.test(email)) {
        this.showToast('Введите корректный email', 'error');
        emailInput.classList.add('error');
        return;
    }
    
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    
    try {
        await this.simulateAPICall({ email, action: 'subscribe' });
        this.showToast('Вы успешно подписались на рассылку!', 'success');
        form.reset();
    } catch (error) {
        this.showToast('Ошибка подписки. Попробуйте позже.', 'error');
    } finally {
        submitBtn.disabled = false;
    }
};

// ============================================
// Маска для телефона
// ============================================

LADA.initPhoneMask = function() {
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    
    phoneInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            
            // Добавляем +7 в начало
            if (value.length > 0 && value[0] !== '7') {
                if (value[0] === '8') {
                    value = '7' + value.slice(1);
                } else {
                    value = '7' + value;
                }
            }
            
            // Форматирование
            let formatted = '';
            
            if (value.length > 0) {
                formatted = '+' + value[0];
            }
            
            if (value.length > 1) {
                formatted += ' (' + value.slice(1, 4);
            }
            
            if (value.length > 4) {
                formatted += ') ' + value.slice(4, 7);
            }
            
            if (value.length > 7) {
                formatted += '-' + value.slice(7, 9);
            }
            
            if (value.length > 9) {
                formatted += '-' + value.slice(9, 11);
            }
            
            e.target.value = formatted;
        });
        
        // Устанавливаем +7 при фокусе на пустое поле
        input.addEventListener('focus', (e) => {
            if (!e.target.value) {
                e.target.value = '+7 ';
            }
        });
    });
};

// ============================================
// Модальное окно
// ============================================

LADA.initModal = function() {
    const { modal, modalOverlay, modalClose } = this.elements;
    
    if (!modal) return;
    
    // Закрытие по оверлею
    if (modalOverlay) {
        modalOverlay.addEventListener('click', () => {
            this.closeModal();
        });
    }
    
    // Закрытие по кнопке
    if (modalClose) {
        modalClose.addEventListener('click', () => {
            this.closeModal();
        });
    }
    
    // Закрытие по Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            this.closeModal();
        }
    });
};

LADA.openModal = function(content) {
    const { modal, modalBody } = this.elements;
    
    if (!modal || !modalBody) return;
    
    modalBody.innerHTML = content;
    modal.classList.add('active');
    document.body.classList.add('menu-open');
};

LADA.closeModal = function() {
    const { modal, modalBody } = this.elements;
    
    if (!modal) return;
    
    modal.classList.remove('active');
    document.body.classList.remove('menu-open');
    
    // Очищаем контент после анимации
    setTimeout(() => {
        if (modalBody) modalBody.innerHTML = '';
    }, 300);
};

// ============================================
// Toast уведомления
// ============================================

LADA.showToast = function(message, type = 'success') {
    const container = this.elements.toastContainer;
    
    if (!container) return;
    
    // Создаём toast
    const toast = document.createElement('div');
    toast.className = `toast toast--${type}`;
    
    // Иконка в зависимости от типа
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    toast.innerHTML = `
        <i class="fas ${icons[type] || icons.info} toast__icon"></i>
        <span class="toast__message">${message}</span>
        <button class="toast__close" aria-label="Закрыть">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Добавляем в контейнер
    container.appendChild(toast);
    
    // Анимация появления
    requestAnimationFrame(() => {
        toast.classList.add('show');
    });
    
    // Закрытие по кнопке
    const closeBtn = toast.querySelector('.toast__close');
    closeBtn.addEventListener('click', () => {
        this.hideToast(toast);
    });
    
    // Автоматическое скрытие
    setTimeout(() => {
        this.hideToast(toast);
    }, this.config.toastDuration);
};

LADA.hideToast = function(toast) {
    toast.classList.remove('show');
    
    // Удаляем из DOM после анимации
    setTimeout(() => {
        toast.remove();
    }, 300);
};

// ============================================
// Утилиты
// ============================================

// Throttle функция для оптимизации обработчиков событий
LADA.throttle = function(func, limit) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
};

// Симуляция API запроса (заменить на реальный fetch)
LADA.simulateAPICall = function(data) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            // 90% успешных запросов
            if (Math.random() > 0.1) {
                resolve({ success: true, message: 'OK' });
            } else {
                reject(new Error('Network error'));
            }
        }, 1000);
    });
};

window.LADA = LADA;
