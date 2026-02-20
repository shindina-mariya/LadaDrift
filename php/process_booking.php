<?php
/**
 * Обработка формы бронирования (классический POST)
 */

require_once __DIR__ . '/models/Booking.php';
require_once __DIR__ . '/models/Car.php';
require_once __DIR__ . '/includes/auth.php';

session_start();

$userId = getCurrentUserId();
$data = $_POST;

if (empty($data)) {
    header('Location: ../booking.html?error=' . urlencode('Данные не получены'));
    exit;
}

try {
    $booking = Booking::createFromForm($data, $userId);
    $errors = $booking->validate();

    if (!empty($errors)) {
        $msg = implode('. ', $errors);
        header('Location: ../booking.html?error=' . urlencode($msg));
        exit;
    }

    if ($booking->getServiceType() === Booking::SERVICE_CAR_RENTAL && $booking->getCarId()) {
        $car = Car::getById($booking->getCarId());
        if (!$car || !$car->isAvailableAt($booking->getBookingDate(), $booking->getTimeSlot())) {
            header('Location: ../booking.html?error=' . urlencode('Выбранное время занято. Выберите другой слот.'));
            exit;
        }
    }

    $booking->save();

    header('Location: ../booking.html?success=1&booking_id=' . $booking->getId());
    exit;

} catch (Exception $e) {
    error_log('process_booking: ' . $e->getMessage());
    header('Location: ../booking.html?error=' . urlencode('Ошибка сохранения. Попробуйте позже.'));
    exit;
}
