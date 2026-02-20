/**
 * LADA Drift - Логика бронирования
 * Многошаговая форма с валидацией и AJAX отправкой
 */

'use strict';

const BookingForm = {
    // Текущий шаг
    currentStep: 1,
    totalSteps: 5,
    
    // Данные формы
    formData: {
        service_type: 'car_rental',
        car_id: null,
        car_name: '',
        car_price: 0,
        booking_date: '',
        time_slot: '',
        duration: 60,
        client_name: '',
        phone: '',
        email: '',
        driver_license: '',
        comment: '',
        promo_code: ''
    },
    
    // Цены услуг
    prices: {
        car_rental: 0, // Зависит от выбранного авто
        track_rental: 15000,
        training: 5000,
        certificate: 3000
    },
    
    // Названия услуг
    serviceLabels: {
        car_rental: 'Аренда авто для дрифта',
        track_rental: 'Аренда трека',
        training: 'Обучение дрифту',
        certificate: 'Подарочный сертификат'
    },
    
    // DOM элементы
    elements: {},
    
    /**
     * Инициализация формы бронирования
     */
    init() {
        this.cacheElements();
        this.bindEvents();
        this.updateStepIndicators();
        this.selectFirstOption();
        const params = new URLSearchParams(location.search);
        if (params.get('success') === '1') {
            this.showSuccessStep(params.get('booking_id') || '');
        }
        if (params.get('error')) {
            this.showError(params.get('error'));
        }
        const today = new Date().toISOString().slice(0, 10);
        if (this.elements.dateInput) this.elements.dateInput.min = today;
    },
    
    /**
     * Кэширование DOM элементов
     */
    cacheElements() {
        this.elements = {
            form: document.getElementById('bookingForm'),
            steps: document.querySelectorAll('.booking-step'),
            stepContents: document.querySelectorAll('.step-content'),
            serviceOptions: document.querySelectorAll('.service-option'),
            carOptions: document.querySelectorAll('.car-option'),
            timeSlots: document.querySelectorAll('.time-slot'),
            dateInput: document.getElementById('bookingDate'),
            timeSlotInput: document.getElementById('timeSlotInput'),
            durationSelect: document.getElementById('durationSelect'),
            driverLicenseGroup: document.getElementById('driverLicenseGroup'),
            promoCodeInput: document.getElementById('promoCodeInput'),
            applyPromoBtn: document.getElementById('applyPromoBtn'),
            submitBtn: document.getElementById('submitBooking'),
            // Summary elements
            summaryService: document.getElementById('summaryService'),
            summaryCar: document.getElementById('summaryCar'),
            summaryCarRow: document.getElementById('summaryCarRow'),
            summaryDate: document.getElementById('summaryDate'),
            summaryTime: document.getElementById('summaryTime'),
            summaryDuration: document.getElementById('summaryDuration'),
            summaryClient: document.getElementById('summaryClient'),
            summaryPhone: document.getElementById('summaryPhone'),
            summaryTotal: document.getElementById('summaryTotal'),
            bookingId: document.getElementById('bookingId')
        };
    },
    
    /**
     * Привязка событий
     */
    bindEvents() {
        // Кнопки навигации
        document.querySelectorAll('[data-next]').forEach(btn => {
            btn.addEventListener('click', () => {
                const nextStep = parseInt(btn.dataset.next);
                this.goToStep(nextStep);
            });
        });
        
        document.querySelectorAll('[data-prev]').forEach(btn => {
            btn.addEventListener('click', () => {
                const prevStep = parseInt(btn.dataset.prev);
                this.goToStep(prevStep);
            });
        });
        
        // Выбор услуги
        this.elements.serviceOptions.forEach(option => {
            option.addEventListener('click', () => {
                this.selectService(option);
            });
        });
        
        // Выбор автомобиля
        this.elements.carOptions.forEach(option => {
            option.addEventListener('click', () => {
                this.selectCar(option);
            });
        });
        
        // Выбор времени
        this.elements.timeSlots.forEach(slot => {
            slot.addEventListener('click', () => {
                if (!slot.classList.contains('disabled')) {
                    this.selectTimeSlot(slot);
                }
            });
        });
        
        // Изменение даты
        if (this.elements.dateInput) {
            this.elements.dateInput.addEventListener('change', (e) => {
                this.formData.booking_date = e.target.value;
                this.loadAvailableSlots();
            });
        }
        
        // Изменение длительности
        if (this.elements.durationSelect) {
            this.elements.durationSelect.addEventListener('change', (e) => {
                this.formData.duration = parseInt(e.target.value);
                this.updatePrice();
            });
        }
        
        // Применение промокода
        if (this.elements.applyPromoBtn) {
            this.elements.applyPromoBtn.addEventListener('click', () => {
                this.applyPromoCode();
            });
        }
        
        // Отправка формы
        if (this.elements.form) {
            this.elements.form.addEventListener('submit', (e) => this.submitBooking(e));
        }
        
        // Маска телефона
        const phoneInput = document.querySelector('input[name="phone"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', (e) => {
                this.formatPhone(e.target);
            });
            phoneInput.addEventListener('focus', (e) => {
                if (!e.target.value) {
                    e.target.value = '+7 ';
                }
            });
        }
    },
    
    /**
     * Выбор первой опции по умолчанию
     */
    selectFirstOption() {
        // Выбираем первую услугу
        const firstService = document.querySelector('.service-option');
        if (firstService) {
            this.selectService(firstService);
        }
        
        // Выбираем первый автомобиль
        const firstCar = document.querySelector('.car-option');
        if (firstCar) {
            this.selectCar(firstCar);
        }
    },
    
    /**
     * Переход к определённому шагу
     */
    goToStep(step) {
        // Валидация текущего шага перед переходом вперёд
        if (step > this.currentStep && !this.validateStep(this.currentStep)) {
            return;
        }
        
        // Обновление данных формы
        this.collectFormData();
        
        // Скрываем текущий шаг
        this.elements.stepContents.forEach(content => {
            content.classList.remove('active');
        });
        
        // Показываем новый шаг
        const newStepContent = document.querySelector(`.step-content[data-step="${step}"]`);
        if (newStepContent) {
            newStepContent.classList.add('active');
        }
        
        // Обновляем текущий шаг
        this.currentStep = step;
        this.updateStepIndicators();
        
        // Обновляем summary на последнем шаге
        if (step === 5) {
            this.updateSummary();
        }
        
        // Показываем/скрываем поле ВУ
        this.toggleDriverLicenseField();

        // Скролл наверх
        window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    
    /**
     * Обновление индикаторов шагов
     */
    updateStepIndicators() {
        this.elements.steps.forEach(step => {
            const stepNum = parseInt(step.dataset.step);
            step.classList.remove('active', 'completed');
            
            if (stepNum === this.currentStep) {
                step.classList.add('active');
            } else if (stepNum < this.currentStep) {
                step.classList.add('completed');
            }
        });
    },
    
    /**
     * Выбор услуги
     */
    selectService(option) {
        // Убираем выделение с других
        this.elements.serviceOptions.forEach(opt => {
            opt.classList.remove('selected');
        });
        
        // Выделяем выбранную
        option.classList.add('selected');
        option.querySelector('input').checked = true;
        
        // Сохраняем значение
        this.formData.service_type = option.querySelector('input').value;
        
        // Обновляем видимость шага с автомобилем
        this.toggleCarStep();
    },
    
    /**
     * Показ/скрытие шага выбора авто
     */
    toggleCarStep() {
        const carStep = document.querySelector('.booking-step[data-step="2"]');
        const needsCar = this.formData.service_type === 'car_rental';
        
        if (carStep) {
            carStep.style.display = needsCar ? '' : 'none';
        }
        
        // Если авто не нужно, пропускаем шаг
        const nextBtn = document.querySelector('[data-next="2"]');
        if (nextBtn) {
            nextBtn.dataset.next = needsCar ? '2' : '3';
        }
    },
    
    /**
     * Выбор автомобиля
     */
    selectCar(option) {
        this.elements.carOptions.forEach(opt => {
            opt.classList.remove('selected');
        });
        
        option.classList.add('selected');
        option.querySelector('input').checked = true;
        
        const input = option.querySelector('input');
        this.formData.car_id = parseInt(input.value);
        this.formData.car_price = parseFloat(input.dataset.price);
        this.formData.car_name = option.querySelector('.car-option__name').textContent;
        
        this.updatePrice();
    },
    
    /**
     * Выбор временного слота
     */
    selectTimeSlot(slot) {
        this.elements.timeSlots.forEach(s => {
            s.classList.remove('selected');
        });
        
        slot.classList.add('selected');
        this.formData.time_slot = slot.dataset.time;
        this.elements.timeSlotInput.value = slot.dataset.time;
    },
    
    /**
     * Загрузка доступных слотов для даты
     */
    loadAvailableSlots() {
        this.elements.timeSlots.forEach(slot => {
            slot.classList.remove('disabled');
        });
    },
    
    /**
     * Показ/скрытие поля водительского удостоверения
     */
    toggleDriverLicenseField() {
        if (this.elements.driverLicenseGroup) {
            const show = this.formData.service_type === 'car_rental';
            this.elements.driverLicenseGroup.style.display = show ? '' : 'none';
            
            const input = this.elements.driverLicenseGroup.querySelector('input');
            if (input) {
                input.required = show;
            }
        }
    },
    
    /**
     * Валидация шага
     */
    validateStep(step) {
        let isValid = true;
        let errorMessage = '';
        
        switch (step) {
            case 1:
                if (!this.formData.service_type) {
                    errorMessage = 'Выберите услугу';
                    isValid = false;
                }
                break;
                
            case 2:
                if (this.formData.service_type === 'car_rental' && !this.formData.car_id) {
                    errorMessage = 'Выберите автомобиль';
                    isValid = false;
                }
                break;
                
            case 3:
                if (!this.formData.booking_date) {
                    errorMessage = 'Выберите дату';
                    isValid = false;
                } else if (!this.formData.time_slot) {
                    errorMessage = 'Выберите время';
                    isValid = false;
                }
                break;
                
            case 4:
                this.collectFormData();
                
                if (!this.formData.client_name || this.formData.client_name.length < 2) {
                    errorMessage = 'Введите ваше имя';
                    isValid = false;
                } else if (!this.validatePhone(this.formData.phone)) {
                    errorMessage = 'Введите корректный номер телефона';
                    isValid = false;
                } else if (!document.getElementById('agreeTerms').checked) {
                    errorMessage = 'Необходимо согласие с условиями';
                    isValid = false;
                }
                break;
        }
        
        if (!isValid && errorMessage) {
            this.showError(errorMessage);
        }
        
        return isValid;
    },
    
    /**
     * Валидация телефона
     */
    validatePhone(phone) {
        const cleaned = phone.replace(/\D/g, '');
        return cleaned.length === 11 && (cleaned[0] === '7' || cleaned[0] === '8');
    },
    
    /**
     * Форматирование телефона
     */
    formatPhone(input) {
        let value = input.value.replace(/\D/g, '');
        
        if (value.length > 0 && value[0] !== '7') {
            if (value[0] === '8') {
                value = '7' + value.slice(1);
            } else {
                value = '7' + value;
            }
        }
        
        let formatted = '';
        
        if (value.length > 0) formatted = '+' + value[0];
        if (value.length > 1) formatted += ' (' + value.slice(1, 4);
        if (value.length > 4) formatted += ') ' + value.slice(4, 7);
        if (value.length > 7) formatted += '-' + value.slice(7, 9);
        if (value.length > 9) formatted += '-' + value.slice(9, 11);
        
        input.value = formatted;
    },
    
    /**
     * Сбор данных формы
     */
    collectFormData() {
        const form = this.elements.form;
        if (!form) return;
        
        const formData = new FormData(form);
        
        this.formData.client_name = formData.get('client_name') || '';
        this.formData.phone = formData.get('phone') || '';
        this.formData.email = formData.get('email') || '';
        this.formData.driver_license = formData.get('driver_license') || '';
        this.formData.comment = formData.get('comment') || '';
        this.formData.promo_code = formData.get('promo_code') || '';
        this.formData.duration = parseInt(formData.get('duration')) || 60;
    },
    
    /**
     * Расчёт стоимости
     */
    calculatePrice() {
        let price = 0;
        const hours = this.formData.duration / 60;
        
        switch (this.formData.service_type) {
            case 'car_rental':
                price = this.formData.car_price * hours;
                break;
            case 'track_rental':
                price = this.prices.track_rental * hours;
                break;
            case 'training':
                price = this.prices.training * hours;
                break;
            case 'certificate':
                price = this.prices.certificate;
                break;
        }
        
        return price;
    },
    
    /**
     * Обновление отображения цены
     */
    updatePrice() {
        const price = this.calculatePrice();
        if (this.elements.summaryTotal) {
            this.elements.summaryTotal.textContent = this.formatPrice(price);
        }
    },
    
    /**
     * Форматирование цены
     */
    formatPrice(price) {
        return new Intl.NumberFormat('ru-RU', {
            style: 'currency',
            currency: 'RUB',
            minimumFractionDigits: 0
        }).format(price);
    },
    
    /**
     * Форматирование даты
     */
    formatDate(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        return date.toLocaleDateString('ru-RU', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    },
    
    /**
     * Обновление блока итогов
     */
    updateSummary() {
        this.collectFormData();
        
        if (this.elements.summaryService) {
            this.elements.summaryService.textContent = this.serviceLabels[this.formData.service_type] || '-';
        }
        
        if (this.elements.summaryCar) {
            this.elements.summaryCar.textContent = this.formData.car_name || '-';
        }
        
        if (this.elements.summaryCarRow) {
            this.elements.summaryCarRow.style.display = 
                this.formData.service_type === 'car_rental' ? '' : 'none';
        }
        
        if (this.elements.summaryDate) {
            this.elements.summaryDate.textContent = this.formatDate(this.formData.booking_date);
        }
        
        if (this.elements.summaryTime) {
            this.elements.summaryTime.textContent = this.formData.time_slot || '-';
        }
        
        if (this.elements.summaryDuration) {
            const hours = this.formData.duration / 60;
            this.elements.summaryDuration.textContent = hours === 1 ? '1 час' : `${hours} часа`;
        }
        
        if (this.elements.summaryClient) {
            this.elements.summaryClient.textContent = this.formData.client_name || '-';
        }
        
        if (this.elements.summaryPhone) {
            this.elements.summaryPhone.textContent = this.formData.phone || '-';
        }
        
        this.updatePrice();
    },
    
    /**
     * Применение промокода
     */
    async applyPromoCode() {
        const code = this.elements.promoCodeInput?.value.trim().toUpperCase();
        
        if (!code) {
            this.showError('Введите промокод');
            return;
        }
        
        // Симуляция проверки промокода
        const validCodes = {
            'FIRSTDRIFT': 10,
            'LADA2024': 15,
            'DRIFT20': 20
        };
        
        if (validCodes[code]) {
            const discount = validCodes[code];
            this.showSuccess(`Промокод применён! Скидка ${discount}%`);
            
            // Обновляем цену со скидкой
            const originalPrice = this.calculatePrice();
            const discountedPrice = originalPrice * (1 - discount / 100);
            
            if (this.elements.summaryTotal) {
                this.elements.summaryTotal.innerHTML = `
                    <span style="text-decoration: line-through; color: var(--color-text-muted); font-size: 0.875rem;">
                        ${this.formatPrice(originalPrice)}
                    </span>
                    ${this.formatPrice(discountedPrice)}
                `;
            }
        } else {
            this.showError('Промокод недействителен');
        }
    },
    
    /**
     * Отправка бронирования
     */
    submitBooking(e) {
        e.preventDefault();
        if (!this.validateStep(4)) return;
        this.collectFormData();
        this.elements.form.submit();
    },
    
    /**
     * Показ экрана успеха
     */
    showSuccessStep(bookingId) {
        // Скрываем все шаги
        this.elements.stepContents.forEach(content => {
            content.classList.remove('active');
            content.style.display = 'none';
        });
        
        // Показываем успех
        const successStep = document.querySelector('.step-content[data-step="success"]');
        if (successStep) {
            successStep.style.display = 'block';
            successStep.classList.add('active');
        }
        
        // Обновляем номер заявки
        if (this.elements.bookingId) {
            this.elements.bookingId.textContent = `#${String(bookingId).padStart(4, '0')}`;
        }
        
        // Скрываем индикатор шагов
        document.querySelector('.booking-steps').style.display = 'none';
    },
    
    /**
     * Показ ошибки
     */
    showError(message) {
        if (typeof LADA !== 'undefined' && LADA.showToast) {
            LADA.showToast(message, 'error');
        } else {
            alert(message);
        }
    },
    
    /**
     * Показ успешного сообщения
     */
    showSuccess(message) {
        if (typeof LADA !== 'undefined' && LADA.showToast) {
            LADA.showToast(message, 'success');
        } else {
            alert(message);
        }
    }
};

// Инициализация при загрузке DOM
document.addEventListener('DOMContentLoaded', () => {
    BookingForm.init();
});
