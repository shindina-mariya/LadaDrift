'use strict';

(function() {
    const cabinetBtnId = 'cabinetBtn';
    const authModalId = 'authModal';
    const authModalOverlayId = 'authModalOverlay';
    const authModalCloseId = 'authModalClose';
    const authModalBodyId = 'authModalBody';

    const getLoginFormHtml = () => `
        <div class="auth-modal__form" id="authFormLogin">
            <h2 class="auth-modal__title">Вход</h2>
            <form method="POST" action="login.php" id="authLoginForm">
                <div class="form-group">
                    <label class="form-label" for="auth_email">Email</label>
                    <input type="email" name="email" id="auth_email" class="form-control" placeholder="example@mail.ru" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="auth_password">Пароль</label>
                    <input type="password" name="password" id="auth_password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn--primary btn--block">
                    <i class="fas fa-sign-in-alt"></i> Войти
                </button>
            </form>
            <p class="auth-modal__footer">
                Нет аккаунта? <a href="#" class="auth-modal__link" data-show="register">Зарегистрироваться</a>
            </p>
        </div>`;

    const getRegisterFormHtml = () => `
        <div class="auth-modal__form" id="authFormRegister">
            <h2 class="auth-modal__title">Регистрация</h2>
            <form method="POST" action="register.php" id="authRegisterForm">
                <div class="form-group">
                    <label class="form-label" for="auth_reg_email">Email *</label>
                    <input type="email" name="email" id="auth_reg_email" class="form-control" placeholder="example@mail.ru" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="auth_reg_password">Пароль (мин. 6 символов) *</label>
                    <input type="password" name="password" id="auth_reg_password" class="form-control" placeholder="••••••••" required minlength="6">
                </div>
                <div class="form-group">
                    <label class="form-label" for="auth_reg_phone">Телефон *</label>
                    <input type="tel" name="phone" id="auth_reg_phone" class="form-control" placeholder="+7 (900) 123-45-67" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="auth_reg_full_name">ФИО *</label>
                    <input type="text" name="full_name" id="auth_reg_full_name" class="form-control" placeholder="Иванов Иван Иванович" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="auth_reg_driver_license">Номер ВУ (опционально)</label>
                    <input type="text" name="driver_license" id="auth_reg_driver_license" class="form-control" placeholder="1234 567890">
                </div>
                <button type="submit" class="btn btn--primary btn--block">
                    <i class="fas fa-user-plus"></i> Зарегистрироваться
                </button>
            </form>
            <p class="auth-modal__footer">
                Уже есть аккаунт? <a href="#" class="auth-modal__link" data-show="login">Войти</a>
            </p>
        </div>`;

    function openAuthModal(showForm) {
        const modal = document.getElementById(authModalId);
        const body = document.getElementById(authModalBodyId);

        if (!modal || !body) return;

        body.innerHTML = showForm === 'register' ? getRegisterFormHtml() : getLoginFormHtml();
        modal.classList.add('active');
        document.body.classList.add('menu-open');

        body.querySelectorAll('.auth-modal__link').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                openAuthModal(this.getAttribute('data-show'));
            });
        });
    }

    function closeAuthModal() {
        const modal = document.getElementById(authModalId);
        if (modal) {
            modal.classList.remove('active');
            document.body.classList.remove('menu-open');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const cabinetBtn = document.getElementById(cabinetBtnId);
        const overlay = document.getElementById(authModalOverlayId);
        const closeBtn = document.getElementById(authModalCloseId);

        if (cabinetBtn) {
            cabinetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openAuthModal('login');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', closeAuthModal);
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', closeAuthModal);
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById(authModalId);
                if (modal && modal.classList.contains('active')) {
                    closeAuthModal();
                }
            }
        });
    });
})();
